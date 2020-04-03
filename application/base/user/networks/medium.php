<?php
/**
 * Medium
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Medium
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Networks;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;

/**
 * Medium class - allows users to connect to their Medium Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Medium implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI, $params, $medium, $redirect_uri, $client_id, $client_secret;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get Medium's client_id
        $this->client_id = get_option('medium_client_id');
        
        // Get Medium's client_secret
        $this->client_secret = get_option('medium_client_secret');
        
        // // Get Medium's redirect
        $this->redirect_uri = site_url('user/callback/medium');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Require the vendor autoload
        include_once FCPATH . 'vendor/autoload.php';
        
        // Create params with connection data
        $this->params = array(
            'client-id' => $this->client_id,
            'client-secret' => $this->client_secret,
            'scopes' => 'basicProfile,publishPost',
            'redirect-url' => $this->redirect_uri,
            'state' => 'publishPost'
        );
        
        // Call the medium's class
        $this->medium = new \JonathanTorres\MediumSdk\Medium($this->params);
        
    }
    
    /**
     * The public method check_availability checks if the Medium api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if client_id and client_secret is not empty
        if ( ($this->client_id != '') AND ( $this->client_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method connect will redirect user to Medium login page.
     */
    public function connect() {
        
        // Get redirect url
        $loginUrl = $this->medium->getAuthenticationUrl();
        
        // Redirect
        header('Location:' . $loginUrl);
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return void
     */
    public function save($token = null) {
        
        // Define the callback status
        $check = 0;
        
        // Verify if the code exists
        if ( $this->CI->input->get('code', TRUE) ) {
            
            // Generate the token
            $postdata = http_build_query(['code' => $this->CI->input->get('code', TRUE), 'client_id' => $this->client_id, 'client_secret' => $this->client_secret, 'grant_type' => 'authorization_code', 'redirect_uri' => $this->redirect_uri]);
            $curl = curl_init('https://api.medium.com/v1/tokens');
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
            $resp = curl_exec($curl);
            curl_close($curl);
            
            // Decode response
            $secret = json_decode($resp, true);
            
            // Verify if access token exists
            if ( isset($secret['access_token']) ) {
                
                // Get access token
                $token = $secret['access_token'];
                
                // Get refresh token
                $refresh = !empty($secret['refresh_token'])?$secret['refresh_token']:'';
                
                // Get expire time
                $expires_in = !empty($secret['expires_at'])?$secret['expires_at']:'';

                // get user data
                $curl = curl_init();
                
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://api.medium.com/v1/me?accessToken=' . $token,CURLOPT_HEADER => false));
                
                // Send the request & save response to $resp
                $data = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);
                
                // Verify if data exists
                if ( $data ) {
                    
                    // Decode response
                    $udata = json_decode($data, true);
                    
                    // Verify if response is valid
                    if ( isset($udata['data']['name']) ) {
                        
                        // Get blog name
                        $name = $udata['data']['name'];
                        
                        // Get blog avatar
                        $imageUrl = $udata['data']['imageUrl'];
                        
                        // Get blog id
                        $id = $udata['data']['id'];
                        
                        if ( $refresh ) {
                            $expires_in = ' ';
                        }
                        
                        // Verify if blog was already saved
                        if ( !$this->CI->networks->get_network_data('medium', $this->CI->user_id, $id) ) {
                            
                            $this->CI->networks->add_network('medium', $id, $token, $this->CI->user_id, $expires_in, $name, $imageUrl, $refresh);
                            
                            $check = 1;
                            
                        } else {
                            
                            $check = 2;
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
        if ( $check === 1 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true); 
            
        } elseif ( $check === 2 ) {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', true);             
            
        }else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }
        
    }
    
    /**
     * The public method post publishes posts on Medium.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('medium', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('medium', $user_id, $args['account']);
            
        }
        
        try {
            
            // Generate new access token
            $accessToken = $this->medium->exchangeRefreshToken($user_details[0]->secret);
            
            // Verify if access token was generated
            if ( @$accessToken ) {
                
                // Set access token
                $this->medium->setAccessToken($accessToken);
                
                $data = [];
        
                $post = $args['post'];

                // Verify if url exists
                if ( $args['url'] ) {
                    $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
                } 
                
                // Verify if title exists
                if ( $args ['title'] ) {
                    
                    $data['title'] = $args['title'];
                    $data['content'] = $post;
                    
                } else {
                    
                    $data['content'] = $post;
                    
                }
                
                preg_match_all('/#([^\s]+)/', $data['content'], $hashtags);
                
                if ( isset($hashtags[0][0]) ) {
                    
                    $tags = array();
                    
                    foreach ( $hashtags as $hashtag ) {
                        
                        $tags[] = str_replace('#', '', $hashtag);
                        $data['content'] = str_replace($hashtag, '', $data['content']);
                        
                    }
                    
                    // Set tags
                    $data['tags'] = $tags[0];
                    
                }

                // Set post format
                $data['contentFormat'] = 'html';
                
                // Set post's status
                $data['publishStatus'] = 'public';
                
                // Publish the post
                $response = $this->medium->createPost($user_details[0]->net_id, $data);

                if ( $response ) {
                    
                    return true;
                    
                } else {
                    
                    // Save the error
                    $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', json_encode($response) );

                    // Then return falsed
                    return false;                    
                    
                }
                
            } else {
                
                // Save the error
                $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', 'Invalid access token.' );

                // Then return falsed
                return false;
                
            }
            
        } catch (Exception $e) {

            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());

            // Then return falsed
            return false;
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {
        
        return array(
            'color' => '#02b875',
            'icon' => '<i class="fab fa-medium"></i>',
            'api' => array('client_id', 'client_secret'),
            'types' => array('post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Medium.
     *
     * @param array $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($url = null) {
    }
    
}

/* End of file medium.php */
