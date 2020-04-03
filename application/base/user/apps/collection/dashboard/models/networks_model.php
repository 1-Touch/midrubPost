<?php
/**
 * Networks Model
 *
 * PHP Version 5.6
 *
 * Networks file contains the Networks Model
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
 * Networks class - operates the networks table.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Networks_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'networks';

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
     * The public method get_accounts gets social networks accounts from database
     *
     * @param integer $user_id contains the user id
     * @param integer $start contains the page number
     * @param integer $limit displays the limit of displayed accounts
     * @param string $key contains the search key
     * 
     * @return object with accounts or false
     */    
    public function get_accounts( $user_id, $start=0, $limit=0, $key = NULL ) {
        
        if ( $key ) {
            $this->db->select('*');
        } else {
            $this->db->select('networks.network_id,networks.network_name,networks.user_name,networks.net_id,networks.user_avatar,networks.expires,networks.api_key,networks.api_secret,COUNT(posts_meta.meta_id) AS num');
        }
        
        $this->db->from($this->table);
        $this->db->where('networks.user_id', $user_id);
        
        if ( $key ) {
            
            // This method allows to escape special characters for LIKE conditions
            $key = $this->db->escape_like_str($key);
            
            // Gets posts which contains the $key
            $this->db->like('user_name', $key);

        } else {
            $this->db->join('posts_meta', 'networks.network_id=posts_meta.network_id', 'left');
            $this->db->group_by('networks.network_id');
            $this->db->order_by('num', 'desc');
        }
        
        if ( $limit ) {
            $this->db->limit($limit, $start);
        }
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            if ( $limit > 0 ) {
                $result = $query->result();
                return $result;
            } else {
                return $query->num_rows();
            }
            
        } else {
            
            return false;
            
        }
        
    }
 
}

/* End of file Networks_model.php */