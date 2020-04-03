<?php
/**
 * Ads Boosts Model
 *
 * PHP Version 7.2
 *
 * ads_boosts_model file contains the Ads Boosts Model
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
 * Ads_boosts_model class - operates the ads_boosts table.
 *
 * @since 0.0.7.7
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Ads_boosts_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'ads_boosts';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        $ads_boosts = $this->db->table_exists('ads_boosts');
        
        if ( !$ads_boosts ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `ads_boosts` (
                              `boost_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `boost_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `user_id` int(11) NOT NULL,
                              `time` int(1) NOT NULL,
                              `created` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        $ads_boosts_meta = $this->db->table_exists('ads_boosts_meta');
        
        if ( !$ads_boosts_meta ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `ads_boosts_meta` (
                              `meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `boost_id` bigint(20) NOT NULL,
                              `meta_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `meta_value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
        $ads_boosts_stats = $this->db->table_exists('ads_boosts_stats');
        
        if ( !$ads_boosts_stats ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `ads_boosts_stats` (
                              `stat_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `boost_id` bigint(20) NOT NULL,
                              `post_id` bigint(20) NOT NULL,
                              `publisher_platforms` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `status` int(1) NOT NULL,
                              `platform_status` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `ad_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `ad_id` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `created` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `end_time` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `end_status` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method save_boost saves a new boost
     *
     * @param integer $user_id contains the user id
     * @param string $boost_name contains the boost's name
     * 
     * @since 0.0.7.7
     * 
     * @return integer with last inserted id or false
     */
    public function save_boost( $user_id, $boost_name ) {
            
        // Add new row
        $data = array(
            'boost_name' => $boost_name,
            'user_id' => $user_id,
            'created' => time()
        );

        $this->db->insert($this->table, $data);

        if ( $this->db->affected_rows() ) {

            return $this->db->insert_id();

        } else {

            return false;

        }
        
    }
    
    /**
     * The public method save_boost_meta saves a new boost's meta
     *
     * @param integer $boost_id contains the boost's id
     * @param string $meta_name contains the meta's name
     * @param string $meta_value contains the meta's value
     * 
     * @since 0.0.7.7
     * 
     * @return integer with last inserted id or false
     */
    public function save_boost_meta( $boost_id, $meta_name, $meta_value ) {
            
        // Add new row
        $data = array(
            'boost_id' => $boost_id,
            'meta_name' => $meta_name,
            'meta_value' => $meta_value
        );

        $this->db->insert('ads_boosts_meta', $data);

        if ( $this->db->affected_rows() ) {

            return $this->db->insert_id();

        } else {

            return false;

        }
        
    }

    /**
     * The public method save_boost_stats saves boots stats
     *
     * @param integer $boost_id contains the boost's id
     * @param integer $post_id contains the post's id
     * @param string $publisher_platforms contains the string with platforms
     * @param integer $status contains the publish status
     * @param string $platform_status contains the error status
     * @param string $ad_name contains the ad's name
     * @param integer $ad_id contains the ad's id
     * @param integer $created contains the time when ad was created
     * @param integer $end_time contains the end time
     * 
     * @since 0.0.7.7
     * 
     * @return integer with last inserted id or false
     */
    public function save_boost_stats( $boost_id, $post_id, $publisher_platforms=NULL, $status, $platform_status, $ad_name, $ad_id, $created, $end_time ) {
            
        // Add new row
        $data = array(
            'boost_id' => $boost_id,
            'post_id' => $post_id,
            'publisher_platforms' => $publisher_platforms,
            'status' => $status,
            'platform_status' => $platform_status,
            'ad_name' => $ad_name,
            'ad_id' => $ad_id,
            'created' => $created,
            'end_time' => $end_time
        );

        if ( $publisher_platforms ) {
            $data['publisher_platforms'] = $publisher_platforms;
        }

        $this->db->insert('ads_boosts_stats', $data);

        if ( $this->db->affected_rows() ) {

            return $this->db->insert_id();

        } else {

            return false;

        }
        
    }
    
    /**
     * The public method update_boost_stats updates boost's stat
     *
     * @param integer $stat_id contains the stat's id
     * @param string $status contains the stat's status
     * @param string $error contains the stat's status
     * 
     * @since 0.0.7.7
     * 
     * @return boolean true or false
     */
    public function update_boost_stats( $stat_id, $status, $error ) {
            
        // Add new row
        $data = array(
            'status' => $status,
            'end_status' => $error
        );

        $this->db->where('stat_id', $stat_id);
        $this->db->update('ads_boosts_stats', $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }    
    
    /**
     * The public method get_Boosts gets all Boosts from the database
     *
     * @param integer $user_id contains the user id
     * @param integer $account_id contains the account id
     * @param integer $start contains the page number
     * @param integer $limit displays the limit of displayed Boosts
     * 
     * @since 0.0.7.7
     * 
     * @return object with Boosts or false
     */    
    public function get_boosts( $user_id, $account_id, $start=0, $limit=0 ) {
        
        $this->db->select('ads_boosts.boost_id, ads_boosts.boost_name, ads_boosts.time');
        $this->db->select("LEFT(FROM_UNIXTIME(ads_boosts_stats.created), 10) as datetime", false);
        $this->db->select("SUM(CASE WHEN ads_boosts_stats.status=2 THEN 1 ELSE 0 END) as errors", false);
        $this->db->select("SUM(CASE WHEN ads_boosts_stats.status=1 THEN 1 ELSE 0 END) as success", false);
        $this->db->from($this->table);
        $this->db->join('ads_boosts_meta', 'ads_boosts.boost_id=ads_boosts_meta.boost_id', 'LEFT');
        $this->db->join('ads_boosts_stats', 'ads_boosts.boost_id=ads_boosts_stats.boost_id', 'left');
        $this->db->where(array(
                'ads_boosts_meta.meta_name' => 'ad_account',
                'ads_boosts_meta.meta_value' => $account_id
            )
        );
        
        $this->db->order_by('ads_boosts.boost_id', 'desc');
        
        if ( $limit > 0 ) {
        
            $this->db->group_by('ads_boosts.boost_id');
            $this->db->limit($limit, $start);
            $query = $this->db->get();
        
        } else {
            
            $this->db->group_by('ads_boosts.boost_id');
            $query = $this->db->get();
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
     * The public method get_boost gets boost's option for reports
     *
     * @param integer $user_id contains the user's id
     * @param integer $boost_id contains the boost's id
     * 
     * @since 0.0.7.7
     * 
     * @return object with Boosts or false
     */    
    public function get_boost( $user_id, $boost_id ) {
        
        $this->db->select('ads_boosts.boost_id, ads_boosts.boost_name, ads_boosts.time');
        $this->db->select("LEFT(FROM_UNIXTIME(ads_boosts_stats.created), 10) as datetime", false);
        $this->db->select("SUM(CASE WHEN ads_boosts_stats.status=2 THEN 1 ELSE 0 END) as errors", false);
        $this->db->select("SUM(CASE WHEN ads_boosts_stats.status=1 THEN 1 ELSE 0 END) as success", false);
        $this->db->from($this->table);
        $this->db->join('ads_boosts_meta', 'ads_boosts.boost_id=ads_boosts_meta.boost_id', 'LEFT');
        $this->db->join('ads_boosts_stats', 'ads_boosts.boost_id=ads_boosts_stats.boost_id', 'left');
        $this->db->where(array(
                'ads_boosts.boost_id' => $boost_id,
                'ads_boosts.user_id' => $user_id
            )
        );
        
        $this->db->group_by('ads_boosts.boost_id');
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
     * The public method get_boost gets boost's meta by boost_id and meta_name
     *
     * @param integer $boost_id contains the boost's id
     * @param string $meta_name contains the meta's name
     * 
     * @since 0.0.7.7
     * 
     * @return string with meta's value or boolean false
     */    
    public function get_boost_meta( $boost_id, $meta_name ) {
        
        $this->db->select('meta_value');
        $this->db->from('ads_boosts_meta');
        $this->db->where(array(
                'boost_id' => $boost_id,
                'meta_name' => $meta_name
            )
        );

        $this->db->limit(1);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result[0]->meta_value;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method get_boost_stats gets stats by post's id
     *
     * @param integer $post_id contains the post's id
     * 
     * @since 0.0.7.7
     * 
     * @return boolean true or false
     */    
    public function get_boost_stats( $post_id ) {
        
        $this->db->select('post_id');
        $this->db->from('ads_boosts_stats');
        $this->db->where(array(
                'post_id' => $post_id
            )
        );

        $this->db->limit(1);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) {
            
            return true;
            
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
        
        $this->db->select('ads_boosts.boost_id, ads_boosts.boost_name, ads_boosts.time, networks.network_id, networks.net_id, networks.user_name');
        $this->db->from($this->table);
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
     * The public method get_reports_by_time gets reports by time
     *
     * @param integer $user_id contains the user_id
     * @param integer $start contains the start of displays posts
     * 
     * @since 0.0.7.7
     * 
     * @return object with reports
     */
    public function get_reports_by_time( $user_id, $start ) {
        
        $this->db->select("ads_boosts.boost_name");
        $this->db->select("LEFT(FROM_UNIXTIME(ads_boosts_stats.created), 10) as datetime", false);
        $this->db->select("SUM(CASE WHEN ads_boosts_stats.status=2 THEN 1 ELSE 0 END) as errors", false);
        $this->db->select("SUM(CASE WHEN ads_boosts_stats.status=1 THEN 1 ELSE 0 END) as success", false);
        $this->db->from('ads_boosts_stats');
        $this->db->join('ads_boosts', 'ads_boosts_stats.boost_id=ads_boosts.boost_id', 'left');
        $this->db->where(array(
                'ads_boosts.user_id' => $user_id,
                'ads_boosts_stats.created >=' => $start
            )
        );
        $this->db->group_by(array('datetime'));
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result_array();
            
        } else {
            
            return '';
            
        }
        
    }
    
    /**
     * The public method get_active_ads gets active ads and verify for spend
     * 
     * @since 0.0.7.7
     * 
     * @return object with active ads or false
     */
    public function get_active_ads() {

        $this->db->select('ads_boosts_stats.stat_id, ads_boosts_stats.ad_id, ads_networks.token');
        $this->db->from('ads_boosts_stats');
        $this->db->join('ads_boosts_meta', 'ads_boosts_stats.boost_id=ads_boosts_meta.boost_id', 'LEFT');
        $this->db->join('ads_boosts_meta a', 'ads_boosts_meta.boost_id=a.boost_id', 'LEFT');
        $this->db->join('ads_networks', 'a.meta_value=ads_networks.network_id', 'LEFT');
        $this->db->where(array(
                'ads_boosts_stats.status' => 1,
                'ads_boosts_meta.meta_name' => 'spending_limit',
                'a.meta_name' => 'ad_account'
            )
        );
        $this->db->order_by('rand()');
        $this->db->limit(5);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) {
            
            return $query->result_array();
            
        } else {
            
            return false;
            
        }
        
    }    
    
    /**
     * The public method delete_boost deletes a boost
     *
     * @param integer $boost_id contains the boost's id
     * @param integer $user_id contains the user's id
     * 
     * @since 0.0.7.7
     * 
     * @return boolean true or false
     */
    public function delete_boost( $boost_id, $user_id ) {
        
        // Delete boost
        $this->db->delete($this->table, array('boost_id' => $boost_id, 'user_id' => $user_id));
        
        if ( $this->db->affected_rows() ) {
            
            // Delete boost's metas
            $this->db->delete('ads_boosts_meta', array('boost_id' => $boost_id));
            
            // Delete all ad boost's records
            run_hook(
                'delete_fb_ads_boost',
                array(
                    'boost_id' => $boost_id
                )
            );
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_boost_records deletes boost's records
     * 
     * @param integer $boost_id contains the boost's id
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_boost_records( $boost_id ) {

        $this->db->delete(
            'ads_boosts_stats',
            array(
                'boost_id' => $boost_id
            )
        );

        $this->db->delete(
            'ads_boosts_meta',
            array(
                'boost_id' => $boost_id
            )
        );

        $this->db->select('post_id');
        $this->db->from('posts');
        $this->db->where(
            array(
                'fb_boost_id' => $boost_id
            )
        );

        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $results = $query->result();

            foreach ($results as $result) {

                $this->db->where('post_id', $result->post_id);
                $this->db->update('posts', array('fb_boost_id' => '0'));

            }
            
        }
        
    }
    
    /**
     * The public method delete_boost_records deletes boost's records
     * 
     * @param integer $account_id contains the account id
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_boost_records_by_account( $account_id ) {

        $this->db->select('boost_id');
        $this->db->from('ads_boosts_meta');
        $this->db->where(
            array(
                'meta_name' => 'ad_account',
                'meta_value' => $account_id
            )
        );
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $results = $query->result();

            foreach ($results as $result ) {
                
                $this->db->delete('ads_boosts', array(
                        'boost_id' => $result->boost_id
                    )
                );

                $this->db->delete('ads_boosts_stats', array(
                        'boost_id' => $result->boost_id
                    )
                );   

                $this->db->delete('ads_boosts_meta', array(
                        'boost_id' => $result->boost_id
                    )
                );

                $this->db->select('post_id');
                $this->db->from('posts');
                $this->db->where(
                    array(
                        'fb_boost_id' => $result->boost_id
                    )
                );
        
                $query = $this->db->get();
                
                if ( $query->num_rows() > 0 ) {
                    
                    $results = $query->result();
        
                    foreach ($results as $result) {
        
                        $this->db->where('post_id', $result->post_id);
                        $this->db->update('posts', array('fb_boost_id' => '0'));
        
                    }
                    
                }
                
            }
            
        }
        
    }

    /**
     * The public method delete_boost_records_by_user deletes boost's records by user_id
     * 
     * @param integer $user_id contains the user id
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_boost_records_by_user( $user_id ) {

        $this->db->select('boost_id');
        $this->db->from('ads_boosts');
        $this->db->where(
            array(
                'user_id' => $user_id
            )
        );
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $results = $query->result();

            foreach ($results as $result ) {
                
                $this->db->delete('ads_boosts', array(
                        'boost_id' => $result->boost_id
                    )
                );

                $this->db->delete('ads_boosts_stats', array(
                        'boost_id' => $result->boost_id
                    )
                );   

                $this->db->delete('ads_boosts_meta', array(
                        'boost_id' => $result->boost_id
                    )
                );

                $this->db->select('post_id');
                $this->db->from('posts');
                $this->db->where(
                    array(
                        'fb_boost_id' => $result->boost_id
                    )
                );
        
                $query = $this->db->get();
                
                if ( $query->num_rows() > 0 ) {
                    
                    $results = $query->result();
        
                    foreach ($results as $result) {
        
                        $this->db->where('post_id', $result->post_id);
                        $this->db->update('posts', array('fb_boost_id' => '0'));
        
                    }
                    
                }
                
            }
            
        }
        
    }    
 
}

/* End of file ads_boosts_model.php */