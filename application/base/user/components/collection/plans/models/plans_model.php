<?php
/**
 * Plans Model
 *
 * PHP Version 7.3
 *
 * Plans_model file contains the Plans Model
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
 * Plans_model class - operates the plans table.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Plans_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'plans';

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
     * The function get_user_plan get user plan by user's id
     *
     * @param $user_id contains the user's id
     * 
     * @return array with plan's data or false
     */
    public function get_user_plan($user_id) {

        $this->db->select('*');
        $this->db->from('users_meta');
        $this->db->where(
            array(
                'user_id' => $user_id
            )
        );
        
        $this->db->like(
            array(
                'meta_name' => 'plan'
            )
        );

        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) {

            return $query->result_array();

        } else {

            return false;
        
        }

    }
    
    /**
     * The function change_plan changes the user's plan
     *
     * @param $plan contains the plan's id
     * @param $user_id contains user's id
     * 
     * @return boolean true of the plan was changed or false
     */
    public function change_plan($plan, $user_id) {

        // Get the plan's period
        $period = $this->get_plan_period($plan);

        // Will be changed the plan
        $this->db->select('*');
        $this->db->from('users_meta');
        $this->db->where(array(
            'user_id' => $user_id,
            'meta_name' => 'plan'
        ));

        $this->db->limit(1);

        $query = $this->db->get();
        
        // Verify if user has a plan
        if ( $query->num_rows() > 0 ) {

            // Get plan
            $plans = $query->result_array();

            // Verify if user has same plan
            if ( $plans[0]['meta_value'] != $plan ) {

                // Renew period
                $data = array(
                    'meta_value' => $plan
                );

                $this->db->where(
                    array(
                        'user_id' => $user_id,
                        'meta_name' => 'plan'
                    )
                );

                $this->db->update('users_meta', $data);
                $this->db->select('*');
                $this->db->from('users_meta');
                $this->db->where(
                    array(
                        'user_id' => $user_id,
                        'meta_name' => 'plan_end'
                    )
                );

                $this->db->limit(1);
                
                $query = $this->db->get();
                
                if ( $query->num_rows() > 0 ) {

                    $date = strtotime('+' . $period . ' day', time());
                    $plan_end = date('Y-m-d H:i:s', $date);
                    $data = array(
                        'user_id' => $user_id,
                        'meta_name' => 'plan_end',
                        'meta_value' => $plan_end
                    );
                    
                    $this->db->where(
                        array(
                            'user_id' => $user_id,
                            'meta_name' => 'plan_end'
                        )
                    );
                    
                    $this->db->update('users_meta', $data);

                } else {

                    $date = strtotime('+' . $period . ' day', time());
                    $plan_end = date('Y-m-d H:i:s', $date);
                    $data = array(
                        'user_id' => $user_id,
                        'meta_name' => 'plan_end',
                        'meta_value' => $plan_end
                    );
                    
                    $this->db->insert('users_meta', $data);
                
                }
                
                return true;

            } else {

                // Get time based on period
                $date = strtotime('+' . $period . ' day', time());

                // Sets when plan will end
                $plan_end = date('Y-m-d H:i:s', $date);

                // Check if the user plan is not ended yet
                $renew = $this->check_if_plan_ended($user_id);

                if ( $renew ) {

                    if ( $renew < time() + 432000 ) {
                        $renew = $date + ($renew - time());
                        $plan_end = date('Y-m-d H:i:s', $renew);
                    }

                }

                $data = array(
                    'user_id' => $user_id,
                    'meta_name' => 'plan_end',
                    'meta_value' => $plan_end
                );

                $this->db->where(
                    array(
                        'user_id' => $user_id,
                        'meta_name' => 'plan_end'
                    )
                );

                $this->db->update('users_meta', $data);

                if ( $this->db->affected_rows() ) {
            
                    return true;
                    
                }

            }

        } else {

            $data = array(
                'user_id' => $user_id,
                'meta_name' => 'plan',
                'meta_value' => $plan
            );

            $this->db->insert('users_meta', $data);

            // Calculate time
            $date = strtotime('+' . $period . ' day', time());
            
            // Sets when the plan ends
            $plan_end = date('Y-m-d H:i:s', $date);

            // Set new plan's data
            $data = array(
                'user_id' => $user_id,
                'meta_name' => 'plan_end',
                'meta_value' => $plan_end
            );
            
            $this->db->insert('users_meta', $data);

            if ( $this->db->affected_rows() ) {
            
                return true;
                
            }
        
        }
        
        return false;
    
    }
    
    /**
     * The function get_plan_period return plan's period by plan_id
     *
     * @param $plan_id contains the plan's id
     * 
     * @return array with plan's period
     */
    public function get_plan_period($plan_id) {

        $this->db->select('*');
        $this->db->from('plans');
        $this->db->where('plan_id', $plan_id);
        $this->db->limit(1);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 ) {

            $result = $query->result_array();
            return $result[0]['period'];

        }

    }
    
    /**
     * The function check_if_plan_ended checks if user's plan has been ended
     *
     * @param $user_id contains user's id
     * 
     * @return string with date when plan ented or false
     */
    public function check_if_plan_ended($user_id) {
            
        // Get the user's plan
        $user_plan = $this->get_user_plan($user_id);
        
        // Verify if user has plan
        if ( $user_plan ) {

            foreach ( $user_plan as $plan ) {

                // Default time end
                $plan_end = time();

                if ( $plan['meta_name'] == 'plan_end' ) {
                    $plan_end = strtotime($plan['meta_value']);
                    return $plan_end;
                }

            }

        }

        return false;
    }
    
}

/* End of file plans_model.php */