<?php
/**
 * Dailymotion
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Dailymotion
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
 * Dailymotion class - allows users to connect to their Dailymotion channels and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Dailymotion implements MidrubBaseUserInterfaces\Networks {

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
        
        // Get the Dailymotion's client_id
        $this->clientId = get_option('dailymotion_client_id');
        
        // Get the Dailymotion's client_secret
        $this->clientSecret = get_option('dailymotion_client_secret');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // The callback
        $this->callback = site_url('user/callback/dailymotion');
        
    }

    /**
     * The public method check_availability checks if the Dailymotion api is configured correctly
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if app_id and app_secret exists
        if ( ($this->clientId != '') and ( $this->clientSecret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
    }

    /**
     * The public method connect will redirect user to Dailymotion login page
     * 
     * @return void
     */
    public function connect() {
        
        // Get the redirect url
        $authUrl = 'https://www.dailymotion.com/oauth/authorize?response_type=code&client_id=' . $this->clientId . '&scope=manage_videos&redirect_uri=' . $this->callback;
        
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
    public function save( $token = null ) {
        
        // Define the callback status
        $check = 0;
        
        // Verify if code exists
        if ( $this->CI->input->get('code', TRUE) ) {
            
            // Get the token
            $curl = curl_init("https://api.dailymotion.com/oauth/token");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt(
                    $curl, CURLOPT_POSTFIELDS, [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $this->CI->input->get('code', TRUE),
                'redirect_uri' => $this->callback,
                'grant_type' => 'authorization_code'
                    ]
            );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($data);
            
            // Verify if the token is valid
            if ( @$data->access_token ) {
                
                // Get refresh token
                $refresh = $data->refresh_token;
                
                // Get access token
                $token = $data->access_token;
                
                // we will use the token to get user data
                $curl = curl_init();
                
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://api.dailymotion.com/me?access_token=' . $token,
                    CURLOPT_HEADER => false
                ));
                
                // Send the request & save response to $resp
                $udata = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);
                
                // Verify if the request was done successfully
                if ( $udata ) {
                    
                    $udecode = json_decode($udata, true);
                    
                    if ( isset($udecode['id']) ) {
                        
                        $name = $udecode['screenname'];
                        
                        if ( !$this->CI->networks->get_network_data('dailymotion', $this->CI->user_id, $udecode['id']) ) {
                            
                            $this->CI->networks->add_network('dailymotion', $udecode['id'], $token, $this->CI->user_id, '', $name, '', $refresh);
                            
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
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', false);             
            
        }else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }
        
    }

    /**
     * The public method post publishes posts on Dailymotion.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post( $args, $user_id = null ) {
        
        // Get user details
        if ( $user_id ) {
            
            // Get account details
            $con = $this->CI->networks->get_network_data('dailymotion', $user_id, $args ['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $con = $this->CI->networks->get_network_data('dailymotion', $user_id, $args ['account']);
            
        }
        
        // Verify if video exists
        if ( !$args['video'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_a_video_is_required_to_publish_here')));

            // Then return false
            return false;
            
        }
        
        // Verify if user social account exists
        if ( $con ) {
            
            // Get the secret
            if ( $con [0]->secret ) {
                
                try {
                    
                    $title = '';
                    $description = '';
                    $post = $args['post'];
                    
                    // Verify if url exists
                    if ( $args['url'] ) {
                        $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
                    }
                    
                    if ( $args['title'] ) {
                        $title = $args['title'];
                        $description = $post;
                    } else {
                        $title = $post;
                    }
                    
                    // Upload the video
                    $video = $args['video'][0]['body'];
                    
                    $curl = curl_init('https://api.dailymotion.com/oauth/token');
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt(
                        $curl, CURLOPT_POSTFIELDS, array(
                            'client_id' => $this->clientId,
                            'client_secret' => $this->clientSecret,
                            'refresh_token' => $con[0]->secret,
                            'grant_type' => 'refresh_token'
                        )
                    );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    
                    $data = curl_exec($curl);
                    
                    curl_close($curl);
                    
                    
                    $data = json_decode($data);
                    
                    $token = @$data->access_token;
                    
                    $curl = curl_init('https://api.dailymotion.com/videos');
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt(
                        $curl, CURLOPT_POSTFIELDS, [
                            'url' => $video,
                            'title' => $title,
                            'description' => $description,
                            'published' => true,
                            'access_token' => $token
                        ]
                    );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $data = curl_exec($curl);
                    curl_close($curl);
                    $data = json_decode($data);

                    // Verify if the video was uploaded
                    if ( $data ) {

                        return true;

                    } else {

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
     * The public method get_info displays information about this class
     * 
     * @return array with social network information
     */
    public function get_info() {
        
        return array(
            'color' => '#0066dc',
            'icon' => '<i class="fas fa-video"></i>',
            'api' => array(
                'client_id',
                'client_secret',
            ),
            'types' => array('post')
        );
        
    }

    /**
     * The public method preview generates a preview for Dailymotion
     *
     * @param array $args contains the video or url
     * 
     * @return array with html preview
     */
    public function preview($args) {
    }

}

/* End of file dailymotion.php */
