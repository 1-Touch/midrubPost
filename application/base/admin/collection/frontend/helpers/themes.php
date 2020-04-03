<?php
/**
 * Themes Helpers
 *
 * This file contains the class Themes
 * with methods to manage the frontend's themes
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\Frontend\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Themes class provides the methods to manage the frontend's themes
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
*/
class Themes {
    
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

        // Require the general themes functions
        require_once APPPATH . 'base/inc/themes/frontend.php';
        
    }
    
    /**
     * The public method load_theme_templates loads theme's templates
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */ 
    public function load_theme_templates() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('contents_category', 'Contents Category', 'trim|required');
            
            // Get received data
            $contents_category = $this->CI->input->post('contents_category');

            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Gets all contents categories
                $all_categories = md_the_contents_categories();

                // Verify if categories exists
                if ($all_categories) {

                    // List all categories
                    foreach ($all_categories as $category) {

                        // Get category slug
                        $slug = array_keys($category);
                        
                        // Verify if is required category
                        if ( $slug[0] === $contents_category ) {

                            // Verify if category has templates_path
                            if ( isset($category[$slug[0]]['templates_path']) ) {

                                // All templates
                                $all_templates =  array();

                                // List all templates
                                foreach (glob($category[$slug[0]]['templates_path'] . '*.php') as $filename) {

                                    // Get name
                                    $template_name = str_replace(array($category[$slug[0]]['templates_path'], '.php'), '', $filename);

                                    // Get template info
                                    $all_templates[] = array(
                                        'slug' => $template_name,
                                        'name' => ucwords(str_replace(array('_','-'), ' ', $template_name))
                                    );

                                }

                                // Display templates
                                $data = array(
                                    'success' => TRUE,
                                    'templates' => $all_templates
                                );

                                echo json_encode($data); 

                            }

                        }

                    }

                }

            }

        }

    }

    /**
     * The public method activate activates a theme
     * 
     * @since 0.0.7.8
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
                if ( md_the_frontend_themes() ) {

                    // List all themes
                    foreach (md_the_frontend_themes() as $theme) {

                        // Verify if theme slug is rqual to $theme_slug
                        if ( $theme['slug'] === $theme_slug ) {

                            // Try to activate the theme
                            if ( update_option('themes_activated_theme', $theme['slug']) ) {

                                // Display success message
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('frontend_the_theme_was_activated')
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
            'message' => $this->CI->lang->line('frontend_the_theme_was_not_activated')
        );

        echo json_encode($data);


    }

    /**
     * The public method deactivate deactivates a theme
     * 
     * @since 0.0.7.8
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
                if ( md_the_frontend_themes() ) {

                    // List all themes
                    foreach (md_the_frontend_themes() as $theme) {

                        // Verify if theme slug is rqual to $theme_slug
                        if ( $theme['slug'] === $theme_slug ) {

                            // Try to deactivate the theme
                            if ( delete_option('themes_activated_theme') ) {

                                // Display success message
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('frontend_the_theme_was_deactivated')
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
            'message' => $this->CI->lang->line('frontend_the_theme_was_not_deactivated')
        );

        echo json_encode($data);


    }

}

/* End of file themes.php */