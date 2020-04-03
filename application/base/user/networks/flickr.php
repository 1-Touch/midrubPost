<?php
/**
 * Flickr
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Flickr
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
 * Flickr class - allows users to connect to their Flickr Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Flickr implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $app_key, $app_secret, $flickr;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get Flickr app key
        $this->app_key = get_option('flickr_app_key');
        
        // Get Flickr app key
        $this->app_secret = get_option('flickr_app_secret');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Verify if the class Felper exists
        if(file_exists(FCPATH.'vendor/felper/Felper.php')){
            
            // Require the class Felper
            include_once FCPATH . 'vendor/felper/Felper.php';
            
            // Call the class Felper
            $this->flickr = new \Felper($this->app_key, $this->app_secret, site_url('user/callback/flickr') );
        }
        
    }
    /**
     * The public method check_availability checks if the Flickr api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if app_key and app_secret exists
        if ( ($this->app_key != '') AND ( $this->app_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    /**
     * The public method connect will redirect user to Flickr login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Generate redirect url
        $this->flickr->authenticate('write');
        
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
        
        // Get response
        $res = $this->flickr->authenticate('write');
        
        // Verify if the response is correct
        if ( isset($res[0]) && isset($res[1]) && isset($res[2]) ) {
            
            // Set the username
            $username = $res[2];
            
            // Set the token
            $token = $res[0];
            
            // Set the secret
            $secret = $res[1];
            
            // Verify if the account already exists
            if ( $this->CI->networks->check_account_was_added('flickr', $username, $this->CI->user_id) ) {
                
                $check = 2;
               
            } else {
                
                $this->CI->networks->add_network('Flickr', $username, $token, $this->CI->user_id, '', $username, '', $secret);
                
                $check = 1;

            }
            
        }
        
        if ( $check === 1 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true); 
            
        } elseif ( $check === 2 ) {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', false);             
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }
        
    }
    
    /**
     * The public method post publishes posts on Flickr.
     *
     * @param  $args contains the post data.
     * @param  $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('flickr', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('flickr', $user_id, $args['account']);
            
        }
        
        // Verify if image exists
        if ( !$args['img'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_a_photo_is_required_to_publish_here')));

            // Then return false
            return false;
            
        }
        
        // check if the image is loaded on server
        $im = explode(base_url(), $args['img'][0]['body']);
        
        if ( isset($im[1]) ) {
            
            // Get the photo path
            $rep = str_replace(base_url(), FCPATH, $args['img'][0]['body']);
            $file = new \CurlFile($rep);
            
        } else {
            
            $curl = curl_init();
            
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_BINARYTRANSFER => 1, CURLOPT_URL => $args['img'][0]['body'], CURLOPT_HEADER => false));
            
            // Send the request & save response to $resp
            $in = curl_exec($curl);
            
            // Close request to clear up some resources
            curl_close($curl);
            
            if ( $in ) {
                
                $filename = FCPATH . 'assets/share/' . uniqid() . time() . '.png';
                
                file_put_contents($filename, $in);
                
                if ( file_exists($filename) ) {
                    
                    $file = new \CurlFile($filename);
                    
                } else {
                    
                    return false;
                    
                }
                
            } else {
                
                return false;
                
            }
            
        }
        
        // Verify if the title exists
        if ( $args['title'] ) {
            
            $data['title'] = $args ['title'];
            $data['description'] = $args['post'];
            
            // Verify if url exists
            if ($args['url'] ) {
                $data['description'] = $data['description'] . ' ' . short_url($args['url']);
            }
            
        } else {
            
            $data['title'] = $args['post'];
            
            // Verify if url exists
            if ($args['url'] ) {
                $data['description'] = short_url($args['url']);
            }
            
        }
        
        $data['api_key'] = $this->app_key;
        
        ksort($data);
        
        $auth_sig = '';
        
        foreach ( $data as $key => $value ) {
            
            if ( is_null($value) ) {
                
                unset($data[$key]);
                
            } else {
                
                $auth_sig .= $key . $value;
                
            }
        }
        
        $data['photo'] = $file;
        
        try {
            
            // Upload the photo
            $result = $this->flickr->upload($data, $user_details[0]->token, $user_details[0]->secret);
            
            // Verify if the photo was uploaded
            if ( preg_match('/photoid/i', $result) ) {
                
                return true;
                
            } else {

                // Save the error
                $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($result));

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
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with social network information
     */
    public function get_info() {
        
        return array(
            'color' => '#ff007f',
            'icon' => '<i class="fab fa-flickr"></i>',
            'api' => array('app_key', 'app_secret'),
            'types' => array('post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Flickr.
     *
     * @param array $args contains the img or url.
     * 
     * @return array with html code
     */
    public function preview($args) {}

}

/* End of file flickr.php */
