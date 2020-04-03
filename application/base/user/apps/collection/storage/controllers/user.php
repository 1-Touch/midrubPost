<?php
/**
 * User Controller
 *
 * This file loads the Storage app in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Storage\Controllers;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * User class loads the Storage app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class User {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load language
        $this->CI->lang->load( 'storage_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STORAGE );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function view() {

        // Set the page's title
        set_the_title($this->CI->lang->line('storage'));

        // Set Storage's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/storage/styles/css/styles.css?ver=' . MIDRUB_BASE_USER_APPS_STORAGE_VERSION), 'text/css', 'all'));

        // Set Storage's Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/storage/js/main.js?ver=' . MIDRUB_BASE_USER_APPS_STORAGE_VERSION)));

        // Set Media's Js
        set_js_urls(array(base_url('assets/user/js/media.js?ver=' . MIDRUB_BASE_USER_APPS_STORAGE_VERSION)));
        
        // Set views params
        set_user_view(
            $this->CI->load->ext_view(
                MIDRUB_BASE_USER_APPS_STORAGE . 'views',
                'main',
                array(
                ),
                true
            )
        );
        
    }

}
