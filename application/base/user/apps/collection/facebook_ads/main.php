<?php
/**
 * Midrub Apps Facebook Ads
 *
 * This file loads the Facebook_ads app
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_APPS_FACEBOOK_ADS') OR define('MIDRUB_BASE_USER_APPS_FACEBOOK_ADS', MIDRUB_BASE_USER . 'apps/collection/facebook_ads/');
defined('MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION') OR define('MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION', '0.1.0');
defined('MIDRUB_ADS_FACEBOOK_GRAPH_VERSION') OR define('MIDRUB_ADS_FACEBOOK_GRAPH_VERSION', 'v6.0');
defined('MIDRUB_ADS_FACEBOOK_GRAPH_URL') OR define('MIDRUB_ADS_FACEBOOK_GRAPH_URL', 'https://graph.facebook.com/' . MIDRUB_ADS_FACEBOOK_GRAPH_VERSION . '/');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;
use MidrubBase\User\Apps\Collection\Facebook_ads\Controllers as MidrubBaseUserAppsCollectionFacebook_adsControllers;

/*
 * Main class loads the Facebook Ads app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Main implements MidrubBaseUserInterfaces\Apps {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
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
        
        if ( !get_option('app_facebook_ads_enable') || !plan_feature('app_facebook_ads') || !team_role_permission('facebook_ads') ) {
            return false;
        } else {
            return true;
        }

    }
    
    /**
     * The public method user loads the app's main page in the user panel
     * 
     * @since 0.0.7.0
     * 
     * @return void
     */
    public function user() {
        
        // Verify if the app is enabled
        if ( !get_option('app_facebook_ads_enable') || !plan_feature('app_facebook_ads') ) {
            show_404();
        }
        
        // Instantiate the class
        (new MidrubBaseUserAppsCollectionFacebook_adsControllers\User)->view();
        
    }
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.7.0
     * 
     * @return void
     */
    public function ajax() {

        // Verify if the app is enabled
        if ( !get_option('app_facebook_ads_enable') || !plan_feature('app_facebook_ads') ) {
            show_404();
        }
        
        // Load language
        $this->CI->lang->load( 'facebook_ads_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS );
        
        // Get automatization's get input
        $automatization = $this->CI->input->get('automatization');
        
        if ( $automatization ) {

            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Apps',
                'Collection',
                'Facebook_ads',
                'Automatizations',
                ucfirst($automatization),
                'Main'
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Run ajax
            (new $cl())->ajax();
            
        } else {
        
            // Get action's get input
            $action = $this->CI->input->get('action');

            if ( !$action ) {
                $action = $this->CI->input->post('action');
            }

            try {

                // Call method if exists
                (new MidrubBaseUserAppsCollectionFacebook_adsControllers\Ajax)->$action();

            } catch (Exception $ex) {

                $data = array(
                    'success' => FALSE,
                    'message' => $ex->getMessage()
                );

                echo json_encode($data);

            }
            
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
     * @since 0.0.7.0
     * 
     * @return void
     */
    public function cron_jobs() {

        // Verify if the app is enabled
        if ( get_option('app_facebook_ads_enable') ) {

            foreach (glob(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'automatizations/*', GLOB_ONLYDIR) as $automatization_dir) {

                $automatization = trim(basename($automatization_dir) . PHP_EOL);
    
                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Apps',
                    'Collection',
                    'Facebook_ads',
                    'Automatizations',
                    ucfirst($automatization),
                    'Main'
                );
    
                // Implode the array above
                $cl = implode('\\', $array);
    
                // Call cron jobs
                (new $cl())->cron_jobs();
    
            }

        }
        
    }
    
    /**
     * The public method delete_account is called when user's account is deleted
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function delete_account($user_id) {

        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_account_model', 'ads_account_model' );

        // Delete user's accounts
        $this->CI->ads_account_model->delete_account_records( $user_id );

        foreach (glob(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'automatizations/*', GLOB_ONLYDIR) as $automatization_dir) {

            $automatization = trim(basename($automatization_dir) . PHP_EOL);

            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Apps',
                'Collection',
                'Facebook_ads',
                'Automatizations',
                ucfirst($automatization),
                'Main'
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Call hooks
            (new $cl())->delete_account($user_id);

        }
        
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

                // Load the app's language files
                $this->CI->lang->load('facebook_ads_admin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS);

                // Verify which component is
                if ( ( md_the_component_variable('component') === 'user' ) && ( $this->CI->input->get('app', TRUE) === 'facebook_ads' ) ) {

                    // Require the Admin Inc
                    get_the_file(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'inc/admin.php');

                } else if ( ( md_the_component_variable('component') === 'user' ) && ( md_the_component_variable('component_display') === 'plans' ) ) {

                    // Require the Plans Inc
                    get_the_file(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'inc/plans.php');

                }

                break;

            case 'user_init':

                // Verify which component is
                if ( md_the_component_variable('component') === 'team' ) {

                    if ( get_option('app_facebook_ads_enable') && plan_feature('app_facebook_ads') ) {

                        // Load the app's language files
                        $this->CI->lang->load('facebook_ads_team_member', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS);

                        // Require the Permissions Inc
                        get_the_file(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'inc/members.php');

                    }

                }

                // Load models
                $this->CI->load->ext_model(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_networks_model', 'ads_networks_model');

                // Add hook in the queue
                add_hook(
                    'delete_ad_account',
                    function ($args) {

                        // Delete ad account's records
                        $this->CI->ads_networks_model->delete_account_records( $this->CI->user_id, $args['account_id'] );

                    }

                );

                // List all automatizations
                foreach ( glob(MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'automatizations/*', GLOB_ONLYDIR) as $automatization_dir ) {

                    // Get the automatization's directory
                    $automatization = trim(basename($automatization_dir) . PHP_EOL);
        
                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Facebook_ads',
                        'Automatizations',
                        ucfirst($automatization),
                        'Main'
                    );
        
                    // Implode the array above
                    $cl = implode('\\', $array);
        
                    // Call automatization's hooks
                    (new $cl())->load_hooks();
        
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
     * @since 0.0.7.6
     * 
     * @return array with app's information
     */
    public function app_info() {
        
        // Load the app's language files
        $this->CI->lang->load( 'facebook_ads_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_FACEBOOK_ADS );
        
        // Return app information
        return array(
            'app_name' => $this->CI->lang->line('advertising'),
            'app_slug' => 'facebook_ads',
            'app_icon' => '<i class="icon-social-facebook"></i>',
            'version' => MIDRUB_BASE_USER_APPS_FACEBOOK_ADS_VERSION,
            'min_version' => '0.0.7.9',
            'max_version' => '0.0.7.9'
        );
        
    }

}

/* End of file main.php */