<?php
/**
 * FB Chatbot Replies Model
 *
 * PHP Version 7.3
 *
 * Fb_chatbot_replies_model file contains the FB Chatbot Replies Model
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
 * Fb_chatbot_replies_model class - operates the chatbot_replies table.
 *
 * @since 0.0.7.6
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Fb_chatbot_replies_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'chatbot_replies';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();

        // Get the table
        $chatbot_replies = $this->db->table_exists('chatbot_replies');

        // Verify if the table exists
        if ( !$chatbot_replies ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_replies` (
                              `reply_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` int(11) NOT NULL,
                              `body` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `accuracy` int(3) NOT NULL,
                              `active` tinyint(1) NOT NULL,
                              `created` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }

        // Get the table
        $chatbot_replies_response = $this->db->table_exists('chatbot_replies_response');

        // Verify if the table exists
        if ( !$chatbot_replies_response ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_replies_response` (
                              `response_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `reply_id` bigint(20) NOT NULL,
                              `body` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `group_id` bigint(20) NOT NULL,
                              `type` tinyint(1) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }

        // Get the table
        $chatbot_replies_categories = $this->db->table_exists('chatbot_replies_categories');

        // Verify if the table exists
        if ( !$chatbot_replies_categories ) {

            // Create the table
            $this->db->query('CREATE TABLE IF NOT EXISTS `chatbot_replies_categories` (
                              `id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `reply_id` bigint(20) NOT NULL,
                              `category_id` bigint(20) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }

    /**
     * The public method get_replies_by_categories gets replies based on categories
     *
     * @param array $categories contains the categories list
     * 
     * @return array with replies or boolean false
     */
    public function get_replies_by_categories( $categories ) {

        $this->db->select('chatbot_replies.reply_id, chatbot_replies.body as keywords, chatbot_replies.accuracy');
        $this->db->from('chatbot_replies_categories');
        $this->db->join('chatbot_pages_categories', 'chatbot_replies_categories.category_id=chatbot_pages_categories.category_id', 'LEFT');
        $this->db->join('chatbot_replies', 'chatbot_replies_categories.reply_id=chatbot_replies.reply_id', 'LEFT');
        $this->db->where_in('chatbot_pages_categories.category_id', $categories);
        $this->db->group_by('chatbot_replies.reply_id');      
        $query = $this->db->get();
        
        // Verify if data exists
        if ( $query->num_rows() > 0 ) {
            
            // Return data
            return $query->result_array();
            
        } else {
            
            return false;
            
        }

    }

    /**
     * The public method replies_for_graph returns replies for graph
     * 
     * @param integer $user_id contains the user's ID
     * @param integer $page_id contains the page's ID
     * 
     * @return array with replies or boolean false
     */
    public function replies_for_graph($user_id, $page_id=NULL) {

        $this->db->select('LEFT(FROM_UNIXTIME(created),10) as datetime', false);
        $this->db->select('COUNT(history_id) as number', false);
        $this->db->from('chatbot_subscribers_history');

        // Verify if page's ID exists
        if ( $page_id ) {

            $this->db->where(
                array(
                    'user_id' => $user_id,
                    'page_id'=> $page_id,
                    'source' => 'facebook_conversations',
                    'created >' => strtotime('-31day', time())
                )
            );

        } else {

            $this->db->where(
                array(
                    'user_id' => $user_id,
                    'source' => 'facebook_conversations',
                    'created >' => strtotime('-31day', time())
                )
            );

        }
        
        $this->db->group_by('datetime');
        $query = $this->db->get();
        
        // Verify if data exists
        if ( $query->num_rows() > 0 ) {
            
            // Return data
            return $query->result_array();
            
        } else {
            
            return false;
            
        }

    }

    /**
     * The public method replies_for_dashboard_graph returns replies for the Dashboard's graph
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @return array with replies or boolean false
     */
    public function replies_for_dashboard_graph($user_id) {

        $this->db->select('LEFT(FROM_UNIXTIME(created),10) as datetime', false);
        $this->db->select('COUNT(history_id) as number', false);
        $this->db->from('chatbot_subscribers_history');

        $this->db->where(
            array(
                'user_id' => $user_id,
                'created >' => strtotime('-31day', time())
            )
        );
        
        $this->db->group_by('datetime');
        $query = $this->db->get();
        
        // Verify if data exists
        if ( $query->num_rows() > 0 ) {
            
            // Return data
            return $query->result_array();
            
        } else {
            
            return false;
            
        }

    }

    /**
     * The public method total_replies_for_dashboard_graph returns total replies for the Dashboard's graph
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @return array with replies or boolean false
     */
    public function total_replies_for_dashboard_graph($user_id) {

        $this->db->select('chatbot_subscribers_history.source, COUNT(history_id) as number', false);
        $this->db->from('chatbot_subscribers_history');

        $this->db->where(
            array(
                'user_id' => $user_id,
                'created >' => strtotime('-31day', time())
            )
        );
        
        $this->db->group_by('chatbot_subscribers_history.source');
        $query = $this->db->get();
        
        // Verify if data exists
        if ( $query->num_rows() > 0 ) {
            
            // Return data
            return $query->result_array();
            
        } else {
            
            return false;
            
        }

    }

    /**
     * The public method reply_activity_graph returns the reply's activity
     * 
     * @param integer $user_id contains the user's ID
     * @param integer $reply_id contains the reply's ID
     * 
     * @return array with activity or boolean false
     */
    public function reply_activity_graph($user_id, $reply_id) {

        $this->db->select('LEFT(FROM_UNIXTIME(created),10) as datetime', false);
        $this->db->select('COUNT(history_id) as number', false);
        $this->db->from('chatbot_subscribers_history');
        $this->db->where(
            array(
                'user_id' => $user_id,
                'reply_id'=> $reply_id,
                'source' => 'facebook_conversations',
                'created >' => strtotime('-31day', time())
            )
        );
        $this->db->group_by('datetime');
        $query = $this->db->get();
        
        // Verify if data exists
        if ( $query->num_rows() > 0 ) {
            
            // Return data
            return $query->result_array();
            
        } else {
            
            return false;
            
        }

    }

    /**
     * The public method replies_by_popularity gets replies by popularity
     * 
     * @param integer $page_id contains the page's ID
     * @param integer $page contains the page's number
     * 
     * @return array with replies or boolean false
     */
    public function replies_by_popularity($user_id, $page_id, $page=0) {

        $this->db->select('question, type, COUNT(history_id) as number', false);
        $this->db->from('chatbot_subscribers_history');

        // Verify if page's ID exists
        if ( $page_id ) {

            $this->db->where(
                array(
                    'user_id' => $user_id,
                    'page_id'=> $page_id,
                    'source' => 'facebook_conversations'
                )
            );

        } else {

            $this->db->where(
                array(
                    'user_id' => $user_id,
                    'source' => 'facebook_conversations'
                )
            );
            
        }

        $this->db->group_by('question');
        $this->db->order_by('number', 'DESC');

        // Verify if $page is positive otherwise return all results
        if ( $page ) {

            // Set number of items and page
            $this->db->limit(10, ($page * 10) );

        }
        
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

/* End of file fb_chatbot_replies_model.php */