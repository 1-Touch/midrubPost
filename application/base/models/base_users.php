<?php
/**
 * Base Users Model
 *
 * PHP Version 7.2
 *
 * Base_users file contains the Base Users Model
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
 * Base_users class - is the main Users model 
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Base_users extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'users';

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
     * The public method last_access updates last access date
     *
     * @param integer $user_id contains user's user_id
     * 
     * @return void
     */
    public function last_access( $user_id ) {
        
        // Where conditions
        $this->db->where('user_id', $user_id);
        
        // Also will be deleted the reset code
        $this->db->update($this->table, array('last_access' => date('Y-m-d H:i:s'), 'reset_code' => ' '));
        
    }

    /**
     * The public method get_user_data_by_username gets user data by username
     *
     * @param string $username contains the user's username
     * 
     * @return object with user data or boolean false
     */
    public function get_user_data_by_username( $username ) {
        
        $this->db->select('user_id, username, email, last_name, first_name, role, status, date_joined, last_access, reset_code, activate');
        $this->db->from($this->table);
        $this->db->where('username', strtolower($username));
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ( $query->num_rows() == 1 ) {
            
            return $query->result();
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method check_user will check if username and password exists
     *
     * @param string $username contains the user's username
     * @param string $password contains the user's password
     * 
     * @return boolean true or false
     */
    public function check_user( $username, $password ) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('username', strtolower($username));
        $this->db->limit(1);
        $query = $this->db->get();
        
        // Verify if user exists
        if ( $query->num_rows() == 1 ) {
            
            // Get response
            $result = $query->result();

            // Verify if password match
            if ( $result[0]->password AND password_verify($password, $result[0]->password) ) {
                
                // Save last access
                $this->last_access( $result[0]->user_id );

                
                return true;
                
            } else {
                
                return false;
                
            }
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method get_user_ceil checks if ceil value exists
     *
     * @param string $ceil contains the ceil's name
     * @param string $value contains the ceil's value
     * @param integer $user_id contains the user's user_id
     * 
     * @return string with user ceil or boolean false
     */
    public function get_user_ceil( $ceil, $value, $user_id = null ) {
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($ceil, strtolower($value));
        $this->db->limit(1);
        $query = $this->db->get();
        
        if ( $query->num_rows() == 1 ) {

            $result = $query->result();
            
            if ( $user_id ) {
                
                if ( $user_id == $result[0]->user_id ) {
                    
                    return false;
                    
                } else {
                    
                    return $result[0]->$ceil;
                    
                }
                
            } else {
                
                return $result[0]->$ceil;
                
            }
            
        } else {
            
            return false;
            
        }
        
    }
    
}

/* End of file base_users.php */