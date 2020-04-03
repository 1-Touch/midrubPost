<?php
/**
 * Themes Helpers
 *
 * This file contains the class Themes
 * with methods to manage the user's themes
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\User\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Themes class provides the methods to manage the user's themes
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
*/
class Themes {
    
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

        // Require the general themes functions
        require_once APPPATH . 'base/inc/themes/user.php';
        
    }

    /**
     * The public method activate activates a theme
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function activate() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('theme_slug', 'Theme Slug', 'trim|required');
            
            // Get received data
            $theme_slug = $this->CI->input->post('theme_slug');

            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Verify if theme exists
                if ( md_the_user_themes() ) {

                    // List all themes
                    foreach (md_the_user_themes() as $theme) {

                        // Verify if theme slug is rqual to $theme_slug
                        if ( $theme['slug'] === $theme_slug ) {

                            // Try to activate the theme
                            if ( update_option('themes_activated_user_theme', $theme['slug']) ) {

                                // Display success message
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('user_the_theme_was_activated')
                                );

                                echo json_encode($data);
                                exit();

                            }

                        }

                    }

                }

            }

        }

        // Display error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('user_the_theme_was_not_activated')
        );

        echo json_encode($data);


    }

    /**
     * The public method deactivate deactivates a theme
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */ 
    public function deactivate() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('theme_slug', 'Theme Slug', 'trim|required');
            
            // Get received data
            $theme_slug = $this->CI->input->post('theme_slug');

            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Verify if theme exists
                if ( md_the_user_themes() ) {

                    // List all themes
                    foreach (md_the_user_themes() as $theme) {

                        // Verify if theme slug is rqual to $theme_slug
                        if ( $theme['slug'] === $theme_slug ) {

                            // Try to deactivate the theme
                            if ( delete_option('themes_activated_user_theme') ) {

                                // Display success message
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('user_the_theme_was_deactivated')
                                );

                                echo json_encode($data);
                                exit();

                            }

                        }

                    }

                }

            }

        }

        // Display error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('user_the_theme_was_not_deactivated')
        );

        echo json_encode($data);


    }

}

/* End of file themes.php */