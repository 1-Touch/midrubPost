<?php
/**
 * FB Chatbot Pages Meta Model
 *
 * PHP Version 7.3
 *
 * Fb_chatbot_pages_meta_model file contains the FB Chatbot Pages Meta Model
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
 * Fb_chatbot_pages_meta_model class - operates the chatbot_pages_meta table.
 *
 * @since 0.0.8.0
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Fb_chatbot_pages_meta_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'chatbot_pages_meta';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();

        // Get the table
        $chatbot_pages_meta = $this->db->table_exists('chatbot_pages_meta');

        // Verify if the table exists
        if ( !$chatbot_pages_meta ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_pages_meta` (
                              `meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` int(11) NOT NULL,
                              `page_id` bigint(20) NOT NULL,
                              `meta_name` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `meta_value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }

        // Get the table
        $chatbot_pages_categories = $this->db->table_exists('chatbot_pages_categories');

        // Verify if the table exists
        if ( !$chatbot_pages_categories ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_pages_categories` (
                              `id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `page_id` bigint(20) NOT NULL,
                              `category_id` bigint(20) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
}

/* End of file Fb_chatbot_pages_meta_model.php */