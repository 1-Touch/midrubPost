<?php
/**
 * User Controller
 *
 * This file loads the Chatbot app in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Controllers;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * User class loads the Chatbot app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class User {
    
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

        // Load the FB Chatbot Subcribers Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_subscribers_model', 'fb_chatbot_subscribers_model' );

        // Load the FB Chatbot Replies Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_replies_model', 'fb_chatbot_replies_model' );

        // Load the FB Chatbot Phone Numbers Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_phone_numbers_model', 'fb_chatbot_phone_numbers_model' );

        // Load the FB Chatbot Email Addresses Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_email_addresses_model', 'fb_chatbot_email_addresses_model' );
        
        // Load language
        $this->CI->lang->load( 'chatbot_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_CHATBOT );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function view() {

        // Set the page's title
        set_the_title($this->CI->lang->line('chatbot'));

        // Set Chatbot's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/chatbot/styles/css/styles.css?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION), 'text/css', 'all'));

        // Verify if is not the main app's page
        if ( $this->CI->input->get('p', TRUE) ) {

            switch ( $this->CI->input->get('p', TRUE) ) {

                case 'new-suggestions':

                    // Set Chatbot's Suggestions Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/suggestions.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                    // Set views params
                    set_user_view(

                        $this->CI->load->ext_view(
                            MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                            'suggestions',
                            array(),
                            true
                        )

                    );                    

                    break;

                case 'suggestions':

                    // Verify if group's ID exists
                    if ( is_numeric($this->CI->input->get('group', TRUE)) ) {

                        // Get group with base model
                        $group = $this->CI->base_model->get_data_where('chatbot_groups', 'group_id, group_name', array(
                            'group_id' => $this->CI->input->get('group', TRUE),
                            'user_id' => $this->CI->user_id
                        ));

                        // Verify if group exists
                        if ( $group ) {

                            // Set Chatbot's Suggestions Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/suggestions.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                            // View's params
                            $view_params = array(
                                'group_id' => $group[0]['group_id'],
                                'group_name' => $group[0]['group_name']
                            );

                            // Use the base model for a simply sql query
                            $get_categories = $this->CI->base_model->get_data_where(
                                'chatbot_suggestions_categories',
                                'chatbot_categories.category_id, chatbot_categories.name',
                                array(
                                    'chatbot_suggestions_categories.group_id' => $group[0]['group_id'],
                                ),
                                array(),
                                array(),
                                array(array(
                                    'table' => 'chatbot_categories',
                                    'condition' => 'chatbot_suggestions_categories.category_id=chatbot_categories.category_id',
                                    'join_from' => 'LEFT'
                                )),
                                array()
                            );

                            // Verify if categories exists
                            if ( $get_categories ) {
                                $view_params['categories'] = $get_categories;
                            }

                            // Set views params
                            set_user_view(

                                $this->CI->load->ext_view(
                                    MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                                    'suggestions',
                                    $view_params,
                                    true
                                )

                            );

                        } else {

                            // Display 404 page
                            show_404();

                        }

                    } else {

                        // Display 404 page
                        show_404();

                    }

                    break;

                case 'replies':

                    // Verify if reply's ID exists
                    if ( is_numeric($this->CI->input->get('reply', TRUE)) ) {

                        // Set Chart JS
                        set_js_urls(array('//www.chartjs.org/dist/2.7.2/Chart.js')); 

                        // Set Utils JS
                        set_js_urls(array('//www.chartjs.org/samples/latest/utils.js'));                         

                        // Set Chatbot's Reply Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/reply.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                        // Use the base model for a simply sql query
                        $get_reply = $this->CI->base_model->get_data_where(
                            'chatbot_replies',
                            'chatbot_replies.reply_id AS reply_id, chatbot_replies.body AS keywords, chatbot_replies.accuracy AS accuracy, chatbot_replies_response.body AS response, chatbot_replies_response.group_id, , chatbot_replies_response.type',
                            array(
                                'chatbot_replies.reply_id' => $this->CI->input->get('reply', TRUE),
                                'chatbot_replies.user_id' => $this->CI->user_id
                            ),
                            array(),
                            array(),
                            array(array(
                                'table' => 'chatbot_replies_response',
                                'condition' => 'chatbot_replies.reply_id=chatbot_replies_response.reply_id',
                                'join_from' => 'LEFT'
                            ))
                        );

                        // If reply missing, show 404
                        if ( !$get_reply ) {

                            // Display 404 page
                            show_404();

                        }

                        // Params to pass to view
                        $params = array();

                        // Set reply's ID
                        $params['reply_id'] = $get_reply[0]['reply_id'];

                        // Set keywords
                        $params['keywords'] = $get_reply[0]['keywords'];

                        // Set accuracy
                        $params['accuracy'] = $get_reply[0]['accuracy'];

                        // Set the response's type
                        $params['type'] = $get_reply[0]['type'];

                        // Verify which kind of type has the response
                        if ( $params['type'] < 2 ) {

                            // Set response
                            $params['response'] = $get_reply[0]['response'];

                        } else {

                            // Set Group's ID
                            $params['group_id'] = $get_reply[0]['group_id'];

                            // Get group's name
                            $group = $this->CI->base_model->get_data_where(
                                'chatbot_groups',
                                '*',
                                array(
                                    'group_id' => $get_reply[0]['group_id']
                                )
                            );

                            // Verify if group exists
                            if ($group) {

                                // Set the group's name
                                $params['group_name'] = $group[0]['group_name'];

                            }

                        }

                        // Use the base model to get the reply's categories
                        $get_categories = $this->CI->base_model->get_data_where(
                            'chatbot_replies_categories',
                            'chatbot_categories.category_id, chatbot_categories.name',
                            array(
                                'chatbot_replies_categories.reply_id' => $this->CI->input->get('reply', TRUE)
                            ),
                            array(),
                            array(),
                            array(array(
                                'table' => 'chatbot_categories',
                                'condition' => 'chatbot_replies_categories.category_id=chatbot_categories.category_id',
                                'join_from' => 'LEFT'
                            ))
                        );

                        // Verify if categories exists
                        if ( $get_categories ) {

                            // Set categories
                            $params['categories'] = $get_categories;

                        }

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                                'reply',
                                $params,
                                true
                            )

                        );

                    } else {

                        // Set Chatbot's Replies Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/replies-list.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                                'replies',
                                array(),
                                true
                            )

                        );

                    }

                    break;

                case 'pages':

                    // Set Chatbot's Pages Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/pages.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                    // Set views params
                    set_user_view(

                        $this->CI->load->ext_view(
                            MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                            'pages',
                            array(),
                            true
                        )

                    );

                    break; 
                    
                case 'subscribers':

                    // Verify if subscriber's ID exists
                    if ( is_numeric($this->CI->input->get('subscriber', TRUE)) ) {

                        // Set Chatbot's Subscriber Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/subscriber.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                        // Params to pass to view
                        $params = array();

                        // Use the base model to get the subscriber's data
                        $get_subscriber = $this->CI->base_model->get_data_where(
                            'chatbot_subscribers',
                            'chatbot_subscribers.subscriber_id, chatbot_subscribers.net_id, chatbot_subscribers.name, networks.secret',
                            array(
                                'chatbot_subscribers.subscriber_id' => $this->CI->input->get('subscriber', TRUE),
                                'chatbot_subscribers.user_id' => $this->CI->user_id
                            ),
                            array(),
                            array(),
                            array(array(
                                'table' => 'networks',
                                'condition' => 'chatbot_subscribers.page_id=networks.network_id',
                                'join_from' => 'LEFT'
                            ))
                        );

                        // Verify if subscriber exists
                        if ( $get_subscriber ) {

                            // Set subscriber's id
                            $params['subscriber_id'] = $get_subscriber[0]['subscriber_id'];                            

                            // Set subscriber's name
                            $params['name'] = $get_subscriber[0]['name'];

                            // Get user's image
                            $image = get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_subscriber[0]['net_id'] . '/picture?type=square&access_token=' . $get_subscriber[0]['secret']);

                            // Verify if user has image
                            if ($image !== FALSE) {

                                // Set user's image
                                $params['image'] = MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_subscriber[0]['net_id'] . '/picture?type=large&access_token=' . $get_subscriber[0]['secret'];

                            } else {

                                // Set default user's image
                                $params['image'] = base_url('assets/img/avatar-placeholder.png');

                            }

                        } else {

                            // Display 404 page
                            show_404();
                            
                        }

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                                'subscriber',
                                $params,
                                true
                            )

                        );

                    } else {

                        // Set Chatbot's Subscribers Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/subscribers-list.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                                'subscribers',
                                array(),
                                true
                            )

                        );

                    }

                    break;

                case 'history':

                    // Verify if conversation's ID exists
                    if ( is_numeric($this->CI->input->get('conversation', TRUE)) ) {

                        // Set Chatbot's Replies Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/replies-list.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                        // Use the base model to get the conversation's data
                        $get_conversation = $this->CI->base_model->get_data_where(
                            'chatbot_subscribers_history',
                            'chatbot_subscribers.subscriber_id, chatbot_subscribers.net_id, chatbot_subscribers.name, networks.secret, chatbot_subscribers_history.question, chatbot_subscribers_history.response, chatbot_subscribers_history.error, chatbot_subscribers_history.type, , chatbot_subscribers_history.group_id, chatbot_subscribers_history.created, chatbot_groups.group_name',
                            array(
                                'chatbot_subscribers_history.history_id' => $this->CI->input->get('conversation', TRUE),
                                'chatbot_subscribers.user_id' => $this->CI->user_id
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
                            ), array(
                                'table' => 'chatbot_groups',
                                'condition' => 'chatbot_subscribers_history.group_id=chatbot_groups.group_id',
                                'join_from' => 'LEFT'
                            ))
                        );

                        // Params to pass to view
                        $params = array();

                        // Verify if the conversation exists
                        if ( $get_conversation ) {                         

                            // Set subscriber's name
                            $params['name'] = $get_conversation[0]['name'];

                            // Get user's image
                            $image = get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_conversation[0]['net_id'] . '/picture?type=square&access_token=' . $get_conversation[0]['secret']);

                            // Verify if user has image
                            if ($image !== FALSE) {

                                // Set user's image
                                $params['image'] = MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_conversation[0]['net_id'] . '/picture?type=large&access_token=' . $get_conversation[0]['secret'];

                            } else {

                                // Set default user's image
                                $params['image'] = base_url('assets/img/avatar-placeholder.png');

                            }

                            // Set the question
                            $params['question'] = $get_conversation[0]['question'];
                            
                            // Set the response
                            $params['response'] = $get_conversation[0]['response'];

                            // Set the reply's type
                            $params['type'] = $get_conversation[0]['type'];
                            
                            // Set the group's ID
                            $params['group_id'] = $get_conversation[0]['group_id'];

                            // Set the group's name
                            $params['group_name'] = $get_conversation[0]['group_name'];
                            
                            // Set the error
                            $params['error'] = $get_conversation[0]['error'];

                            // Set the created time
                            $params['created'] = $get_conversation[0]['created'];
                            
                        } else {

                            // Display 404 page
                            show_404();
                            
                        }

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                                'conversation',
                                $params,
                                true
                            )

                        );

                    } else {

                        // Set Chatbot's History Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/history.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                        // Set views params
                        set_user_view(

                            $this->CI->load->ext_view(
                                MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                                'history',
                                array(),
                                true
                            )

                        );

                    }

                    break;

                case 'phone-numbers':

                    // Set Chatbot's Phone Numbers Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/phones-list.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                    // Set views params
                    set_user_view(

                        $this->CI->load->ext_view(
                            MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                            'phone_numbers',
                            array(),
                            true
                        )

                    );

                    break;

                case 'email-addresses':

                    // Set Chatbot's Emails Addresses Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/emails-list.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                    // Set views params
                    set_user_view(

                        $this->CI->load->ext_view(
                            MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                            'email_addresses',
                            array(),
                            true
                        )

                    );

                    break;
                    
                case 'audit-logs':

                    // Set Chart JS
                    set_js_urls(array('//www.chartjs.org/dist/2.7.2/Chart.js'));

                    // Set Utils JS
                    set_js_urls(array('//www.chartjs.org/samples/latest/utils.js'));

                    // Set Chatbot's Audit Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/audit-logs.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

                    // Params to pass to view
                    $params = array(); 
                    
                    // Use the base model to get the Facebook Pages count
                    $facebook_pages = $this->CI->base_model->get_data_where(
                        'networks',
                        'COUNT(network_id) AS total',
                        array(
                            'network_name' => 'facebook_pages',
                            'user_id' => $this->CI->user_id
                        )
                    );

                    // Verify if Facebook Pages exists
                    if ( $facebook_pages ) {

                        // Set number of pages
                        $params['total_pages'] = $facebook_pages[0]['total'];

                    } else {

                        // Set number of pages
                        $params['total_pages'] = 0;                        

                    }

                    // Get total number of subscribers with base model
                    $subscribers = $this->CI->base_model->get_data_where(
                        'chatbot_subscribers',
                        'COUNT(subscriber_id) AS total',
                        array(
                            'user_id' => $this->CI->user_id
                        )
                    );

                    // Verify if subscribers exists
                    if ( $subscribers ) {

                        // Set number of subscribers
                        $params['total_subscribers'] = $subscribers[0]['total'];

                    } else {

                        // Set number of subscribers
                        $params['total_subscribers'] = 0;                        

                    }

                    // Get total number of replies with base model
                    $replies = $this->CI->base_model->get_data_where(
                        'chatbot_subscribers_history',
                        'COUNT(history_id) AS total',
                        array(
                            'user_id' => $this->CI->user_id,
                            'source' => 'facebook_conversations'
                        )
                    );

                    // Verify if replies exists
                    if ( $replies ) {

                        // Set number of replies
                        $params['total_replies'] = $replies[0]['total'];

                    } else {

                        // Set number of subscribers
                        $params['total_replies'] = 0;                        

                    }                    

                    // Set views params
                    set_user_view(

                        $this->CI->load->ext_view(
                            MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                            'audit_logs',
                            $params,
                            true
                        )

                    );

                    break;

                default:

                    // Display 404 page
                    show_404();
                
                    break;

            }

        } else {

            // Set Chatbot's Suggestions List Js
            set_js_urls(array(base_url('assets/base/user/apps/collection/chatbot/js/suggestions-list.js?ver=' . MIDRUB_BASE_USER_APPS_CHATBOT_VERSION)));

            // Prepare the params to pass
            $params = array(
                'messages_limit' => $this->check_plan_limit(),
                'new_phone_numbers' => 0,
                'new_email_addresses' => 0
            );

            // Use the base model for a simply sql query
            $get_new_phone_numbers = $this->CI->base_model->get_data_where(
                'chatbot_phone_numbers',
                'phone_id',
                array(
                    'user_id' => $this->CI->user_id,
                    'new >' => 0
                )
            );

            // Verify if new phone numbers exists
            if ( $get_new_phone_numbers ) {

                // Set notification
                $params['new_phone_numbers'] = 1;

            }

            // Use the base model for a simply sql query
            $get_new_email_addresses = $this->CI->base_model->get_data_where(
                'chatbot_email_addresses',
                'email_id',
                array(
                    'user_id' => $this->CI->user_id,
                    'new >' => 0
                )
            );

            // Verify if new email addresses exists
            if ( $get_new_email_addresses ) {

                // Set notification
                $params['new_email_addresses'] = 1;

            }

            // Set views params
            set_user_view(

                $this->CI->load->ext_view(
                    MIDRUB_BASE_USER_APPS_CHATBOT . 'views',
                    'main',
                    $params,
                    true
                )

            );

        }
        
    }

    /**
     * The protected method check_plan_limit verifies if user has reached the plan's limit
     * 
     * @since 0.0.8.0
     * 
     * @return boolean true or false
     */
    protected function check_plan_limit() {

        // Get number of bot's messages
        $sent_messages = get_user_option('facebook_chatbot_bot_messages');

        // Verify if bot has replied before
        if ( $sent_messages ) {

            // Unserialize array
            $messages_array = unserialize($sent_messages);

            // Get the user's plan
            $plan_id = get_user_option('plan');

            // Get the messages for the user's plan
            $messages = plan_feature('app_facebook_chatbot_replies', $plan_id);

            // If user didn't configured the plan for replies, the value is 0
            if ( !$messages ) {
                $messages = 0;
            }

            // Verify if user has reached the plan's limits
            if ( ($messages_array['date'] === date('Y-m')) && ( $messages <= $messages_array['messages']) ) {
                return true;
            }

        }
        
        return false;

    }

}

/* End of file user.php */