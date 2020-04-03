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
namespace MidrubBase\Auth\Collection\Signup\Controllers;

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
        if ( file_exists( MIDRUB_BASE_AUTH_SIGNUP . '/language/' . $this->CI->config->item('language') . '/auth_signup_lang.php' ) ) {
            $this->CI->lang->load( 'auth_signup', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_AUTH_SIGNUP . '/' );
        }

        // Load Plans Model
        $this->CI->load->model('plans');
        
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

            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signup_network_not_available'));
            exit();

        }

        // Verify if option is enabled
        if ( !get_option('enable_auth_' . strtolower($network)) ) {
            
            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signup_network_not_enabled'));
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

            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signup_network_not_configured'));
            exit();            

        }

        // Set the sign up page
        $sign_up = the_url_by_page_role('sign_up') ? the_url_by_page_role('sign_up') : site_url('auth/signup');

        // Redirect user
        (new $cl())->connect($sign_up . '/' . $network);
        
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

            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signup_network_not_available'));
            exit();

        }

        // Verify if option is enabled
        if ( !get_option('enable_auth_' . strtolower($network)) ) {
            
            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signup_network_not_enabled'));
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

            echo str_replace('(network)', ucfirst($network), $this->CI->lang->line('auth_signup_network_not_configured'));
            exit();            

        }

        // Set the sign up page
        $sign_up = the_url_by_page_role('sign_up') ? the_url_by_page_role('sign_up') : site_url('auth/signup');

        // Try to login
        $login = (new $cl())->save($sign_up . '/' . $network);

        // Verify if login is success
        if ( $login['success'] ) {

            redirect(site_url('user/app/dashboard'));

        } else {

            return $login;

        }
        
    }

}
