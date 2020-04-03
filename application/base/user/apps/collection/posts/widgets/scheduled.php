<?php
/**
 * Scheduled Widget
 *
 * This file contains the class Scheduled
 * with contains the Scheduled's widget
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Widgets;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Interfaces as MidrubBaseUserAppsCollectionPostsInterfaces;

/*
 * Scheduled class provides the methods to process the Scheduled's widget
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Scheduled implements MidrubBaseUserAppsCollectionPostsInterfaces\Widgets {
    
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

        // Load language
        $this->CI->lang->load( 'widgets', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER . 'apps/collection/posts/');
        
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
        
        return array(
            'widget' => '<div class="col-xl-[xl] col-lg-12 col-md-12 graphs">'
                            . '<div class="row">'
                                . '<div class="col-xl-12">'
                                    . '<div class="col-xl-12 theme-box">'
                                        . '<div class="canvas-header">'
                                            . '<h4 class="canvas-title">' . $this->CI->lang->line('posts') . '</h4>'
                                            . '<p class="canvas-description">' . $this->CI->lang->line('posts_in_the_last_30_days') . '</p>'
                                        . '</div>'
                                        . '<canvas id="canvas"></canvas>'
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
            'name' => $this->CI->lang->line('posts_statistics'),
            'slug' => 'app_posts_posts_statistics',
            'order' => 1
        );
        
    }

}

