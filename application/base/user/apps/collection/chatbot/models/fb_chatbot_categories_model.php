<?php
/**
 * FB Chatbot Categories Model
 *
 * PHP Version 7.3
 *
 * Fb_chatbot_categories_model file contains the FB Chatbot Categories Model
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
 * Fb_chatbot_categories_model class - operates the chatbot_categories table.
 *
 * @since 0.0.7.6
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Fb_chatbot_categories_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'chatbot_categories';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();

        // Get the table
        $chatbot_categories = $this->db->table_exists('chatbot_categories');

        // Verify if the table exists
        if ( !$chatbot_categories ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_categories` (
                              `category_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` int(11) NOT NULL,
                              `name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `created` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method save_category creates a category
     *
     * @param integer $user_id contains the user's id
     * @param string $name contains the list's name
     * 
     * @return integer with last inserted id or false
     */
    public function save_category( $user_id, $name ) {
        
        // Get current time
        $created = time();
        
        // Set data
        $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'created' => $created
        );
        
        // Insert data
        $this->db->insert($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            // Return last inserted ID
            return $this->db->insert_id();
            
        } else {
            
            return false;
            
        }
        
    }
    
}

/* End of file fb_chatbot_categories_model.php */