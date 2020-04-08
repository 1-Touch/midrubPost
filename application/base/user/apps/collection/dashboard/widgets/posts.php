<?php
/**
 * Posts Widget
 *
 * This file contains the class Posts
 * with contains the posts widget
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
 * Posts class provides the methods to process the posts widget
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Posts implements MidrubBaseUserAppsCollectionDashboardInterfaces\Widgets {
    
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
        
        // Get published posts
        $published_posts = get_user_option( 'published_posts', $user_id );
        
        // Create $published variable
        $published = 0;
        
        if ( $published_posts ) {
            
            $posts_data = unserialize($published_posts);
            
            if ( ($posts_data['date'] === date('Y-m')) ) {

                $published = $posts_data['posts'];

            }
            
        }
        
        $plan_data[0]['published_posts'] = $published;
        
        // Get percentage
        $posts_left = number_format($this->widget_helper( $user_id, $plan_end, $plan_data ));

        // Get processbar color
        if ( $posts_left < 90 ) {
            
            $color = ' bg-success';
            
        } else {
            
            $color = ' bg-danger';
            
        }
        
        $publish_posts = 0;
        
        if ( isset($plan_data[0]['publish_posts']) ) {
            $publish_posts = $plan_data[0]['publish_posts'];
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
                                        . '<i class="fa fa-paper-plane"></i>'
                                    . '</div>'
                                    . '<div class="col-xl-9 col-sm-9 col-9">'
                                        . '<h3>' . $this->CI->lang->line('posts') . '</h3>'
                                        . '<p>' . $this->CI->lang->line('in_this_month') . '</p>'
                                    . '</div>'
                                . '</div>'
                                . '<div class="row">'
                                    . '<div class="col-xl-12">'
                                        . '<hr>'
                                        . '<div class="stats-bottom">'
                                            . '<div class="row">'
                                                . '<div class="col-xl-9 col-sm-10 col-10">'
                                                    . $published . '/' . $publish_posts
                                                . '</div>'
                                                . '<div class="col-xl-3 col-sm-2 col-2 text-right">'
                                                    . $upgrade
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="progress">'
                                                . '<div class="progress-bar' . $color . '" role="progressbar" style="width: ' . $posts_left . '%" aria-valuenow="' . $posts_left . '" aria-valuemin="0" aria-valuemax="100"></div>'
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
        
        // If is empty return 0
        if ( !$plan_data[0]['publish_posts'] ) {
            return '0';
        }

        return (100 - ( ( $plan_data[0]['publish_posts'] - $plan_data[0]['published_posts'] ) / $plan_data[0]['publish_posts'] ) * 100);
        
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
            'name' => $this->CI->lang->line('posts'),
            'slug' => 'app_dashboard_posts_widget',
            'order' => 3
        );
        
    }

}

