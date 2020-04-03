<?php
/**
 * User Controller
 *
 * This file loads the Settings component in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\User\Components\Collection\Team\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Require the Team Inc
md_include_component_file(MIDRUB_BASE_USER_COMPONENTS_TEAM . 'inc/team.php');

/*
 * User class loads the Dashboard app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class User {
    
    /**
     * Class variables
     *
     * @since 0.0.7.9
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.9
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load language
        $this->CI->lang->load( 'team_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_COMPONENTS_TEAM );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function view() {

        // Set the Team's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/components/collection/team/styles/css/styles.css?ver=' . MIDRUB_BASE_USER_COMPONENTS_TEAM_VERSION), 'text/css', 'all'));
        
        // Set the Team's Js
        set_js_urls(array(base_url('assets/base/user/components/collection/team/js/main.js?ver=' . MIDRUB_BASE_USER_COMPONENTS_TEAM_VERSION)));

        // Set views params
        set_user_view(
            $this->CI->load->ext_view(
                MIDRUB_BASE_USER_COMPONENTS_TEAM . 'views',
                'main',
                array(),
                true
            )
        );
        
    }
    
}
