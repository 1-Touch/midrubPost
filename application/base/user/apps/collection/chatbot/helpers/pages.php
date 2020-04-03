<?php
/**
 * Pages Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class Pages
 * with methods to process the pages data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Helpers;

// Constats
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Pages class provides the methods to process the pages data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class Pages {
    
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
        
        // Load the FB Chatbot Pages Meta
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_pages_meta_model', 'fb_chatbot_pages_meta_model' );
        
    }

    //-----------------------------------------------------
    // Main class's methods
    //-----------------------------------------------------
    
    /**
     * The public method load_all_connected_pages loads the pages
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function load_all_connected_pages() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_no_pages_found')
                );

                // Display the false response
                echo json_encode($data);
                exit();
                
            } else {

                // Use the base model for a simply sql query
                $get_pages = $this->CI->base_model->get_data_where(
                    'networks',
                    'network_id, user_name',
                    array(
                        'network_name' => 'facebook_pages',
                        'user_id' => $this->CI->user_id
                    ),
                    array(),
                    array('user_name' => $this->CI->db->escape_like_str($key))
                );

                // Verify if pages exists
                if ( $get_pages ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'pages' => $get_pages
                    );                    

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_pages_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * The public method load_connected_pages loads pages by page
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function load_connected_pages() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_no_pages_found')
                );

                // Display the false response
                echo json_encode($data);
                exit();
                
            } else {

                // If $page is false, set 1
                if (!$page) {
                    $page = 1;
                }

                // Set the limit
                $limit = 10;
                $page--;

                // Use the base model for a simply sql query
                $get_pages = $this->CI->base_model->get_data_where(
                    'networks',
                    'network_id, net_id, user_name, secret',
                    array(
                        'network_name' => 'facebook_pages',
                        'user_id' => $this->CI->user_id
                    ),
                    array(),
                    array('user_name' => $this->CI->db->escape_like_str($key)),
                    array(),
                    array(
                        'order' => array('network_id', 'desc'),
                        'start' => ($page * $limit),
                        'limit' => $limit
                    )
                );

                // Verify if pages exists
                if ( $get_pages ) {

                    // Use the base model for a simply sql query
                    $total = $this->CI->base_model->get_data_where(
                        'networks',
                        'COUNT(network_id) AS total',
                        array(
                            'network_name' => 'facebook_pages',
                            'user_id' => $this->CI->user_id
                        ),
                        array(),
                        array('user_name' => $this->CI->db->escape_like_str($key))
                    );

                    // Pages variable
                    $pages = array();

                    // List all pages
                    foreach ( $get_pages as $pag ) {

                        $pages[] = array(
                            'network_id' => $pag['network_id'],
                            'user_name' => $pag['user_name'],
                            'user_picture' => MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $pag['net_id'] . '/picture?type=square&access_token=' . $pag['secret'],
                            'access_token' => $pag['secret']
                        );

                    } 

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'pages' => $pages,
                        'total' => $total[0]['total'],
                        'page' => ($page + 1),
                        'words' => array(
                            'connect' => $this->CI->lang->line('chatbot_connect')
                        )
                    );                    

                    // Display the response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_no_pages_found')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            }
            
        }
        
    }

    /**
     * The public method connect_facebook_page connects a Facebook Page
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function connect_facebook_page() {

        // Get page's ID
        $page_id = $this->CI->input->get('page_id', TRUE);

        // If page's ID is numeric
        if ( is_numeric($page_id) ) {

            // Use the base model to verify if user is the owner of the page
            $get_page = $this->CI->base_model->get_data_where(
                'networks',
                '*',
                array(
                    'network_id' => $page_id,
                    'network_name' => 'facebook_pages',
                    'user_id' => $this->CI->user_id
                )
            );

            if ( $get_page ) {

                // Prepare params
                $params = array(
                    'access_token' => $get_page[0]['secret']
                );

                // Get request
                $page = json_decode(get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_page[0]['net_id']  . '?' . urldecode(http_build_query($params)) ), true);

                // Verify if page token is still valid
                if ( isset($page['id']) ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'page_id' => $page_id,
                        'subscribed' => false
                    );

                    // Get Facebook Page Meta
                    $get_page_metas = $this->CI->base_model->get_data_where(
                        'chatbot_pages_meta',
                        '*',
                        array(
                            'page_id' => $page_id
                        )
                    );

                    // Verify if the page has meta
                    if ( $get_page_metas ) {

                        // List all metas
                        foreach ( $get_page_metas as $meta ) {

                            // Verify if meta has selected menu
                            if ( $meta['meta_name'] === 'selected_menu' ) {

                                // Get group's name
                                $group = $this->CI->base_model->get_data_where(
                                    'chatbot_groups',
                                    '*',
                                    array(
                                        'group_id' => $meta['meta_value']
                                    )
                                ); 
                                
                                // Verify if group exists
                                if ( $group ) {

                                    // Set the group's name
                                    $data['group_name'] = $group[0]['group_name'];

                                }

                            }

                        }

                    }

                    // Get connected bots
                    $bots = json_decode(get(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_page[0]['net_id']  . '/subscribed_apps?' . urldecode(http_build_query($params)) ), true);

                    // Verify if bots exists
                    if ( $bots["data"] ) {

                        // List all bots
                        foreach ( $bots["data"] as $bot ) {

                            if ( $bot['id'] === get_option('facebook_pages_app_id') ) {
                                $data['subscribed'] = TRUE;
                                break;
                            }

                        }

                    }

                    // Verify if the page has meta
                    if ( $get_page_metas ) {

                        // Set meta
                        $data['meta'] = $get_page_metas;

                    }

                    // Use the base model to get all Facebook Page's categories
                    $categories = $this->CI->base_model->get_data_where(
                        'chatbot_pages_categories',
                        '*',
                        array(
                            'page_id' => $page_id
                        )
                    );

                    // If categories exists add them to array
                    if ( $categories ) {

                        // Set categories
                        $data['categories'] = $categories;

                    }

                    // Display the success response
                    echo json_encode($data);                    

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_invalid_access_token')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            } else {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_you_are_not_owner')
                );

                // Display the false response
                echo json_encode($data);

            }

        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('chatbot_page_not_found')
            );

            // Display the false response
            echo json_encode($data);

        }
        
    }

    /**
     * Saves Facebook Page configuration
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function save_page_configuration() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('page_id', 'Page ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('default_message', 'Default Message', 'trim');
            $this->CI->form_validation->set_rules('default_message_enabled', 'Default Message Enabled', 'trim');
            $this->CI->form_validation->set_rules('greeting_message_enabled', 'Greeting Message Enabled', 'trim');
            $this->CI->form_validation->set_rules('greeting_message', 'Greeting Message', 'trim');
            $this->CI->form_validation->set_rules('menu_enabled', 'Menu Enabled', 'trim');
            $this->CI->form_validation->set_rules('selected_menu', 'Selected Menu', 'trim');
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');        

            // Get data
            $page_id = $this->CI->input->post('page_id', TRUE);
            $default_message = $this->CI->input->post('default_message', TRUE);
            $default_message_enabled = $this->CI->input->post('default_message_enabled', TRUE);
            $greeting_message_enabled = $this->CI->input->post('greeting_message_enabled', TRUE);
            $greeting_message = $this->CI->input->post('greeting_message', TRUE);
            $menu_enabled = $this->CI->input->post('menu_enabled', TRUE);
            $selected_menu = $this->CI->input->post('selected_menu', TRUE);
            $categories = $this->CI->input->post('categories', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_page_not_found')
                );

                // Display the false response
                echo json_encode($data);
                
            } else {

                // Use the base model to verify if user is the owner of the page
                $get_page = $this->CI->base_model->get_data_where(
                    'networks',
                    '*',
                    array(
                        'network_id' => $page_id,
                        'network_name' => 'facebook_pages',
                        'user_id' => $this->CI->user_id
                    )
                );

                if ( $get_page ) {

                    // Delete Facebook Pages meta
                    $this->CI->base_model->delete('chatbot_pages_meta', array('page_id' => $page_id));

                    // Delete Facebook Pages categories
                    $this->CI->base_model->delete('chatbot_pages_categories', array('page_id' => $page_id));

                    // Set count
                    $count = 0;

                    // Set uncount
                    $uncount = 0;

                    // Verify if the default response exists
                    if ( $default_message ) {

                        // Save Facebook Meta
                        $default_msg = array(
                            'user_id' => $this->CI->user_id,
                            'page_id' => $page_id,
                            'meta_name' => 'default_message',
                            'meta_value' => $default_message
                        );

                        // Save default message by using the basic model
                        if ( $this->CI->base_model->insert('chatbot_pages_meta', $default_msg) ) {
                            $count++;

                            // Verify if default message should be enabled
                            if ( $default_message_enabled ) {

                                // Save Facebook Meta
                                $default_msg = array(
                                    'user_id' => $this->CI->user_id,
                                    'page_id' => $page_id,
                                    'meta_name' => 'default_message_enable',
                                    'meta_value' => '1'
                                );

                                // Save default message by using the basic model
                                if ( $this->CI->base_model->insert('chatbot_pages_meta', $default_msg) ) {
                                    $count++;
                                } else {
                                    $uncount++;
                                }

                            }
                            
                        } else {
                            $uncount++;
                        }

                    }

                    // Verify if the greeting message exists
                    if ( $greeting_message ) {

                        // Save Facebook Meta
                        $default_msg = array(
                            'user_id' => $this->CI->user_id,
                            'page_id' => $page_id,
                            'meta_name' => 'greeting_message',
                            'meta_value' => $greeting_message
                        );

                        // Save greeting message by using the basic model
                        if ( $this->CI->base_model->insert('chatbot_pages_meta', $default_msg) ) {
                            $count++;

                            // Verify if greeting message should be enabled
                            if ( $greeting_message_enabled ) {

                                // Save Facebook Meta
                                $default_msg = array(
                                    'user_id' => $this->CI->user_id,
                                    'page_id' => $page_id,
                                    'meta_name' => 'greeting_message_enable',
                                    'meta_value' => '1'
                                );

                                // Save greeting message by using the basic model
                                if ( $this->CI->base_model->insert('chatbot_pages_meta', $default_msg) ) {

                                    // Set greeting message
                                    $greeting = array('greeting' => array(
                                        array(
                                            'locale' => 'default',
                                            'text' => $greeting_message
                                        )
                                    ));

                                    // Set default message
                                    json_decode(post(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . 'me/messenger_profile', $greeting, $get_page[0]['secret'] ), true);

                                    $count++;

                                } else {
                                    $uncount++;
                                }

                            } else {

                                // Prepare params
                                $params = array(
                                    'fields' => array('greeting'),
                                    'access_token' => $get_page[0]['secret']
                                );

                                // Prepare the request
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . 'me/messenger_profile?' . urldecode(http_build_query($params)));
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_exec($curl);
                                curl_close($curl);
                                
                            }
                            
                        } else {
                            $uncount++;
                        }

                    }

                    // Verify if the menu exists
                    if ( $selected_menu ) {

                        // We need to verify if the suggestions group is of the current user
                        $get_suggestions = $this->CI->base_model->get_data_where(
                            'chatbot_suggestions',
                            'suggestion_id',
                            array(
                                'group_id' => $selected_menu,
                                'user_id' => $this->CI->user_id
                            )
                        );

                        // Verify if user has the suggestion group
                        if ( $get_suggestions ) {

                            // Save Facebook Meta
                            $default_msg = array(
                                'user_id' => $this->CI->user_id,
                                'page_id' => $page_id,
                                'meta_name' => 'selected_menu',
                                'meta_value' => $selected_menu
                            );

                            // Save menu by using the basic model
                            if ( $this->CI->base_model->insert('chatbot_pages_meta', $default_msg) ) {
                                $count++;

                                // Verify if menu should be enabled
                                if ( $menu_enabled ) {

                                    // If menu is enabled, create it on Facebook
                                    $group_suggestions = $this->CI->base_model->get_data_where(
                                        'chatbot_suggestions_meta',
                                        'chatbot_suggestions_meta.suggestion_id, chatbot_suggestions_meta.field_type, chatbot_suggestions_meta.field_name, chatbot_suggestions_meta.field_value',
                                        array(
                                            'chatbot_suggestions.group_id' => $selected_menu,
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
                                    
                                    // Verify if suggestions exists
                                    if ( $group_suggestions ) {

                                        // Default field's name
                                        $field_name = '';

                                        // Menu items
                                        $menu_items = array();

                                        // Count items
                                        $count_items = 0;

                                        // List all suggestions
                                        foreach ( $group_suggestions as $group_suggestion ) {

                                            if ( $field_name === $group_suggestion['field_name'] ) {
                                                $count_items++;
                                            } else if ( !$field_name ) {
                                                $field_name = $group_suggestion['field_name'];
                                            }

                                            $menu_items[$count_items]['suggestion_id'] = $group_suggestion['suggestion_id'];
                                            $menu_items[$count_items][$group_suggestion['field_name']] = $group_suggestion['field_value'];

                                        }

                                        // Verify if menu's exists
                                        if ( $menu_items ) {

                                            // Items array
                                            $items = array();

                                            // List all menu items
                                            foreach ($menu_items as $menu_item) {

                                                // Verify the option's type
                                                switch ( $menu_item['type'] ) {

                                                    case 'link':

                                                        // Set item
                                                        $items[] = array(
                                                            'type' => 'web_url',
                                                            'title' => $menu_item['title'],
                                                            'url' => $menu_item['link'],
                                                            'webview_height_ratio' => 'full',
                                                        );

                                                        break;

                                                    case 'suggestions-group':

                                                        // Set item
                                                        $items[] = array(
                                                            'type' => 'postback',
                                                            'title' => $menu_item['title'],
                                                            'payload' => 'suggestion-' . $menu_item['suggestion_id']
                                                        );

                                                        break;

                                                }

                                            }

                                            // Menu data
                                            $menu_data = array(

                                                'get_started' => array(
                                                    'payload' => 'GET_STARTED_PAYLOAD'
                                                ),
                                                'persistent_menu' => array(
                                                    array(
                                                        'locale' => 'default',
                                                        'composer_input_disabled' => 'false',
                                                        'call_to_actions' => $items

                                                    )

                                                )

                                            );

                                            // Set default message
                                            $menu_response = json_decode(post(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . 'me/messenger_profile', $menu_data, $get_page[0]['secret']), true);

                                            // Verify if the menu was saved
                                            if ( !empty($menu_response['result']) ) {

                                                // Save Facebook Meta
                                                $default_msg = array(
                                                    'user_id' => $this->CI->user_id,
                                                    'page_id' => $page_id,
                                                    'meta_name' => 'menu_enable',
                                                    'meta_value' => '1'
                                                );

                                                // Save menu by using the basic model
                                                if ( $this->CI->base_model->insert('chatbot_pages_meta', $default_msg) ) {
                                                    $count++;
                                                } else {
                                                    $uncount++;
                                                }

                                            }

                                        }

                                    }

                                } else {

                                    // Prepare params
                                    $params = array(
                                        'fields' => array('persistent_menu'),
                                        'access_token' => $get_page[0]['secret']
                                    );

                                    // Prepare the request to disable the persistent menu
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . 'me/messenger_profile?' . urldecode(http_build_query($params)));
                                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_exec($curl);
                                    curl_close($curl);

                                }

                            } else {

                                $uncount++;

                            }

                        } else {

                            // Prepare params
                            $params = array(
                                'fields' => array('persistent_menu'),
                                'access_token' => $get_page[0]['secret']
                            );

                            // Prepare the request to disable the persistent menu
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . 'me/messenger_profile?' . urldecode(http_build_query($params)));
                            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_exec($curl);
                            curl_close($curl);
                            
                        }

                    }

                    // Verify if categories exists
                    if ( $categories ) {

                        // List all categories
                        foreach ( $categories as $category_id ) {

                            // Use the base model to verify if user is the owner of the category
                            $get_category = $this->CI->base_model->get_data_where(
                                'chatbot_categories',
                                '*',
                                array(
                                    'category_id' => $category_id,
                                    'user_id' => $this->CI->user_id
                                )
                            );

                            // Verify if the category and user exists
                            if ($get_category) {


                                // Prepare the Category
                                $category = array(
                                    'page_id' => $page_id,
                                    'category_id' => $category_id
                                );

                                // Save the Category
                                if ( $this->CI->base_model->insert('chatbot_pages_categories', $category) ) {
                                    $count++;
                                } else {
                                    $uncount++;
                                }

                            }

                        }

                    }

                    if ( ( $count > 0 ) && ( $uncount > 0 ) ) {

                        // Prepare the success response
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('chatbot_changes_saved_successfully_but_error_occurred')
                        );

                        // Display the success response
                        echo json_encode($data);

                    } else if ( ( $count > 0 ) && ( $uncount === 0 ) ) {

                        // Prepare the success response
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('chatbot_changes_saved_successfully')
                        );

                        // Display the success response
                        echo json_encode($data);

                    } else if ( ( $count < 1 ) && ( $uncount > 1 ) ) {

                        // Prepare the false response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('chatbot_changes_not_saved_successfully')
                        );

                        // Display the false response
                        echo json_encode($data);

                    } else {

                        // Prepare the success response
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('chatbot_changes_saved_successfully')
                        );

                        // Display the success response
                        echo json_encode($data);
                        
                    }

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_you_are_not_owner')
                    );

                    // Display the false response
                    echo json_encode($data);

                } 

            }

        }
        
    }

    /**
     * The public method account_manager_delete_accounts deletes a Facebook Page
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function account_manager_delete_accounts() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('page_id', 'Page ID', 'trim|numeric|required');     

            // Get data
            $page_id = $this->CI->input->post('page_id', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_page_not_found')
                );

                // Display the false response
                echo json_encode($data);
                
            } else {

                // Use the base model to verify if user is the owner of the page
                $get_page = $this->CI->base_model->get_data_where(
                    'networks',
                    '*',
                    array(
                        'network_id' => $page_id,
                        'network_name' => 'facebook_pages',
                        'user_id' => $this->CI->user_id
                    )
                );

                // Verify if the page exists
                if ( $get_page ) { 

                    // Try to delete the page
                    if ( $this->CI->base_model->delete('networks', array('network_id' => $page_id) ) ) {

                        // Delete all facebook page's records
                        run_hook(
                            'delete_network_account',
                            array(
                                'account_id' => $page_id
                            )

                        );

                        // Prepare the success response
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('chatbot_facebook_page_was_deleted')
                        );

                        // Display the success response
                        echo json_encode($data);

                    } else {

                        // Prepare the error response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('chatbot_facebook_page_was_not_deleted')
                        );

                        // Display the error response
                        echo json_encode($data);

                    }

                    exit();

                }

            }

        }

        // Prepare the error response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('chatbot_error_occurred')
        );

        // Display the error response
        echo json_encode($data);

    }

    /**
     * The public method connect_to_bot connects a Facebook Page to bot
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function connect_to_bot() {

        // Get page's ID
        $page_id = $this->CI->input->get('page_id', TRUE);

        // If page's ID is numeric
        if ( is_numeric($page_id) ) {

            // Use the base model to verify if user is the owner of the page
            $get_page = $this->CI->base_model->get_data_where(
                'networks',
                '*',
                array(
                    'network_id' => $page_id,
                    'network_name' => 'facebook_pages',
                    'user_id' => $this->CI->user_id
                )
            );

            if ( $get_page ) {

                // Try to subscribe
                $subscribe = json_decode(post(MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_page[0]['net_id']  . '/subscribed_apps', array('subscribed_fields' => 'feed, conversations, messages, messaging_postbacks, messaging_optins, message_deliveries, message_reads, message_reactions'), $get_page[0]['secret']), true);

                // Verify if page was subscribed from the bot list
                if ( !empty($subscribe['success']) ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('chatbot_page_was_subscribed')
                    );

                    // Display the success response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_page_was_not_subscribed')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            } else {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_you_are_not_owner')
                );

                // Display the false response
                echo json_encode($data);

            }

        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('chatbot_page_not_found')
            );

            // Display the false response
            echo json_encode($data);

        }
        
    }

    /**
     * The public method disconnect_from_bot disconnects a Facebook Page from bot
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function disconnect_from_bot() {

        // Get page's ID
        $page_id = $this->CI->input->get('page_id', TRUE);

        // If page's ID is numeric
        if ( is_numeric($page_id) ) {

            // Use the base model to verify if user is the owner of the page
            $get_page = $this->CI->base_model->get_data_where(
                'networks',
                '*',
                array(
                    'network_id' => $page_id,
                    'network_name' => 'facebook_pages',
                    'user_id' => $this->CI->user_id
                )
            );

            if ( $get_page ) {

                // Prepare params
                $params = array(
                    'access_token' => $get_page[0]['secret']
                );

                // Prepare the request
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, MIDRUB_CHATBOT_FACEBOOK_GRAPH_URL . $get_page[0]['net_id']  . '/subscribed_apps?' . urldecode(http_build_query($params)));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($curl);
                $unsubscribe = json_decode($result, true);
                curl_close($curl);

                // Verify if page was removed from the bot list
                if ( !empty($unsubscribe['success']) ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('chatbot_page_was_unsubscribed')
                    );

                    // Display the success response
                    echo json_encode($data);

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_page_was_not_unsubscribed')
                    );

                    // Display the false response
                    echo json_encode($data);

                }
                
            } else {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_you_are_not_owner')
                );

                // Display the false response
                echo json_encode($data);

            }

        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('chatbot_page_not_found')
            );

            // Display the false response
            echo json_encode($data);

        }
        
    }

    /**
     * Selects Facebook Page ID
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function select_facebook_page_category() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('page_id', 'Page ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('category_id', 'Category ID', 'trim|numeric|required');

            // Get data
            $page_id = $this->CI->input->post('page_id', TRUE);
            $category_id = $this->CI->input->post('category_id', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() === false ) {

                // Prepare the false response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('chatbot_page_or_category_wrong')
                );

                // Display the false response
                echo json_encode($data);
                
            } else {

                // Use the base model to verify if user is the owner of the page
                $get_page = $this->CI->base_model->get_data_where(
                    'networks',
                    '*',
                    array(
                        'network_id' => $page_id,
                        'network_name' => 'facebook_pages',
                        'user_id' => $this->CI->user_id
                    )
                );

                // Verify if user is owner of the Facebook Page
                if ( $get_page ) {

                    // Use the base model to verify if user is the owner of the category
                    $get_category = $this->CI->base_model->get_data_where(
                        'chatbot_categories',
                        '*',
                        array(
                            'category_id' => $category_id,
                            'user_id' => $this->CI->user_id
                        )
                    );

                    // Verify if the category and user exists
                    if ( $get_category ) {

                        // Prepare the success response
                        $data = array(
                            'success' => TRUE,
                            'category_id' => $category_id
                        );

                        // Display the success response
                        echo json_encode($data);

                    } else {

                        // Prepare the false response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('chatbot_you_are_not_owner_category')
                        );

                        // Display the false response
                        echo json_encode($data);
                        
                    }

                } else {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('chatbot_you_are_not_owner')
                    );

                    // Display the false response
                    echo json_encode($data);

                } 

            }

        }
        
    }
    
}

/* End of file pages.php */