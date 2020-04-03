<?php
/**
 * Ads Networks Model
 *
 * PHP Version 7.2
 *
 * ads_networks_model file contains the Ads Networks Model
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
 * Ads_networks_model class - operates the ads_networks table.
 *
 * @since 0.0.7.5
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Ads_networks_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'ads_networks';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        $ads_networks = $this->db->table_exists('ads_networks');
        
        if ( !$ads_networks ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `ads_networks` (
                              `network_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `network_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `net_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `type` int(4) NOT NULL,
                              `user_id` int(11) NOT NULL,
                              `user_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `date` datetime NOT NULL,
                              `expires` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `token` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `secret` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                              `extra` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method save_ads_network saves a new ads network
     *
     * @param string $name contains the network's name
     * @param integer $net_id contains the user id from the social network
     * @param integer $type contains the ads account's account type
     * @param integer $user_id contains the user id
     * @param string $expires contains the date when the session will expire
     * @param string $token contains the access token
     * @param string $secret contains the token secret
     * @param string $user_name the username from the social network
     * @param string $extra could contain a network's extra information
     * 
     * @return boolean true or false
     */
    public function add_network( $name, $net_id, $type, $user_id, $expires, $token, $secret=NULL, $user_name, $extra ) {
        
        // First verify if the account was already added
        $this->db->select( 'network_id' );
        $this->db->from( $this->table );
        $this->db->where( array(
                'network_name' => strtolower($name),
                'net_id' => $net_id,
                'user_id' => $user_id,
                'type' => $type
            )
        );
        
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ) {
            $result = $query->result();
            return $result[0]->network_id;
        }
            
        // Add new row
        $data = array(
            'network_name' => strtolower($name),
            'net_id' => $net_id,
            'user_id' => $user_id,
            'type' => $type,
            'user_name' => $user_name,
            'date' => date('Y-m-d h:i:s'),
            'expires' => $expires,
            'token' => $token,
            'extra' => $extra
        );
        
        if ( $secret ) {
            
            $data['secret'] = trim($secret);
            
        }
        
        $this->db->insert($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            return $this->db->insert_id();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_networks gets networks from the database
     *
     * @param integer $user_id contains the user_id
     * @param integer $type contains the ad's type
     * @param integer $start contains the start of displayed ad accounts
     * @param integer $limit displays the limit of displayed ad accounts
     * @param string $key contains the search key
     * 
     * @return object with ad accounts or false
     */
    public function get_networks( $user_id, $type, $start, $limit, $key = NULL ) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array(
            'user_id' => $user_id,
            'type' => $type
        ));
        
        // If $key exists means will displayed ad accounts by search
        if ( $key ) {
            
            // This method allows to escape special characters for LIKE conditions
            $key = $this->db->escape_like_str($key);
            
            // Gets ad accounts which contains the $key
            $this->db->like('user_name', $key);
            
        }
        
        $this->db->order_by('network_id', 'desc');
        
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

                return $results;
            
            } else {
                return $query->num_rows();
            }
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_account gets accounts by network_id
     *
     * @param integer $network_id contains the network_id
     * 
     * @since 0.0.7.6
     * 
     * @return object with account data or false
     */    
    public function get_account( $network_id ) {
        
	$this->db->select('*');
	$this->db->from($this->table);
	$this->db->where('network_id', $network_id);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_account_records deletes the account records
     * 
     * @param integer $user_id contains user_id
     * @param integer $account_id contains the account's id
     * 
     * @return void
     */
    public function delete_account_records( $user_id, $account_id ) {

        // Delete the facebook ad accounts
        $this->db->delete($this->table, array(
                'network_id' => $account_id,
                'user_id' => $user_id
            )
        );
        
        if ( $this->db->affected_rows() ) {
            
            $this->db->delete('ads_account', array(
                    'network_id' => $account_id
                )
            );
            
        }
        
    }
 
}

/* End of file ads_networks_model.php */