<?php
/**
 * Rss Model
 *
 * PHP Version 5.6
 *
 * RSS file contains the RSS Model
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
 * RSS class - operates the rss table.
 *
 * @since 0.0.7.0
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Rss_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'rss';

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
     * The public method save_new_rss saves new rss
     *
     * @param integer $user_id contains the current user_id
     * @param string $url contains the Feed's url
     * @param string $title contains the Feed's title
     * @param string $description contains the Feed's description
     * 
     * @return integer with status
     */
    public function save_new_rss( $user_id, $url, $title, $description ) {
        
        $this->db->select('rss_id');
        $this->db->from($this->table);
        $this->db->where(array('user_id' => $user_id, 'rss_url' => $url));
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return '1';
            
        }
        
        $data = array(
            'user_id' => $user_id,
            'rss_name' => $title,
            'rss_description' => $description,
            'rss_url' => $url,
            'added' => date('Y-m-d H:i:s')
        );
        
        $this->db->insert($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            $insert_id = $this->db->insert_id();
            return $insert_id;
            
        } else {
            
            return '3';
            
        }
        
    }
    
    /**
     * The public method save_rss_post saves RSS's posts
     *
     * @param integer $user_id contains the current user_id
     * @param integer $rss_id contains the rss_id
     * @param string $rss_url contains the url of the post
     * @param string $time contains the time when the post will be published
     * @param string $img contains image if exists
     * @param integer $status contains the post's status
     * 
     * @return boolean true or false
     */
    public function save_rss_post( $user_id, $rss_id, $rss_url, $time, $title, $content = NULL, $img = NULL, $status = 0 ) {
        
        // Set the parameters to verify if the post was saved before
        $data = array(
            'rss_id' => $rss_id,
            'user_id' => $user_id,
            'url' => $rss_url
        );
        
        $this->db->select('*');
        $this->db->from('rss_posts');
        $this->db->where($data);
        $query = $this->db->get();
        
        if ( $query->num_rows() == 0 ) {
            
            // Prepare the data to save
            $data = array(
                'rss_id' => $rss_id,
                'user_id' => $user_id,
                'status' => $status,
                'title' => $title,
                'content' => $content,
                'url' => $rss_url,
                'img' => $img,
                'published' => time(),
                'scheduled' => $time
            );
            
            $this->db->insert('rss_posts', $data);
            
            // Check if the post was saved
            if ( $this->db->affected_rows() ) {
                
                $insert_id = $this->db->insert_id();
                return $insert_id;
                
            } else {
                
                return false;
                
            }
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method save_post_meta saves post meta
     *
     * @since 0.0.7.4
     * 
     * @param integer $post_id contains the post_id
     * @param integer $account contains the account where will be published the post
     * @param string $name contains the network's name
     * @param integer $status may be a number 0, 1 or 2
     * @param integer $user_id contains the user_id
     * @param integer $published_id contains the published id
     * 
     * @return integer with the last inserted id or false
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
        
        $this->db->insert('rss_posts_meta', $data);
        
        // Check if the post was saved
        if ( $this->db->affected_rows() ) {

            $insert_id = $this->db->insert_id();
            return $insert_id;

        } else {

            return false;

        }
        
    }
    
    /**
     * The public method save_published saves published posts
     *
     * @param integer $user_id contains the current user_id
     * @param integer $rss_id contains the rss_id
     * @param string $title contains the feed's title
     * @param string $content contents the feed's content
     * @param string $url contains the post url
     * 
     * @return integer 1 if the post was saved or false
     */
    public function save_published( $user_id, $rss_id, $title, $content = NULL, $url ) {
        
        $this->db->select('rss_id');
        $this->db->from('rss_posts');
        
        $title = $this->db->escape_like_str($title);
        
        $content = $this->db->escape_like_str($content);
        
        $this->db->where(array(
            'rss_id' => $rss_id,
            'user_id' => $user_id,
            'url' => trim($url)));
        
        $query = $this->db->get();
        
        if ( $query->num_rows() == 0 ) {
            
            $this->db->insert('rss_posts', array(
                'rss_id' => $rss_id,
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'url' => $url,
                'published' => time()));
            
            // Check if the post was saved
            if ( $this->db->affected_rows() ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_rss gets data by rss_id
     *
     * @param integer $rss_id contains the rss_id
     * @param integer $user_id contains the user's ID
     * 
     * @return object with rss data or false
     */
    public function get_rss( $rss_id, $user_id ) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('rss_id' => $rss_id, 'user_id' => $user_id));
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_rss_feeds gets all user's rss feeds
     *
     * @param integer $user_id contains the current user_id
     * @param integer $start contains a number where start to displays posts
     * @param integer $limit contains a number which means the limit of displayed posts
     * @param string $key contains the search key
     * 
     * @return object with all user's feeds or false
     */
    public function get_rss_feeds( $user_id, $start, $limit=0, $key = NULL ) {
        
        $this->db->select('rss.rss_id,rss.rss_name,rss.rss_description,rss.group_id,rss.enabled,rss.refferal,rss.rss_url,COUNT(case rss_posts.status when 1 then 1 else null end) as num,COUNT(DISTINCT rss_accounts.account_id) as accounts', false);
        $this->db->from($this->table);
        $this->db->join('rss_posts', 'rss.rss_id=rss_posts.rss_id', 'left');
        $this->db->join('rss_accounts', 'rss.rss_id=rss_accounts.rss_id', 'left');
        $this->db->where(array('rss.user_id' => $user_id));
        
        if ( $key ) {
            
            // This method allows to escape special characters for LIKE conditions
            $key = $this->db->escape_like_str($key);
            
            // Gets posts which contains the $key
            $this->db->like('rss.rss_name', $key);
            
        }
        
        $this->db->group_by('rss.rss_id');
        $this->db->order_by('rss.rss_id', 'desc');
        
        if ( $limit > 0 ) {
            $this->db->limit($limit, $start);
        }
        
        $query = $this->db->get();
        
        if ( !$limit ) {
            return $query->num_rows();
        }
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method get_posts gets published posts from database
     *
     * @param integer $user_id contains the current user_id
     * @param integer $start contains a number where start to displays posts
     * @param integer $limit contains a number which means the limit of displayed posts
     * @param integer $rss_id contains the rss_id
     * @param string $key contains the search key
     * 
     * @return object with posts or false
     */
    public function get_posts( $user_id, $start, $limit, $rss_id = 0, $key=NULL ) {
        
        // Set where parameters
        $data = array(
            'user_id' => $user_id
        );
        
        if ( $rss_id ) {
            $data['rss_id'] = $rss_id;
        } else {
            $data['published >'] = 0;
        }
        
        $this->db->select('rss_id,post_id,title,content,url,published,scheduled', false);
        $this->db->from('rss_posts');
        $this->db->where($data);
        
        // If $key exists means will displayed posts by search
        if ( $key ) {
            
            // This method allows to escape special characters for LIKE conditions
            $key = $this->db->escape_like_str($key);
            
            // Gets posts which contains the $key
            $this->db->like('title', $key);
            
        }
        
        $this->db->order_by('post_id', 'desc');
        
        // Verify if limit is not 0
        if ( $limit ) {
            $this->db->limit($limit, $start);
        }
        
        $query = $this->db->get();
        
        if ( !$limit ) {
            return $query->num_rows();
        }
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_post gets published post from database
     *
     * @param integer $user_id contains the current user_id
     * @param integer $post_id contains the post's ID
     * 
     * @return object with post data or false
     */
    public function get_post( $user_id, $post_id ) {
        
        // Set where parameters
        $data = array(
            'post_id' => $post_id,
            'user_id' => $user_id
        );
        
        $this->db->select('rss_posts.rss_id,rss_posts.status,rss_posts.post_id,rss_posts.title,rss_posts.content,rss_posts.url,rss_posts.img,rss_posts.published,rss_posts.scheduled', false);
        $this->db->from('rss_posts');
        $this->db->where($data);
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method was_published checks if post was published before
     *
     * @param integer $user_id contains the current user_id
     * @param integer $rss_id contains the rss_id
     * @param string $url contains the post url
     * 
     * @return boolean true or false
     */
    public function was_published( $user_id, $rss_id, $url ) {
        
        // Set parameters for where
        $data = array(
            'rss_posts.rss_id' => $rss_id,
            'rss_posts.user_id' => $user_id,
            'rss_posts.url' => trim($url)
        );
        
        $this->db->select('rss_id');
        $this->db->from('rss_posts');
        $this->db->where( $data );
        $query = $this->db->get();
        
        if ( $query->num_rows() == 0 ) {
            
            return true;
            
        } else {
            
            return false;
            
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
        
        $this->db->select('*, LEFT(FROM_UNIXTIME(rss_posts.published),10) as datetime', false);
        $this->db->select('COUNT(rss_posts.post_id) as number', false);
        $this->db->from('rss_posts');
        $this->db->where(array('rss_posts.user_id' => $user_id, 'rss_posts.published >' => strtotime('-' . $time . 'day', time())));
        $this->db->group_by(array('datetime'));
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            
            // Create new array
            $new_array = [];
            
            foreach ( $result as $data ) {
                
                $new_array[date('d/m', $data->published)] = $data->number;
                
            }
            
            return $new_array;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method update_rss_meta updates a rss's meta
     *
     * @param integer $rss_id contains the rss_id
     * @param string $name contains the column name
     * @param string $val contains the column value
     * 
     * @return bolean true or false
     */
    public function update_rss_meta( $rss_id, $name, $val ) {
        
        $this->db->where(['rss_id' => $rss_id]);
        $this->db->update($this->table, array($name => $val));
        
        // Check if the rss table was updated
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_rss_group gets the RSS's group
     *
     * @param integer $rss_id contains the RSS's ID
     * 
     * @return object with group data or boolean false
     */
    public function get_rss_group( $rss_id ) {
        
        $this->db->select('rss.group_id,lists.list_id,,lists.type,lists.name');
        $this->db->from($this->table);
        $this->db->join('lists', 'rss.group_id=lists.list_id', 'left');
        $this->db->where(array('rss.rss_id' => $rss_id));
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_rss_feed deletes a RSS Feed
     *
     * @param integer $rss_id contains the rss_id
     * 
     * @return bolean true or false
     */
    public function delete_rss_feed( $rss_id ) {
        
        $this->db->delete( $this->table, array( 'rss_id' => $rss_id ) );
        
        // Check if the rss table was deleted
        if ( $this->db->affected_rows() ) {
            
            $this->db->select( '*' );
            $this->db->from( 'rss_posts' );
            $this->db->where( array( 'rss_id' => $rss_id ) );
            $query = $this->db->get();

            if ( $query->num_rows() > 0 ) {

                $results = $query->result();
                
                foreach ( $results as $result ) {
                    
                    $this->db->delete( 'rss_posts', array( 'post_id' => $result->post_id ) );
                    $this->db->delete( 'rss_posts_meta', array( 'post_id' => $result->post_id ) );
                    
                }

            }
            
            $this->db->delete( 'rss_accounts', array( 'rss_id' => $rss_id ) );
            
            return true;
            
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
        
        $this->db->select('rss_posts_meta.meta_id,rss_posts_meta.network_id,rss_posts_meta.post_id,rss_posts_meta.status,rss_posts_meta.network_status,networks.network_name,networks.user_name');
        $this->db->from('rss_posts_meta');
        $this->db->join('networks', 'rss_posts_meta.network_id=networks.network_id', 'left');
        $this->db->where(['networks.user_id' => $user_id, 'rss_posts_meta.post_id' => $post_id]);
        
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
     * The public method delete_rss_post deletes a RSS's post based on post_id
     *
     * @param integer $post_id contains the post's ID
     * 
     * @return bolean true or false
     */
    public function delete_rss_post( $post_id ) {
        
        $this->db->delete( 'rss_posts', array( 'post_id' => $post_id ) );
        
        // Check if the post was deleted
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method enable_or_disable_rss_option enables and disables a rss options. First call, enables social network, second call disable.
     *
     * @param integer $user_id contains the current user_id
     * @param integer $rss_id contains the rss_id
     * @param string $option contains the option's name
     * 
     * @return boolean true if the option was enabled/disabled or false
     */
    public function enable_or_disable_rss_option( $user_id, $rss_id, $option, $value = NULL ) {
        
        if ( $value ) {
            
            $this->db->where(['rss_id' => $rss_id, 'user_id' => $user_id]);
            $this->db->update($this->table, [$option => $value]);
            
            if ( $this->db->affected_rows() ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        } else {
            
            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where(['rss_id' => $rss_id, 'user_id' => $user_id, $option => '1']);
            $query = $this->db->get();
            
            if ( $query->num_rows() === 1 ) {
                
                // If the option is enabled, will be disabled
                $this->db->where(['rss_id' => $rss_id, 'user_id' => $user_id]);
                $this->db->update($this->table, array($option => '0'));
                
            } else {
                
                if ( $option === 'include' || $option === 'exclude' || $option === 'refferal' ) {
                    $value = '';
                } else {
                    $value = '1';
                }
                
                // If the network not exists, will be added with value 1
                $this->db->where(['rss_id' => $rss_id, 'user_id' => $user_id]);
                $this->db->update($this->table, array($option => $value));
                
            }
            
            // Check if option was saved or deleted successfully
            if ( $this->db->affected_rows() ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        }
        
    }
    
    /**
     * The public method get_random_rss gets one random rss feed
     * 
     * @return object with rss or false
     */
    public function get_random_rss() {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array('enabled' => '1', 'completed' => '0', 'pub' => '0'));
        $this->db->order_by('rss_id', 'RANDOM');
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
     * The public method reset_rss resets all completed RSS
     * 
     * @return boolean true or false
     */
    public function reset_rss() {
        
        $this->db->where(array('enabled' => 1, 'completed' => 1));
        
        $this->db->update($this->table, array('completed' => 0));
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method set_completed set completed for a feed
     *
     * @param integer $rss_id contains the rss_id
     * @param integer $num contains the completed value/1
     * 
     * @return boolean true if rss was completed or false
     */
    public function set_completed( $rss_id, $num ) {
        
        $this->db->where(array('rss_id' => $rss_id));
        $this->db->update($this->table, array('completed' => $num));
        
        // Check if the rss table was updated
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_random_rss_m gets one random rss post
     * 
     * @param integer $limit contains the random's limit
     * 
     * @return object with rss or false
     */
    public function get_random_rss_m( $limit ) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('rss_posts', 'rss.rss_id=rss_posts.rss_id', 'left');
        $this->db->where("rss.enabled='1' AND rss.completed='0' AND rss.pub='1' AND rss_posts.scheduled < '" . time() . "' AND rss_posts.status > '1'");
        $this->db->order_by('rss_posts.post_id', 'RANDOM');
        $this->db->limit($limit);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result_array();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method update_rss_post_field updates a RSS's post field
     *
     * @param integer $post_id contains the post's id
     * @param string $ceil_name contains the ceil's name
     * @param string $ceil_value contains the ceil's value
     * 
     * @return bolean true or false
     */
    public function update_rss_post_field( $post_id, $ceil_name, $ceil_value ) {
        
        $this->db->where(array('post_id' => $post_id));
        $this->db->update('rss_posts', array($ceil_name => $ceil_value));
        
        // Check if the rss table was updated
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_rss_feed deletes a RSS Feed
     *
     * @param integer $user_id contains the user's id
     * 
     * @return bolean true or false
     */
    public function delete_rss_feeds( $user_id ) {
        
        $this->db->delete( $this->table, array( 'user_id' => $user_id ) );
        
        // Check if the rss table was deleted
        if ( $this->db->affected_rows() ) {
            
            $this->db->select('*');
            $this->db->from('rss_posts');
            $this->db->where(array('user_id' => $user_id));
            $query = $this->db->get();

            if ( $query->num_rows() > 0 ) {
                
                $posts = $query->result();
                
                foreach ( $posts as $post ) {
                    $this->db->delete('rss_posts_meta', array('post_id' => $post->post_id));
                    $this->db->delete('rss_accounts', array('rss_id' => $post->rss_id));
                }

            }
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_post deletes a RSS's post
     *
     * @param integer $user_id contains the user's id
     * @param integer $post_id contains the post_id
     * 
     * @return boolean if the post was deleted successfully or false
     */
    public function delete_post($user_id, $post_id) {
        
        // Delete RSS's post
        $this->db->delete('rss_posts', array(
            'user_id' => $user_id,
            'post_id' => $post_id
        ));  

        if ( $this->db->affected_rows() ) {
            
            // Delete RSS's post's meta
            $this->db->delete('rss_posts_meta', array('post_id' => $post_id));            
            
            return true;

        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_posts_by_time gets published posts by time
     *
     * @param integer $user_id contains the user_id
     * @param integer $start contains the start of displays posts
     * @param integer $rss_id contains the rss_id
     * 
     * @return object with posts or false
     */
    public function get_posts_by_time( $user_id, $start, $rss_id ) {
        
        $this->db->select("LEFT(FROM_UNIXTIME(rss_posts.published), 10) as datetime", false);
        $this->db->select("COUNT(rss_posts.post_id) as total", false);
        $this->db->from('rss_posts');
        $this->db->where(array(
                'rss_posts.rss_id' => $rss_id,
                'rss_posts.user_id' => $user_id,
                'rss_posts.published >=' => $start,
                'rss_posts.status' => 1
            )
        );
        $this->db->group_by(array('datetime'));
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            // Get posts
            $posts = $query->result_array();
            
            $this->db->select("LEFT(FROM_UNIXTIME(rss_posts_meta.sent_time), 10) as datetime", false);
            $this->db->select("SUM(CASE WHEN rss_posts_meta.status=2 THEN 1 ELSE 0 END) as errors", false);
            $this->db->select("COUNT(rss_posts_meta.meta_id) as total", false);
            $this->db->from('rss_posts_meta');
            $this->db->join('rss_posts', 'rss_posts.post_id=rss_posts_meta.post_id', 'left');
            $this->db->where(array(
                    'rss_posts.rss_id' => $rss_id,
                    'rss_posts.user_id' => $user_id,
                    'rss_posts.published >=' => $start,
                    'rss_posts.status >' => 0
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

/* End of file Rss_model.php */