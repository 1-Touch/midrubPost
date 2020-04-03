<?php
/**
 * Ads Account Model
 *
 * PHP Version 7.2
 *
 * ads_account_model file contains the Ads Account Model
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
 * Ads_account_model class - operates the ads_account table.
 *
 * @since 0.0.7.6
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Ads_account_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'ads_account';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        $ads_account = $this->db->table_exists('ads_account');
        
        if ( !$ads_account ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `ads_account` (
                              `ads_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` int(11) NOT NULL,
                              `network_id` bigint(20) NOT NULL,
                              `network` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method save_ads_account saves a new ads account
     *
     * @param integer $user_id contains the user id
     * @param integer $network_id contains the network_id
     * @param string $network contains the network
     * 
     * @since 0.0.7.6
     * 
     * @return integer with last inserted id or false
     */
    public function save_account($user_id, $network_id, $network)
    {

        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array(
            'user_id' => $user_id,
            'network' => $network
        ));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->result();

            $this->db->where(array(
                'user_id' => $user_id,
                'network' => $network
            ));

            $data = array(
                'network_id' => $network_id
            );

            $this->db->update($this->table, $data);

            if ($this->db->affected_rows()) {

                return $result[0]->ads_id;

            } else {

                return false;
            }

        } else {

            // Add new row
            $data = array(
                'user_id' => $user_id,
                'network_id' => $network_id,
                'network' => $network
            );

            $this->db->insert($this->table, $data);

            if ($this->db->affected_rows()) {

                return $this->db->insert_id();

            } else {

                return false;

            }

        }

    }
    
    /**
     * The public method get_account gets selected ad account
     *
     * @param integer $user_id contains the user id
     * @param string $network contains the network
     * 
     * @since 0.0.7.6
     * 
     * @return integer with last inserted id or false
     */
    public function get_account($user_id, $network) {

        $this->db->select('ads_networks.*');
        $this->db->from($this->table);
        $this->db->join('ads_networks', 'ads_networks.network_id=ads_account.network_id', 'left');
        $this->db->where(array(
            'ads_account.user_id' => $user_id,
            'ads_account.network' => $network
        ));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $result = $query->result();

            return $result;

        } else {

            return false;
        }

    }
    
    /**
     * The public method save_ads_account deletes an ads account
     *
     * @param integer $user_id contains the user id
     * @param integer $network_id contains the network_id
     * 
     * @return boolean true or false
     */
    public function delete_account( $user_id, $network_id ) {

        // Delete the selected ad account
        $this->db->delete($this->table, array(
                'user_id' => $user_id,
                'network_id' => $network_id
            )
        );
        
        if ( $this->db->affected_rows() ) {

            return true;

        } else {

            return false;

        }
        
    }

    /**
     * The public method delete_account_records deletes user's records in app
     *
     * @param integer $user_id contains the user id
     * 
     * @return void
     */
    public function delete_account_records( $user_id ) {
        
        $this->db->select('network_id, net_id');
        $this->db->from('ads_networks');
        $this->db->where(array(
            'user_id' => $user_id
        ));

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $results = $query->result();

            if ( $results ) {

                foreach ( $results as $result ) {

                    $this->delete_account( $user_id, $result->network_id );

                    if ( file_exists(MIDRUB_FACEBOOK_ADS_APP_PATH . '/cache/' . $result->net_id . '-overview.json') ) {
                        unlink(MIDRUB_FACEBOOK_ADS_APP_PATH . '/cache/' . $result->net_id . '-overview.json');
                    }

                    if ( file_exists(MIDRUB_FACEBOOK_ADS_APP_PATH . '/cache/' . $result->net_id . '-campaigns.json') ) {
                        unlink(MIDRUB_FACEBOOK_ADS_APP_PATH . '/cache/' . $result->net_id . '-campaigns.json');
                    }

                    if ( file_exists(MIDRUB_FACEBOOK_ADS_APP_PATH . '/cache/' . $result->net_id . '-adsets.json') ) {
                        unlink(MIDRUB_FACEBOOK_ADS_APP_PATH . '/cache/' . $result->net_id . '-adsets.json');
                    }

                    if ( file_exists(MIDRUB_FACEBOOK_ADS_APP_PATH . '/cache/' . $result->net_id . '-ads.json') ) {
                        unlink(MIDRUB_FACEBOOK_ADS_APP_PATH . '/cache/' . $result->net_id . '-ads.json');
                    }

                }

            }

        }
        
    }    

}

/* End of file ads_account_model.php */