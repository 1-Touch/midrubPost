<?php
/**
 * User Controller
 *
 * This file loads the Dashboard app in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Dashboard\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * User class loads the Dashboard app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */
class User {
    
    /**
     * Class variables
     *
     * @since 0.0.7.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load Team Model
        $this->CI->load->model('team');
        
        // Load language
        $this->CI->lang->load( 'dashboard_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_DASHBOARD );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.0
     * 
     * @return void
     */
    public function view() {

        // Set the Dashboard's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/dashboard/styles/css/dashboard.css?ver=' . MD_VER), 'text/css', 'all'));

        // Set Moment Js
        set_js_urls(array('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js'));

        // Set Chart Js
        set_js_urls(array('//www.chartjs.org/dist/2.7.2/Chart.js'));

        // Set Utils Js
        set_js_urls(array('//www.chartjs.org/samples/latest/utils.js'));
        
        // Set the Dashboard Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/dashboard/js/dashboard.js?ver=' . MD_VER)));
        
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

        // Set the page's title
        set_the_title($this->CI->lang->line('dashboard'));

        // Get plan end
        $plan_end = get_user_option( 'plan_end', $this->CI->user_id );

        // Default expired
        $expired = 0;

        // Default expires
        $expires_soon = 0;

        // Verify if the plan has end
        if ($plan_end) {

            if ( (strtotime($plan_end) + 864000) < time() ) {

                $expired = 1;

                // Change the plan
                $this->CI->plans->change_plan(1, $this->CI->user_id);

                // Set new plan end
                $plan_end = get_user_option('plan_end', $this->CI->user_id);
                
            } elseif (strtotime($plan_end) < time() + 432000) {

                $expires_soon = 1;

            }

        }

        // Get the user's plan
        $user_plan = get_user_option( 'plan', $this->CI->user_id );
        
        // Get plan data
        $plan_data = $this->CI->plans->get_plan( $user_plan );
        
        // Set widgets
        $widgets = array();
        
        // Get default widgets
        $default_widgets = array();
        
        if ( get_option('app_dashboard_left_side_position') && get_option('app_dashboard_enable_default_widgets') ) {

            $full_size = 'col-xl-5';

            $plan_data[0]['size'] = 6;

        } else {

            $full_size = 'col-xl-12';

            $plan_data[0]['size'] = 3;

        }        

        if ( get_option('app_dashboard_enable_default_widgets') ) {
            
            // List all default widgets
            foreach ( glob(MIDRUB_BASE_USER_APPS_DASHBOARD . 'widgets/*.php') as $filename ) {

                $className = str_replace( array( MIDRUB_BASE_USER_APPS_DASHBOARD . 'widgets/', '.php' ), '', $filename );

                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Apps',
                    'Collection',
                    'Dashboard',
                    'Widgets',
                    ucfirst($className)
                );       

                // Implode the array above
                $cl = implode('\\',$array);

                // Instantiate the class
                $response = (new $cl())->display_widget( $this->CI->user_id, $plan_end, $plan_data );

                // Add widget to $default_widgets array
                $default_widgets[$response['order']] = $response['widget'];

            }

            arsort($default_widgets);
            
            if ( $default_widgets ) {
                
                $widgets[0] = '<div class="' . $full_size . ' col-lg-12 col-md-12 stats">'
                                . '<div class="row">';

                $i = 0;
                
                foreach ( $default_widgets as $widget ) {
                    
                    if ( get_option('app_dashboard_left_side_position') && $i % 1 ) {
                        
                        $widgets[0] .= '</div><div class="row">';
                        
                    }
                    
                    $widgets[0] .= $widget;
                    
                    $i++;
                    
                }
                
                $widgets[0] .= '</div>'
                            . '</div>';
            
            }
        
        }
        
        $apps_widgets = array();

        foreach ( glob( MIDRUB_BASE_USER . 'apps/collection/*', GLOB_ONLYDIR) as $directory ) {

            $dir = str_replace( MIDRUB_BASE_USER . 'apps/collection/', '', $directory );

            if ( !get_option('app_' . $dir . '_enable') || !plan_feature('app_' . $dir) ) {
                continue;
            }

            if ( $dir === 'dashboard' ) {
                
                continue;
                
            } else {

                // Verify if has widgets
                if (is_dir(MIDRUB_BASE_USER . 'apps/collection/' . $dir . '/widgets/')) {

                    foreach ( glob(MIDRUB_BASE_USER . 'apps/collection/' . $dir . '/widgets/*.php') as $filename ) {

                        $className = str_replace( array( MIDRUB_BASE_USER . 'apps/collection/' . $dir . '/widgets/', '.php' ), '', $filename );
        
                        // Create an array
                        $array = array(
                            'MidrubBase',
                            'User',
                            'Apps',
                            'Collection',
                            ucfirst($dir),
                            'Widgets',
                            ucfirst($className)
                        );       
        
                        // Implode the array above
                        $cl = implode('\\',$array);

                        // Instantiate the class
                        $response = (new $cl())->display_widget($this->CI->user_id, $plan_end, $plan_data);

                        // Add widget to $widgets array
                        $apps_widgets[$response['order'] . '_' . $className]['widget'] = $response['widget'];
        
                    }
                    
                }
                
            }

        }

        if ( $apps_widgets ) {
            
            ksort($apps_widgets);
            
            $e = 0;
            
            foreach ( $apps_widgets as $key_w => $value_w ) {

                if ( $full_size === 'col-xl-5' && $e === 0 ) {

                    if ( !isset($widgets[0]) ) {
                        $widgets[0] = '';
                    }
                    
                    $widgets[0] .= str_replace( '[xl]', '7', $value_w['widget'] );
                    
                } else {
                    
                    $widgets[$key_w] = str_replace( '[xl]', '12', $value_w['widget'] );
                    
                }
                
                $e++;
                
            }
            
        }

        // Set views params
        set_user_view(
            $this->CI->load->ext_view(
                MIDRUB_BASE_USER_APPS_DASHBOARD . 'views',
                'main',
                array(
                    'widgets' => $widgets,
                    'expired' => $expired,
                    'expires_soon' => $expires_soon
                ),
                true
            )
        );

    }
    
}
