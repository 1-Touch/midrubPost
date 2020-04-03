<?php
/**
 * FB Chatbot Phone Numbers Model
 *
 * PHP Version 7.3
 *
 * Fb_chatbot_phone_numbers_model file contains the FB Chatbot Phone Numbers Model
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
 * Fb_chatbot_phone_numbers_model class - operates the chatbot_phone_numbers table.
 *
 * @since 0.0.8.1
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Fb_chatbot_phone_numbers_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'chatbot_phone_numbers';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();

        // Get the table
        $chatbot_phone_numbers = $this->db->table_exists('chatbot_phone_numbers');

        // Verify if the table exists
        if ( !$chatbot_phone_numbers ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_phone_numbers` (
                              `phone_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` int(11) NOT NULL,
                              `history_id` bigint(20) NOT NULL,
                              `body` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `new` tinyint(1) NOT NULL,
                              `source` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `created` varchar(30) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
                            
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
}

/* End of file fb_chatbot_phone_numbers_model.php */