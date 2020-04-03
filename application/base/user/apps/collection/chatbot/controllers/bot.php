<?php
/**
 * Bot Controller
 *
 * This file works as bot and processes the requests
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
 * Bot class works as bot and processes the requests
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Bot {
    
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

        // Load the FB Chatbot Replies Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_replies_model', 'fb_chatbot_replies_model' );

        // Load the FB Chatbot Subcribers Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_subscribers_model', 'fb_chatbot_subscribers_model' );

        // Load language
        $this->CI->lang->load( 'chatbot_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_CHATBOT );
        
    }
    
    /**
     * The public method process processes the request
     * 
     * @param array $request contains the request array
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function process($request) {

        // Use the base model to get the page's data
        $get_page = $this->CI->base_model->get_data_where(
            'networks',
            '*',
            array(
                'net_id' => $request['entry'][0]['id'],
                'network_name' => 'facebook_pages'
            )
        );

        // Verify if the page exists
        if ($get_page) {

            // Get number of bot's messages
            $sent_messages = get_user_option('facebook_chatbot_bot_messages', $get_page[0]['user_id']);

            // Verify if bot has replied before
            if ($sent_messages) {

                // Unserialize array
                $messages_array = unserialize($sent_messages);

                // Get the user's plan
                $plan_id = get_user_option('plan', $get_page[0]['user_id']);

                // Get the messages for the user's plan
                $messages = plan_feature('app_facebook_chatbot_replies', $plan_id);

                // If user didn't configured the plan for replies, the value is 0
                if (!$messages) {
                    $messages = 0;
                }

                // Verify if user has reached the plan's limits
                if (($messages_array['date'] === date('Y-m')) and ($messages <= $messages_array['messages'])) {
                    exit();
                }
            }

            // Get page conversations
            $conversations = json_decode(get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_page[0]['net_id'] . '/conversations?fields=scoped_thread_key&access_token=' . $get_page[0]['secret']), true);

            // Verify if conversations exists
            if (isset($conversations['data'][0]['id'])) {

                // Get page messages
                $messages = json_decode(get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $conversations['data'][0]['id'] . '/messages?fields=id,from,message&access_token=' . $get_page[0]['secret']), true);

                // Verify if messages exists
                if (isset($messages['data'][0]['from']['id'])) {

                    // Verify if last message wasn't provided by the bot
                    if ($messages['data'][0]['from']['id'] !== $get_page[0]['net_id']) {

                        // Get thread ID
                        $thread_id = $conversations['data'][0]['id'];

                        // Verify if payload exists
                        if (isset($request['entry'][0]['messaging'][0]['postback']['payload'])) {

                            // Get suggestions ids
                            $id = explode('suggestion-', $request['entry'][0]['messaging'][0]['postback']['payload']);

                            // Verify if the payload was provided by Midrub Facebook Chatbot
                            if (isset($id[1])) {

                                // Get suggestions
                                $group_suggestions = $this->CI->base_model->get_data_where(
                                    'chatbot_suggestions_meta',
                                    'chatbot_suggestions_meta.suggestion_id, chatbot_suggestions_meta.field_type, chatbot_suggestions_meta.field_name, chatbot_suggestions_meta.field_value',
                                    array(
                                        'chatbot_suggestions_meta.field_type' => 'body',
                                        'chatbot_suggestions.parent_id' => $id[1]
                                    ),
                                    array(),
                                    array(),
                                    array(array(
                                        'table' => 'chatbot_suggestions',
                                        'condition' => 'chatbot_suggestions_meta.suggestion_id=chatbot_suggestions.suggestion_id',
                                        'join_from' => 'LEFT'
                                    ))

                                );

                                // Get suggestions header
                                $group_header = $this->CI->base_model->get_data_where(
                                    'chatbot_suggestions_meta',
                                    'chatbot_suggestions.group_id, chatbot_suggestions.template_type, chatbot_suggestions_meta.suggestion_id, chatbot_suggestions_meta.field_type, chatbot_suggestions_meta.field_name, chatbot_suggestions_meta.field_value',
                                    array(
                                        'chatbot_suggestions_meta.field_type' => 'header',
                                        'chatbot_suggestions.parent_id' => $id[1]
                                    ),
                                    array(),
                                    array(),
                                    array(array(
                                        'table' => 'chatbot_suggestions',
                                        'condition' => 'chatbot_suggestions_meta.suggestion_id=chatbot_suggestions.suggestion_id',
                                        'join_from' => 'LEFT'
                                    ))

                                );

                                // Verify if suggestions exists
                                if ($group_suggestions && $group_header) {

                                    // Set header's image
                                    $image = '';

                                    // Set header's title
                                    $title = '';

                                    // Set header's subtitle
                                    $subtitle = '';

                                    // Set header's link
                                    $link = '';

                                    // List the header fields
                                    foreach ($group_header as $header) {

                                        // Switch fields
                                        switch ($header['field_name']) {

                                            case 'title':

                                                // Set new title's value
                                                $title = $header['field_value'];

                                                break;

                                            case 'subtitle':

                                                // Set new subtitle's value
                                                $subtitle = $header['field_value'];

                                                break;

                                            case 'url':

                                                // Set new link's value
                                                $link = $header['field_value'];

                                                break;

                                            case 'cover_src':

                                                // Set new image's value
                                                $image = $header['field_value'];

                                                break;
                                        }
                                    }

                                    // Default field's name
                                    $field_name = '';

                                    // Suggestion's items
                                    $suggestion_items = array();

                                    // Count items
                                    $count_items = 0;

                                    // List all suggestions
                                    foreach ($group_suggestions as $group_suggestion) {

                                        if ($field_name === $group_suggestion['field_name']) {
                                            $count_items++;
                                        } else if (!$field_name) {
                                            $field_name = $group_suggestion['field_name'];
                                        }

                                        $suggestion_items[$count_items]['suggestion_id'] = $group_suggestion['suggestion_id'];
                                        $suggestion_items[$count_items][$group_suggestion['field_name']] = $group_suggestion['field_value'];
                                    }

                                    // Verify if items exists
                                    if ($suggestion_items) {

                                        // Items array
                                        $items = array();

                                        // List all suggestion's items
                                        foreach ($suggestion_items as $suggestion_item) {

                                            // Verify the option's type
                                            switch ($suggestion_item['type']) {

                                                case 'link':

                                                    // Set item
                                                    $items[] = array(
                                                        'type' => 'web_url',
                                                        'title' => $suggestion_item['title'],
                                                        'url' => $suggestion_item['link']
                                                    );

                                                    break;

                                                case 'suggestions-group':

                                                    // Set item
                                                    $items[] = array(
                                                        'type' => 'postback',
                                                        'title' => $suggestion_item['title'],
                                                        'payload' => 'suggestion-' . $suggestion_item['suggestion_id']
                                                    );

                                                    break;
                                            }
                                        }

                                        // History ID var
                                        $history_id = 0;

                                        // Get subscriber
                                        $subscriber = $this->CI->base_model->get_data_where(
                                            'chatbot_subscribers',
                                            'subscriber_id',
                                            array(
                                                'net_id' => $messages['data'][0]['from']['id'],
                                                'network_name' => 'facebook_pages'

                                            )

                                        );

                                        // Verify if subscriber exists
                                        if ($subscriber) {

                                            // Prepare the history
                                            $history = array(
                                                'user_id' => $get_page[0]['user_id'],
                                                'page_id' => $get_page[0]['network_id'],
                                                'subscriber_id' => $subscriber[0]['subscriber_id'],
                                                'question' => $this->CI->security->xss_clean($messages['data'][0]['message']),
                                                'group_id' => $group_header[0]['group_id'],
                                                'source' => 'facebook_conversations',
                                                'type' => 2,
                                                'created' => time()
                                            );

                                            // Save subscriber's history by using the Base's Model
                                            $query = $this->CI->base_model->insert('chatbot_subscribers_history', $history);

                                            // Verify if the history was saved
                                            if ($query) {
                                                $history_id = $query;
                                            }
                                        } else {

                                            // Prepare the subscriber data
                                            $subscriber = array(
                                                'user_id' => $get_page[0]['user_id'],
                                                'page_id' => $get_page[0]['network_id'],
                                                'network_name' => 'facebook_pages',
                                                'net_id' => $messages['data'][0]['from']['id'],
                                                'name' => $messages['data'][0]['from']['name'],
                                                'created' => time()
                                            );

                                            // Save subscriber's by using the Base's Model
                                            $subscriber_id = $this->CI->base_model->insert('chatbot_subscribers', $subscriber);

                                            // Verify if the subscriber was saved
                                            if ($subscriber_id) {

                                                // Prepare the history
                                                $history = array(
                                                    'user_id' => $get_page[0]['user_id'],
                                                    'page_id' => $get_page[0]['network_id'],
                                                    'subscriber_id' => $subscriber_id,
                                                    'question' => $this->CI->security->xss_clean($messages['data'][0]['message']),
                                                    'group_id' => $group_header[0]['group_id'],
                                                    'source' => 'facebook_conversations',
                                                    'type' => 2,
                                                    'created' => time()
                                                );

                                                // Save subscriber's history by using the Base's Model
                                                $query = $this->CI->base_model->insert('chatbot_subscribers_history', $history);

                                                // Verify if the history was saved
                                                if ($query) {
                                                    $history_id = $query;
                                                }

                                            }
                                            
                                        }

                                        // Default response array
                                        $response = array();

                                        // Use response by template's type
                                        switch ($group_header[0]['template_type']) {

                                            case 'media-template':

                                                // Prepare the media's array
                                                $media_array = array(
                                                    'message' => array(
                                                        'attachment' => array(
                                                            'type' => 'image',
                                                            'payload' => array(
                                                                'is_reusable' => true,
                                                                'url' => $image

                                                            )

                                                        )

                                                    )

                                                );


                                                // Upload Media
                                                $media_response = json_decode(post(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . 'me/message_attachments', $media_array, $get_page[0]['secret']), true);

                                                // Verify if the media was uploaded
                                                if (isset($media_response['attachment_id'])) {

                                                    // Prepare the response
                                                    $response = array(
                                                        'recipient' => array(
                                                            'id' => $request['entry'][0]['messaging'][0]['sender']['id']
                                                        ),
                                                        'messaging_type' => 'RESPONSE',
                                                        'message' => array(
                                                            'attachment' => array(
                                                                'type' => 'template',
                                                                'payload' => array(
                                                                    'template_type' => 'media',
                                                                    "elements" => array(
                                                                        array(
                                                                            'media_type' => 'image',
                                                                            'attachment_id' => $media_response['attachment_id'],
                                                                            'buttons' => $items

                                                                        )

                                                                    )

                                                                )

                                                            )

                                                        )

                                                    );
                                                    
                                                } else {

                                                    // Verify if history's ID exists
                                                    if ($history_id > 0) {

                                                        // Save the error
                                                        $this->CI->base_model->update_ceil('chatbot_subscribers_history', array(
                                                            'history_id' => $history_id
                                                        ), array(
                                                            'error' => $this->CI->lang->line('chatbot_image_can_not_be_uploaded')
                                                        ));
                                                        
                                                    }
                                                }

                                                break;

                                            case 'button-template':

                                                // Prepare the response
                                                $response = array(
                                                    'recipient' => array(
                                                        'id' => $request['entry'][0]['messaging'][0]['sender']['id']
                                                    ),
                                                    'messaging_type' => 'RESPONSE',
                                                    'message' => array(
                                                        'attachment' => array(
                                                            'type' => 'template',
                                                            'payload' => array(
                                                                'template_type' => 'button',
                                                                'text' => $title,
                                                                'buttons' => $items

                                                            )

                                                        )

                                                    )

                                                );

                                                break;

                                            default:

                                                // Prepare the response
                                                $response = array(
                                                    'recipient' => array(
                                                        'id' => $request['entry'][0]['messaging'][0]['sender']['id']
                                                    ),
                                                    'messaging_type' => 'RESPONSE',
                                                    'message' => array(
                                                        'attachment' => array(
                                                            'type' => 'template',
                                                            'payload' => array(
                                                                'template_type' => 'generic',
                                                                'elements' => array(
                                                                    array(
                                                                        'title' => $title,
                                                                        'image_url' => $image,
                                                                        'subtitle' => $subtitle,
                                                                        'default_action' => array(
                                                                            'type' => 'web_url',
                                                                            'url' => $link,
                                                                            'webview_height_ratio' => 'tall'
                                                                        ),
                                                                        'buttons' => $items

                                                                    )

                                                                )

                                                            )

                                                        )

                                                    )

                                                );


                                                break;
                                        }

                                        // Prepare the response
                                        $response = array(
                                            'page' => $get_page,
                                            'request' => $request,
                                            'response' => $response,
                                            'history_id' => $history_id
                                        );

                                        // Send the response
                                        $this->send_response($response);
                                        exit();

                                    }

                                }

                            }

                        } else {

                            // Use the base model to get all Facebook Page's categories
                            $categories = $this->CI->base_model->get_data_where(
                                'chatbot_pages_categories',
                                '*',
                                array(
                                    'page_id' => $get_page[0]['network_id']
                                )
                            );

                            // If categories exists add them to array
                            if ($categories) {

                                // Categories array
                                $array = array();

                                // List found categories
                                foreach ($categories as $category) {

                                    // Add categories to array
                                    $array[] = $category['category_id'];
                                }

                                // Get all replies based on categories
                                $all_replies = $this->CI->fb_chatbot_replies_model->get_replies_by_categories($array);

                                // Verify if replies exists
                                if ($all_replies) {

                                    // Best match reply
                                    $best_match = array();

                                    // List all replies
                                    foreach ($all_replies as $reply) {

                                        // Get keywords
                                        $keywords = explode(' ', $reply['keywords']);

                                        // Verify if keywords exists
                                        if ($keywords) {

                                            // Prepare the message
                                            $message = $this->clear($messages['data'][0]['message']);

                                            // Verify if text exists
                                            if ($message) {

                                                // Count matches
                                                $count = 0;

                                                // List keywords
                                                foreach ($keywords as $keyword) {

                                                    // Verify if the keyword is a string
                                                    if (!$this->clear($keyword)) {
                                                        continue;
                                                    }

                                                    // Verify if keyword exists
                                                    if (strpos($message, $this->clear($keyword)) !== false) {
                                                        $count++;
                                                    }
                                                }

                                                // Calculate percentage
                                                $percentChange = (1 - $count / count($keywords)) * 100;

                                                // Set percentage
                                                $total = round((100 - $percentChange), 0);

                                                // Verify if the reply is good
                                                if ($total >= $reply['accuracy']) {

                                                    // Verify if best match exists
                                                    if ($best_match) {

                                                        // Very if old accuraccy is less
                                                        if ($best_match['accuracy'] < $total) {

                                                            // Set accuracy
                                                            $best_match['accuracy'] = $total;

                                                            // Set reply
                                                            $best_match['reply'] = $reply;
                                                        }

                                                    } else {

                                                        // Set accuracy
                                                        $best_match['accuracy'] = $total;

                                                        // Set reply
                                                        $best_match['reply'] = $reply;

                                                    }

                                                }

                                            }

                                        }

                                    }

                                    // Verify if best match exists
                                    if ($best_match) {

                                        // Use the base model to get all the reply's categories
                                        $reply_categories = $this->CI->base_model->get_data_where(
                                            'chatbot_replies_categories',
                                            '*',
                                            array(
                                                'reply_id' => $best_match['reply']['reply_id']
                                            )
                                        );

                                        // Use the base model to get the reply's response
                                        $response = $this->CI->base_model->get_data_where(
                                            'chatbot_replies_response',
                                            '*',
                                            array(
                                                'reply_id' => $best_match['reply']['reply_id']
                                            )
                                        );

                                        // Verify if reply response exists
                                        if ($response) {

                                            // Get the response's type
                                            if ($response[0]['type'] < 2) {

                                                // History ID var
                                                $history_id = 0;

                                                // Verify if subscriber exists
                                                if ($subscriber) {

                                                    // Verify if categories exists
                                                    if ($reply_categories) {

                                                        // List all categories
                                                        foreach ($reply_categories as $category) {

                                                            // Verify if the user has already the category
                                                            $subscriber_has = $this->CI->base_model->get_data_where(
                                                                'chatbot_subscribers_categories',
                                                                '*',
                                                                array(
                                                                    'subscriber_id' => $subscriber[0]['subscriber_id'],
                                                                    'category_id' => $category['category_id']
                                                                )

                                                            );

                                                            // If missing, save category
                                                            if (!$subscriber_has) {

                                                                // Category's array
                                                                $cat = array(
                                                                    'subscriber_id' => $subscriber[0]['subscriber_id'],
                                                                    'category_id' => $category['category_id']
                                                                );

                                                                // Save category's by using the Base's Model
                                                                $this->CI->base_model->insert('chatbot_subscribers_categories', $cat);
                                                            }
                                                        }
                                                    }

                                                    // Prepare the history
                                                    $history = array(
                                                        'user_id' => $get_page[0]['user_id'],
                                                        'page_id' => $get_page[0]['network_id'],
                                                        'reply_id' => $best_match['reply']['reply_id'],
                                                        'subscriber_id' => $subscriber[0]['subscriber_id'],
                                                        'question' => $this->CI->security->xss_clean($messages['data'][0]['message']),
                                                        'response' => $response[0]['body'],
                                                        'source' => 'facebook_conversations',
                                                        'type' => 1,
                                                        'created' => time()
                                                    );

                                                    // Save subscriber's history by using the Base's Model
                                                    $query = $this->CI->base_model->insert('chatbot_subscribers_history', $history);

                                                    // Verify if the history was saved
                                                    if ($query) {
                                                        $history_id = $query;
                                                    }
                                                } else {

                                                    // Prepare the subscriber data
                                                    $subscriber = array(
                                                        'user_id' => $get_page[0]['user_id'],
                                                        'page_id' => $get_page[0]['network_id'],
                                                        'network_name' => 'facebook_pages',
                                                        'net_id' => $messages['data'][0]['from']['id'],
                                                        'name' => $messages['data'][0]['from']['name'],
                                                        'created' => time()
                                                    );

                                                    // Save subscriber's by using the Base's Model
                                                    $subscriber_id = $this->CI->base_model->insert('chatbot_subscribers', $subscriber);

                                                    // Verify if the subscriber was saved
                                                    if ($subscriber_id) {

                                                        // Verify if categories exists
                                                        if ($reply_categories) {

                                                            // List all categories
                                                            foreach ($reply_categories as $category) {

                                                                // Category's array
                                                                $cat = array(
                                                                    'subscriber_id' => $subscriber_id,
                                                                    'category_id' => $category['category_id']
                                                                );

                                                                // Save category's by using the Base's Model
                                                                $this->CI->base_model->insert('chatbot_subscribers_categories', $cat);
                                                            }
                                                        }

                                                        // Prepare the history
                                                        $history = array(
                                                            'user_id' => $get_page[0]['user_id'],
                                                            'page_id' => $get_page[0]['network_id'],
                                                            'reply_id' => $best_match['reply']['reply_id'],
                                                            'subscriber_id' => $subscriber_id,
                                                            'question' => $this->CI->security->xss_clean($messages['data'][0]['message']),
                                                            'response' => $response[0]['body'],
                                                            'source' => 'facebook_conversations',
                                                            'type' => 1,
                                                            'created' => time()
                                                        );

                                                        // Save subscriber's history by using the Base's Model
                                                        $query = $this->CI->base_model->insert('chatbot_subscribers_history', $history);

                                                        // Verify if the history was saved
                                                        if ($query) {
                                                            $history_id = $query;
                                                        }

                                                    }
                                                    
                                                }

                                                // Set response
                                                $response = json_decode(post(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $thread_id . '/messages', array('message' => $response[0]['body']), $get_page[0]['secret']), true);

                                                // Verify if response wasn't sent successfully
                                                if (isset($response['error'])) {

                                                    // Verify if history's id is positive
                                                    if ($history_id) {

                                                        // Save the error
                                                        $this->CI->base_model->update_ceil('chatbot_subscribers_history', array(
                                                            'history_id' => $history_id
                                                        ), array(
                                                            'error' => json_encode($response)
                                                        ));
                                                    }
                                                    
                                                } else {

                                                    // Register a new reply
                                                    $this->set_bot_message_number($get_page[0]['user_id']);

                                                    // Set history's id
                                                    $request['history_id'] = $history_id;

                                                    // Set user's id
                                                    $request['user_id'] = $get_page[0]['user_id'];

                                                    // Verify for phone numbers
                                                    $this->set_phone_numbers( $request );

                                                    // Verify for email addresses
                                                    $this->set_email_addresses( $request );

                                                }

                                                exit();

                                            } else if ($response[0]['type'] > 1) {

                                                // Get suggestions
                                                $group_suggestions = $this->CI->base_model->get_data_where(
                                                    'chatbot_suggestions_meta',
                                                    'chatbot_suggestions_meta.suggestion_id, chatbot_suggestions_meta.field_type, chatbot_suggestions_meta.field_name, chatbot_suggestions_meta.field_value',
                                                    array(
                                                        'chatbot_suggestions.group_id' => $response[0]['group_id'],
                                                        'chatbot_suggestions_meta.field_type' => 'body',
                                                        'chatbot_suggestions.parent_id <' => 1
                                                    ),
                                                    array(),
                                                    array(),
                                                    array(array(
                                                        'table' => 'chatbot_suggestions',
                                                        'condition' => 'chatbot_suggestions_meta.suggestion_id=chatbot_suggestions.suggestion_id',
                                                        'join_from' => 'LEFT'
                                                    ))

                                                );

                                                // Get suggestions header
                                                $group_header = $this->CI->base_model->get_data_where(
                                                    'chatbot_suggestions_meta',
                                                    'chatbot_suggestions.group_id, chatbot_suggestions.template_type, chatbot_suggestions_meta.suggestion_id, chatbot_suggestions_meta.field_type, chatbot_suggestions_meta.field_name, chatbot_suggestions_meta.field_value',
                                                    array(
                                                        'chatbot_suggestions.group_id' => $response[0]['group_id'],
                                                        'chatbot_suggestions_meta.field_type' => 'header',
                                                        'chatbot_suggestions.parent_id <' => 1
                                                    ),
                                                    array(),
                                                    array(),
                                                    array(array(
                                                        'table' => 'chatbot_suggestions',
                                                        'condition' => 'chatbot_suggestions_meta.suggestion_id=chatbot_suggestions.suggestion_id',
                                                        'join_from' => 'LEFT'
                                                    ))

                                                );

                                                // Verify if suggestions exists
                                                if ($group_suggestions && $group_header) {

                                                    // Set header's image
                                                    $image = '';

                                                    // Set header's title
                                                    $title = '';

                                                    // Set header's subtitle
                                                    $subtitle = '';

                                                    // Set header's link
                                                    $link = '';

                                                    // List the header fields
                                                    foreach ($group_header as $header) {

                                                        // Switch fields
                                                        switch ($header['field_name']) {

                                                            case 'title':

                                                                // Set new title's value
                                                                $title = $header['field_value'];

                                                                break;

                                                            case 'subtitle':

                                                                // Set new subtitle's value
                                                                $subtitle = $header['field_value'];

                                                                break;

                                                            case 'url':

                                                                // Set new link's value
                                                                $link = $header['field_value'];

                                                                break;

                                                            case 'cover_src':

                                                                // Set new image's value
                                                                $image = $header['field_value'];

                                                                break;
                                                        }
                                                    }

                                                    // Default field's name
                                                    $field_name = '';

                                                    // Suggestion's items
                                                    $suggestion_items = array();

                                                    // Count items
                                                    $count_items = 0;

                                                    // List all suggestions
                                                    foreach ($group_suggestions as $group_suggestion) {

                                                        if ($field_name === $group_suggestion['field_name']) {
                                                            $count_items++;
                                                        } else if (!$field_name) {
                                                            $field_name = $group_suggestion['field_name'];
                                                        }

                                                        $suggestion_items[$count_items]['suggestion_id'] = $group_suggestion['suggestion_id'];
                                                        $suggestion_items[$count_items][$group_suggestion['field_name']] = $group_suggestion['field_value'];
                                                    }

                                                    // Verify if items exists
                                                    if ($suggestion_items) {

                                                        // Items array
                                                        $items = array();

                                                        // List all suggestion's items
                                                        foreach ($suggestion_items as $suggestion_item) {

                                                            // Verify the option's type
                                                            switch ($suggestion_item['type']) {

                                                                case 'link':

                                                                    // Set item
                                                                    $items[] = array(
                                                                        'type' => 'web_url',
                                                                        'title' => $suggestion_item['title'],
                                                                        'url' => $suggestion_item['link']
                                                                    );

                                                                    break;

                                                                case 'suggestions-group':

                                                                    // Set item
                                                                    $items[] = array(
                                                                        'type' => 'postback',
                                                                        'title' => $suggestion_item['title'],
                                                                        'payload' => 'suggestion-' . $suggestion_item['suggestion_id']
                                                                    );

                                                                    break;
                                                            }
                                                        }

                                                        // Get subscriber
                                                        $subscriber = $this->CI->base_model->get_data_where(
                                                            'chatbot_subscribers',
                                                            'subscriber_id',
                                                            array(
                                                                'net_id' => $messages['data'][0]['from']['id'],
                                                                'network_name' => 'facebook_pages'
                                                            )
                                                        );

                                                        // History ID var
                                                        $history_id = 0;

                                                        // Verify if subscriber exists
                                                        if ($subscriber) {

                                                            // Verify if categories exists
                                                            if ($reply_categories) {

                                                                // List all categories
                                                                foreach ($reply_categories as $category) {

                                                                    // Verify if the user has already the category
                                                                    $subscriber_has = $this->CI->base_model->get_data_where(
                                                                        'chatbot_subscribers_categories',
                                                                        '*',
                                                                        array(
                                                                            'subscriber_id' => $subscriber[0]['subscriber_id'],
                                                                            'category_id' => $category['category_id']
                                                                        )

                                                                    );

                                                                    // If missing, save category
                                                                    if (!$subscriber_has) {

                                                                        // Category's array
                                                                        $cat = array(
                                                                            'subscriber_id' => $subscriber[0]['subscriber_id'],
                                                                            'category_id' => $category['category_id']
                                                                        );

                                                                        // Save category's by using the Base's Model
                                                                        $this->CI->base_model->insert('chatbot_subscribers_categories', $cat);
                                                                    }
                                                                }
                                                            }

                                                            // Prepare the history
                                                            $history = array(
                                                                'user_id' => $get_page[0]['user_id'],
                                                                'page_id' => $get_page[0]['network_id'],
                                                                'reply_id' => $best_match['reply']['reply_id'],
                                                                'subscriber_id' => $subscriber[0]['subscriber_id'],
                                                                'question' => $this->CI->security->xss_clean($messages['data'][0]['message']),
                                                                'group_id' => $group_header[0]['group_id'],
                                                                'source' => 'facebook_conversations',
                                                                'type' => 2,
                                                                'created' => time()
                                                            );

                                                            // Save subscriber's history by using the Base's Model
                                                            $query = $this->CI->base_model->insert('chatbot_subscribers_history', $history);

                                                            // Verify if the history was saved
                                                            if ($query) {
                                                                $history_id = $query;
                                                            }
                                                        } else {

                                                            // Prepare the subscriber data
                                                            $subscriber = array(
                                                                'user_id' => $get_page[0]['user_id'],
                                                                'page_id' => $get_page[0]['network_id'],
                                                                'network_name' => 'facebook_pages',
                                                                'net_id' => $messages['data'][0]['from']['id'],
                                                                'name' => $messages['data'][0]['from']['name'],
                                                                'created' => time()
                                                            );

                                                            // Save subscriber's by using the Base's Model
                                                            $subscriber_id = $this->CI->base_model->insert('chatbot_subscribers', $subscriber);

                                                            // Verify if the subscriber was saved
                                                            if ($subscriber_id) {

                                                                // Verify if categories exists
                                                                if ($reply_categories) {

                                                                    // List all categories
                                                                    foreach ($reply_categories as $category) {

                                                                        // Category's array
                                                                        $cat = array(
                                                                            'subscriber_id' => $subscriber_id,
                                                                            'category_id' => $category['category_id']
                                                                        );

                                                                        // Save category's by using the Base's Model
                                                                        $this->CI->base_model->insert('chatbot_subscribers_categories', $cat);
                                                                    }
                                                                }

                                                                // Prepare the history
                                                                $history = array(
                                                                    'user_id' => $get_page[0]['user_id'],
                                                                    'page_id' => $get_page[0]['network_id'],
                                                                    'reply_id' => $best_match['reply']['reply_id'],
                                                                    'subscriber_id' => $subscriber_id,
                                                                    'question' => $this->CI->security->xss_clean($messages['data'][0]['message']),
                                                                    'group_id' => $group_header[0]['group_id'],
                                                                    'source' => 'facebook_conversations',
                                                                    'type' => 2,
                                                                    'created' => time()
                                                                );

                                                                // Save subscriber's history by using the Base's Model
                                                                $query = $this->CI->base_model->insert('chatbot_subscribers_history', $history);

                                                                // Verify if the history was saved
                                                                if ($query) {
                                                                    $history_id = $query;
                                                                }
                                                            }
                                                        }

                                                        // Default response array
                                                        $response = array();

                                                        // Use response by template's type
                                                        switch ($group_header[0]['template_type']) {

                                                            case 'media-template':

                                                                // Prepare the media's array
                                                                $media_array = array(
                                                                    'message' => array(
                                                                        'attachment' => array(
                                                                            'type' => 'image',
                                                                            'payload' => array(
                                                                                'is_reusable' => true,
                                                                                'url' => $image

                                                                            )

                                                                        )

                                                                    )

                                                                );


                                                                // Upload Media
                                                                $media_response = json_decode(post(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . 'me/message_attachments', $media_array, $get_page[0]['secret']), true);

                                                                // Verify if the media was uploaded
                                                                if (isset($media_response['attachment_id'])) {

                                                                    // Prepare the response
                                                                    $response = array(
                                                                        'recipient' => array(
                                                                            'id' => $request['entry'][0]['messaging'][0]['sender']['id']
                                                                        ),
                                                                        'messaging_type' => 'RESPONSE',
                                                                        'message' => array(
                                                                            'attachment' => array(
                                                                                'type' => 'template',
                                                                                'payload' => array(
                                                                                    'template_type' => 'media',
                                                                                    "elements" => array(
                                                                                        array(
                                                                                            'media_type' => 'image',
                                                                                            'attachment_id' => $media_response['attachment_id'],
                                                                                            'buttons' => $items
                                                                                        )
                                                                                    )

                                                                                )

                                                                            )

                                                                        )

                                                                    );
                                                                } else {

                                                                    // Verify if history's ID exists
                                                                    if ($history_id > 0) {

                                                                        // Save the error
                                                                        $this->CI->base_model->update_ceil('chatbot_subscribers_history', array(
                                                                            'history_id' => $history_id
                                                                        ), array(
                                                                            'error' => $this->CI->lang->line('chatbot_image_can_not_be_uploaded')
                                                                        ));
                                                                    }
                                                                }

                                                                break;

                                                            case 'button-template':

                                                                // Prepare the response
                                                                $response = array(
                                                                    'recipient' => array(
                                                                        'id' => $request['entry'][0]['messaging'][0]['sender']['id']
                                                                    ),
                                                                    'messaging_type' => 'RESPONSE',
                                                                    'message' => array(
                                                                        'attachment' => array(
                                                                            'type' => 'template',
                                                                            'payload' => array(
                                                                                'template_type' => 'button',
                                                                                'text' => $title,
                                                                                'buttons' => $items

                                                                            )

                                                                        )

                                                                    )

                                                                );

                                                                break;

                                                            default:

                                                                // Prepare the response
                                                                $response = array(
                                                                    'recipient' => array(
                                                                        'id' => $request['entry'][0]['messaging'][0]['sender']['id']
                                                                    ),
                                                                    'messaging_type' => 'RESPONSE',
                                                                    'message' => array(
                                                                        'attachment' => array(
                                                                            'type' => 'template',
                                                                            'payload' => array(
                                                                                'template_type' => 'generic',
                                                                                'elements' => array(
                                                                                    array(
                                                                                        'title' => $title,
                                                                                        'image_url' => $image,
                                                                                        'subtitle' => $subtitle,
                                                                                        'default_action' => array(
                                                                                            'type' => 'web_url',
                                                                                            'url' => $link,
                                                                                            'webview_height_ratio' => 'tall'
                                                                                        ),
                                                                                        'buttons' => $items

                                                                                    )

                                                                                )

                                                                            )

                                                                        )

                                                                    )

                                                                );


                                                                break;
                                                        }

                                                        // Prepare the response
                                                        $response = array(
                                                            'page' => $get_page,
                                                            'request' => $request,
                                                            'response' => $response,
                                                            'history_id' => $history_id
                                                        );

                                                        // Send the response
                                                        $this->send_response($response);
                                                        exit();

                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }

                        }

                        // Get Facebook Page Meta
                        $get_default_message = $this->CI->base_model->get_data_where(
                            'chatbot_pages_meta',
                            'meta_value',
                            array(
                                'user_id' => $get_page[0]['user_id'],
                                'page_id' => $get_page[0]['network_id'],
                                'meta_name' => 'default_message'
                            )
                        );

                        // Get Facebook Page Meta
                        $default_enabled = $this->CI->base_model->get_data_where(
                            'chatbot_pages_meta',
                            'meta_value',
                            array(
                                'user_id' => $get_page[0]['user_id'],
                                'page_id' => $get_page[0]['network_id'],
                                'meta_name' => 'default_message_enable'
                            )
                        );

                        // Verify if message exists and if is enabled
                        if ($default_enabled && $get_default_message) {

                            // Get subscriber
                            $subscriber = $this->CI->base_model->get_data_where(
                                'chatbot_subscribers',
                                'subscriber_id',
                                array(
                                    'net_id' => $messages['data'][0]['from']['id'],
                                    'network_name' => 'facebook_pages'

                                )

                            );

                            // History ID var
                            $history_id = 0;

                            // Verify if subscriber exists
                            if ($subscriber) {

                                // Prepare the history
                                $history = array(
                                    'user_id' => $get_page[0]['user_id'],
                                    'page_id' => $get_page[0]['network_id'],
                                    'subscriber_id' => $subscriber[0]['subscriber_id'],
                                    'question' => $this->CI->security->xss_clean($messages['data'][0]['message']),
                                    'response' => $get_default_message[0]['meta_value'],
                                    'source' => 'facebook_conversations',
                                    'type' => 1,
                                    'created' => time()
                                );

                                // Save subscriber's history by using the Base's Model
                                $query = $this->CI->base_model->insert('chatbot_subscribers_history', $history);

                                // Verify if the history was saved
                                if ($query) {
                                    $history_id = $query;
                                }

                            } else {

                                // Prepare the subscriber data
                                $subscriber = array(
                                    'user_id' => $get_page[0]['user_id'],
                                    'page_id' => $get_page[0]['network_id'],
                                    'network_name' => 'facebook_pages',
                                    'net_id' => $messages['data'][0]['from']['id'],
                                    'name' => $messages['data'][0]['from']['name'],
                                    'created' => time()
                                );

                                // Save subscriber's by using the Base's Model
                                $subscriber_id = $this->CI->base_model->insert('chatbot_subscribers', $subscriber);

                                // Verify if the subscriber was saved
                                if ($subscriber_id) {

                                    // Prepare the history
                                    $history = array(
                                        'user_id' => $get_page[0]['user_id'],
                                        'page_id' => $get_page[0]['network_id'],
                                        'subscriber_id' => $subscriber_id,
                                        'question' => $this->CI->security->xss_clean($messages['data'][0]['message']),
                                        'response' => $get_default_message[0]['meta_value'],
                                        'source' => 'facebook_conversations',
                                        'type' => 1,
                                        'created' => time()
                                    );

                                    // Save subscriber's history by using the Base's Model
                                    $query = $this->CI->base_model->insert('chatbot_subscribers_history', $history);

                                    // Verify if the history was saved
                                    if ($query) {
                                        $history_id = $query;

                                    }

                                }

                            }

                            // Set response
                            $response = json_decode(post(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $thread_id . '/messages', array('message' => $get_default_message[0]['meta_value']), $get_page[0]['secret']), true);

                            // Verify if response wasn't sent successfully
                            if (isset($response['error'])) {

                                // Verify if history's id is positive
                                if ($history_id) {

                                    // Save the error
                                    $this->CI->base_model->update_ceil('chatbot_subscribers_history', array(
                                        'history_id' => $history_id
                                    ), array(
                                        'error' => json_encode($response)
                                    ));
                                }
                                
                            } else {

                                // Register a new reply
                                $this->set_bot_message_number($get_page[0]['user_id']);

                                // Set history's id
                                $request['history_id'] = $history_id;

                                // Set user's id
                                $request['user_id'] = $get_page[0]['user_id'];

                                // Verify for phone numbers
                                $this->set_phone_numbers( $request );

                                // Verify for email addresses
                                $this->set_email_addresses( $request );

                            }

                        }

                    }

                }

            }

        }
        
    }

    /**
     * The pretected method clear removes special characters
     * 
     * @param string $string contains the string to clear
     * 
     * @since 0.0.8.0
     * 
     * @return string with clean string
     */
    protected function clear($string) {

        // Replaces all spaces
        $string = str_replace(' ', '-', $string);

        // Removes special characters
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);

    }

    /**
     * The pretected method send_response sends bot's response
     * 
     * @param array $response contains the response
     * 
     * @since 0.0.8.0
     * 
     * @return string with clean string
     */
    protected function send_response($response) {

        // Send response
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . 'me/messages?access_token=' . $response['page'][0]['secret']);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($response['response']));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=UTF-8"));
        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);

        // Verify if response wasn't sent successfully
        if ( isset($result['error']) ) {

            // Verify if history's id is positive
            if ( $response['history_id'] ) {

                // Save the error
                $this->CI->base_model->update_ceil('chatbot_subscribers_history', array(
                    'history_id' => $response['history_id']
                ), array(
                    'error' => json_encode($result)
                ));

            }

        } else {

            // Register a new reply
            $this->set_bot_message_number( $response['page'][0]['user_id'] );

            // Set history's id
            $response['request']['history_id'] = $response['history_id'];

            // Set user's id
            $response['request']['user_id'] = $response['page'][0]['user_id'];

            // Verify for phone numbers
            $this->set_phone_numbers( $response['request'] );

            // Verify for email addresses
            $this->set_email_addresses( $response['request'] );

        }

    }

    /**
     * The protected method set_bot_message_number adds a new message count
     *
     * @param integer $user_id contains user_id
     * 
     * @return boolean true or false
     */ 
    protected function set_bot_message_number( $user_id ) {
        
        // Get number of bot's messages
        $sent_messages = get_user_option('facebook_chatbot_bot_messages', $user_id);
        
        // Verify if bot has replied before
        if ( $sent_messages ) {
            
            // Unserialize array
            $messages_array = unserialize($sent_messages);
            
            // Verify if the bot has replies in this month
            if ( $messages_array['date'] === date('Y-m') ) {
                
                // Get number of replies
                $messages = $messages_array['messages'];
                
                // Increase the number
                $messages++;
                
                // Set new record
                $record = serialize(
                    array(
                        'date' => date('Y-m'),
                        'messages' => $messages
                    )
                );
                
            } else {
                
                // Set new record
                $record = serialize(
                    array(
                        'date' => date('Y-m'),
                        'messages' => 1
                    )
                );  
                
            }
            
        } else {
            
            // Set new record
            $record = serialize(
                array(
                    'date' => date('Y-m'),
                    'messages' => 1
                )
            );
            
        }
        
        update_user_option($user_id, 'facebook_chatbot_bot_messages', $record);
        
    }

    /**
     * The protected method set_phone_numbers saves the phone numbers
     *
     * @param array $request contains the request array
     * 
     * @return void
     */ 
    protected function set_phone_numbers( $request ) {

        // Verify if message exists
        if ( isset($request['entry'][0]['messaging'][0]['message']['text']) ) {

            // Prepare the message
            $message = htmlspecialchars($request['entry'][0]['messaging'][0]['message']['text']);

            // Found array
            $found = array();

            // Save all phone numbers in $found
            preg_match_all('/[0-9]{3}[\-][0-9]{6}|[0-9]{3}[\s][0-9]{6}|[0-9]{3}[\s][0-9]{3}[\s][0-9]{4}|[0-9]{9}|[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}/', $message, $found);

            // Verify if phone numbers exists
            if ( !empty($found[0]) ) {

                // List all phone numbers
                foreach ( $found[0] as $phone ) {

                    // Verify if number is greater than 5 characters
                    if ( strlen($phone) > 5 ) {

                        // Prepare the phone
                        $phone_data = array(
                            'user_id' => $request['user_id'],
                            'history_id' => $request['history_id'],
                            'body' => $phone,
                            'new' => 1,
                            'source' => 'facebook_conversations',
                            'created' => time()
                        );

                        // Save the phone number by using the Base's Model
                        $this->CI->base_model->insert('chatbot_phone_numbers', $phone_data);

                    }

                }

            }

        }
        
    }

    /**
     * The protected method set_email_addresses saves the email's addresses
     *
     * @param array $request contains the request array
     * 
     * @return void
     */ 
    protected function set_email_addresses( $request ) {

        // Verify if message exists
        if ( isset($request['entry'][0]['messaging'][0]['message']['text']) ) {
            
            // Prepare the message
            $message = htmlspecialchars($request['entry'][0]['messaging'][0]['message']['text']);

            // Found array
            $found = array();

            // Save all email addresses in $found
            preg_match_all('/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i', $message, $found);

            // Verify if email addresses exists
            if ( !empty($found[0]) ) {
                
                // List all email addresses
                foreach ( $found[0] as $email ) {

                    // Prepare the email
                    $email_data = array(
                        'user_id' => $request['user_id'],
                        'history_id' => $request['history_id'],
                        'body' => $email,
                        'new' => 1,
                        'source' => 'facebook_conversations',
                        'created' => time()
                    );

                    // Save the email addresses by using the Base's Model
                    $this->CI->base_model->insert('chatbot_email_addresses', $email_data);

                }

            }

        }
        
    }

}

/* End of file bot.php */