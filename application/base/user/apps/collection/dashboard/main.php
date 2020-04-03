<?php
/**
 * Midrub Apps Dashboard
 *
 * This file loads the Dashboard app
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Dashboard;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_APPS_DASHBOARD') OR define('MIDRUB_BASE_USER_APPS_DASHBOARD', MIDRUB_BASE_USER . 'apps/collection/dashboard/');
defined('MIDRUB_BASE_USER_APPS_DASHBOARD_VERSION') OR define('MIDRUB_BASE_USER_APPS_DASHBOARD_VERSION', '0.3');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;
use MidrubBase\User\Apps\Collection\Dashboard\Controllers as MidrubBaseUserAppsCollectionDashboardControllers;

/*
 * Main class loads the Dashboard app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class Main implements MidrubBaseUserInterfaces\Apps {
   
    /**
     * Class variables
     *
     * @since 0.0.7.9
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.9
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

        if ( !get_option('app_dashboard_enable') || !plan_feature('app_dashboard') || !team_role_permission('dashboard') ) {
            return false;
        } else {
            return true;
        }
        
    }
    
    /**
     * The public method user loads the app's main page in the user panel
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function user() {

        // Verify if the app is enabled
        if ( !get_option('app_dashboard_enable') || !plan_feature('app_dashboard') ) {
            show_404();
        }

        // Instantiate the class
        (new MidrubBaseUserAppsCollectionDashboardControllers\User)->view();
        
    }
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function ajax() {
        
        // Get action's get input
        $action = $this->CI->input->get('action');

        if ( !$action ) {
            $action = $this->CI->input->post('action');
        }
        
        try {
            
            // Call method if exists
            (new MidrubBaseUserAppsCollectionDashboardControllers\Ajax)->$action();
            
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
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function rest($endpoint) {

    }
    
    /**
     * The public method cron_jobs loads the cron jobs commands
     * 
     * @since 0.0.7.9
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
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function delete_account($user_id) {
        
    }
    
    /**
     * The public method hooks contains the app's hooks
     * 
     * @param string $category contains the hooks category
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function load_hooks( $category ) {

        // Load and run hooks based on category
        switch ( $category ) {

            case 'admin_init':

                // Load the admin app's language files
                $this->CI->lang->load('dashboard_admin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_DASHBOARD);

                // Verify which component is
                if ( ( md_the_component_variable('component') === 'user' ) && ( $this->CI->input->get('app', TRUE) === 'dashboard' ) ) {

                    // Require the Admin Inc
                    md_include_component_file(MIDRUB_BASE_USER_APPS_DASHBOARD . 'inc/admin.php');

                } else if ( ( md_the_component_variable('component') === 'user' ) && ( md_the_component_variable('component_display') === 'plans' ) ) {

                    // Require the Plans Inc
                    md_include_component_file(MIDRUB_BASE_USER_APPS_DASHBOARD . 'inc/plans.php');

                }

                break;

            case 'user_init':

                // Verify which component is
                if ( md_the_component_variable('component') === 'team' ) {

                    if ( get_option('app_dashboard_enable') && plan_feature('app_dashboard') ) {

                        // Load the app's language files
                        $this->CI->lang->load('dashboard_member', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_DASHBOARD);

                        // Require the Permissions Inc
                        md_include_component_file(MIDRUB_BASE_USER_APPS_DASHBOARD . 'inc/members.php');

                    }

                }

                break;


        }

    }

    /**
     * The public method guest contains the app's access for guests
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function guest() {

        // Display 404 page
        show_404();

    }
    
    /**
     * The public method app_info contains the app's info
     * 
     * @since 0.0.7.9
     * 
     * @return array with app's information
     */
    public function app_info() {

        // Load the app's language files
        $this->CI->lang->load( 'dashboard_admin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_DASHBOARD );
        
        // Return app information
        return array(
            'app_name' => $this->CI->lang->line('dashboard'),
            'app_slug' => 'dashboard',
            'app_icon' => '<i class="icon-speedometer"></i>',
            'version' => MIDRUB_BASE_USER_APPS_DASHBOARD_VERSION,
            'min_version' => '0.0.7.9',
            'max_version' => '0.0.7.9'
        );
        
    }

}

/* End of file main.php */
