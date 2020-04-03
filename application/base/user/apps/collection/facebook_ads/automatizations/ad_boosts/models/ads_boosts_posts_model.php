<?php
/**
 * Posts Model
 *
 * PHP Version 7.2
 *
 * Ads_boosts_posts_model file contains the Posts Model
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
 * Ads_boosts_posts_model class - operates the posts table.
 *
 * @since 0.0.7.0
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Ads_boosts_posts_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'posts';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method get_posts gets all posts from database
     * 
     * @return object with posts or false
     */
    public function get_posts() {
        
        $this->db->select('posts.post_id,posts.user_id,posts.body,posts.title,posts.url,posts.img,posts.video,posts.sent_time,posts.status,posts.fb_boost_id,posts_meta.network_name');
        $this->db->from($this->table);
        $this->db->join('posts_meta', 'posts.post_id=posts_meta.post_id', 'left');
        $this->db->where(
            array(
                'posts.sent_time >' => (time() - 86400),
                'posts.status' => '1',
                'fb_boost_id >' => 0
            )
        );
        $this->db->group_by(['posts.post_id']);
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {

            // Get results
            $results = $query->result();

            // Create a new array
            $array = [];

            foreach ($results as $result) {

                // Each result will be a new object
                $array[] = (object)array(
                    'user_id' => $result->user_id,
                    'post_id' => $result->post_id,
                    'body' => htmlentities($result->body),
                    'title' => $result->title,
                    'url' => $result->url,
                    'video' => $result->video,
                    'img' => $result->img,
                    'sent_time' => $result->sent_time,
                    'status' => $result->status,
                    'fb_boost_id' => $result->fb_boost_id,
                    'network_name' => $result->network_name,
                );
            }

            return $array;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method all_social_networks_by_post_id gets all social network where the post($post_id) must be published
     *
     * @param integer $post_id contains the post_id
     * 
     * @return array with networks 
     */
    public function all_social_networks_by_post_id( $post_id ) {
        
        $this->db->select('posts_meta.meta_id,posts_meta.network_id,posts_meta.post_id,posts_meta.status,posts_meta.network_status,posts_meta.published_id,networks.net_id,networks.network_name,networks.user_name');
        $this->db->from('posts_meta');
        $this->db->join('networks', 'posts_meta.network_id=networks.network_id', 'left');
        $this->db->where(
            array(
                'posts_meta.post_id' => $post_id
            )
        );
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $networks = $query->result_array();
            return $networks;
            
        } else {

            return false;

        }
        
    }
 
}

/* End of file ads_boosts_posts_model.php */