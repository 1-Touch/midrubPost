<?php
/**
 * Activity Widget
 *
 * This file contains the class Activity
 * with contains the Activity's widget
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Widgets;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Chatbot\Interfaces as MidrubBaseUserAppsCollectionChatbotInterfaces;

/*
 * Activity class provides the methods to process the Activity's widget
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class Activity implements MidrubBaseUserAppsCollectionChatbotInterfaces\Widgets {
    
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

        // Load language
        $this->CI->lang->load( 'chatbot_widgets', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER . 'apps/collection/chatbot/');
        
    }

    /**
     * The public method display_widget will return the widget html
     * 
     * @since 0.0.8.0
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

        // Verify if the widget is not enabled
        if ( !get_option('app_chatbot_enable_activity') ) {

            // Return empty data
            return array(
                'widget' => '',
                'order' => $widget_info['order']
            );

        }

        // Set Chatbot's Widgets styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/chatbot/styles/css/widgets.css'), 'text/css', 'all'));

        // Set Chart JS
        set_js_urls(array('//www.chartjs.org/dist/2.7.2/Chart.js'));

        // Set Utils JS
        set_js_urls(array('//www.chartjs.org/samples/latest/utils.js'));

        // Set Chatbot's Widgets Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/widgets.js')));

        // Return data
        return array(
            'widget' => '<div class="col-xl-8 col-lg-12 col-md-12">'
                            . '<div class="dashboard-chatbot-widget theme-box">'
                                . '<div class="panel panel-default">'
                                    . '<div class="panel-heading">'
                                        . '<div class="row">'
                                            . '<div class="col-6 col-lg-8">'
                                                . '<i class="lni-stats-up"></i>'
                                                . $this->CI->lang->line('chatbot_activity_stats')
                                            . '</div>'
                                        . '</div>'
                                    . '</div>'
                                    . '<div class="panel-body p-3">'
                                        . '<canvas id="replies-stats-chart" height="500"></canvas>'
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>'
                        . '<div class="col-xl-4 col-lg-12 col-md-12">'
                            . '<div class="dashboard-chatbot-widget theme-box">'
                                . '<div class="panel panel-default">'
                                    . '<div class="panel-heading">'
                                        . '<div class="row">'
                                            . '<div class="col-6 col-lg-8">'
                                                . '<i class="lni-bar-chart"></i>'
                                                . $this->CI->lang->line('chatbot_totally_stats')
                                            . '</div>'
                                        . '</div>'
                                    . '</div>'
                                    . '<div class="panel-body p-3">'
                                        . '<canvas id="pi-chart" height="500"></canvas>'
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>',
            'order' => $widget_info['order']);
        
    }
    
    /**
     * The public method widget_helper processes the widget content
     * 
     * @since 0.0.8.0
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
     * @since 0.0.8.0
     * 
     * @return array with widget information
     */ 
    public function widget_info() {
        
        return array(
            'name' => $this->CI->lang->line('posts_statistics'),
            'slug' => 'app_chatbot_activity',
            'order' => 1
        );
        
    }

}

