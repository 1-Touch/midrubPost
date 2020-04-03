<?php
/**
 * Init Controller
 *
 * This file loads the Frontend Component in the admin's panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Admin\Collection\Frontend\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

// Require the contents_categories functions file
require_once APPPATH . 'base/inc/contents/contents_categories.php';

// Require the frontend_pages functions file
require_once APPPATH . 'base/inc/pages/frontend_pages.php';

// Require the auth components functions file
require_once APPPATH . 'base/inc/auth/components.php';

// Require the frontend themes functions
require_once APPPATH . 'base/inc/themes/frontend.php';

/*
 * Init class loads the Frontend Component in the admin's panel
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Init {
    
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
        
        $this->CI->lang->load( 'frontend', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_ADMIN_FRONTEND);

        // Load Base Contents Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_contents', 'base_contents' );
        
    }
    
    /**
     * The public method view loads the frontend's template
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function view() {

        // Set page's title
        md_set_the_title($this->CI->lang->line('frontend'));

        // Set the component's slug
        md_set_component_variable('component_slug', 'frontend');

        // Set styles
        md_set_css_urls(array('stylesheet', base_url('assets/base/admin/collection/frontend/styles/css/styles.css?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION), 'text/css', 'all'));
        md_set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css', 'text/css', 'all'));

        // Template array
        $template = array();

        // Supported groups
        $supported_groups = array(
            'contents_category',
            'frontend_page'
        );

        // Verify if there is an input parameter
        if ( $this->CI->input->get('p', true) ) {
            
            switch ( $this->CI->input->get('p', true) ) {
                
                case 'editor':

                    // Set styles
                    md_set_css_urls(array('stylesheet', base_url('assets/base/admin/collection/frontend/styles/css/multimedia.css?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION), 'text/css', 'all'));

                    // Require the contents_read function file
                    require_once APPPATH . 'base/inc/contents/contents_read.php';

                    // Get contents category
                    $category_slug = $this->CI->input->get('category', true);

                    // Get content's id
                    $content_id = $this->CI->input->get('content_id', true);

                    // Get auth's component if exists
                    $component = $this->CI->input->get('component', true);

                    // Verify if auth's component exists
                    if ( $component ) {

                        // Get auth's component
                        $auth_component = md_the_auth_components($component);

                        if ( $auth_component ) {

                            // Set auth's component value
                            md_set_component_variable('auth_component', $auth_component);   

                        } else {

                            show_404();

                        }

                    }
                    
                    // Get theme's template if exists
                    $theme_template = $this->CI->input->get('template', true);

                    // Verify if theme's template exists
                    if ( $theme_template ) {

                        // Get frontend theme's templates
                        $the_templates = md_the_frontend_theme_templates($theme_template);

                        // Selected template
                        $selected_template = array();

                        // List all templates
                        foreach ( $the_templates as $the_template ) {

                            if ( $the_template['slug'] === $theme_template ) {
                                $selected_template = $the_template;
                            }

                        }

                        if ( $selected_template ) {

                            // Set theme template value
                            md_set_component_variable('theme_template', $selected_template);   

                        } else {

                            show_404();

                        }

                    }                    

                    // Set default content status
                    md_set_component_variable('content_status', 1);                    

                    // Verify if content_id variable exists
                    if ( $content_id ) {

                        // Get content by content_id
                        $content = $this->CI->base_contents->get_content($content_id);

                        // Set content_id variable
                        md_set_component_variable('content_id', $content_id);  

                        // Verify if content's id exists and if category slug is correct
                        if ( !$content ) {

                            show_404();

                        } else if( $content[0]['contents_category'] !== $category_slug ) {

                            show_404();

                        } else {

                            // Set content status
                            md_set_component_variable('content_status', $content[0]['status']);

                            // Set content slug
                            md_set_component_variable('content_slug', $content[0]['contents_slug']);

                            // Set content
                            md_set_single_content($content);

                        }

                    }

                    // List all contents categories
                    if ( md_the_contents_categories() ) {

                        // Get content categories array
                        $contents_categories = md_the_contents_categories();

                        foreach ( $contents_categories as $contents_category ) {

                            if ( isset($contents_category[$category_slug]) ) {

                                // Set the contents category slug
                                md_set_component_variable('contents_category_slug', $category_slug);

                                // Set the contents category
                                md_set_component_variable('contents_category', $contents_category[$category_slug]);

                                // Verify if editor key exists
                                if ( isset($contents_category[$category_slug]['editor']) ) {

                                    // Set the contents category
                                    md_set_component_variable('contents_category', $contents_category[$category_slug]);                                    

                                }

                                break;

                            }

                        }

                    }

                    // If contents category isn't defined, show the 404 error
                    if ( !md_the_component_variable('contents_category') ) {

                        show_404();
                        
                    }

                    // Get all contents category metas
                    $category_metas = md_the_contents_categories_metas($category_slug);

                    // Verify if one or more category meta's exists
                    if ( $category_metas ) {

                        // List all metas
                        foreach ( $category_metas as $meta ) {

                            // Verify if the meta has the css_urls array
                            if ( isset($meta['css_urls']) ) {

                                // Verify if the css_urls array is not empty
                                if ($meta['css_urls']) {

                                    // List all css links
                                    foreach ( $meta['css_urls'] as $css_link_array ) {

                                        // Add css link in the queue
                                        md_set_css_urls($css_link_array);

                                    }

                                }

                            }

                            // Verify if the meta has the js_urls array
                            if ( isset($meta['js_urls']) ) {
                             
                                // Verify if the js_urls array is not empty
                                if ($meta['js_urls']) {

                                    // List all js links
                                    foreach ( $meta['js_urls'] as $js_link_array ) {

                                        // Add js link in the queue
                                        md_set_js_urls($js_link_array);

                                    }

                                }
                                
                            }

                            // Verify if the meta's fields array exists
                            if ( isset($meta['fields']) && isset($meta['name']) && isset($meta['slug']) ) {

                                // Verify if meta's fields array is not empty
                                if ( $meta['fields'] && $meta['name'] && $meta['slug'] ) {
                                    
                                    // Add fields to list
                                    md_set_contents_meta_fields($meta['name'], $meta['slug'], $meta['fields']);

                                }

                            }

                        }

                    }

                    // Get all contents category options
                    $category_options = md_the_contents_categories_options($category_slug);

                    // Verify if category has options
                    if ( $category_options ) {

                        // List all options
                        foreach ( $category_options as $option ) {

                            // Verify if the option has the css_urls array
                            if ( isset($option['css_urls']) ) {

                                // Verify if the css_urls array is not empty
                                if ($option['css_urls']) {

                                    // List all css links
                                    foreach ( $option['css_urls'] as $css_link_array ) {

                                        // Add css link in the queue
                                        md_set_css_urls($css_link_array);

                                    }

                                }

                            }

                            // Verify if the option has the js_urls array
                            if ( isset($option['js_urls']) ) {
                             
                                // Verify if the js_urls array is not empty
                                if ($option['js_urls']) {

                                    // List all js links
                                    foreach ( $option['js_urls'] as $js_link_array ) {

                                        // Add js link in the queue
                                        md_set_js_urls($js_link_array);

                                    }

                                }
                                
                            }

                            // Verify if the option's fields array exists
                            if ( isset($option['fields']) && isset($option['name']) && isset($option['slug']) ) {

                                // Verify if option's fields array is not empty
                                if ( $option['fields'] && $option['name'] && $option['slug'] ) {
                                    
                                    // Add fields to list
                                    md_set_contents_option_fields($option['name'], $option['slug'], $option['fields']);

                                }

                            }

                        }

                    }

                    // Set javascript links for editor
                    md_set_js_urls(array('//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js'));
                    md_set_js_urls(array(base_url('assets/base/admin/collection/frontend/js/editor.js?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION)));

                    // Load the editor
                    $template['body'] = $this->CI->load->ext_view(MIDRUB_BASE_ADMIN_FRONTEND .  'views', 'editor', array(), true);

                    break;

                default:

                    // Get Group
                    $group = $this->CI->input->get('group', true);

                    // Verify if group is supported
                    if ( in_array($group, $supported_groups) ) {

                        switch ( $group ) {

                            case 'contents_category':

                                // Get contents categories array
                                $contents_categories = md_the_contents_categories();
                                
                                // Verify if $contents_categories isn't empty
                                if ( $contents_categories ) {

                                    // List all contents categories
                                    foreach ( $contents_categories as $contents_category ) {

                                        // Get category slug
                                        $category_slug = array_keys($contents_category);

                                        // Verify if contents slug meets the page param
                                        if ( $category_slug[0] === $this->CI->input->get('p', true) ) {

                                            // Set the component's display
                                            md_set_component_variable('component_display', 'contents');

                                            // Set the component's order data
                                            md_set_component_variable('component_order_data', $category_slug[0]);

                                            // Set the contents category
                                            md_set_component_variable('contents_category', $contents_category[$category_slug[0]]);

                                            // Set js links
                                            md_set_js_urls(array(base_url('assets/base/admin/collection/frontend/js/contents-list.js?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION)));

                                            // Set the body
                                            $template['body'] = $this->CI->load->ext_view(MIDRUB_BASE_ADMIN_FRONTEND .  'views', 'main', array(), true);

                                        }

                                    }

                                }

                                break;

                            case 'frontend_page':

                                // Get frontend pages
                                $frontend_pages = md_the_frontend_pages();

                                // Verify if frontend pages exists
                                if ( $frontend_pages ) {

                                    foreach ( $frontend_pages as $frontend_page ) {

                                        // Get page slug
                                        $page_slug = array_keys($frontend_page);

                                        if ( $page_slug[0] === $this->CI->input->get('p', true) ) {

                                            // Verify if the page has the css_urls array
                                            if (isset($frontend_page[$page_slug[0]]['css_urls'])) {

                                                // Verify if the css_urls array is not empty
                                                if ($frontend_page[$page_slug[0]]['css_urls']) {

                                                    // List all css links
                                                    foreach ($frontend_page[$page_slug[0]]['css_urls'] as $css_link_array) {

                                                        // Add css link in the queue
                                                        md_set_css_urls($css_link_array);
                                                    }
                                                }
                                            }

                                            // Verify if the page has the js_urls array
                                            if (isset($frontend_page[$page_slug[0]]['js_urls'])) {

                                                // Verify if the js_urls array is not empty
                                                if ($frontend_page[$page_slug[0]]['js_urls']) {

                                                    // List all js links
                                                    foreach ($frontend_page[$page_slug[0]]['js_urls'] as $js_link_array) {

                                                        // Add js link in the queue
                                                        md_set_js_urls($js_link_array);
                                                    }
                                                }
                                            }

                                            // Set the component's display
                                            md_set_component_variable('component_display', $page_slug[0]);

                                            // Load the editor
                                            $template['body'] = $this->CI->load->ext_view(MIDRUB_BASE_ADMIN_FRONTEND .  'views', 'page', array(), true);

                                        }

                                    }

                                }

                                break;

                        }

                    }

                    // If component display isn't defined, show the 404 error
                    if ( !md_the_component_variable('component_display') ) {

                        show_404();
                        
                    }

                    break;

            }

        } else {

            // Verify if contents categories exists
            if (md_the_contents_categories()) {

                // Get contents category array
                $contents_category = md_the_contents_categories();

                // Get category slug
                $category_slug = array_keys($contents_category[0]);

                // Set the component's display
                md_set_component_variable('component_display', 'contents');

                // Set the component's order data
                md_set_component_variable('component_order_data', $category_slug[0]);

                // Set the contents category
                md_set_component_variable('contents_category', $contents_category[0][$category_slug[0]]);

            }

            // Set js links
            md_set_js_urls(array(base_url('assets/base/admin/collection/frontend/js/contents-list.js?ver=' . MIDRUB_BASE_ADMIN_FRONTEND_VERSION)));

            $template['body'] = $this->CI->load->ext_view(MIDRUB_BASE_ADMIN_FRONTEND .  'views', 'main', array(), true);
            
        }

        // Making temlate and send data to view.
        $template['header'] = $this->CI->load->view('admin/layout/header2', array('admin_header' => admin_header()), true);
        $template['left'] = $this->CI->load->view('admin/layout/left', array(), true);
        $template['footer'] = $this->CI->load->view('admin/layout/footer', array(), true);
        $this->CI->load->ext_view(MIDRUB_BASE_ADMIN_FRONTEND . 'views/layout', 'index', $template);
        
    }

}
