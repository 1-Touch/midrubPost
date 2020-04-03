<?php
/**
 * Rss Accounts Model
 *
 * PHP Version 5.6
 *
 * rss_accounts_model file contains the RSS Accounts Model
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
 * Rss_accounts_model class - operates the rss_accounts table.
 *
 * @since 0.0.7.0
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Rss_accounts_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'rss_accounts';

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
     * The public method save_rss_account adds a new social account to a feed
     *
     * @param integer $network_id contains the network_id 
     * @param integer $rss_id contains the rss_id
     * 
     * @return integer with status
     */
    public function save_rss_account( $network_id, $rss_id ) {
        
        $this->db->select('rss_id');
        $this->db->from($this->table);
        $this->db->where( array('network_id' => $network_id, 'rss_id' => $rss_id) );
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return '1';
            
        }
        
        $data = array(
            'network_id' => $network_id,
            'rss_id' => $rss_id
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
     * The public method delete_rss_account deletes a RSS Feed's social account
     *
     * @param integer $network_id contains the network_id 
     * @param integer $rss_id contains the rss_id
     * 
     * @return integer with status
     */
    public function delete_rss_account( $network_id, $rss_id ) {
        
        $this->db->delete( $this->table, array('network_id' => $network_id, 'rss_id' => $rss_id) );
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_rss_accounts gets the RSS's accounts
     *
     * @param integer $rss_id contains the rss_id
     * @param string $key contains the search key
     * 
     * @return integer with status
     */
    public function get_rss_accounts( $rss_id, $key = NULL ) {
        
        $this->db->select('rss_accounts.network_id,networks.net_id,networks.user_avatar,networks.user_name,networks.network_name,networks.user_id,networks.expires,networks.token,networks.secret');
        $this->db->from($this->table);
        $this->db->join('networks', 'rss_accounts.network_id=networks.network_id', 'left');
        $this->db->where("(UNIX_TIMESTAMP(networks.expires) > '" . time() . "' OR LENGTH(networks.expires) < '5') AND rss_accounts.rss_id='" . $rss_id . "'", NULL, FALSE);
        
        if ( $key ) {
            
            // This method allows to escape special characters for LIKE conditions
            $key = $this->db->escape_like_str($key);
            
            // Gets posts which contains the $key
            $this->db->like('networks.user_name', $key);
            
        }
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }  
 
}

/* End of file Rss_accounts_model.php */