<?php

/**
 * Ajax Controller
 *
 * This file processes the app's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Chatbot\Helpers as MidrubBaseUserAppsCollectionChatbotHelpers;

/*
 * Ajax class processes the app's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

class Ajax
{

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
        $this->CI = &get_instance();

        // Load language
        $this->CI->lang->load( 'chatbot_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_CHATBOT );

    }

    /**
     * The public method save_suggestions saves the suggestions group
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function save_suggestions() {

        // Saves a suggestions group
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Suggestions)->save_suggestions();

    }

    /**
     * The public method load_suggestions loads the group with suggestions
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_suggestions() {

        // Load suggestions
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Suggestions)->load_suggestions();

    }

    /**
     * The public method suggestions_groups gets suggestions groups
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function suggestions_groups() {

        // Gets Groups
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Groups)->suggestions_groups();

    }

    /**
     * The public method delete_group deletes suggestions groups
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function delete_group() {

        // Deletes Groups
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Groups)->delete_group();

    }    

    /**
     * The public method create_category creates a category
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function create_category() {

        // Creates a category
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Categories)->create_category();

    }

    /**
     * The public method get_categories_by_page gets categories by page
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function get_categories_by_page() {

        // Gets categories
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Categories)->get_categories_by_page();

    }

    /**
     * The public method get_all_categories gets all categories
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function get_all_categories() {

        // Gets all categories
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Categories)->get_all_categories();

    }    

    /**
     * The public method delete_category deletes a category
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function delete_category() {

        // Deletes a category
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Categories)->delete_category();

    }

    /**
     * The public method save_reply saves reply
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function save_reply() {

        // Save reply
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->save_reply();

    }

    /**
     * The public method update_reply updates a reply
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function update_reply() {

        // Update reply
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->update_reply();

    }

    /**
     * The public method load_replies loads replies
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_replies() {

        // Loads replies
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->load_replies();

    }

    /**
     * The public method check_for_replies verifies if user has replies
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function check_for_replies() {

        // Verifies if user has replies
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->check_for_replies();

    }

    /**
     * The public method delete_replies deletes replies
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function delete_replies() {

        // Delete replies
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->delete_replies();

    }

    /**
     * The public method replies_by_popularity loads replies by popularity
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function replies_by_popularity() {

        // Load replies by popularity
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->replies_by_popularity();

    }

    /**
     * The public method replies_for_graph returns replies for graph
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function replies_for_graph() {

        // Load replies for graph
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->replies_for_graph();

    }

    /**
     * The public method dashboard_replies_for_graph returns replies for graph
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function dashboard_replies_for_graph() {

        // Load replies for graph
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->dashboard_replies_for_graph();

    }

    /**
     * The public method dashboard_total_replies_for_graph returns all replies for graph
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function dashboard_total_replies_for_graph() {

        // Load all replies for graph
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->dashboard_total_replies_for_graph();

    }

    /**
     * The public method reply_activity_graph returns the reply's activity for the graph
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function reply_activity_graph() {

        // Load activity for reply
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Replies)->reply_activity_graph();

    }

    /**
     * The public method load_all_connected_pages search for connected pages(could return all)
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_all_connected_pages() {

        // Search for pages
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Pages)->load_all_connected_pages();

    }

    /**
     * The public method load_connected_pages search for connected pages and return by page
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_connected_pages() {

        // Search for pages
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Pages)->load_connected_pages();

    }

    /**
     * The public method connect_facebook_page tries to connect a Facebook Page to be configured
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function connect_facebook_page() {

        // Connect a Facebook Page
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Pages)->connect_facebook_page();

    }

    /**
     * The public method account_manager_delete_accounts deletes a Facebook Page
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function account_manager_delete_accounts() {

        // Deletes Facebook Page
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Pages)->account_manager_delete_accounts();

    }

    /**
     * The public method save_page_configuration saves a Facebook Page configuration
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function save_page_configuration() {

        // Save Facebook Page configuration
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Pages)->save_page_configuration();

    }

    /**
     * The public method connect_to_bot connects Facebook Page to bot
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function connect_to_bot() {

        // Connects Facebook Page to bot
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Pages)->connect_to_bot();

    }

    /**
     * The public method disconnect_from_bot disconnects Facebook Page from bot
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function disconnect_from_bot() {

        // Disconnects Facebook Page from bot
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Pages)->disconnect_from_bot();

    }

    /**
     * The public method select_facebook_page_category select a Facebook Page category
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function select_facebook_page_category() {

        // Selects Facebook Page category
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Pages)->select_facebook_page_category();

    }

    /**
     * The public method upload_csv uploads a CSV file
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function upload_csv() {

        // Uploads a CSV file
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Csv)->upload_csv();

    }

    /**
     * The public method export_csv exports replies in a CSV file
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function export_csv() {

        // Download replies
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Csv)->export_csv();

    }

    /**
     * The public method load_subscribers gets subscribers
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_subscribers() {

        // Load subscribers
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Subscribers)->load_subscribers();

    }

    /**
     * The public method load_reply_subscribers gets subscribers for a reply
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_reply_subscribers() {

        // Load subscribers for a reply
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Subscribers)->load_reply_subscribers();

    }    

    /**
     * The public method get_all_subscriber_categories gets subscriber's categories
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function get_all_subscriber_categories() {

        // Load categories
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Subscribers)->get_all_subscriber_categories();

    }

    /**
     * The public method select_subscriber_category selects subscriber's categories
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function select_subscriber_category() {

        // Select or unselect a category
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Subscribers)->select_subscriber_category();

    }

    /**
     * The public method get_all_subscriber_messages gets the subscriber's messages
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function get_all_subscriber_messages() {

        // Gets messages
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Subscribers)->get_all_subscriber_messages();

    }

    /**
     * The public method save_subscriber_categories adds or removes categories for to subscriber
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function save_subscriber_categories() {

        // Removes or adds categories
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Subscribers)->save_subscriber_categories();

    }

    /**
     * The public method load_history loads the chatbot's history
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */
    public function load_history() {

        // Loads conversations
        (new MidrubBaseUserAppsCollectionChatbotHelpers\History)->load_history();

    }

    /**
     * The public method load_phone_numbers loads phone numbers
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    public function load_phone_numbers() {

        // Loads phone numbers
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Phone_numbers)->load_phone_numbers();

    }

    /**
     * The public method delete_phone_numbers deletes phone numbers
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    public function delete_phone_numbers() {

        // Deletes phone numbers
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Phone_numbers)->delete_phone_numbers();

    }

    /**
     * The public method check_for_phone_numbers verifies if user has phone numbers
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    public function check_for_phone_numbers() {

        // Verifies if user has phone numbers
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Phone_numbers)->check_for_phone_numbers();

    }

    /**
     * The public method export_phone_csv downloads phone numbers
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    public function export_phone_csv() {

        // Download phone numbers
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Csv)->export_phone_csv();

    }

    /**
     * The public method load_email_addresses loads email addresses
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    public function load_email_addresses() {

        // Loads email addresses
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Email_addresses)->load_email_addresses();

    }

    /**
     * The public method delete_email_addresses deletes email addresses
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    public function delete_email_addresses() {

        // Deletes email addresses
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Email_addresses)->delete_email_addresses();

    }

    /**
     * The public method check_for_email_addresses verifies if user has email addresses
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    public function check_for_email_addresses() {

        // Verifies if user has email addresses
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Email_addresses)->check_for_email_addresses();

    }

    /**
     * The public method export_email_csv downloads email addresses
     * 
     * @since 0.0.8.1
     * 
     * @return void
     */
    public function export_email_csv() {

        // Download email addresses
        (new MidrubBaseUserAppsCollectionChatbotHelpers\Csv)->export_email_csv();

    }

}

/* End of file ajax.php */