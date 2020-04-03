<?php
/**
 * Storage Widget
 *
 * This file contains the class Storage
 * with contains the storage widget
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Dashboard\Widgets;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Dashboard\Interfaces as MidrubBaseUserAppsCollectionDashboardInterfaces;

/*
 * Storage class provides the methods to process the storage widget
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Storage implements MidrubBaseUserAppsCollectionDashboardInterfaces\Widgets {
    
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
        
    }

    /**
     * The public method display_widget will return the widget html
     * 
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user's id
     * @param string $plan_end contains the plan's end period time
     * @param object $plan_data contains the user's plan's data
     * 
     * @return array with widget html
     */ 
    public function display_widget( $user_id, $plan_end, $plan_data ) {
        
        // Get the widget info
        $widget_info = $this->widget_info();
        
        // Get user storage
        $user_storage = get_user_option( 'user_storage', $this->CI->user_id );
        
        $plan_data[0]['user_storage'] = $user_storage;
        
        if ( $user_storage ) {
            
            $user_storage = calculate_size($user_storage);
            
        } else {
            
            $user_storage = 0;
            $plan_data[0]['user_storage'] = 0;
                    
        }
        
        // Get percentage
        $free_space = number_format($this->widget_helper( $user_id, $plan_end, $plan_data ));

        // Get processbar color
        if ( $free_space < 90 ) {
            
            $color = ' bg-success';
            
        } else {
            
            $color = ' bg-danger';
            
        }
        
        $upgrade = '';
        
        if ( !$this->CI->session->userdata( 'member' ) ) {
            $upgrade = '<a href="' . site_url('user/plans') . '"><i class="fas fa-chart-line"></i></a>';
        }
        
        return array(
            'widget' => '<div class="col-xl-' . $plan_data[0]['size'] . '">'
                            . '<div class="col-xl-12 theme-box">'
                                . '<div class="row">'
                                    . '<div class="col-xl-3 col-sm-3 col-3">'
                                        . '<i class="icon-drawer"></i>'
                                    . '</div>'
                                    . '<div class="col-xl-9 col-sm-9 col-9">'
                                        . '<h3>' . $this->CI->lang->line('storage') . '</h3>'
                                        . '<p>' . $this->CI->lang->line('total_available_storage') . '</p>'
                                    . '</div>'
                                . '</div>'
                                . '<div class="row">'
                                    . '<div class="col-xl-12">'
                                        . '<hr>'
                                        . '<div class="stats-bottom">'
                                            . '<div class="row">'
                                                . '<div class="col-xl-9 col-sm-10 col-10">'
                                                    . $user_storage . '/' . calculate_size($plan_data[0]['storage'])
                                                . '</div>'
                                                . '<div class="col-xl-3 col-sm-2 col-2 text-right">'
                                                    . $upgrade
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="progress">'
                                                . '<div class="progress-bar' . $color . '" role="progressbar" style="width: ' . $free_space . '%" aria-valuenow="' . $free_space . '" aria-valuemin="0" aria-valuemax="100"></div>'
                                            . '</div>'
                                        . '</div>'
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>',
            'order' => $widget_info['order']);
        
    }
    
    /**
     * The public method widget_helper processes the widget content
     * 
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user's id
     * @param string $plan_end contains the plan's end period time
     * @param object $plan_data contains the user's plan's data
     * 
     * @return array with widget's content
     */ 
    public function widget_helper( $user_id, $plan_end, $plan_data ) {
        
        if ( $plan_data[0]['storage'] < 1 ) {
            return '0';
        }
        
        return (100 - ( ( $plan_data[0]['storage'] - $plan_data[0]['user_storage'] ) / $plan_data[0]['storage'] ) * 100);
        
    }
    
    /**
     * The public method widget_info contains the widget options
     * 
     * @since 0.0.7.0
     * 
     * @return array with widget information
     */ 
    public function widget_info() {
        
        return array(
            'name' => $this->CI->lang->line('storage'),
            'slug' => 'app_dashboard_storage_widget',
            'order' => 4
        );
        
    }

}

