<?php
/**
 * Tickets Model
 *
 * PHP Version 5.6
 *
 * Blue_tickets_model file contains the Tickets Model
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
 * Blue_tickets_model class - operates the tickets table.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Blue_tickets_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'tickets';

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
     * The public method get_all_tickets_for gets all non replied or replied 
     *
     * @param integer $user_id contains the user's id
     * 
     * @return integer number of tickets
     */
    public function get_all_tickets_for( $user_id = NULL ) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        
        if ( $user_id ) {
            
            $this->db->where(array('user_id' => $user_id));
            $this->db->order_by('ticket_id', 'desc');
            $this->db->limit(5);
            
        } else {
            
            $this->db->where(array('status' => 1));
            
        }
        
        $query = $this->db->get();
        
        if ( $user_id ) {
            
            $result = $query->result_array();
            return $result;
            
        } else {
            
            return $query->num_rows();
            
        }
        
    }
    
}

/* End of file blue_tickets_model.php */