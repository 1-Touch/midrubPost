<?php
/**
 * Midrub Apps Test
 *
 * This file loads the Storage app
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Test;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;
use MidrubBase\User\Apps\Collection\Test\Controllers as MidrubBaseUserAppsCollectionTestControllers;

/*
 * Main class loads the Storage app loader
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

        /*if ( !get_option('app_test_enable') || !plan_feature('app_test') || !team_role_permission('test') ) {
            return false;
        } else {
            return true;
        }*/
        
    }
    
    /**
     * The public method user loads the app's main page in the user panel
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function user() {

    }    

    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function ajax() {
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
     * @since 0.0.7.6
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
     * @since 0.0.7.6
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

        /*set_admin_app_options(

            array (

                array (
                    'type' => 'checkbox_input',
                    'slug' => 'app_test_enable',
                    'label' => 'Enable App',
                    'label_description' => 'If is enabled test'
                )   
            )
        );*/

        /*********************************************************************************/

        //Load and run hooks based on category
        switch ( $category ) {


            case 'admin_init':

                // Verify which component is
                if ( ( md_the_component_variable('component') === 'user' ) && ( $this->CI->input->get('app', TRUE) === 'test_app' ) ) {
                    set_admin_app_options(
                        array (
                            array (
                                'type' => 'checkbox_input',
                                'slug' => 'app_test_enable',
                                'label' => 'Enable App',
                                'label_description' => 'If is enabled test'
                            )   
                        )
                    ); 

                } else if ( ( md_the_component_variable('component') === 'user' ) && ( md_the_component_variable('component_display') === 'plans' ) ) {

                    echo "Test Two"; //die;

                    set_plans_options(

                        array(
                            'name' => 'test',
                            'icon' => '<i class="fas fa-address-card"></i>',
                            'slug' => 'test',
                            'fields' => array(

                                array (
                                    'type' => 'checkbox_input',
                                    'slug' => 'test_app',
                                    'label' => 'Enable App',
                                    'label_description' => 'If is enabled test'
                                )

                            )

                        )

                    ); 
                    
                }

                break;

            case 'user_init':


                // Verify which component is
                if ( md_the_component_variable('component') === 'user' ) {


                    set_plans_options(

                        array(
                            'name' => 'test',
                            'icon' => '<i class="fas fa-address-card"></i>',
                            'slug' => 'test',
                            'fields' => array(

                                array (
                                    'type' => 'checkbox_input',
                                    'slug' => 'test_app',
                                    'label' => 'Enable App',
                                    'label_description' => 'If is enabled test'
                                )

                            )

                        )

                    ); 
                    /*if ( get_option('app_storage_enable') && plan_feature('app_storage') ) {

                        // Load the app's language files
                        $this->CI->lang->load('storage_member', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STORAGE);

                        // Require the Permissions Inc
                        md_include_component_file(MIDRUB_BASE_USER_APPS_STORAGE . 'inc/members.php');

                    }*/

                }

                break;

        }





        /**********************************************************************************/
        /*set_plans_options(

        array(
            'name' => 'test',
            'icon' => '<i class="icon-drawer"></i>',
            'slug' => 'app_test_enable',
            'fields' => array(

                array (
                    'type' => 'checkbox_input',
                    'slug' => 'app_test_enable',
                    'label' => 'Enable App',
                    'label_description' => 'If is enabled test'
                )

            )

        )

        );*/
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
        
        // Return app information
        return array(
            'app_name' => 'Test App',
            'app_slug' => 'test_app',
            'app_icon' => '<i class="fas fa-address-card"></i>',
            'version' => '1.0',
            'min_version' => '0.0.7.9',
            'max_version' => '0.0.7.9',
        );
        
    }

}
