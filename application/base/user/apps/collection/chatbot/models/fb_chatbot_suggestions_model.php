<?php
/**
 * FB Chatbot Suggestions Model
 *
 * PHP Version 7.3
 *
 * Fb_chatbot_suggestions_model file contains the FB Chatbot Suggestions Model
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
 * Fb_chatbot_suggestions_model class - operates the chatbot_suggestions table.
 *
 * @since 0.0.7.6
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Fb_chatbot_suggestions_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'chatbot_suggestions';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();

        // Get the table
        $chatbot_groups = $this->db->table_exists('chatbot_groups');

        // Verify if the table exists
        if ( !$chatbot_groups ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_groups` (
                              `group_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` int(11) NOT NULL,
                              `group_name` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `created` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }

        // Get the table
        $chatbot_suggestions = $this->db->table_exists('chatbot_suggestions');

        // Verify if the table exists
        if ( !$chatbot_suggestions ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_suggestions` (
                              `suggestion_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `group_id` bigint(20) NOT NULL,
                              `user_id` int(11) NOT NULL,
                              `template_type` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `created` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `parent_id` bigint(20) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }

        // Get the table
        $chatbot_suggestions_meta = $this->db->table_exists('chatbot_suggestions_meta');

        // Verify if the table exists
        if ( !$chatbot_suggestions_meta ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_suggestions_meta` (
                              `meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `suggestion_id` bigint(20) NOT NULL,
                              `field_type` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `field_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `field_value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }

        // Get the table
        $chatbot_suggestions_categories = $this->db->table_exists('chatbot_suggestions_categories');

        // Verify if the table exists
        if ( !$chatbot_suggestions_categories ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_suggestions_categories` (
                              `id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `group_id` bigint(20) NOT NULL,
                              `category_id` bigint(20) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method get_suggestions gets suggestions based on group's id
     *
     * @param integer $group_id contains the group's id
     * @param integer $user_id contains the user's id
     * 
     * @return array with suggestions
     */
    public function get_suggestions( $group_id, $user_id ) {
        
        $this->db->select('*');
        $this->db->from('chatbot_suggestions_meta');
        $this->db->join('chatbot_suggestions', 'chatbot_suggestions_meta.suggestion_id=chatbot_suggestions.suggestion_id', 'LEFT');
        $this->db->join('chatbot_groups', 'chatbot_suggestions.group_id=chatbot_groups.group_id', 'LEFT');
        $this->db->where(array(
            'chatbot_suggestions.group_id' => $group_id,
            'chatbot_suggestions.user_id' => $user_id
        ));
        $query = $this->db->get();
        
        // Verify if data exists
        if ( $query->num_rows() > 0 ) {
            
            // Return data
            return $query->result_array();
            
        } else {
            
            return false;
            
        }
        
    }
    
}

/* End of file Fb_chatbot_suggestions_model.php */