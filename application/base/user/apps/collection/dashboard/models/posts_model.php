<?php
/**
 * Posts Model
 *
 * PHP Version 5.6
 *
 * Posts file contains the Posts Model
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
if ( !defined('BASEPATH') ) {
    
    exit('No direct script access allowed');
    
}

/**
 * Posts class - operates the posts table.
 *
 * @since 0.0.7.0
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Posts_model extends CI_MODEL {
    
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
     * The public method save_post saves post before send on social networks
     *
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user_id
     * @param string $post contains the post content
     * @param string $url contains the post's url
     * @param string $img contains the post's image url
     * @param integer $time contains the time when will be published the post
     * @param integer $publish contains a number. If 0 the post will be saved as draft.
     * @param string $categories contains the category where will be published the post
     * 
     * @return integer with inserted id or false
     */
    public function save_post( $user_id, $post, $url, $img, $video = NULL, $time, $publish, $categories = NULL, $post_title = NULL ) {
        
        // Get current ip
        $ip = $this->input->ip_address();
        
        // Decode URL-encoded strings
        $post = rawurldecode($post);
        
        // Set data
        $data = [
            'user_id' => $user_id,
            'body' => $post,
            'title' => $post_title,
            'url' => $url,
            'img' => $img,
            'category' => $categories,
            'sent_time' => $time,
            'ip_address' => $ip,
            'status' => $publish,
            'view' => '1'
            ];
        
        // Verify if video exists
        if ( $video ) {
            
            $data['video'] = $video;
            
        }
        
        // Insert post
        $this->db->insert($this->table, $data);
        
        // Verify if post was saved
        if ( $this->db->affected_rows() ) {
            
            // Return last inserted id
            return $this->db->insert_id();
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method save_post_meta saves post meta
     *
     * @since 0.0.7.0
     * 
     * @param integer $post_id contains the post_id
     * @param integer $account contains the account where will be published the post
     * @param string $name contains the network's name
     * @param integer $status may be a number 0, 1 or 2
     * @param integer $user_id contains the user_id
     * 
     * @return void
     */
    public function save_post_meta( $post_id, $account, $name, $status=0, $user_id=0 ) {
        
        // Get current time
        $time = time();
        
        // Set data
        $data = ['post_id' => $post_id, 'network_id' => $account, 'network_name' => $name, 'sent_time' => $time, 'status' => $status];
        
        // Verify if post failed
        if ( $status > 1 ) {
            
            // Get the last error
            // Load User model
            $this->load->model( 'User', 'user' );
            $error_code = $this->user->get_user_option( $user_id, 'last-social-error' );
            
            if ( $error_code ) {
                
                $data['network_status'] = $error_code;
                $this->user->delete_user_option( $user_id, 'last-social-error' );
                
            }
        }
        
        $this->db->insert('posts_meta', $data);
    }

    /**
     * The public method update_post_meta updates post meta after publishing
     *
     * @param $meta_id contains the meta_id
     * @param $status contains the published status
     * @param integer $user_id contains the user_id
     * 
     * @return boolean true or false
     */
    public function update_post_meta( $meta_id, $status, $user_id ) {
        
        // Get current time
        $time = time();
        
        // Set data
        $data = ['sent_time' => $time, 'status' => $status];
        
        // Verify if post failed
        if ( $status > 1 ) {
            
            // Get the last error
            // Load User model
            $this->load->model( 'User', 'user' );
            $error_code = $this->user->get_user_option( $user_id, 'last-social-error' );
            
            if ( $error_code ) {
                
                $data['network_status'] = $error_code;
                $this->user->delete_user_option( $user_id, 'last-social-error' );
                
            }
        }
        
        $this->db->where('meta_id', $meta_id);
        $this->db->update('posts_meta', $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_post gets post content
     *
     * @param integer $user_id contains the user's id
     * @param integer $post_id contains the post's id
     * 
     * @return array with post's data or false if post doesn't exists
     */
    public function get_post( $user_id, $post_id ) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(['user_id' => $user_id, 'post_id' => $post_id]);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            $sent = "";
            
            // Update view with 0
            $this->db->where('post_id', $post_id);
            $this->db->update($this->table, ['view' => 0]);
            
            // Get history of published post on social networks
            if ( $this->sentnetworkmess($post_id) ) {
                
                $sent = $this->sentnetworkmess($post_id);
                
            }
            
            return array(
                'post_id' => $result[0]->post_id,
                'user_id' => $result[0]->user_id,
                'body' => $result[0]->body,
                'title' => $result[0]->title,
                'category' => $result[0]->category,
                'url' => $result[0]->url,
                'img' => $result[0]->img,
                'video' => $result[0]->video,
                'status' => $result[0]->status,
                'sent' => $sent,
                'time' => $result[0]->sent_time,
                'current' => time(),
                'parent' => $result[0]->parent
            );
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_posts gets all posts from database
     *
     * @param integer $user_id contains the user_id
     * @param integer $start contains the start of displays posts
     * @param integer $limit displays the limit of displayed posts
     * @param string $key contains the search key
     * 
     * @return object with posts or false
     */
    public function get_posts( $user_id, $start, $limit, $key = NULL ) {
        
        $this->db->select('posts.post_id,posts.body,posts.title,posts.url,posts.img,posts.video,posts.sent_time,posts.status,posts_meta.network_name');
        $this->db->from($this->table);
        $this->db->join('posts_meta', 'posts.post_id=posts_meta.post_id', 'left');
        $this->db->where('posts.user_id', $user_id);
        
        // If $key exists means will displayed posts by search
        if ( $key ) {
            
            // This method allows to escape special characters for LIKE conditions
            $key = $this->db->escape_like_str($key);
            
            // Gets posts which contains the $key
            $this->db->like('posts.body', $key);
            
        }
        
        $this->db->order_by('posts.sent_time', 'desc');
        
        // Verify if $limit is not null
        if ( $limit ) {
            $this->db->limit($limit, $start);
        }
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            
            // Verify if $limit is not null
            if ( $limit ) {
            
                // Get results
                $results = $query->result();

                // Create a new array
                $array = [];

                foreach ( $results as $result ) {

                    // Each result will be a new object
                    $array[] = (object) array(
                        'post_id' => $result->post_id,
                        'body' => $result->body,
                        'title' => $result->title,
                        'url' => $result->url,
                        'video' => $result->video,
                        'img' => $result->img,
                        'sent_time' => $result->sent_time,
                        'status' => $result->status,
                        'history' => $this->sentnetworkmess($result->post_id),
                        'network_name' => $result->network_name,
                    );

                }

                return $array;
            
            } else {
                return $query->num_rows();
            }
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_scheduled_posts gets scheduled posts based on time ASC
     *
     * @param integer $user_id contains the user_id
     * @param integer $limit displays the limit of displayed posts
     * 
     * @return object with posts or false
     */
    public function get_scheduled_posts( $user_id, $limit ) {
        
        $this->db->select('FROM_UNIXTIME(posts.sent_time) as datetime', false);
        
        $this->db->select('post_id,body,title,url,img,video,sent_time,status');
        
        $this->db->from($this->table);
        
        $this->db->where([
            'user_id' => $user_id,
            'sent_time >=' => time()
        ]);
        
        $this->db->limit($limit);
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            // Get results
            $results = $query->result();
            
            return $results;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_published_posts gets published and scheduled posts
     *
     * @param integer $user_id contains the user_id
     * @param integer $start contains the start of displays posts
     * @param integer $end displays the end time of displayed posts
     * 
     * @return object with posts or false
     */
    public function get_published_posts( $user_id, $start, $end ) {
        
        $this->db->select('FROM_UNIXTIME(posts.sent_time) as datetime', false);
        $this->db->select('post_id,body,title,url,img,video,sent_time,status');
        $this->db->from($this->table);
        $this->db->where([
            'user_id' => $user_id,
            'sent_time >=' => ($start - 259200),
            'sent_time <=' => $end,
            'status >' => 0
        ]);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            // Get results
            $results = $query->result();
            
            return $results;
            
        } else {
            
            return '';
            
        }
        
    }
    
    /**
     * The public method all_social_networks_by_post_id gets all social network where the post($post_id) must be published
     *
     * @param integer $user_id contains the user_id
     * @param integer $post_id contains the post_id
     * @param integer $group contains the group's option
     * 
     * @return array with networks 
     */
    public function all_social_networks_by_post_id( $user_id, $post_id, $group = 0 ) {
        
        $this->db->select('posts_meta.meta_id,posts_meta.network_id,posts_meta.post_id,posts_meta.status,networks.network_name,networks.user_name');
        $this->db->from('posts_meta');
        $this->db->join('networks', 'posts_meta.network_id=networks.network_id', 'left');
        $this->db->where(['networks.user_id' => $user_id, 'posts_meta.post_id' => $post_id]);
        
        if ( $group ) {
            $this->db->group_by('networks.network_name');
        }
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $networks = $query->result_array();
            return $networks;
            
        }
        
    }
    
    /**
     * The public method delete_post deletes a post
     *
     * @param integer $user_id contains the user's id
     * @param integer $post_id contains the post_id
     * 
     * @return boolean if the post was deleted successfully or false
     */
    public function delete_post($user_id, $msgId) {
        
        // First we check if the post exists
        $this->db->select('post_id');
        $this->db->from($this->table);
        $this->db->where(['user_id' => $user_id, 'post_id' => $msgId]);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            // Load resend model
            $this->load->model('Resend', 'resend');
            $resend_id = $this->resend->get_post_resend_id($user_id,$msgId);
            
            // Then will be deleted the post's meta
            $this->db->delete('posts_meta', ['post_id' => $msgId]);
            
            // Then will be deleted the post
            $this->db->delete($this->table, ['post_id' => $msgId]);
            
            if ( $this->db->affected_rows() ) {
                
                if ( is_numeric($resend_id) ) {
                    
                    // Then will be deleted the resend's row
                    $this->db->delete('resend', ['resend_id' => $resend_id]);
                    $this->db->delete('resend_meta', ['resend_id' => $resend_id]);
                    $this->db->delete('resend_rules', ['resend_id' => $resend_id]);
                    
                }
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method sentnetworkmess gets information about the published post on the social network
     *
     * @param integer $fromId contains the post_id
     * 
     * @return array with published posts
     */
    private function sentnetworkmess( $fromId ) {
        
        $this->db->select('posts_meta.meta_id,posts_meta.network_name,posts_meta.status,networks.user_name,posts_meta.network_status');
        $this->db->from('posts_meta');
        $this->db->join('networks', 'posts_meta.network_id=networks.network_id', 'left');
        $this->db->where(['posts_meta.post_id' => $fromId]);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result_array();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
 
}

/* End of file Posts_model.php */