<?php
/**
 * Facebook
 *
 * PHP Version 5.6
 *
 * Connect and and sign up with Facebook
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */

 // Define the file namespace
namespace MidrubBase\Auth\Social;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Auth\Interfaces as MidrubBaseAuthInterfaces;

/**
 * Facebook class - connect and sign up with Facebook
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Facebook implements MidrubBaseAuthInterfaces\Social {

    /**
     * Class variables
     */
    public $CI, $fb, $app_id, $app_secret;

    /**
     * Initialize the class
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Facebook app id
        $this->app_id = get_option('facebook_auth_app_id');
        
        // Get the Facebook app secret
        $this->app_secret = get_option('facebook_auth_app_secret');
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
            
        if (($this->app_id != '') AND ( $this->app_secret != '')) {
            
            $this->fb = new \Facebook\Facebook([
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => 'v3.3',
                'default_access_token' => '{access-token}',
            ]);
            
        }
        
    }

    /**
     * The public method check_availability verifies if social class is configured
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        if ( ($this->app_id != '') AND ( $this->app_secret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method connect redirects user to social network where should approve permissions
     * 
     * @param string $redirect_url contains the redirect's url
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function connect($redirect_url=NULL) {

        $helper = $this->fb->getRedirectLoginHelper();
        
        // We need only email permission
        $permissions = array('email');

        // Create the redirect url
        $loginUrl = $helper->getLoginUrl($redirect_url, $permissions);
        
        // Redirect user
        header('Location:' . $loginUrl);
        
    }

    /**
     * The public method save gets the access token and saves it
     * 
     * @param string $redirect_url contains the redirect's url
     * 
     * @return array with response
     */ 
    public function save($redirect_url=NULL) {

        // This function will get access token
        try {
            
            $helper = $this->fb->getRedirectLoginHelper();
            $access_token = $helper->getAccessToken($redirect_url);
            $access_token = (array) $access_token;
            $access_token = array_values($access_token);
            
            if (isset($access_token[0])) {

                // Get cURL resource
                $curl = curl_init();
                
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://graph.facebook.com/me?fields=id,name,email&access_token=' . $access_token[0], CURLOPT_HEADER => false));
                
                // Send the request
                $response = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);

                // Gets user's data
                $getUserdata = json_decode($response, true);

                if ( isset($getUserdata['id']) ) {

                    // Verify if email exists
                    if ( !isset($getUserdata['email']) ) {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('auth_no_email_found')
                        );

                    }

                    // Load the Base Users Model
                    $this->CI->load->ext_model(MIDRUB_BASE_PATH . 'models/', 'Base_users', 'base_users');

                    // Load the bcrypt library
                    $this->CI->load->library('bcrypt');

                    // Create $user_args array
                    $user_args = array();

                    // Set the user name
                    $user_args['username'] = 'f.' . $getUserdata['id'];

                    // Set the email
                    $user_args['email'] = trim($getUserdata['email']);

                    // Set the password
                    $user_args['password'] = $this->CI->bcrypt->hash_password(uniqid());

                    // Set first name
                    $user_args['first_name'] = $getUserdata['name'];

                    // Set the default status
                    $user_args['status'] = 1;

                    // Set date when user joined
                    $user_args['date_joined'] = date('Y-m-d H:i:s');

                    // Set user's ip
                    $user_args['ip_address'] = $this->CI->input->ip_address();

                    // Verify if email already exists
                    if ( $this->CI->base_users->get_user_ceil('email', $user_args['email']) ) {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('auth_email_was_found_in_the_database')
                        );

                    }

                    // Save the user
                    $user_id = $this->CI->base_model->insert('users', $user_args);

                    // Verify if user was saved successfully
                    if ( $user_id ) {

                        // Set default user's plan
                        $this->CI->plans->change_plan(1, $user_id);

                        // Verify if the user has a referrer
                        if ($this->CI->session->userdata('referrer')) {

                            // Get referrer
                            $referrer = base64_decode($this->CI->session->userdata('referrer'));

                            // Verify if referrer is valid
                            if (is_numeric($referrer)) {

                                // Load Referrals model
                                $this->CI->load->model('referrals');

                                // Save referral
                                $this->CI->referrals->save_referrals($referrer, $user_id, 1);

                                // Delete session
                                $this->CI->session->unset_userdata('referrer');
                            }

                        }

                        // Register session
                        $this->CI->session->set_userdata('username', 'f.' . $getUserdata['id']);

                        return array(
                            'success' => TRUE
                        );

                    } else {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('auth_registtration_failed')
                        );                        

                    }
                    
                } else {

                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('auth_an_error_occurred')
                    );    
                    
                }
                
            } else {
                
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('auth_an_error_occurred')
                );  
                
            }
            
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {

            // When Graph returns an error
            return array(
                'success' => FALSE,
                'message' => $e->getMessage()
            ); 
            
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            
            // When validation fails or other local issues
            return array(
                'success' => FALSE,
                'message' => $e->getMessage()
            );             
            
        }
        
    }

    /**
     * The public method login uses the access token to verify if user is register already
     * 
     * @param string $redirect_url contains the redirect url
     * 
     * @since 0.0.7.8
     * 
     * @return array with response
     */
    public function login($redirect_url=NULL) {

        // This function will get access token
        try {
            
            $helper = $this->fb->getRedirectLoginHelper();
            $access_token = $helper->getAccessToken($redirect_url);
            $access_token = (array) $access_token;
            $access_token = array_values($access_token);
            
            if (isset($access_token[0])) {

                // Get cURL resource
                $curl = curl_init();
                
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://graph.facebook.com/me?fields=id,name,email&access_token=' . $access_token[0], CURLOPT_HEADER => false));
                
                // Send the request
                $response = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);

                // Gets user's data
                $getUserdata = json_decode($response, true);

                if ( isset($getUserdata['id']) ) {

                    // Verify if email exists
                    if ( !isset($getUserdata['email']) ) {
                        
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('auth_no_email_found')
                        );
                        
                    }

                    // Get user from database by Facebook id
                    $get_user = $this->CI->base_model->get_data_where('users', 'username', array('username' => 'f.' . $getUserdata['id']));

                    // Verify if user exists
                    if ( $get_user ) {

                        // Register session
                        $this->CI->session->set_userdata('username', 'f.' . $getUserdata['id']);

                        return array(
                            'success' => TRUE
                        );

                    } else {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('auth_no_account_found')
                        );                        

                    }

                }
                
            }

            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('auth_an_error_occurred')
            );  
            
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            
            // When Graph returns an error
            return array(
                'success' => FALSE,
                'message' => $e->getMessage()
            );  
            
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            
            // When validation fails or other local issues
            return array(
                'success' => FALSE,
                'message' => $e->getMessage()
            );  
            
        }

    }

    /**
     * The public method get_info displays information about this class
     * 
     * @return object with network data
     */
    public function get_info() {

        return (object)array(
            'color' => '#3b5998',
            'icon' => '<i class="fab fa-facebook-f"></i>',
            'api' => array('app_id', 'app_secret')
        );
        
    }

}
