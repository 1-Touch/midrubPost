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
namespace MidrubBase\User\Components\Collection\Settings\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Require the Functions Inc
require_once MIDRUB_BASE_USER_COMPONENTS_SETTINGS . 'inc/functions.php';

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
        $this->CI->lang->load( 'settings_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_COMPONENTS_SETTINGS );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function view() {
        
        // Set FullCalendar's styles
        set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css?ver=' . MD_VER, 'text/css', 'all'));

        // Set the Dashboard's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/components/collection/settings/styles/css/settings.css?ver=' . MD_VER), 'text/css', 'all'));

        // Set Moment Js
        set_js_urls(array('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js'));

        // Set Chart Js
        set_js_urls(array('//www.chartjs.org/dist/2.7.2/Chart.js'));

        // Set Utils Js
        set_js_urls(array('//www.chartjs.org/samples/latest/utils.js'));
        
        // Set the Main Settings Js
        set_js_urls(array(base_url('assets/base/user/components/collection/settings/js/main.js?ver=' . MD_VER)));
        
        // Prepare view
        $this->set_user_view();
        
    }

    /**
     * The private method set_user_view sets user view
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    private function set_user_view() {

        // Get page if exists
        $page = $this->CI->input->get('p', TRUE);

        // Verify if page exists
        if ( !$page ) {
            $page = 'general';
        }

        // Get all options
        $options = the_user_component_options();

        // Verify if options exists
        if ( $options ) {

            // Default counter
            $count = 0;

            // Total counter
            $total = 0;

            // List all options and verify if page is valid
            foreach ( $options as $option ) {

                if ( $page === $option['section_slug'] ) {

                    if ( $page === 'referrals' ) {

                        // Load Referrals Model
                        $this->CI->load->model('referrals');

                        // Get Referral stats
                        $stats = $this->CI->referrals->get_stats($this->CI->user_id);

                        // Set options content
                        $options[$total]['section_fields'][] = array (
                            'type' => 'content',
                            'slug' => 'referrals',
                            'content' => $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_COMPONENTS_SETTINGS . 'views',
                                'referrals',
                                array(
                                    'stats' => $stats
                                ),
                                true
                            )
                        );

                    } else if ( $page === 'plan_usage' ) {

                        // Set options content
                        $options[$total]['section_fields'][] = array (
                            'type' => 'content',
                            'slug' => 'plan_usage',
                            'content' => $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_COMPONENTS_SETTINGS . 'views',
                                'plan_usage',
                                array(),
                                true
                            )
                        );

                    } else if ( $page === 'invoices' ) {

                        // Set options content
                        $options[$total]['section_fields'][] = array (
                            'type' => 'content',
                            'slug' => 'invoices',
                            'content' => $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_COMPONENTS_SETTINGS . 'views',
                                'invoices',
                                array(),
                                true
                            )
                        );

                    }

                    $count++;

                }

                $total++;

            }

            // If page is not valid, show 404
            if ( $count < 1 ) {

                // Display 404 page
                show_404();

            }

        }

        // Prepare view params
        $params = array(
            'options' => $options,
            'page' => $page
        );

        // Set views params
        set_user_view(
            $this->CI->load->ext_view(
                MIDRUB_BASE_USER_COMPONENTS_SETTINGS . 'views',
                'main',
                $params,
                true
            )
        );

    }
    
}
