<?php
/**
 * Twilio
 *
 * PHP Version 7.2
 *
 * Connect the Twilio account
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Networks;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;

/**
 * Twilio class - allows users to connect their Twilio phone numbers
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Twilio implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI, $check;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
    }
    
    /**
     * The public method check_availability for Pinterest's Boards will return always true
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        return true;
        
    }
    
    /**
     * The public method connect will redirect user to login page
     *
     * @return void
     */
    public function connect() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('account_sid', 'Account SID', 'trim|required');
            $this->CI->form_validation->set_rules('auth_token', 'Auth Token', 'trim|required');
            
            // Get post data
            $account_sid = $this->CI->input->post('account_sid');
            $auth_token = $this->CI->input->post('auth_token');
            
            // Verify if form data is valid
            if ($this->CI->form_validation->run() == false) {
                
                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);
                
            } else {

                // Prepare the url
                $url = 'https://api.twilio.com/2010-04-01/Accounts/' . $account_sid . '/IncomingPhoneNumbers.json';

                // Request for phone numbers
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($curl, CURLOPT_USERPWD, $account_sid . ':' . $auth_token);
                $result = json_decode(curl_exec($curl), true);
                curl_close($curl);

                // Verify if phone numbers exists
                if ( isset($result['incoming_phone_numbers']) ) {

                    // List all Phone numbers
                    foreach ( $result['incoming_phone_numbers'] as $phone ) {

                        // Get the sid
                        $sid = $phone['sid'];
                        
                        // Get the phone's number
                        $phone_number = $phone['phone_number'];

                        // Verify if the phone number exists
                        if ( !$this->CI->networks->get_network_data('twilio', $this->CI->user_id, $sid) ) {
                            
                            // Save phone number
                            $this->CI->networks->add_network( 'twilio', $sid, $account_sid,$this->CI->user_id, '', $phone_number, '', $auth_token );
                            
                        }

                    }

                    // Display the success message
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_phone_numbers_connected') . '</p>', true);
                    
                } else {
                    
                    // Display the error message
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_phone_numbers_connected') . '</p>', false);
                    
                }
                
            }
            
            exit();
            
        }
        
        // Display the login form
        echo get_instance()->ecl('Social_login')->content('Account SID', 'Auth Token', 'Connect', $this->get_info(), 'Twilio', '');
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     *
     * @return void
     */
    public function save($token = null) {
        
    }
    
    /**
     * The public method post publishes posts on Pinterest's Boards
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     *
     * @return boolean true or false
     */
    public function post($args, $user_id = null) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $con = $this->CI->networks->get_network_data('pinterest_bot', $user_id, $args ['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $con = $this->CI->networks->get_network_data('pinterest_bot', $user_id, $args ['account']);
            
        }
        
        // Verify if image exists
        if ( !$args['img'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_a_photo_is_required_to_publish_here')));
            
            // Then return false
            return false;
            
        }
        
        // Verify if account exists
        if ( $con ) {
            
            // Verify if token exists
            if ( $con [0]->token ) {
                
                try {
                    
                    // Get post value
                    $post = $args['post'];
                    
                    // Verify if title is not empty
                    if ( $args['title'] ) {
                        
                        $post = $args['title'] . ' ' . $post;
                        
                    }
                    
                    $connect = PinterestBot::create();
                    
                    $user_name = $this->CI->user->get_username_by_id($user_id);
                    
                    $secret_key = $user_name;
                    $secret_iv = $user_name . $con[0]->net_id;
                    
                    $output = false;
                    $encrypt_method = "AES-256-CBC";
                    $key = hash( 'sha256', $secret_key );
                    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
                    $output = openssl_decrypt( base64_decode( $con[0]->secret ), $encrypt_method, $key, 0, $iv );
                    
                    $connect->auth->login($con[0]->token, $output);
                    
                    // Verify if url exists
                    if ( $args['url'] ) {
                        
                        $post = str_replace($args['url'], ' ', $post);
                        
                        // Create a pin
                        $result = $connect->pins->create($args['img'][0]['body'], $con[0]->net_id, $post, short_url($args['url']));
                        
                    } else {
                        
                        // Create a pin
                        $result = $connect->pins->create($args['img'][0]['body'], $con[0]->net_id, $post);
                        
                    }
                    
                    // Verify if the post was published
                    if ( $result ) {
                        
                        // The post was published
                        return true;
                        
                    } else {
                        
                        // Save the error
                        $this->CI->user_meta->update_user_meta( $user_id, 'last-social-error', json_encode($result) );
                        
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
     * @return array with network's data
     */
    public function get_info() {
        
        return array(
          'color' => '#f22f46',
          'icon' => '<i class="fab fa-whatsapp"></i>',
          'api' => array(),
          'types' => array()
        );
        
    }
    
    /**
     * This function generates a preview for Pinterest's Boards
     *
     * @param array $args contains the img or url.
     *
     * @return array with html
     */
    public function preview($args) {
        
    }
    
}

/* End of file twilio.php */
