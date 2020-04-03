<?php
/**
 * Init Controller
 *
 * This file loads the Update Component in the admin's panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\Update\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Init class loads the Update Component in the admin's panel
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Init {
    
    /**
     * Class variables
     *
     * @since 0.0.8.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load the component's language files
        $this->CI->lang->load( 'update', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_UPDATE );
        
    }
    
    /**
     * The public method view loads the update's template
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function view() {

        // Set page's title
        md_set_the_title($this->CI->lang->line('update'));

        // Set the component's slug
        md_set_component_variable('component_slug', 'update');

        // Set Main Js file
        md_set_js_urls(array(base_url('assets/base/admin/collection/update/js/main.js?ver=' . MIDRUB_BASE_ADMIN_UPDATE_VERSION)));

        // Set styles
        md_set_css_urls(array('stylesheet', base_url('assets/base/admin/collection/update/styles/css/styles.css?ver=' . MIDRUB_BASE_ADMIN_UPDATE_VERSION), 'text/css', 'all'));

        // Verify if there is an input parameter
        if ( $this->CI->input->get('p', true) ) {

            // Load the main's view
            $template['body'] = $this->CI->load->ext_view(MIDRUB_BASE_ADMIN_UPDATE .  'views', 'development', array(), true);

        } else {

            // Default restore
            $restore = false;
        
            // Verify if backup exists
            if ( file_exists('backup/backup.json') ) {
                
                // If backup exists
                $restore = true;
                
            }

            // Current version
            $current_version = 0;

            // Verify if update.json exists
            if ( file_exists('update.json') ) {
            
                // Get content
                $get_last = json_decode(file_get_contents('update.json'), TRUE);

                // Verify if version exists
                if ( isset($get_last['version']) ) {

                    // Set version
                    $current_version = $get_last['version'];

                }
                
            }
            
            // Get update
            $update_down = json_decode(get('https://update.midrub.com/'), TRUE);

            // New version variable
            $new_version = '';

            // Changelogs variable
            $changelogs = '';

            // Verify if update exists
            if ( $update_down ) {

                // Verify if current version is not same as on server
                if ( $update_down['version'] !== $current_version ) {

                    // Set new version
                    $new_version = $update_down['version'];

                    // Set changelogs
                    $changelogs = $update_down['changelogs'];

                }
                
            }

            // Load the main's view
            $template['body'] = $this->CI->load->ext_view(MIDRUB_BASE_ADMIN_UPDATE .  'views', 'main', array(
                'restore' => $restore,
                'new_version' => $new_version,
                'changelogs' => $changelogs
            ), true);            

        }

        // Making temlate and send data to view.
        $template['header'] = $this->CI->load->view('admin/layout/header2', array('admin_header' => admin_header()), true);
        $template['left'] = $this->CI->load->view('admin/layout/left', array(), true);
        $template['footer'] = $this->CI->load->view('admin/layout/footer', array(), true);
        $this->CI->load->ext_view(MIDRUB_BASE_ADMIN_UPDATE . 'views/layout', 'index', $template);
        
    }

}
