<?php
/**
 * Midrub Apps Chatbot
 *
 * This file loads the Chatbot app
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_APPS_CHATBOT') OR define('MIDRUB_BASE_USER_APPS_CHATBOT', MIDRUB_BASE_USER . 'apps/collection/chatbot/');
defined('MIDRUB_BASE_USER_APPS_CHATBOT_VERSION') OR define('MIDRUB_BASE_USER_APPS_CHATBOT_VERSION', '0.0.6');
defined('MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL') OR define('MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL', 'https://graph.facebook.com/v4.0/');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;
use MidrubBase\User\Apps\Collection\Chatbot\Controllers as MidrubBaseUserAppsCollectionChatbotControllers;
use MidrubBase\User\Apps\Collection\Commenter as MidrubBaseUserAppsCollectionCommenter;
use MidrubBase\User\Apps\Collection\Inboxall as MidrubBaseUserAppsCollectionInboxall;

/*
 * Main class loads the Chatbot app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Main implements MidrubBaseUserInterfaces\Apps {
    
    /**
     * Class variables
     *
     * @since 0.0.8.0
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.0
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }

    /**
     * The public method check_availability checks if the app is available
     *
     * @return boolean true or false
     */
    public function check_availability() {

        if ( !get_option('app_chatbot_enable') || !plan_feature('app_chatbot') || !team_role_permission('chatbot') ) {
            return false;
        } else {
            return true;
        }
        
    }
    
    /**
     * The public method user loads the app's main page in the user panel
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function user() {
        
        // Verify if the app is enabled
        if ( !get_option('app_chatbot_enable') || !plan_feature('app_chatbot') || !team_role_permission('chatbot') ) {
            show_404();
        }
        
        // Instantiate the class
        (new MidrubBaseUserAppsCollectionChatbotControllers\User)->view();
        
    }    

    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function ajax() {
        
        // Verify if the app is enabled
        if ( !get_option('app_chatbot_enable') || !plan_feature('app_chatbot') || !team_role_permission('chatbot') ) {
            exit();
        }        
        
        // Get action's get input
        $action = $this->CI->input->get('action');

        if ( !$action ) {
            $action = $this->CI->input->post('action');
        }
        
        try {
            
            // Call method if exists
            (new MidrubBaseUserAppsCollectionChatbotControllers\Ajax)->$action();
            
        } catch (Exception $ex) {
            
            $data = array(
                'success' => FALSE,
                'message' => $ex->getMessage()
            );
            
            echo json_encode($data);
            
        }
        
    }

    /**
     * The public method rest processes the rest's requests
     * 
     * @param string $endpoint contains the requested endpoint
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function rest($endpoint) {

    }
    
    /**
     * The public method cron_jobs loads the cron jobs commands
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function cron_jobs() {
        
    }
    
    /**
     * The public method delete_account is called when user's account is deleted
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function delete_account($user_id) {
        
        // Require the User Inc
        require_once MIDRUB_BASE_USER_APPS_CHATBOT . 'inc/user.php';

        // Delete all user's records in this app
        delete_user_from_facebook_chatbot($user_id);

    }

    /**
     * The public method hooks contains the app's hooks
     * 
     * @param string $category contains the hooks category
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_hooks( $category ) {

        // Load and run hooks based on category
        switch ( $category ) {

            case 'admin_init':

                // Load the admin app's language files
                $this->CI->lang->load('chatbot_admin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_CHATBOT);

                // Verify which component is
                if ( ( md_the_component_variable('component') === 'user' ) && ( $this->CI->input->get('app', TRUE) === 'chatbot' ) ) {

                    // Require the Admin Inc
                    md_include_component_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'inc/admin.php');

                } else if ( ( md_the_component_variable('component') === 'user' ) && ( md_the_component_variable('component_display') === 'plans' ) ) {

                    // Require the Plans Inc
                    md_include_component_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'inc/plans.php');

                }

                break;

            case 'user_init':

                // Verify which component is
                if ( md_the_component_variable('component') === 'team' ) {

                    if ( get_option('app_chatbot_enable') && plan_feature('app_chatbot') ) {

                        // Load the app's language files
                        $this->CI->lang->load('chatbot_member', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_CHATBOT);

                        // Require the Permissions Inc
                        md_include_component_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'inc/members.php');

                    }

                }

                break;

        }

        // Require the General Inc
        get_the_file(MIDRUB_BASE_USER_APPS_CHATBOT . 'inc/general.php');

    }

    /**
     * The public method guest contains the app's access for guests
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function guest() {

        // Verify if the inboxall app exists
        if ( class_exists('MidrubBase\User\Apps\Collection\Inboxall\Main') ) {

            // Process the request
            (new MidrubBaseUserAppsCollectionInboxall\Main)->guest();   

        }

        // Verify if is a bot verification
        if ( $this->CI->input->get('hub_challenge', TRUE) && $this->CI->input->get('hub_verify_token', TRUE) ) {
            
            // Verify if the token is correct
            if ( $this->CI->input->get('hub_verify_token', TRUE) === get_option('app_facebook_chatbot_verify_token') ) {

                // Display hub's challenge
                echo $this->CI->input->get('hub_challenge', TRUE);

            }
            
        } else {

            // Decode the Facebook Request
            $input = $this->CI->security->xss_clean(json_decode(file_get_contents('php://input'), true));

            // Verify if Facebook Page Id exists
            if ( isset($input['entry'][0]['id']) ) {

                // Verify if the request is for feed
                if ( isset($input['entry'][0]['changes'][0]['field']) ) {

                    // Verify if the commenter app exists
                    if ( class_exists('MidrubBase\User\Apps\Collection\Commenter\Main') ) {

                        // Process the request
                        (new MidrubBaseUserAppsCollectionCommenter\Main)->guest();   

                    }
    
                } else {

                    // Process the request
                    (new MidrubBaseUserAppsCollectionChatbotControllers\Bot)->process($input);

                }

            }

        }

    }
    
    /**
     * The public method app_info contains the app's info
     * 
     * @since 0.0.8.0
     * 
     * @return array with app's information
     */
    public function app_info() {
        
        // Return app information
        return array(
            'app_name' => $this->CI->lang->line('chatbot'),
            'app_slug' => 'chatbot',
            'app_icon' => '<i class="icon-bubbles"></i>',
            'version' => MIDRUB_BASE_USER_APPS_CHATBOT_VERSION,
            'min_version' => '0.0.8.0',
            'max_version' => '0.0.8.0',
        );
        
    }

}

/* End of file main.php */