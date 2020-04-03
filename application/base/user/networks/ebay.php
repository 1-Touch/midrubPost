<?php
/**
 * Ebay
 *
 * PHP Version 7.2
 *
 * Connect to Ebay
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
 * Ebay class - allows users to connect to their Ebay Account
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Ebay implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI, $app_id, $dev_id, $cert_id, $redirect_url, $ru_name;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();

        // Get Ebay's app ID
        $this->app_id = get_option('ebay_app_id');
        
        // Get Ebay's dev ID
        $this->dev_id = get_option('ebay_dev_id');

        // Get Ebay's cert ID
        $this->cert_id = get_option('ebay_cert_id');

        // Get Ebay's RuName
        $this->ru_name = get_option('ebay_ru_name');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Ebay Callback
        $this->redirect_url = site_url('user/callback/ebay');
        
    }
    
    /**
     * The public method check_availability checks if the Telegram api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {

        if ( ($this->app_id !== "") && ($this->dev_id !== "") && ($this->cert_id !== "") && ($this->ru_name !== "") ) {
            return true;
        } else {
            return false;
        }

    }
    
    /**
     * The public method connect will redirect user to Telegram login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Params for request
        $params = array(
            'client_id' => $this->app_id,
            'redirect_uri' => $this->ru_name,
            'prompt' => 'login',
            'state' => time(),
            'scope' => 'https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly',
            'response_type' => 'code'
        );

        // Get redirect url
        $loginUrl = 'https://auth.ebay.com/oauth2/authorize?' . urldecode(http_build_query($params));

        // Redirect
        header('Location:' . $loginUrl);
        
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

            // Params for request
            $params = array(
                'grant_type' => 'authorization_code',
                'code' => $this->CI->input->get('code', TRUE),
                'redirect_uri' => $this->ru_name
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,"https://api.ebay.com/identity/v1/oauth2/token");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . base64_encode($this->app_id . ':' . $this->cert_id)
                )
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode( curl_exec ($ch), true );
            curl_close ($ch);

            if ( isset($response['access_token']) ) {

                $feed = '<?xml version="1.0" encoding="utf-8"?>'
                    . '<GetUserRequest xmlns="urn:ebay:apis:eBLBaseComponents">'
                        . '<Version>1085</Version>'
                        . '<MessageID>XML call: OAuth Token in trading</MessageID>'
                        . '<DetailLevel>ReturnAll</DetailLevel>'
                    . '</GetUserRequest>';

                $feed = trim($feed);
                $headers = array
                (
                'X-EBAY-API-COMPATIBILITY-LEVEL: 1085',
                    'X-EBAY-API-IAF-TOKEN: ' . $response['access_token'],
                    'X-EBAY-API-CALL-NAME: GetUser',
                    'X-EBAY-API-SITEID: 0'
                );
                
                // Send request to eBay and load response in $response
                $connection = curl_init();
                curl_setopt($connection, CURLOPT_URL, "https://api.ebay.com/ws/api.dll");
                curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($connection, CURLOPT_POST, 1);
                curl_setopt($connection, CURLOPT_POSTFIELDS, $feed);
                curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
                $res = curl_exec($connection);
                curl_close($connection);
                
                $xml = simplexml_load_string($res);
                $jsonArr = json_encode($xml);
                $data = json_decode($jsonArr,TRUE);
                
                if ( isset($data['Ack']) ) {

                    if ( $data['Ack'] === 'Success' ) {

                        // Verify if the account already exists
                        if ( $this->CI->networks->check_account_was_added('ebay', $data['User']['UserID'], $this->CI->user_id) ) {

                            $check = 2;

                        } else {

                            $this->CI->networks->add_network('ebay', $data['User']['UserID'], $response['access_token'], $this->CI->user_id, '', $data['User']['UserID'], '', $response['refresh_token']);

                            $check = 1;

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
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }

    }
    
    /**
     * The public method post publishes posts on Telegram Channels.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return object with network's data
     */
    public function get_info() {
        
        return array(
            'color' => '#febf2c',
            'icon' => '<i class="fab fa-ebay"></i>',
            'api' => array('app_id', 'dev_id', 'cert_id', 'ru_name'),
            'types' => array('markets', 'ebay')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Telegram Channels.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
        
    }

}

/* End of file ebay.php */
