<?php
/**
 * Storage Lists Model
 *
 * PHP Version 5.6
 *
 * Storage Lists Model contains the Storage Lists Model
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
 * Storage_lists_model class - operates the lists table.
 *
 * @since 0.0.7.6
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Storage_lists_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'lists';

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
     * The public method save_category creates a category
     *
     * @param integer $user_id contains the user_id
     * @param string $type contains the list's type
     * @param string $name contains the list's name
     * @param string $description contains the list's description
     * 
     * @return integer with last inserted id or false
     */
    public function save_category( $user_id, $type, $name, $description ) {
        
        // Get current time
        $created = time();
        
        // Set data
        $data = array(
            'user_id' => $user_id,
            'type' => $type,
            'name' => $name,
            'description' => $description,
            'created' => $created
        );
        
        // Insert data
        $this->db->insert($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            // Return last inserted ID
            return $this->db->insert_id();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method save_media_category saves media in category
     *
     * @param integer $category_id contains the category's id 
     * @param integer $user_id contains the user's id
     * @param integer $media_id contains the media's id
     * 
     * @return integer with last inserted id or false
     */
    public function save_media_category( $category_id, $user_id, $media_id ) {
        
        $this->db->select('*');
        $this->db->from('lists_meta');
        
        $this->db->where(array(
            'list_id' => $category_id,
            'user_id' => $user_id,
            'body' => $media_id
        ));
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return false;
            
        } else {
        
            // Set data
            $data = array(
                'list_id' => $category_id,
                'user_id' => $user_id,
                'body' => $media_id
            );

            // Insert data
            $this->db->insert('lists_meta', $data);

            if ( $this->db->affected_rows() ) {

                // Return last inserted ID
                return $this->db->insert_id();

            } else {

                return false;

            }
            
        }
        
    }
 
    /**
     * The public method get_categories gets all media's categories
     *
     * @param integer $user_id contains the user id
     * @param string $type contains the list's type
     * 
     * @return object with groups or false
     */    
    public function get_categories( $user_id, $type ) {

        $this->db->select('*');
        $this->db->from($this->table);
        
        $this->db->where(array(
            'user_id' => $user_id,
            'type' => $type
        ));
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $result = $query->result();
            return $result;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method if_user_has_category verifies if user has the category
     *
     * @param integer $user_id contains the user_id
     * @param integer $list contains the list's ID
     * @param string $type contains the list's type
     * 
     * @return boolean true or false
     */
    public function if_user_has_category( $user_id, $list, $type ) {
        
        $this->db->select('*');
        $this->db->from('lists');
        $this->db->where(array(
                'user_id' => $user_id,
                'list_id' => $list,
                'type' => $type
            )
        );
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_category deletes a media's category
     *
     * @param integer $user_id contains the user_id
     * @param integer $list_id contains the list's ID
     * @param string $type contains the list's type
     * 
     * @return boolean true or false
     */
    public function delete_category( $user_id, $list_id, $type ) {
        
        $this->db->delete($this->table, array(
                'list_id' => $list_id,
                'user_id' => $user_id,
                'type' => $type
            )
        );
        
        if ( $this->db->affected_rows() ) {

            // Delete all media category's records
            md_run_hook(
                'delete_media_category',
                array(
                    'list_id' => $list_id
                )
            );
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_from_category deletes a media's from a category
     *
     * @param integer $user_id contains the user_id
     * @param integer $list_id contains the list's ID
     * @param integer $media_id contains the media's ID
     * 
     * @return boolean true or false
     */
    public function delete_from_category( $user_id, $list_id, $media_id ) {
        
        $this->db->delete('lists_meta', array(
                'list_id' => $list_id,
                'user_id' => $user_id,
                'body' => $media_id
            )
        );
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_category_records deletes all category's records
     * 
     * @param integer $user_id contains user_id
     * @param integer $list_id contains the list's id
     * 
     * @return void
     */
    public function delete_category_records( $user_id, $list_id ) {
        
        $this->db->delete('lists_meta', array(
                'list_id' => $list_id
            )
        );
        
    }
    
    /**
     * The public method delete_category_meta_records deletes all category's meta records
     * 
     * @param integer $media_id contains the media's id
     * 
     * @return void
     */
    public function delete_category_meta_records( $media_id ) {
        
        $this->db->select('lists_meta.meta_id');
        $this->db->from('lists_meta');
        $this->db->join('lists', 'lists_meta.list_id=lists.list_id', 'left');
        $this->db->where(array(
            'lists.type' => 'storage',
            'lists_meta.body' => $media_id
        ));
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $results = $query->result();
            
            foreach ( $results as $result ) {
            
                $this->db->delete('lists_meta', array(
                        'meta_id' => $result->meta_id

                    )
                );
                
            }
            
        }
        
    }
    
}

/* End of file Storage_lists_model.php */