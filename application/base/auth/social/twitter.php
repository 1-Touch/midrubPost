<?php
/**
 * Twitter
 *
 * PHP Version 5.6
 *
 * Connect and and sign up with Twitter
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */

// Define the file namespace
namespace MidrubBase\Auth\Social;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\Auth\Interfaces as MidrubBaseAuthInterfaces;
use Abraham\TwitterOAuth\TwitterOAuth;

// If session valiable doesn't exists will be created
if (!isset($_SESSION)) {
    session_start();
}

/**
 * Twitter class - connect and sign up with Twitter
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Twitter implements MidrubBaseAuthInterfaces\Social {

    /**
     * Class variables
     */
    public $CI, $connection, $twitter_key, $twitter_secret, $redirect_url;

    /**
     * Initialize the class
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Set the Twitter app key
        $this->twitter_key = get_option('twitter_auth_api_key');
        
        // Set the Twitter app secret
        $this->twitter_secret = get_option('twitter_auth_api_secret');
        
        // Load the Twtter dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
        // Connect to Twitter api
        $this->connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret);
        
        // Set timeout
        $this->connection->setTimeouts(10, 15);
        
    }

    /**
     * The public method check_availability verifies if social class is configured
     *
     * @return boolean true or false
     */
    public function check_availability() {

        if ( ($this->twitter_key != '') AND ( $this->twitter_secret != '') ) {
            
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

        // Set redirect
        $this->redirect_url = $redirect_url;

        // Set the callback 
        $request_token = $this->connection->oauth('oauth/request_token', array('oauth_callback' => $this->redirect_url));
        
        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        
        // Create the redirect url
        $url = $this->connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']) );
        
        // Redirect user
        header('Location: ' . $url);
    }

    /**
     * The public method save gets the access token and saves it
     * 
     * @param string $redirect_url contains the redirect's url
     * 
     * @return array with response
     */ 
    public function save($redirect_url=NULL) {
        
        // Verify if return code exists
        if ( $this->CI->input->get('oauth_verifier', TRUE) ) {

            // this function will get access token
            if ($this->CI->input->get('denied', TRUE)) {

                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('auth_an_error_occurred')
                ); 

            }

            $twitterOauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            
            $twitterOauth->setTimeouts(10, 15);
            
            $twToken = $twitterOauth->oauth('oauth/access_token', array('oauth_verifier' => $this->CI->input->get('oauth_verifier', TRUE)));
            
            $newTwitterOauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $twToken['oauth_token'], $twToken['oauth_token_secret']);
            
            $newTwitterOauth->setTimeouts(10, 15);
            
            $response = (array) $newTwitterOauth->get('account/verify_credentials', array('include_email' => 'true') );
            
            if ( $twToken['oauth_token'] ) {

                // Verify if email exists
                if (!isset($response['email'])) {

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
                $user_args['username'] = 't.' . $response['id'];

                // Set the email
                $user_args['email'] = trim($response['email']);

                // Set the password
                $user_args['password'] = $this->CI->bcrypt->hash_password(uniqid());

                // Set first name
                $user_args['first_name'] = $response['screen_name'];

                // Set the default status
                $user_args['status'] = 1;

                // Set date when user joined
                $user_args['date_joined'] = date('Y-m-d H:i:s');

                // Set user's ip
                $user_args['ip_address'] = $this->CI->input->ip_address();

                // Verify if email already exists
                if ($this->CI->base_users->get_user_ceil('email', $user_args['email'])) {

                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('auth_email_was_found_in_the_database')
                    );
                    
                }

                // Save the user
                $user_id = $this->CI->base_model->insert('users', $user_args);

                // Verify if user was saved successfully
                if ($user_id) {

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
                    $this->CI->session->set_userdata('username', 't.' . $response['id']);

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

        // Verify if return code exists
        if ( $this->CI->input->get('oauth_verifier', TRUE) ) {

            // this function will get access token
            if ($this->CI->input->get('denied', TRUE)) {

                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('auth_an_error_occurred')
                ); 

            }

            $twitterOauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            
            $twitterOauth->setTimeouts(10, 15);
            
            $twToken = $twitterOauth->oauth('oauth/access_token', array('oauth_verifier' => $this->CI->input->get('oauth_verifier', TRUE)));
            
            $newTwitterOauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $twToken['oauth_token'], $twToken['oauth_token_secret']);
            
            $newTwitterOauth->setTimeouts(10, 15);
            
            $response = (array) $newTwitterOauth->get('account/verify_credentials', array('include_email' => 'true') );
            
            if ( $twToken['oauth_token'] ) {

                // Verify if email's address exists
                if (isset($response['email'])) {

                    // Get user from database by twitter id
                    $get_user = $this->CI->base_model->get_data_where('users', 'username', array('username' => 't.' . $response['id'], 'email' => $response['email']));

                    // Verify if user exists
                    if ($get_user) {

                        // Register session
                        $this->CI->session->set_userdata('username', 't.' . $response['id']);

                        return array(
                            'success' => TRUE
                        );

                    } else {

                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('auth_no_account_found')
                        );

                    }

                } else {

                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('auth_no_email_found')
                    );

                }
                
            } else {
                
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('auth_an_error_occurred')
                ); 
                
            }
        
        }

    }

    /**
     * The public method get_info displays information about this class
     * 
     * @return object with network data
     */
    public function get_info() {
        
        return (object) array(
            'color' => '#1da1f2',
            'icon' => '<i class="fab fa-twitter"></i>',
            'api' => array('api_key', 'api_secret')
        );
        
    }

}
