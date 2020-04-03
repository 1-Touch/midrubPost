<?php
/**
 * Gmail
 *
 * PHP Version 7.2
 *
 * Connect to Gmail
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
 * Gmail class - allows users to connect to their Gmail account
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Gmail implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $connect, $client, $CI, $clientId, $clientSecret, $apiKey, $appName, $scriptUri;

    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Gmail's client_id
        $this->clientId = get_option('gmail_client_id');
        
        // Get the Gmail's client_secret
        $this->clientSecret = get_option('gmail_client_secret');
        
        // Get the Gmail's api key
        $this->apiKey = get_option('gmail_api_key');
        
        // Get the Gmail's application name
        $this->appName = get_option('gmail_google_application_name');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Require the  vendor's libraries
        require_once FCPATH . 'vendor/autoload.php';
        
        // Gmail Callback
        $this->scriptUri = site_url('user/callback/gmail');
        
    }

    /**
     * The public method check_availability checks if the Blogger api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if clientId, clientSecret and apiKey exists
        if ( ($this->clientId != '') AND ( $this->clientSecret != '') AND ( $this->apiKey != '') ) {
            
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
        
        // Call the class Google_Client
        $this->client = new \Google_Client();
        
        // Name of the google application
        $this->client->setApplicationName($this->appName);
        
        // Set the client_id
        $this->client->setClientId($this->clientId);
        
        // Set the client_secret
        $this->client->setClientSecret($this->clientSecret);
        
        // Redirects to same url
        $this->client->setRedirectUri($this->scriptUri);
        
        // Set the api key
        $this->client->setDeveloperKey($this->apiKey);
        
        // Load required scopes
        $this->client->setScopes(array('https://mail.google.com/ https://www.googleapis.com/auth/gmail.modify https://www.googleapis.com/auth/gmail.readonly https://www.googleapis.com/auth/userinfo.profile'));
        
        // Offline because we need to get refresh token
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');
        
        // Get the redirect url
        $authUrl = $this->client->createAuthUrl();
        
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
        if ( $this->CI->input->get('code', TRUE) ) {
            
            // Call the class Google_Client
            $this->client = new \Google_Client();
            
            // Offline because we need to get refresh token
            $this->client->setAccessType('offline');
            
            // Name of the google application
            $this->client->setApplicationName($this->appName);

            // Set the client_id
            $this->client->setClientId($this->clientId);

            // Set the client_secret
            $this->client->setClientSecret($this->clientSecret);

            // Redirects to same url
            $this->client->setRedirectUri($this->scriptUri);

            // Set the api key
            $this->client->setDeveloperKey($this->apiKey);
            
            // Send the received code
            $this->client->authenticate( $this->CI->input->get('code', TRUE) );
            
            // Get access token
            $token = $this->client->getAccessToken();
          
            // Set access token
            $this->client->setAccessToken($token);

            // Verify if token exists
            if ( isset($token['access_token']) ) {
                
                // Get refresh token
                $refresh = $token['refresh_token'];
                
                // Get expiration time
                $expires_in = '';
                
                // Get access token
                $token = $token['access_token'];
                
                // Get the user's profile
                $udata = get('https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token);

                // Veify if response is valid
                if ($udata) {
                    
                    // Decode response
                    $udecode = json_decode($udata, true);
                    
                    if ( isset($udecode['sub']) ) {
                                    
                        // Verify if the account already was saved
                        if ( !$this->CI->networks->get_network_data('gmail', $this->CI->user_id, $udecode['sub']) ) {

                            // Save
                            $this->CI->networks->add_network('gmail', $udecode['sub'], $token, $this->CI->user_id, $expires_in, $udecode['name'], $udecode['picture'], $refresh);

                        }

                        $check++;
                        
                    }
                    
                }
                
            }
            
        }
        
        if ( $check === 1 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true); 
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', false);             
            
        }
        
    }

    /**
     * The public method post publishes posts on Blogger.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post( $args, $user_id = null ) {
    }

    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network data
     */
    public function get_info() {

        return array(
            'color' => '#d63d3d',
            'icon' => '<i class="fab fa-google"></i>',
            'api' => array('client_id', 'client_secret', 'api_key', 'google_application_name'),
            'types' => array('email')
        );
        
    }

    /**
     * The public method preview generates a preview(deprecated)
     *
     * @param array $args contains the img or url.
     */
    public function preview($url = null) {
    }

}

/* End of file gmail.php */
