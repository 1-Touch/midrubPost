<?php
/**
 * Ads Labels Model
 *
 * PHP Version 5.6
 *
 * ads_labels_model file contains the Ads Labels Model
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
 * Ads_labels_model class - operates the ads_labels table.
 *
 * @since 0.0.7.7
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Ads_labels_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'ads_labels';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        $ads_labels = $this->db->table_exists('ads_labels');
        
        if ( !$ads_labels ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `ads_labels` (
                              `label_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `label_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `user_id` int(11) NOT NULL,
                              `time` int(1) NOT NULL,
                              `created` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        $ads_labels_meta = $this->db->table_exists('ads_labels_meta');
        
        if ( !$ads_labels_meta ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `ads_labels_meta` (
                              `meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `label_id` bigint(20) NOT NULL,
                              `meta_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `meta_value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
        $ads_labels_stats = $this->db->table_exists('ads_labels_stats');
        
        if ( !$ads_labels_stats ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `ads_labels_stats` (
                              `stat_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `label_id` bigint(20) NOT NULL,
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
     * The public method save_label saves a new label
     *
     * @param integer $user_id contains the user id
     * @param string $label_name contains the label's name
     * 
     * @since 0.0.7.7
     * 
     * @return integer with last inserted id or false
     */
    public function save_label( $user_id, $label_name ) {
            
        // Add new row
        $data = array(
            'label_name' => $label_name,
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
     * The public method save_label_meta saves a new label's meta
     *
     * @param integer $label_id contains the label's id
     * @param string $meta_name contains the meta's name
     * @param string $meta_value contains the meta's value
     * 
     * @since 0.0.7.7
     * 
     * @return integer with last inserted id or false
     */
    public function save_label_meta( $label_id, $meta_name, $meta_value ) {
            
        // Add new row
        $data = array(
            'label_id' => $label_id,
            'meta_name' => $meta_name,
            'meta_value' => $meta_value
        );

        $this->db->insert('ads_labels_meta', $data);

        if ( $this->db->affected_rows() ) {

            return $this->db->insert_id();

        } else {

            return false;

        }
        
    }
    
    /**
     * The public method update_label_stats updates label's stat
     *
     * @param integer $stat_id contains the stat's id
     * @param string $status contains the stat's status
     * @param string $error contains the stat's status
     * 
     * @since 0.0.7.7
     * 
     * @return boolean true or false
     */
    public function update_label_stats( $stat_id, $status, $error ) {
            
        // Add new row
        $data = array(
            'status' => $status,
            'end_status' => $error
        );

        $this->db->where('stat_id', $stat_id);
        $this->db->update('ads_labels_stats', $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }    
    
    /**
     * The public method get_labels gets all labels from the database
     *
     * @param integer $user_id contains the user id
     * @param integer $account_id contains the account id
     * @param integer $start contains the page number
     * @param integer $limit displays the limit of displayed labels
     * 
     * @since 0.0.7.7
     * 
     * @return object with labels or false
     */    
    public function get_labels( $user_id, $account_id, $start=0, $limit=0 ) {
        
        $this->db->select('ads_labels.label_id, ads_labels.label_name, ads_labels.time');
        $this->db->select("LEFT(FROM_UNIXTIME(ads_labels_stats.created), 10) as datetime", false);
        $this->db->select("SUM(CASE WHEN ads_labels_stats.status=2 THEN 1 ELSE 0 END) as errors", false);
        $this->db->select("SUM(CASE WHEN ads_labels_stats.status=1 THEN 1 ELSE 0 END) as success", false);
        $this->db->from($this->table);
        $this->db->join('ads_labels_meta', 'ads_labels.label_id=ads_labels_meta.label_id', 'LEFT');
        $this->db->join('ads_labels_stats', 'ads_labels.label_id=ads_labels_stats.label_id', 'left');
        $this->db->where(array(
                'ads_labels_meta.meta_name' => 'ad_account',
                'ads_labels_meta.meta_value' => $account_id
            )
        );
        
        $this->db->order_by('ads_labels.label_id', 'desc');
        
        if ( $limit > 0 ) {
        
            $this->db->group_by('ads_labels.label_id');
            $this->db->limit($limit, $start);
            $query = $this->db->get();
        
        } else {
            
            $this->db->group_by('ads_labels.label_id');
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
     * The public method get_labels gets all labels from the database
     *
     * @param integer $user_id contains the user's id
     * @param integer $label_id contains the label's id
     * 
     * @since 0.0.7.7
     * 
     * @return object with labels or false
     */    
    public function get_label( $user_id, $label_id ) {
        
        $this->db->select('ads_labels.label_id, ads_labels.label_name, ads_labels.time');
        $this->db->select("LEFT(FROM_UNIXTIME(ads_labels_stats.created), 10) as datetime", false);
        $this->db->select("SUM(CASE WHEN ads_labels_stats.status=2 THEN 1 ELSE 0 END) as errors", false);
        $this->db->select("SUM(CASE WHEN ads_labels_stats.status=1 THEN 1 ELSE 0 END) as success", false);
        $this->db->from($this->table);
        $this->db->join('ads_labels_meta', 'ads_labels.label_id=ads_labels_meta.label_id', 'LEFT');
        $this->db->join('ads_labels_stats', 'ads_labels.label_id=ads_labels_stats.label_id', 'left');
        $this->db->where(array(
                'ads_labels.label_id' => $label_id,
                'ads_labels.user_id' => $user_id
            )
        );
        
        $this->db->group_by('ads_labels.label_id');
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
        
        $this->db->select("ads_labels.label_name");
        $this->db->select("LEFT(FROM_UNIXTIME(ads_labels_stats.created), 10) as datetime", false);
        $this->db->select("SUM(CASE WHEN ads_labels_stats.status=2 THEN 1 ELSE 0 END) as errors", false);
        $this->db->select("SUM(CASE WHEN ads_labels_stats.status=1 THEN 1 ELSE 0 END) as success", false);
        $this->db->from('ads_labels_stats');
        $this->db->join('ads_labels', 'ads_labels_stats.label_id=ads_labels.label_id', 'left');
        $this->db->where(array(
                'ads_labels.user_id' => $user_id,
                'ads_labels_stats.created >=' => $start
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
     * @since 0.0.8.1
     * 
     * @return object with active ads or false
     */
    public function get_active_ads() {

        $this->db->select('ads_labels_stats.stat_id, ads_labels_stats.ad_id, ads_networks.token, ads_labels_meta.meta_value');
        $this->db->from('ads_labels_stats');
        $this->db->join('ads_labels_meta', 'ads_labels_stats.label_id=ads_labels_meta.label_id', 'LEFT');
        $this->db->join('ads_labels_meta a', 'ads_labels_stats.label_id=a.label_id', 'LEFT');
        $this->db->join('ads_networks', 'a.meta_value=ads_networks.network_id', 'LEFT');
        $this->db->where(array(
                'ads_labels_stats.status' => 1,
                'ads_labels_meta.meta_name' => 'spending_limit',
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
     * The public method delete_label deletes a label
     *
     * @param integer $label_id contains the label's id
     * @param integer $user_id contains the user's id
     * 
     * @since 0.0.7.7
     * 
     * @return boolean true or false
     */
    public function delete_label( $label_id, $user_id ) {
        
        // Delete label
        $this->db->delete($this->table, array('label_id' => $label_id, 'user_id' => $user_id));
        
        if ( $this->db->affected_rows() ) {
            
            // Delete label's metas
            $this->db->delete('ads_labels_meta', array('label_id' => $label_id));

            // Delete all ad label's records
            run_hook(
                'delete_fb_ads_label',
                array(
                    'label_id' => $label_id
                )
            );
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_label_records deletes label's records
     * 
     * @param integer $label_id contains the label's id
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_label_records( $label_id ) {

        // Delete the facebook ad labels
        $this->db->delete('networks', array(
                'network_name' => 'facebook_ad_labels',
                'net_id' => $label_id
            )
        );
        
        if ( $this->db->affected_rows() ) {
            
            $this->db->delete('ads_labels_stats', array(
                    'label_id' => $label_id
                )
            );   
            
            $this->db->delete('ads_labels_meta', array(
                    'label_id' => $label_id
                )
            );             
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_label_records deletes label's records
     * 
     * @param integer $account_id contains the account id
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_label_records_by_account($account_id)
    {

        $this->db->select('label_id');
        $this->db->from('ads_labels_meta');
        $this->db->where(
            array(
                'meta_name' => 'ad_account',
                'meta_value' => $account_id
            )
        );

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $results = $query->result();

            foreach ($results as $result) {

                $this->db->delete(
                    'ads_labels',
                    array(
                        'label_id' => $result->label_id
                    )
                );

                $this->db->delete(
                    'ads_labels_stats',
                    array(
                        'label_id' => $result->label_id
                    )
                );

                $this->db->delete(
                    'ads_labels_meta',
                    array(
                        'label_id' => $result->label_id
                    )
                );

                // Use the base model for a simply sql query
                $this->base_model->delete(
                    'networks',
                    array(
                        'network_name' => 'facebook_ad_labels',
                        'net_id' => $result->label_id
                    )
                );

            }

        }

    }

    /**
     * The public method delete_label_records_by_user deletes label's records by user's id
     * 
     * @param integer $user_id contains the user id
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_label_records_by_user($user_id)
    {

        $this->db->select('label_id');
        $this->db->from('ads_labels');
        $this->db->where(
            array(
                'user_id' => $user_id
            )
        );

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $results = $query->result();

            foreach ($results as $result) {

                $this->db->delete(
                    'ads_labels',
                    array(
                        'label_id' => $result->label_id
                    )
                );

                $this->db->delete(
                    'ads_labels_stats',
                    array(
                        'label_id' => $result->label_id
                    )
                );

                $this->db->delete(
                    'ads_labels_meta',
                    array(
                        'label_id' => $result->label_id
                    )
                );

            }

        }
        
    }    
 
}

/* End of file ads_labels_model.php */