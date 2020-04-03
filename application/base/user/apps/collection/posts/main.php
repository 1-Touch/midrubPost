<?php
/**
 * Midrub Apps Posts
 *
 * This file loads the Posts app
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_APPS_POSTS') OR define('MIDRUB_BASE_USER_APPS_POSTS', MIDRUB_BASE_USER . 'apps/collection/posts/');
defined('MIDRUB_BASE_USER_APPS_POSTS_VERSION') OR define('MIDRUB_BASE_USER_APPS_POSTS_VERSION', '0.0.8');
defined('MIDRUB_POSTS_FACEBOOK_GRAPH_URL') OR define('MIDRUB_POSTS_FACEBOOK_GRAPH_URL', 'https://graph.facebook.com/v4.0/');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;
use MidrubBase\User\Apps\Collection\Posts\Controllers as MidrubBaseUserAppsCollectionPostsControllers;

/*
 * Main class loads the Posts app loader
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

        if ( !get_option('app_posts_enable') || !plan_feature('app_posts') || !team_role_permission('posts') ) {
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
        if ( !get_option('app_posts_enable') || !plan_feature('app_posts') ) {
            show_404();
        }
        
        // Instantiate the class
        (new MidrubBaseUserAppsCollectionPostsControllers\User)->view();
        
    }
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function ajax() {

        // Verify if the app is enabled
        if ( !get_option('app_posts_enable') || !plan_feature('app_posts') ) {
            exit();
        }
        
        // Get action's get input
        $action = $this->CI->input->get('action', TRUE);

        if ( !$action ) {
            $action = $this->CI->input->post('action');
        }
        
        try {
            
            // Call method if exists
            (new MidrubBaseUserAppsCollectionPostsControllers\Ajax)->$action();
            
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

        // Verify if the app is enabled
        if ( !get_option('app_posts_enable') ) {

            echo json_encode(array(
                'status' => FALSE,
                'message' => $this->CI->lang->line('api_requested_endpoint_unavailable')
            ));

            exit();

        }
        
        try {
            
            // Call method if exists
            (new MidrubBaseUserAppsCollectionPostsControllers\Api)->$endpoint();
            
        } catch (Exception $ex) {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('api_requested_endpoint_unavailable')
            );
            
            echo json_encode($data);
            
        }
        
    }
    
    /**
     * The public method cron_jobs loads the cron jobs commands
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function cron_jobs() {

        // Verify if the app is enabled
        if ( get_option('app_posts_enable') ) {

            // Publish scheduled posts
            (new MidrubBaseUserAppsCollectionPostsControllers\Cron)->publish_scheduled();
            
            // Publish RSS's posts
            (new MidrubBaseUserAppsCollectionPostsControllers\Cron)->publish_rss_posts();
            
            // Publish scheduled RSS's posts
            (new MidrubBaseUserAppsCollectionPostsControllers\Cron)->publish_scheduled_rss_posts();

        }

        
    }
    
    /**
     * The public method delete_account is called when user's account is deleted
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function delete_account($user_id) {
        
        // Load the app's models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Posts_model', 'posts_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Rss_model', 'rss_model' );
        
        // Delete user's posts
        $this->CI->posts_model->delete_user_posts($user_id);
        
        // Delete user's RSS Feeds
        $this->CI->rss_model->delete_rss_feeds($user_id);        
        
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
        switch ($category) {

            case 'admin_init':

                // Load the posts_admin's language file
                $this->CI->lang->load('posts_admin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);

                // Verify which component is
                if ((md_the_component_variable('component') === 'user') && ($this->CI->input->get('app', TRUE) === 'posts')) {

                    // Require the Admin Inc
                    get_the_file(MIDRUB_BASE_USER_APPS_POSTS . 'inc/admin.php');

                } else if ((md_the_component_variable('component') === 'user') && (md_the_component_variable('component_display') === 'plans')) {

                    // Require the Plans Inc
                    get_the_file(MIDRUB_BASE_USER_APPS_POSTS . 'inc/plans.php');

                } else if ((md_the_component_variable('component') === 'settings')) {

                    // Load the posts_api's language file
                    $this->CI->lang->load('posts_api', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);

                    // Require the Api Permissions Inc
                    get_the_file(MIDRUB_BASE_USER_APPS_POSTS . 'inc/api_permissions.php');

                }

                break;

            case 'user_init':

                // Verify which component is
                if (md_the_component_variable('component') === 'settings') {

                    // Load the app's language files
                    $this->CI->lang->load('posts_settings', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);

                    // Require the User Inc
                    get_the_file(MIDRUB_BASE_USER_APPS_POSTS . 'inc/user.php');

                } else if (md_the_component_variable('component') === 'team') {

                    if (get_option('app_posts_enable') && plan_feature('app_posts')) {

                        // Load the app's language files
                        $this->CI->lang->load('posts_member', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);

                        // Require the Permissions Inc
                        get_the_file(MIDRUB_BASE_USER_APPS_POSTS . 'inc/members.php');

                    }

                }

                break;

            case 'rest_init':

                // Load the posts_api's language file
                $this->CI->lang->load('posts_api', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);

                // Require the Api Permissions Inc
                get_the_file(MIDRUB_BASE_USER_APPS_POSTS . 'inc/api_permissions.php');

                break;
                
        }

        // Require the General Inc
        get_the_file(MIDRUB_BASE_USER_APPS_POSTS . 'inc/general.php');

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
     * @return void
     */
    public function app_info() {
        
        // Load language
        $this->CI->lang->load( 'widgets', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);
        
        // Return app information
        return array(
            'app_name' => $this->CI->lang->line('posts'),
            'app_slug' => 'posts',
            'app_icon' => '<i class="icon-layers"></i>',
            'version' => MIDRUB_BASE_USER_APPS_POSTS_VERSION,
            'min_version' => '0.0.7.9',
            'max_version' => '0.0.7.9',
        );
        
    }

}

/* End of file main.php */