<?php
/**
 * Teams Roles Model
 *
 * PHP Version 7.2
 *
 * Teams_roles contains the Teams Roles Model
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
 * Teams_roles class - operates the teams_roles table
 *
 * @since 0.0.7.9
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Teams_roles extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'teams_roles';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        // Get teams_roles table
        $teams_roles = $this->db->table_exists('teams_roles');
        
        if ( !$teams_roles ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `teams_roles` (
                `role_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `user_id` int(11) NOT NULL,
                `role` varchar(250) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

            $this->db->query('CREATE TABLE IF NOT EXISTS `teams_roles_permission` (
                `permission_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `role_id` int(20) NOT NULL,
                `permission` varchar(250) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
                            
            $this->db->query('ALTER TABLE `teams` ADD role_id BIGINT(20) AFTER member_email');
            $this->db->query('ALTER TABLE `teams` DROP COLUMN role');
            
        }
        
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
}

/* End of file teams_roles.php */