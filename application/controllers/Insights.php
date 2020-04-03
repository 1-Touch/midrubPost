<?php
/**
 * Insights Controller
 *
 * PHP Version 5.6
 *
 * Insights contains the Insights class for Insights
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Insights class - contains all methods for Insights
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Insights extends MY_Controller {
    
    private $user_id, $user_role;
    
    public function __construct() {
        parent::__construct();
        
        // Load form helper library
        $this->load->helper('form');
        
        // Load form validation library
        $this->load->library('form_validation');
        
        // Load User Model
        $this->load->model('user');
        
        // Load User Meta Model
        $this->load->model('user_meta');
        
        // Load Plans Model
        $this->load->model('plans');
        
        // Load Tickets Model
        $this->load->model('tickets');
        
        // Load Networks Model
        $this->load->model('networks');
        
        // Load Posts Model
        $this->load->model('posts');
        
        // Load Templates Model
        $this->load->model('templates');
        
        // Load Main Helper
        $this->load->helper('main_helper');
        
        // Load Fourth Helper
        $this->load->helper('fourth_helper');
        
        // Load session library
        $this->load->library('session');
        
        // Load URL Helper
        $this->load->helper('url');
        
        // Load SMTP
        $config = smtp();
        
        // Load Sending Email Class
        $this->load->library('email', $config);
        
        if (isset($this->session->userdata['username'])) {
            
            // Set user_id
            $this->user_id = $this->user->get_user_id_by_username($this->session->userdata['username']);
            
            // Set user_role
            $this->user_role = $this->user->check_role_by_username($this->session->userdata['username']);
            
            // Set user_status
            $this->user_status = $this->user->check_status_by_username($this->session->userdata['username']);
            
        }
        
        // Load language
        if ( file_exists( APPPATH . 'language/' . $this->config->item('language') . '/default_user_lang.php' ) ) {
            $this->lang->load( 'default_user', $this->config->item('language') );
        }
        if( file_exists( APPPATH . 'language/' . $this->config->item('language') . '/default_alerts_lang.php' ) ) {
            $this->lang->load( 'default_alerts', $this->config->item('language') );
        }
        
    }
    
    /**
     * The public method insights_page displays the insights page
     * 
     * @param integer $network_id contains the network_id
     * 
     * @return void
     */
    public function insights_page( $network_id = NULL ) {
        
        // Verify if Insights section is enabled
        if ( !get_option('insights') ) {
            show_404();
        }
        
        if ($network_id) {

            // Verify if session exists and if the user is admin
            $this->if_session_exists($this->user_role, 0);
            
            // Get account data
            $account = $this->networks->get_account( $network_id );
            
            // Verify if account exists
            if ( $account ) {
                
                // Verify if current user has the social account
                if ( $account[0]->user_id == $this->user_id ) {
                    
                    echo json_encode($account[0]);
                    
                }
                
            }
            
        } else {

            // Check if the current user is admin and if session exists
            $this->check_session($this->user_role, 0);
            $this->check_unconfirmed_account();

            // Load User Helper
            $this->load->helper('user_helper');

            // Get all valid network connections
            $networks = $this->networks->get_networks($this->user_id);

            $this->body = 'user/insights';
            $this->content = ['networks' => $networks];
            $this->user_layout();
            
        }
        
    }

    /**
     * The function check_unconfirmed_account checks if the current user's account is confirmed
     * 
     * @return void
     */
    protected function check_unconfirmed_account() {
        
        // This function verifies if user has a confirmed account
        if ($this->user_status == 0) {
            
            redirect('/user/unconfirmed-account');
            
        }
        
    }
    
}

/* End of file Insights.php */
