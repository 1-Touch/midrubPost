<?php
/**
 * FB Chatbot Subscribers Model
 *
 * PHP Version 7.3
 *
 * Fb_chatbot_subscribers_model file contains the FB Chatbot Subscribers Model
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fb_chatbot_subscribers_model class - operates the chatbot_subscribers table.
 *
 * @since 0.0.8.0
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Fb_chatbot_subscribers_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'chatbot_subscribers';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();

        // Get the table
        $chatbot_subscribers = $this->db->table_exists('chatbot_subscribers');

        // Verify if the table exists
        if ( !$chatbot_subscribers ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_subscribers` (
                              `subscriber_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` int(11) NOT NULL,
                              `page_id` bigint(20) NOT NULL,
                              `network_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `net_id` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `location` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `created` varchar(30) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }

        // Get the table
        $chatbot_subscribers_categories = $this->db->table_exists('chatbot_subscribers_categories');

        // Verify if the table exists
        if ( !$chatbot_subscribers_categories ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_subscribers_categories` (
                              `id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `subscriber_id` bigint(20) NOT NULL,
                              `category_id` bigint(20) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }

        // Get the table
        $chatbot_subscribers_history = $this->db->table_exists('chatbot_subscribers_history');

        // Verify if the table exists
        if ( !$chatbot_subscribers_history ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_subscribers_history` (
                              `history_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` int(11) NOT NULL,
                              `page_id` bigint(20) NOT NULL,
                              `reply_id` bigint(20) NOT NULL,
                              `subscriber_id` bigint(20) NOT NULL,
                              `question` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `response` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `error` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `group_id` bigint(20) NOT NULL,
                              `type` tinyint(1) NOT NULL,
                              `source` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `created` varchar(30) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
}

/* End of file fb_chatbot_subscribers_model.php */