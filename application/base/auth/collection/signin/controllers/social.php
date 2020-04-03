<?php
/**
 * Social Controller
 *
 * This file connects user by using networks
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Auth\Collection\Signin\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Social class connects user by using networks
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Social {
    
    /**
     * Class variables
     *
     * @since 0.0.7.8
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.8
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load the component's language files
        $this->CI->lang->load( 'auth_signin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_AUTH_SIGNIN );
        
    }
    
    /**
     * The public method connect redirects user to the network
     * 
     * @param string $network contains the name of the network
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function connect($network) {

        // Verify if network exists
        if ( !file_exists(MIDRUB_BASE_AUTH . 'social/' . $network . '.php') ) {

            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signin_network_not_available'));
            exit();

        }

        // Verify if option is enabled
        if ( !get_option('enable_auth_' . strtolower($network)) ) {
            
            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signin_network_not_enabled'));
            exit();

        }

        // Require network
        require_once MIDRUB_BASE_AUTH . 'social/' . $network . '.php';

        // Create an array
        $array = array(
            'MidrubBase',
            'Auth',
            'Social',
            ucfirst($network)
        );

        // Implode the array above
        $cl = implode('\\', $array);

        // Verify if network is configured
        if (!(new $cl())->check_availability()) {

            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signin_network_not_configured'));
            exit();            

        }

        // Set the sign in page
        $sign_in = the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin');

        // Redirect user
        (new $cl())->connect($sign_in . '/' . $network);
        
    }

    /**
     * The public method login tries to login the user
     * 
     * @param string $network contains the name of the network
     * 
     * @since 0.0.7.8
     * 
     * @return array with error message or void
     */
    public function login($network) {

        // Verify if network exists
        if ( !file_exists(MIDRUB_BASE_AUTH . 'social/' . $network . '.php') ) {

            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signin_network_not_available'));
            exit();

        }

        // Verify if option is enabled
        if ( !get_option('enable_auth_' . strtolower($network)) ) {
            
            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signin_network_not_enabled'));
            exit();

        }

        // Require network
        require_once MIDRUB_BASE_AUTH . 'social/' . $network . '.php';

        // Create an array
        $array = array(
            'MidrubBase',
            'Auth',
            'Social',
            ucfirst($network)
        );

        // Implode the array above
        $cl = implode('\\', $array);

        // Verify if network is configured
        if (!(new $cl())->check_availability()) {

            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signin_network_not_configured'));
            exit();            

        }

        // Set the sign in page
        $sign_in = the_url_by_page_role('sign_in') ? the_url_by_page_role('sign_in') : site_url('auth/signin');

        // Try to login
        $login = (new $cl())->login($sign_in . '/' . $network);

        // Verify if login is success
        if ( $login['success'] ) {

            // Load Base Model
            $this->CI->load->ext_model(MIDRUB_BASE_PATH . 'models/', 'Base_users', 'base_users');

            // Get user data
            $user_data = $this->CI->base_users->get_user_data_by_username($this->CI->session->userdata['username']);

            // Get the user's plan
            $user_plan = get_user_option('plan', $user_data[0]->user_id);

            // Verify if user has a plan, if no add default plan
            if (!$user_plan) {
                $this->CI->plans->change_plan(1, $user_data[0]->user_id);
            }

            // Redirect to the dashboard app
            redirect(site_url('user/app/dashboard'));

        } else {

            return $login;

        }
        
    }

}
