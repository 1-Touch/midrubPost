<?php
/**
 * Wordpress
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Wordpress
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
 * Wordpress class - allows users to connect to their Wordpress and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Wordpress implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI, $redirect_uri, $client_id, $client_secret;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get Wordpress client_id
        $this->client_id = get_option('wordpress_client_id');
        
        // Get Wordpress client_secret
        $this->client_secret = get_option('wordpress_client_secret');
        
        // Get Wordpress redirect
        $this->redirect_uri = site_url('user/callback/wordpress');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );

    }
    
    /**
     * The public method check_availability checks if the Wordpress api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if client_id and client_secret exists
        if ( ($this->client_id != '') AND ( $this->client_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method connect will redirect user to Wordpress login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Prepare params to send
        $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code'
        );

        // Generate redirect url
        $loginUrl = 'https://public-api.wordpress.com/oauth2/authorize?' . urldecode(http_build_query($params));

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
        
        // Verify if code exists
        if ( $this->CI->input->get('code', TRUE) ) {
            
            // Get access token
            $curl = curl_init('https://public-api.wordpress.com/oauth2/token');
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt(
                $curl, CURLOPT_POSTFIELDS, array(
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'code' => $this->CI->input->get('code', TRUE),
                    'redirect_uri' => $this->redirect_uri,
                    'grant_type' => 'authorization_code'
                )
            );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $auth = curl_exec($curl);
            curl_close($curl);
            
            // Decode response
            $secret = json_decode($auth, true);
            
            // Verify if token was get
            if ( isset($secret['access_token']) ) {
                
                // Set access token
                $token = $secret['access_token'];
                
                // Set blog_id
                $blog_id = $secret['blog_id'];
                
                // Set blog url
                $blog_url = $secret['blog_url'];
                
                // Get user's information
                $curl = curl_init('https://public-api.wordpress.com/rest/v1.1/me/');
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $me = json_decode(curl_exec($curl), true);
                curl_close($curl);
                
                // Default avatar
                $avatar = '';

                // Verify if avatar exists
                if ( isset($me['avatar_URL']) ) {

                    // Set avatar if exists
                    $avatar = $me['avatar_URL'];

                }
                
                // Verify if token and blog exists
                if ( ($token != '') AND ( $blog_id != '') ) {
                    
                    // Get url
                    $blog_url = str_replace(['http://', 'https://'], ['', ''], $blog_url);
                    
                    // Verify if account was added
                    if ( $this->CI->networks->check_account_was_added('wordpress', $blog_id, $this->CI->user_id) ) {
                        
                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_already_added') . ' Wordpress ' . $this->CI->lang->line('networks_change_your_account') . '</p>', false);
                        
                    } else {
                        
                        // Try to add an account
                        if ( $this->CI->networks->add_network('wordpress', $blog_id, $token, $this->CI->user_id, '', $blog_url, $avatar) ) {

                            // Display the success message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true);

                        } else {

                            // Display the error message
                            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', true);
    
                        }
                        
                    }
                    
                }
                
            }
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false); 
            
        }
        
    }
    
    /**
     * The public method post publishes posts on Wordpress.
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
            $user_details = $this->CI->networks->get_network_data('wordpress', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('wordpress', $user_id, $args['account']);
            
        }
        
        $data = [];
        
        $post = $args['post'];

        // Verify if url exists
        if ( $args['url'] ) {
            $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
        }
        
        // Verify if title is not empty
        if ( $args ['title'] ) {
            
            $data['title'] = $args ['title'];
            $data['content'] = $post;
            
        } else {
            
            $data['content'] = $post;
            
        }
        
        // Verify if image exists
        if ( $args ['img'] ) {
            
            $data['media_urls[]'] = $args['img'][0]['body'];
            
        }
        
        // Check if category exists
        if ( $args['category'] ) {
            
            $category = json_decode($args['category'], true);

            if ( isset($category[$args['account']]) ) {
                
                $data['categories'] = array($category[$args['account']]);
                
            }
            
        }

        $options = array(
            'http' =>
            array(
                'ignore_errors' => true,
                'method' => 'POST',
                'header' =>
                array(
                    0 => 'authorization: Bearer ' . $user_details[0]->token,
                    1 => 'Content-Type: application/x-www-form-urlencoded',
                ),
                'content' =>
                http_build_query($data),
            ),
        );

        $context = stream_context_create($options);
        
        $response = file_get_contents(
                'https://public-api.wordpress.com/rest/v1.1/sites/' . $user_details[0]->net_id . '/posts/new/', false, $context
        );

        $response = json_decode($response, true);
        
        // Verify if post was published
        if ( isset($response['ID']) ) {
            
            return true;
            
        } else {

            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($response) );

            // Then return falsed
            return false;
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network data
     */
    public function get_info() {
        
        return array(
            'color' => '#0090bb',
            'icon' => '<i class="fab fa-wordpress"></i>',
            'api' => array('client_id', 'client_secret'),
            'types' => array('post', 'rss', 'categories')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Wordpress.
     *
     * @param array $args contains the img or url.
     * 
     * @return array with html data
     */
    public function preview($url = null) {
    }
    
}

/* End of file wordpress.php */
