<?php
/**
 * Whatsapp
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Whatsapp
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
 * Whatsapp class - allows users to connect to their Whatsapp Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Whatsapp implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $CI;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
    }
    
    /**
     * The public method check_availability checks if the Telegram api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        return true;
        
    }
    
    /**
     * The public method connect will redirect user to Telegram login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('phone_number', 'Phone Number', 'trim|numeric|required');

            // Get post data
            $phone_number = $this->CI->input->post('phone_number');

            // Verify if form data is valid
            if ($this->CI->form_validation->run() == false) {

                // Display the error popup
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_the_phone_number_should_have_numeric') . '</p>', false);
                

            } else {

                // Verify if account was already added
                if (!$this->CI->networks->check_account_was_added('whatsapp', $phone_number, $this->CI->user_id)) {

                    if ( $this->CI->networks->add_network('whatsapp', $phone_number, $phone_number, $this->CI->user_id, '', $phone_number, '', '') ) {

                        // Display the success message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true);
                        exit();

                    }

                }

                // Display the error popup
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', false);

            }

        } else {
            
            // Display the login form
            echo $this->CI->ecl('Social_login')->content('Phone Number', '', 'Connect', $this->get_info(), 'whatsapp', $this->CI->lang->line('networks_an_error_occurred'));            
            
        }
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return boolean true or false
     */
    public function save($token = null) {
        
    }
    
    /**
     * The public method post publishes posts on Telegram Groups.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Get user details
        if ( !$user_id ) {
            
            // Get the user ID
            $user_id = $this->CI->user_id;
            
        }
        
        // Verify if user wants groups instead accounts
        if ( !get_user_option('mobile_installed', $user_id) ) {

            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_the_mobile_client_should_be_installed_in_your_device')));

            // Then return false
            return false;

        }

        return true;
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {
        
        return array(
            'color' => '#25d366',
            'icon' => '<i class="fab fa-whatsapp-square"></i>',
            'api' => array(),
            'types' => array('post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Telegram Groups.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {}
    
}

/* End of file whatsapp.php */
