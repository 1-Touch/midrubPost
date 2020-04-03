<?php
/**
 * Plan Widget
 *
 * This file contains the class Plan
 * with contains the plan's widget
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
 * Plan class provides the methods to process the plan widget
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Plan implements MidrubBaseUserAppsCollectionDashboardInterfaces\Widgets {
    
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
        
        // Calculate remaining time
        $time = strip_tags( calculate_time(strtotime($plan_end), time()) );
        
        // Get percentage
        $time_left = number_format($this->widget_helper( $user_id, $plan_end, $plan_data ));
        
        // Get processbar color
        if ( $time_left < 90 ) {
            
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
                                        . '<i class="icon-user"></i>'
                                    . '</div>'
                                    . '<div class="col-xl-9 col-sm-9 col-9">'
                                        . '<h3>' . $plan_data[0]['plan_name'] . '</h3>'
                                        . '<p>' . $plan_data[0]['header'] . '</p>'
                                    . '</div>'
                                . '</div>'
                                . '<div class="row">'
                                    . '<div class="col-xl-12">'
                                        . '<hr>'
                                        . '<div class="stats-bottom">'
                                            . '<div class="row">'
                                                . '<div class="col-xl-9 col-sm-10 col-10">'
                                                    . '<i class="icon-hourglass"></i> ' . $time
                                                . '</div>'
                                                . '<div class="col-xl-3 col-sm-2 col-2 text-right">'
                                                    . $upgrade
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="progress">'
                                                . '<div class="progress-bar' . $color . '" role="progressbar" style="width: ' . $time_left . '%" aria-valuenow="' . $time_left . '" aria-valuemin="0" aria-valuemax="100"></div>'
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
        
        $period = (strtotime($plan_end) - (strtotime($plan_end) - ($plan_data[0]['period'] * 86400)));
        
        $time_taken = ( strtotime($plan_end) - time() );
        
        return ( ( $period - $time_taken ) / $period ) * 100;
        
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
            'name' => plan_feature('plan_name'),
            'slug' => 'app_dashboard_plan_widget',
            'order' => 1
        );
        
    }

}

