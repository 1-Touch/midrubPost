<?php
/**
 * History Widget
 *
 * This file contains the class History
 * with contains the History's widget
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
 * History class provides the methods to process the History's widget
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class History implements MidrubBaseUserAppsCollectionChatbotInterfaces\Widgets {
    
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
        if ( !get_option('app_chatbot_enable_history') || !$this->CI->db->table_exists('chatbot_subscribers') ) {

            // Return empty data
            return array(
                'widget' => '',
                'order' => $widget_info['order']
            );

        }

        // Chatbot Conversations Widget
        $chatbot_conversations_widget = '';

        if ( get_option('app_chatbot_enable') && plan_feature('app_chatbot') && team_role_permission('chatbot') ) {

            // Use the base model for a simply sql query
            $get_subscribers = $this->CI->base_model->get_data_where(
                'chatbot_subscribers',
                'chatbot_subscribers.subscriber_id, chatbot_subscribers.net_id, chatbot_subscribers.name, networks.secret',
                array(
                    'chatbot_subscribers.user_id' => $this->CI->user_id
                ),
                array(),
                array(),
                array(array(
                    'table' => 'networks',
                    'condition' => 'chatbot_subscribers.page_id=networks.network_id',
                    'join_from' => 'LEFT'
                )),
                array(
                    'order' => array('chatbot_subscribers.subscriber_id', 'desc'),
                    'start' => 0,
                    'limit' => 5
                )
            );

            // Create new subscribers variable
            $subscribers = '';

            // Verify if subscribers exists
            if ( $get_subscribers ) {

                // List subscribers
                foreach ( $get_subscribers as $subscriber ) {

                    // Get user's image
                    $image = get('https://graph.facebook.com/' . $subscriber['net_id'] . '/picture?type=square&access_token=' . $subscriber['secret']);

                    // Img variable
                    $img = '';

                    // Verify if user has image
                    if ( $image !== FALSE ) {

                        // Set user's image
                        $img = 'https://graph.facebook.com/' . $subscriber['net_id'] . '/picture?type=large&access_token=' . $subscriber['secret'];

                    } else {

                        // Set default user's image
                        $img = base_url('assets/img/avatar-placeholder.png');

                    }

                    // Add subscriber to list
                    $subscribers .= '<li>'
                            . '<div class="row">'
                                . '<div class="col-xl-8 col-6">'
                                    . '<h3>'
                                        . '<img src="' . $img . '">'
                                        . $subscriber['name']
                                    . '</h3>'
                                . '</div>'
                                . '<div class="col-xl-4 col-6 text-right">'
                                    . '<a href="' . base_url( 'user/app/chatbot?p=subscribers&subscriber=' . $subscriber['subscriber_id']) . '" class="btn btn-outline-info inbox-account-details">'
                                        . '<i class="lni-pie-chart"></i>'
                                        . $this->CI->lang->line('chatbot_details')
                                    . '</a>'
                                . '</div>'
                            . '</div>'
                        . '</li>';

                }
                
            } else {

                // Set default messsage
                $subscribers = '<li class="no-results">'
                        . $this->CI->lang->line('chatbot_no_subscribers_found')
                    . '</li>';

            }


            // Use the base model for a simply sql query
            $get_conversations = $this->CI->base_model->get_data_where(
                'chatbot_subscribers_history',
                'chatbot_subscribers_history.history_id, chatbot_subscribers.subscriber_id, chatbot_subscribers.net_id, chatbot_subscribers.name, networks.secret',
                array(
                    'chatbot_subscribers.user_id' => $this->CI->user_id,
                    'chatbot_subscribers_history.source' => 'facebook_conversations'
                ),
                array(),
                array(),
                array(array(
                    'table' => 'chatbot_subscribers',
                    'condition' => 'chatbot_subscribers_history.subscriber_id=chatbot_subscribers.subscriber_id',
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'networks',
                    'condition' => 'chatbot_subscribers.page_id=networks.network_id',
                    'join_from' => 'LEFT'
                )),
                array(
                    'order' => array('chatbot_subscribers_history.history_id', 'desc'),
                    'start' => 0,
                    'limit' => 5
                )
            );

            // Chatbot conversations variable
            $chatbot_conversations = '';

            // Verify if conversations exists
            if ( $get_conversations ) {

                // List conversations
                foreach ( $get_conversations as $conversation ) {

                    // Get user's image
                    $image = get('https://graph.facebook.com/' . $conversation['net_id'] . '/picture?type=square&access_token=' . $conversation['secret']);

                    // Img variable
                    $img = '';

                    // Verify if user has image
                    if ($image !== FALSE) {

                        // Set user's image
                        $img = 'https://graph.facebook.com/' . $conversation['net_id'] . '/picture?type=large&access_token=' . $conversation['secret'];

                    } else {

                        // Set default user's image
                        $img = base_url('assets/img/avatar-placeholder.png');

                    }

                    // Add conversation to list
                    $chatbot_conversations .= '<li>'
                                . '<div class="row">'
                                    . '<div class="col-xl-8 col-6">'
                                        . '<h3>'
                                            . '<img src="' . $img . '">'
                                            . $conversation['name']
                                        . '</h3>'
                                    . '</div>'
                                    . '<div class="col-xl-4 col-6 text-right">'
                                        . '<a href="' . base_url('user/app/chatbot?p=history&conversation=' . $conversation['history_id']) . '" class="btn btn-outline-info inbox-account-details">'
                                            . '<i class="lni-pie-chart"></i>'
                                            . $this->CI->lang->line('chatbot_details')
                                        . '</a>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

                }

            } else {

                // Set default messsage
                $chatbot_conversations = '<li class="no-results">'
                        . $this->CI->lang->line('chatbot_no_conversations_found')
                    . '</li>';

            }

            // Set Chatbot Conversations Widget's value
            $chatbot_conversations_widget = '<div class="col-xl-4 col-lg-12 col-md-12">'
                . '<div class="dashboard-chatbot-widget theme-box">'
                    . '<div class="panel panel-default">'
                        . '<div class="panel-heading">'
                            . '<div class="row">'
                                . '<div class="col-6 col-lg-8">'
                                    . '<i class="lni-users"></i>'
                                    . $this->CI->lang->line('chatbot_subscribers')
                                . '</div>'
                            . '</div>'
                        . '</div>'
                        . '<div class="panel-body p-3">'
                            . '<ul class="subscribers-list">'
                                . $subscribers
                            . '</ul>'
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
                                    . '<i class="lni-sort-amount-asc"></i>'
                                    . $this->CI->lang->line('chatbot_chatbot_history')
                                . '</div>'
                            . '</div>'
                        . '</div>'
                        . '<div class="panel-body p-3">'
                            . '<ul class="subscribers-list">'
                                . $chatbot_conversations
                            . '</ul>'                                    
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>';

        }

        // Commenter Conversations Widget
        $commenter_conversations_widget = '';
        
        // Verify if the Commenter app is enabled
        if ( get_option('app_commenter_enable') ) {

                // Use the base model for a simply sql query
                $get_conversations = $this->CI->base_model->get_data_where(
                    'chatbot_subscribers_history',
                    'chatbot_subscribers_history.history_id, chatbot_subscribers.subscriber_id, chatbot_subscribers.net_id, chatbot_subscribers.name, networks.secret',
                    array(
                        'chatbot_subscribers.user_id' => $this->CI->user_id,
                        'chatbot_subscribers_history.source' => 'facebook_feed'
                    ),
                    array(),
                    array(),
                    array(array(
                        'table' => 'chatbot_subscribers',
                        'condition' => 'chatbot_subscribers_history.subscriber_id=chatbot_subscribers.subscriber_id',
                        'join_from' => 'LEFT'
                    ), array(
                        'table' => 'networks',
                        'condition' => 'chatbot_subscribers.page_id=networks.network_id',
                        'join_from' => 'LEFT'
                    )),
                    array(
                        'order' => array('chatbot_subscribers_history.history_id', 'desc'),
                        'start' => 0,
                        'limit' => 5
                    )
                );

                // Verify if conversations exists
                if ( $get_conversations ) {

                    // Commenter Conversations variable
                    $commenter_conversations = '';

                    // List conversations
                    foreach ( $get_conversations as $conversation ) {

                        // Sub array
                        $sub_array = array(
                            'history_id' => $conversation['history_id'],
                            'subscriber_id' => $conversation['subscriber_id'],
                            'name' => $conversation['name']
                        );

                        // Get user's image
                        $image = get('https://graph.facebook.com/' . $conversation['net_id'] . '/picture?type=square&access_token=' . $conversation['secret']);

                        // Verify if user has image
                        if ($image !== FALSE) {

                            // Set user's image
                            $sub_array['image'] = 'https://graph.facebook.com/' . $conversation['net_id'] . '/picture?type=large&access_token=' . $conversation['secret'];

                        } else {

                            // Set default user's image
                            $sub_array['image'] = base_url('assets/img/avatar-placeholder.png');

                        }

                        // Add conversation to list
                        $commenter_conversations .= '<li>'
                            . '<div class="row">'
                                . '<div class="col-xl-8 col-6">'
                                    . '<h3>'
                                        . '<img src="' . $img . '">'
                                        . $conversation['name']
                                    . '</h3>'
                                . '</div>'
                                . '<div class="col-xl-4 col-6 text-right">'
                                    . '<a href="' . base_url('user/app/commenter?p=history&conversation=' . $conversation['history_id']) . '" class="btn btn-outline-info inbox-account-details">'
                                        . '<i class="lni-pie-chart"></i>'
                                        . $this->CI->lang->line('chatbot_details')
                                    . '</a>'
                                . '</div>'
                            . '</div>'
                        . '</li>';

                    }

                } else {

                    // Set default messsage
                    $commenter_conversations = '<li class="no-results">'
                            . $this->CI->lang->line('chatbot_no_conversations_found')
                        . '</li>';
        
                }

            // Set Commenter Conversations Widget's value
            $commenter_conversations_widget = '<div class="col-xl-4 col-lg-12 col-md-12">'
                            . '<div class="dashboard-chatbot-widget theme-box">'
                                . '<div class="panel panel-default">'
                                    . '<div class="panel-heading">'
                                        . '<div class="row">'
                                            . '<div class="col-6 col-lg-8">'
                                                . '<i class="lni-sort-amount-asc"></i>'
                                                . $this->CI->lang->line('chatbot_commenter_history')
                                            . '</div>'
                                        . '</div>'
                                    . '</div>'
                                    . '<div class="panel-body p-3">'
                                        . '<ul class="subscribers-list">'
                                            . $commenter_conversations
                                        . '</ul>'                                    
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>';

        }

        // Return data
        return array(
            'widget' => $chatbot_conversations_widget
                        . $commenter_conversations_widget,
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
            'slug' => 'app_chatbot_history',
            'order' => 2
        );
        
    }

}

