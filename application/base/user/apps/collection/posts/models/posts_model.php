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
 * @license  https://elements.envato.com/license-terms
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
 * @license  https://elements.envato.com/license-terms
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
     * @param string $post_title contains the post's title
     * @param string $fb_boost_id contains the Ad boost's ID
     * 
     * @return integer with inserted id or false
     */
    public function save_post( $user_id, $post, $url, $img, $video = NULL, $time, $publish, $categories = NULL, $post_title = NULL, $fb_boost_id = 0 ) {
        
        // Get current ip
        $ip = $this->input->ip_address();
        
        // Decode URL-encoded strings
        $post = rawurldecode($post);
        
        // Set data
        $data = array(
            'user_id' => $user_id,
            'body' => $post,
            'title' => $post_title,
            'url' => $url,
            'img' => $img,
            'category' => $categories,
            'sent_time' => $time,
            'ip_address' => $ip,
            'status' => $publish,
            'view' => '1',
            'fb_boost_id' => $fb_boost_id
        );
        
        // Verify if video exists
        if ( $video ) {
            
            $data['video'] = $video;
            
        }
        
        // Insert post
        $this->db->insert($this->table, $data);
        
        // Verify if post was saved
        if ( $this->db->affected_rows() ) {
            
            $last_id = $this->db->insert_id();
            
            // Load Activities model
            $this->load->model( 'Activities', 'activities' );
            
            $member_id = 0;
            
            if ( $this->session->userdata( 'member' ) ) {
                
                // Load Team model
                $this->load->model( 'Team', 'team' );
                
                // Get member team info
                $member_info = $this->team->get_member( $user_id, 0, $this->session->userdata( 'member' ) );
                
                if ( $member_info ) {
                    
                    $member_id = $member_info[0]->member_id;
                    
                }
                
            }
            
            $this->activities->save_activity( 'posts', 'posts', $last_id, $user_id, $member_id );
            
            // Return last inserted id
            return $last_id;
            
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
     * @param integer $published_id contains the published id
     * 
     * @return void
     */
    public function save_post_meta( $post_id, $account, $name, $status=0, $user_id=0, $published_id=0 ) {
        
        // Get current time
        $time = time();
        
        // Set data
        $data = array(
            'post_id' => $post_id,
            'network_id' => $account,
            'network_name' => $name,
            'sent_time' => $time,
            'status' => $status,
            'published_id' => $published_id
        );
        
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
     * @param integer $meta_id contains the meta_id
     * @param integer $status contains the published status
     * @param integer $user_id contains the user_id
     * @param integer $published_id contains the published id
     * 
     * @return boolean true or false
     */
    public function update_post_meta( $meta_id, $status, $user_id, $published_id=0 ) {
        
        // Get current time
        $time = time();
        
        // Set data
        $data = array(
            'sent_time' => $time,
            'status' => $status,
            'published_id' => $published_id
        );
        
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
     * The public method save_post saves post before send on social networks
     *
     * @since 0.0.8.0
     * 
     * @param integer $post_id contains the post's ID
     * @param integer $user_id contains the user_id
     * @param string $post contains the post content
     * @param string $url contains the post's url
     * @param string $img contains the post's image url
     * @param string $post_title contains the post's title
     * @param string $time contains the publish time
     * 
     * @return integer with inserted id or false
     */
    public function update_post( $post_id, $user_id, $post, $url, $img, $video = NULL, $post_title = NULL, $time = 0 ) {
        
        // Decode URL-encoded strings
        $post = rawurldecode($post);
        
        // Set data
        $data = array(
            'body' => $post,
            'title' => $post_title,
            'url' => $url,
            'img' => $img
        );
        
        // Verify if video exists
        if ( $video ) {
            $data['video'] = $video;
        }

        // Verify if time is not 0
        if ( $time > 0 ) {
            $data['sent_time'] = $time;
        }

        $this->db->where(

            array(
                'post_id' => $post_id,
                'user_id' => $user_id,
            )

        );
        $this->db->update($this->table, $data);
        
        // Verify if post was saved
        if ( $this->db->affected_rows() ) {

            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method update_post_meta deletes the $published_id
     *
     * @param $meta_id contains the meta_id
     * 
     * @return boolean true or false
     */
    public function empty_published_id( $meta_id ) {
        
        // Set data
        $data = array(
            'published_id' => 0
        );
        
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
            
            // Get history of published post on social networks
            if ( $this->sentnetworkmess($post_id) ) {
                
                $sent = $this->sentnetworkmess($post_id);
                
            }
            
            return array(
                'post_id' => $result[0]->post_id,
                'user_id' => $result[0]->user_id,
                'body' => htmlentities($result[0]->body),
                'title' => $result[0]->title,
                'category' => $result[0]->category,
                'url' => $result[0]->url,
                'img' => $result[0]->img,
                'video' => $result[0]->video,
                'status' => $result[0]->status,
                'sent' => $sent,
                'fb_boost_id' => $result[0]->fb_boost_id,
                'time' => $result[0]->sent_time,
                'current' => time(),
                'parent' => $result[0]->parent
            );
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method get_boost_single gets boost's option for boost a post
     *
     * @param integer $user_id contains the user's id
     * @param integer $boost_id contains the boost's id
     * 
     * @since 0.0.7.7
     * 
     * @return object with Boosts or false
     */    
    public function get_boost_single( $user_id, $boost_id ) {
        
        $this->db->select('ads_boosts.boost_id, ads_boosts.boost_name, networks.network_id, networks.net_id, networks.user_name');
        $this->db->from('ads_boosts');
        $this->db->join('ads_boosts_meta', 'ads_boosts.boost_id=ads_boosts_meta.boost_id', 'LEFT');
        $this->db->join('networks', 'ads_boosts_meta.meta_value=networks.net_id', 'LEFT');
        $this->db->where(array(
                'ads_boosts.boost_id' => $boost_id,
                'ads_boosts.user_id' => $user_id,
                'ads_boosts_meta.meta_name' => 'facebook_page_id',
                'networks.user_id' => $user_id
            )
        );
        
        $this->db->limit(1);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_post_meta gets post's meta by id
     *
     * @param integer $meta_id contains the meta's id
     * 
     * @return array with meta's data or false if posts_meta doesn't exists
     */
    public function get_post_meta( $meta_id ) {
        
        $this->db->select('*');
        $this->db->from('posts_meta');
        $this->db->where(['meta_id' => $meta_id]);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result_array();
            
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
        
        $this->db->select('posts.post_id,posts.user_id,posts.body,posts.title,posts.url,posts.img,posts.video,posts.sent_time,posts.status,posts_meta.network_name');
        $this->db->from($this->table);
        $this->db->join('posts_meta', 'posts.post_id=posts_meta.post_id', 'left');
        $this->db->where('posts.user_id', $user_id);
        $this->db->group_by(['posts.post_id']);
        
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
                        'user_id' => $result->user_id,
                        'post_id' => $result->post_id,
                        'body' => htmlentities($result->body),
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
     * The public method get_posts_by_meta gets all posts by meta from database
     *
     * @param integer $user_id contains the user_id
     * @param integer $start contains the start of displays posts
     * @param integer $limit displays the limit of displayed posts
     * @param string $key contains the search key
     * 
     * @return object with posts or false
     */
    public function get_posts_by_meta( $user_id, $start, $limit, $key = NULL ) {
        
        $this->db->select('posts.post_id,posts.body,posts.title,posts.url,posts.img,posts.video,posts.sent_time,posts.status,posts_meta.meta_id,posts_meta.network_name');
        $this->db->from('posts_meta');
        $this->db->join('posts', 'posts_meta.post_id=posts.post_id', 'left');
        $this->db->where(array(
            'posts.user_id' => $user_id,
            'LENGTH(posts_meta.published_id) >' => 1
        ));
        
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
                        'body' => htmlentities($result->body),
                        'title' => $result->title,
                        'url' => $result->url,
                        'video' => $result->video,
                        'img' => $result->img,
                        'sent_time' => $result->sent_time,
                        'status' => $result->status,
                        'history' => $this->sentnetworkmess($result->post_id),
                        'meta_id' => $result->meta_id,
                        'network_name' => $result->network_name
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
            'status <>' => 0
        ]);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            // Get results
            $results = $query->result();

            // Create a new array
            $array = [];

            foreach ( $results as $result ) {

                // Each result will be a new object
                $array[] = (object) array(
                    'post_id' => $result->post_id,
                    'body' => htmlentities($result->body),
                    'title' => $result->title,
                    'url' => $result->url,
                    'video' => $result->video,
                    'img' => $result->img,
                    'sent_time' => $result->sent_time,
                    'datetime' => $result->datetime,
                    'status' => $result->status
                );

            }

            return $array;
            
        } else {
            
            return '';
            
        }
        
    }
    
    /**
     * The public method get_last_posts gets last published posts limit by $time
     *
     * @param integer $user_id contains the user_id
     * @param integer $time contains the time period
     * 
     * @return array with posts or false
     */
    public function get_last_posts($time, $user_id) {
        
        $this->db->select('*, LEFT(FROM_UNIXTIME(posts.sent_time),10) as datetime', false);
        $this->db->select('COUNT(posts.post_id) as number', false);
        $this->db->from($this->table);
        $this->db->join('posts_meta', 'posts_meta.post_id=posts.post_id', 'left');
        $this->db->where(array('posts.user_id' => $user_id, 'posts_meta.sent_time >' => strtotime('-' . $time . 'day', time()), 'posts_meta.status' => '1'));
        $this->db->group_by(array('datetime'));
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            
            // Create new array
            $new_array = [];
            
            foreach ( $result as $data ) {
                
                $new_array[date('d/m', $data->sent_time)] = $data->number;
                
            }
            
            return $new_array;
            
        } else {
            
            return false;
            
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
        
        $this->db->select('posts_meta.meta_id,posts_meta.network_id,posts_meta.post_id,posts_meta.status,posts_meta.network_status,networks.net_id,networks.network_name,networks.user_name');
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
    public function delete_post($user_id, $post_id) {
        
        // First we check if the post exists
        $this->db->select('post_id');
        $this->db->from($this->table);
        $this->db->where(array(
            'user_id' => $user_id,
            'post_id' => $post_id
        ));
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {

            // Delete all post's records
            md_run_hook(
                'delete_social_post',
                array(
                    'post_id' => $post_id
                )
            );
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_all_scheduled_posts gets all scheduled posts that must be published before the time()
     *
     * @param integer $limit contains the period time
     * 
     * @return object with scheduled posts or false
     */
    public function get_all_scheduled_posts($limit) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(['status' => 2, 'sent_time <' => time()]);
        $this->db->limit($limit);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result_array();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method change_scheduled_to_publish changes a scheduled post to publish
     *
     * @param integer $post_id contains the post_id
     * 
     * @return boolean true or false
     */
    public function change_scheduled_to_publish( $post_id ) {
        
        // Get current time
        $time = time();
        
        // Set data
        $data = ['sent_time' => $time, 'status' => 1];
        
        $this->db->where('post_id', $post_id);
        $this->db->update($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_user_posts deletes all user's posts
     *
     * @param integer $user_id contains the user's id
     * 
     * @return array with published posts
     */
    public function delete_user_posts( $user_id ) {
        
        $this->load->model('Resend', 'resend');
        $posts = $this->get_user_posts($user_id);
        
        if ( $posts ) {
            
            foreach ( $posts as $post ) {
                
                $resend_id = $this->resend->get_post_resend_id($user_id,$post->post_id);
                
                $this->db->delete('posts_meta', array('post_id' => $post->post_id));
                
                if( $resend_id ) {
                    
                    $this->db->delete('resend', ['resend_id' => $resend_id]);
                    $this->db->delete('resend_meta', ['resend_id' => $resend_id]);
                    $this->db->delete('resend_rules', ['resend_id' => $resend_id]);
                    
                }
                
            }
            
        }
        
        $this->db->delete('posts', array('user_id' => $user_id));
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_user_posts gets all user's posts
     *
     * @param integer $user_id contains user_id
     * 
     * @return object with posts or false
     */
    public function get_user_posts( $user_id ) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('user_id' => $user_id));
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
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
    
    /**
     * The public method delete_post_records deletes all post's records
     * 
     * @param integer $user_id contains user_id
     * @param integer $post_id contains the post's id
     * 
     * @return void
     */
    public function delete_post_records( $user_id, $post_id ) {

        // Load resend model
        $this->load->model('Resend', 'resend');
        $resend_id = $this->resend->get_post_resend_id( $user_id, $post_id );

        // Then will be deleted the post's meta
        $this->db->delete('posts_meta', array('post_id' => $post_id));

        // Then will be deleted the post
        $this->db->delete($this->table, array('post_id' => $post_id));

        if ( $this->db->affected_rows() ) {

            if ( is_numeric($resend_id) ) {

                // Then will be deleted the resend's row
                $this->db->delete('resend', array('resend_id' => $resend_id));
                $this->db->delete('resend_meta', array('resend_id' => $resend_id));
                $this->db->delete('resend_rules', array('resend_id' => $resend_id));

            }

            if ( get_user_option('settings_delete_activities') ) {

                // Load Activities model
                $this->load->model( 'Activities', 'activities' );

                // Delete activity by post's id
                $this->activities->delete_activity( 0, $post_id );

            }

        }
        
    }
    
    /**
     * The public method delete_media_records deletes a post's media
     * 
     * @param integer $user_id contains user_id
     * @param integer $media_id contains the post's id
     * 
     * @return void
     */
    public function delete_media_records( $user_id, $media_id ) {

        $this->db->select('post_id, img, video');
        $this->db->from($this->table);
        $this->db->like('img', $media_id);
        $this->db->or_like('video', $media_id);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) {
            
            $results = $query->result();
            
            foreach ( $results as $result ) {
                
                $images = unserialize($result->img);
                
                if ( $images ) {
                    
                    $imgs = array();
                    
                    foreach ( $images as $img ) {
                        
                        if ( $img !== $media_id ) {
                            
                            $imgs[] = $img;
                            
                        }
                        
                    }

                    $this->db->where('post_id', $result->post_id);
                    $this->db->update($this->table, array('img' => serialize($imgs)));
                    
                }
                
                $videos = unserialize($result->video);
                
                if ( $videos ) {
                    
                    $vids = array();
                    
                    foreach ( $videos as $vid ) {
                        
                        if ( $vid !== $media_id ) {
                            
                            $vids[] = $vid;
                            
                        }
                        
                    }

                    $this->db->where('post_id', $result->post_id);
                    $this->db->update($this->table, array('video' => serialize($vids)));
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method get_posts_by_time gets published posts by time
     *
     * @param integer $user_id contains the user_id
     * @param integer $start contains the start of displays posts
     * 
     * @return object with posts or false
     */
    public function get_posts_by_time( $user_id, $start ) {
        
        $this->db->select("LEFT(FROM_UNIXTIME(posts.sent_time), 10) as datetime", false);
        $this->db->select("COUNT(posts.post_id) as total", false);
        $this->db->from($this->table);
        $this->db->where(array(
                'posts.user_id' => $user_id,
                'posts.sent_time >=' => $start,
                'posts.status' => 1
            )
        );
        $this->db->group_by(array('datetime'));
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            // Get posts
            $posts = $query->result_array();
            
            $this->db->select("LEFT(FROM_UNIXTIME(posts_meta.sent_time), 10) as datetime", false);
            $this->db->select("SUM(CASE WHEN posts_meta.status=2 THEN 1 ELSE 0 END) as errors", false);
            $this->db->select("COUNT(posts_meta.meta_id) as total", false);
            $this->db->from('posts_meta');
            $this->db->join('posts', 'posts.post_id=posts_meta.post_id', 'left');
            $this->db->where(array(
                    'posts.user_id' => $user_id,
                    'posts.sent_time >=' => $start,
                    'posts.status >' => 0
                )
            );
            $this->db->group_by(array('datetime'));
            $query = $this->db->get();
            
            $accounts = array();

            if ( $query->num_rows() > 0 ) {

                // Get accounts
                $accounts = $query->result_array();

            }
            
            return array(
                'posts' => $posts,
                'accounts' => $accounts
            );
            
        } else {
            
            return '';
            
        }
        
    }
 
}

/* End of file posts_model.php */