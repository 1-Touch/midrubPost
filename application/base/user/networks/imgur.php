<?php
/**
 * Imgur
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Imgur
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
 * Imgur class - allows users to connect to their Imgur Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Imgur implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $api, $callback, $CI, $clientId, $clientSecret;

    /**
     * Load networks and user model.
     */
    public function __construct() {

        // Get the CodeIgniter super object
        $this->CI = & get_instance();

        // Get the Imgur's client_id
        $this->clientId = get_option('imgur_client_id');

        // Get the Imgur's client_secret
        $this->clientSecret = get_option('imgur_client_secret');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );

        // Get redirect url
        $this->callback = site_url('user/callback/imgur');
    }

    /**
     * The public method check_availability checks if the Imgur api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {

        // Verify if clientId and clientSecret is not empty
        if ( ($this->clientId != '') and ( $this->clientSecret != '') ) {

            return true;

        } else {

            return false;

        }

    }

    /**
     * The public method connect will redirect user to Google login page.
     * 
     * @return void
     */
    public function connect() {

        // Get redirect url
        $authUrl = 'https://api.imgur.com/oauth2/authorize?client_id=' . $this->clientId . '&response_type=code&state=code';

        // Redirect
        header('Location:' . $authUrl);

    }

    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return boolean true or false
     */
    public function save($token = null) {

        // Define the callback status
        $check = 0;

        // Verify if code exists
        if ($this->CI->input->get('code', TRUE)) {

            // Generate access token
            $curl = curl_init('https://api.imgur.com/oauth2/token');
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt(
                $curl,
                CURLOPT_POSTFIELDS,
                array(
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code' => $this->CI->input->get('code', TRUE),
                    'redirect_uri' => $this->callback,
                    'grant_type' => 'authorization_code'
                )
            );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            curl_close($curl);

            // Decode response
            $data = json_decode($data, true);

            // Verify if access token exists
            if ( $data['access_token']) {

                // Get refresh token
                $refresh = $data['refresh_token'];

                // Get access token
                $token = $data['access_token'];

                // Verify if the account was already saved
                if (!$this->CI->networks->get_network_data('imgur', $data['account_id'], $this->CI->user_id)) {

                    // Save account
                    $this->CI->networks->add_network('imgur', $data['account_id'], $token, $this->CI->user_id, '', $data['account_username'], '', $refresh);

                    $check = 1;

                } else {

                    $check = 2;

                }

            }

        }

        if ($check === 1) {

            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('your_account_was_connected') . '</p>', true);
        } elseif ($check === 2) {

            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('your_account_was_not_connected') . '</p>', true);
        } else {

            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);
        }
    }

    /**
     * The public method post publishes posts on Imgur.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {

        // Verify if user_id exists
        if ($user_id) {

            // Get account details
            $con = $this->CI->networks->get_network_data('imgur', $user_id, $args ['account']);

        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $con = $this->CI->networks->get_network_data('imgur', $user_id, $args ['account']);

        }

        // Verify if user's account exists
        if ($con) {

            // Verify if the secret column is not empty
            if ( $con[0]->secret ) {

                try {

                    // Refresh the token 
                    $curl = curl_init('https://api.imgur.com/oauth2/token');
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt(
                        $curl,
                        CURLOPT_POSTFIELDS,
                        array(
                            'client_id' => $this->clientId,
                            'client_secret' => $this->clientSecret,
                            'refresh_token' => $con[0]->secret,
                            'grant_type' => 'refresh_token'
                        )
                    );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $data = curl_exec($curl);
                    curl_close($curl);

                    // Decode response
                    $data = json_decode($data);

                    // Get post data
                    $title = $args['post'];

                    $post = '';

                    // If title is not empty
                    if ($args['title']) {

                        // Set the title
                        $title = $args['title'];

                        // Set post's body
                        $post = $args['post'];
                    }
                    
                    $tags = '';
                    
                    preg_match_all('/#([^\s]+)/', $args['post'], $hashtags);
                    
                    if (isset($hashtags[0][0])) {

                        $tags = array();

                        foreach ($hashtags as $hashtag) {

                            $tags[] = str_replace('#', '', $hashtag);
                            
                            if ($args['title']) {
                            
                                $post = str_replace($hashtag, '', $post);
                                
                            } else {
                                
                                $title = str_replace($hashtag, '', $title);
                                
                            }
                            
                        }

                        // Set tags
                        $tags = implode( ',', $tags[0]);
                    }

                    // Verify if url exists
                    if ($args['url']) {
                        $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
                    }

                    // Verify if token exists
                    if (@$data->access_token) {

                        $token = $data->access_token;

                        $post_data = array(
                            'title' => $title,
                            'description' => $post,
                            'image' => $args['img'][0]['body']
                        );

                        // Verify if category was selected
                        if ($args['category']) {

                            $category = json_decode($args['category'], true);

                            if (@$category[$args['account']]) {

                                $post_data['album'] = $category[$args['account']];
                            }
                        }

                        // Upload the image
                        $curl = curl_init('https://api.imgur.com/3/image');
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
                        curl_setopt(
                                $curl, CURLOPT_POSTFIELDS, $post_data
                        );
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        $data = curl_exec($curl);
                        curl_close($curl);

                        // Decode the response
                        $data = json_decode($data);

                        // Verify if the post was published
                        if (@$data->data->title) {

                            $post_data = array(
                                'title' => $title,
                                'terms' => 1
                            );
                            
                            if ( $tags ) {
                                
                                $post_data['tags'] = $tags;
                                
                            }

                            // Upload the image
                            $curl = curl_init('https://api.imgur.com/3/gallery/image/' . $data->data->id);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
                            curl_setopt(
                                    $curl, CURLOPT_POSTFIELDS, $post_data
                            );
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            $data = curl_exec($curl);
                            curl_close($curl);

                            return true;
                            
                        } else {

                            // Save the error
                            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($data));
                            
                            return false;
                            
                        }
                        
                    } else {

                        // Save the error
                        $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', 'Invalid access token.');

                        // Then return false
                        return false;
                    }
                    
                } catch (Exception $e) {

                    // Save the error
                    $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());

                    // Then return false
                    return false;
                    
                }
                
            }
            
        }
        
    }

    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {

        return array(
            'color' => '#1bb76e',
            'icon' => '<i class="fa fa-info" aria-hidden="true"></i>',
            'api' => array(
                'client_id',
                'client_secret',
            ),
            'types' => array('post', 'rss', 'categories')
        );
    }

    /**
     * The public method preview generates a preview for Midrub
     *
     * @param $args contains the image or url
     * 
     * @return array with html coontent
     */
    public function preview($args) {
        
    }

}

/* End of file imgur.php */
