<?php
/**
 * Userarea Controller
 *
 * PHP Version 5.6
 *
 * Userarea contains the Userarea class for user account
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
 * Userarea class - contains all metods and pages for user account.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Userarea extends MY_Controller {

    public $user_id, $user_role, $user_status, $socials = array();

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
        
        // Load Posts Model
        $this->load->model('posts');
        
        // Load Plans Model
        $this->load->model('plans');
        
        // Load Urls Model
        $this->load->model('urls');
        
        // Load Networks Model
        $this->load->model('networks');
        
        // Load Campaigns Model
        $this->load->model('campaigns');
        
        // Load Notifications Model
        $this->load->model('notifications');
        
        // Load Options Model
        $this->load->model('options');
        
        // Load session library
        $this->load->library('session');
        
        // Load URL Helper
        $this->load->helper('url');
        
        // Load Main Helper
        $this->load->helper('main_helper');
        
        // Load User Helper
        $this->load->helper('user_helper');
        
        // Load Alerts Helper
        $this->load->helper('alerts_helper');
        
        // Load SMTP
        $config = smtp();
        
        // Load Sending Email Class
        $this->load->library('email', $config);
        
        // Load Gshorter library
        $this->load->library('gshorter');
        
        // Check if session username exists
        if (isset($this->session->userdata['username'])) {
            
            // Set user_id
            $this->user_id = $this->user->get_user_id_by_username($this->session->userdata['username']);
            
            // Set user_role
            $this->user_role = $this->user->check_role_by_username($this->session->userdata['username']);
            
            // Set user_status
            $this->user_status = $this->user->check_status_by_username($this->session->userdata['username']);
            
        }
        
        // Get user language
        $user_lang = get_user_option('user_language');
        
        // Verify if user has selected a language
        if ( $user_lang ) {
            $this->config->set_item('language', $user_lang);
        }
        
        // Load language
        $this->lang->load( 'default_user', $this->config->item('language') );
        $this->lang->load( 'default_alerts', $this->config->item('language') );
        $this->lang->load( 'default_networks', $this->config->item('language') );
        
    }

    /**
     * The public method ajax is universal caller for default user ajax calls
     * 
     * @param string $name contains the helper name
     * 
     * @return void
     */
    public function ajax($name) {
        
        // Verify if session exists and if the user is admin
        $this->if_session_exists($this->user_role,0);
        
        // Verify if helper exists
        if ( file_exists( APPPATH . 'helpers/' . $name . '_helper.php' ) ) {
            
            // Get action's get input
            $action = $this->input->get('action');

            if ( !$action ) {
                $action = $this->input->post('action');
            }

            try {

                // Load Helper
                $this->load->helper($name . '_helper');
                
                // Call the function
                $action();

            } catch (Exception $ex) {

                $data = array(
                    'success' => FALSE,
                    'message' => $ex->getMessage()
                );

                echo json_encode($data);

            }
            
        } else {
            
            show_error('Invalid parameters');
            
        }
        
    } 

    /**
     * The public method tools displays the tools page.
     * 
     * @param string $name contains the tool's name
     * 
     * @return void
     */
    public function tools($name = NULL) {
        
        // Check if the current user is admin and if session exists
        $this->check_session($this->user_role, 0);
        
        // Verify if account is confirmed
        $this->_check_unconfirmed_account();
        
        // Verify if the tool is enabled
        if ( $this->options->check_enabled('enable_tools_page') == false ) {
            
            show_404();
            
        }
        
        // Require the Tools interface
        require_once APPPATH . 'interfaces/Tools.php';
        
        if ( $name ) {
            
            if ( file_exists(APPPATH . 'tools' . '/' . $name . '/' . $name . '.php') ) {
                
                // Require the tool
                require_once APPPATH . 'tools' . '/' . $name . '/' . $name . '.php';
                
                // Call the class
                $class = ucfirst(str_replace('-', '_', $name));
                $get = new $class;
                
                
                $page = $get->page(['user_id' => $this->user_id]);
                
                $info = $get->check_info();
                
                // Load view/user/tool.php file
                $this->body = 'user/tool';
                $this->content = ['info' => $info, 'page' => $page];
                $this->user_layout();
                
            } else {
                
                echo display_mess(47);
                
            }
            
        } else {
            
            // Get all available tools.
            $classes = [];
            
            foreach (scandir(APPPATH . 'tools') as $dirname) {
                
                if ( is_dir(APPPATH . 'tools' . '/' . $dirname) && ($dirname != '.') && ($dirname != '..') ) {
                    
                    require_once APPPATH . 'tools' . '/' . $dirname . '/' . $dirname . '.php';
                    
                    $class = ucfirst(str_replace('-', '_', $dirname));
                    
                    $get = new $class;
                    
                    $classes[] = $get->check_info();
                    
                }
                
            }
            
            // Get favourites tools
            $get_favourites = $this->user_meta->get_favourites($this->user_id);
            
            $favourites = '';
            
            if ( $get_favourites ) {
                
                $favourites = unserialize($get_favourites[0]->meta_value);
                
            }
            
            // Get all options
            $options = $this->options->get_all_options();
            
            // Load view/user/tools.php file
            $this->body = 'user/tools';
            $this->content = ['tools' => $classes, 'favourites' => $favourites, 'options' => $options];
            $this->user_layout();
            
        }
        
    }

    /**
     * The public method connect redirects user to the login page
     *
     * @param string $networks contains the name of network
     * 
     * @return void
     */
    public function connect($network) {

        // Connects user to his social account
        $this->check_session($this->user_role, 0);
        
        // Load plan features
        $limit_accounts = $this->plans->get_plan_features($this->user_id, 'network_accounts');
        
        // Get all accounts connected for social network
        $allaccounts = $this->networks->get_all_accounts($this->user_id, $network);
        
        if ( !$allaccounts ) {
            $allaccounts = array();
        }
        
        // Verify if user has reached his plan
        if ( $limit_accounts <= count($allaccounts) && !$this->input->get('account', TRUE) ) {
            
            // Display the error message
            echo $this->ecl('Social_login_connect')->view($this->lang->line('social_connector'), '<p class="alert alert-error">' . $this->lang->line('reached_maximum_number_allowed_accounts') . '</p>', false);
            exit();
            
        }

        // Require the base class
        $this->load->file(APPPATH . 'base/main.php');
        
        // Verify if the network exists
        if ( file_exists(APPPATH . 'base/user/networks/' . $network . '.php') ) {
            
            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Networks',
                ucfirst($network)
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Try to connect
            (new $cl())->connect();
            
        } else {
            
            display_mess(47);
            
        }
        
    }

    /**
     * The public method callback saves token from a social network
     *
     * @param string $network contains the network name
     * 
     * @return void
     */
    public function callback($network) {
        
        // Check if the current user is admin and if session exists
        $this->check_session($this->user_role, 0);
        
        // Require the base class
        $this->load->file(APPPATH . 'base/main.php');
        
        // Verify if the network exists
        if ( file_exists(APPPATH . 'base/user/networks/' . $network . '.php') ) {
            
            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Networks',
                ucfirst($network)
            );

            // Implode the array above
            $cl = implode('\\', $array);
            
            // Save token
            (new $cl())->save();
            
        } else {
            
            display_mess(47);
            
        }
        
    }

    /**
     * The public method save_token saves token and user information from his social account
     *
     * @param string $network contains the network name
     * @param string $token contains the token
     * 
     * @return void
     */
    public function save_token($network, $token) {
        
        // Check if the current user is admin and if session exists
        $this->check_session($this->user_role, 0);
        
        // Clean the token
        $token = str_replace('-', '/', $token);
        $clean_token = $this->security->xss_clean(base64_decode($token));

        // Require the base class
        $this->load->file(APPPATH . 'base/main.php');
        
        // Verify if the network exists
        if ( file_exists(APPPATH . 'base/user/networks/' . $network . '.php') ) {
            
            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Networks',
                ucfirst($network)
            );

            // Implode the array above
            $cl = implode('\\', $array);
            
            if ( (new $cl())->save($clean_token) ) {
                
                echo 1;
                
            } else {
                
                echo display_mess(1);
                
            }
            
        } else {
            
            echo display_mess(1);
            
        }
        
    }

    /**
     * The public method short redirects to original url
     *
     * @param string $param contains the param from url
     * 
     * @return void
     */
    public function short($param) {
        
        $un = $this->security->xss_clean(base64_decode(str_replace('-', '/', $param)));
        
        if ( is_numeric($un) ) {
            
            $original = $this->urls->get_original_url($un);
            
            if ( $original ) {
                
                redirect($original);
                
            } else {
                
                echo display_mess(47);
                
            }
            
        } else {
            
            show_404();
            
        }
        
    }
    
    /**
     * The public method tool loads the tool functions
     * 
     * @param string $name contains the tool's name
     * 
     * @return void
     */
    public function tool($name) {
        
        if ( $name ) {
            
            if ( file_exists(APPPATH . 'tools' . '/' . $name . '/functions.php') ) {
                
                require_once APPPATH . 'tools' . '/' . $name . '/functions.php';
                
            } else {
                
                echo display_mess(47);
                
            }
            
        }
        
    }

    /**
     * The public method delete_user_account deletes current user account
     * 
     * @return void
     */
    public function delete_user_account() {
        
        // Verify if session exists and if the user is admin
        $this->if_session_exists($this->user_role,0);
        
        // Require the base class
        $this->load->file(APPPATH . 'base/main.php');

        defined('MIDRUB_BASE_USER') OR define('MIDRUB_BASE_USER', APPPATH . 'base/user/');

        // List all apps
        foreach (glob(APPPATH . 'base/user/apps/collection/*', GLOB_ONLYDIR) as $dir) {

            $app_dir = trim(basename($dir) . PHP_EOL);

            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Apps',
                'Collection',
                ucfirst($app_dir),
                'Main'
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Delete user's data
            (new $cl())->delete_account($this->user_id);

        }
        
        // Delete connected social accounts
        $this->networks->delete_network('all', $this->user_id);
        
        // Load Fourth Helper
        $this->load->helper('fourth_helper');
        
        // Load Tickets Model
        $this->load->model('tickets');
        
        // Delete tickets
        $this->tickets->delete_tickets($this->user_id);

        // Load Referrals model
        $this->load->model('referrals');

        // Delete referrals
        $this->referrals->delete_referrals($this->user_id);
        
        // Load Media Model
        $this->load->model('media');
        
        // Get all user medias
        $getmedias = $this->media->get_user_medias($this->user_id, 0, 1000000);
      
        // Verify if user has media and delete them
        if ( $getmedias ) {
            
            // Load Media Helper
            $this->load->helper('media_helper');
            
            foreach( $getmedias as $media ) {
                delete_media($media->media_id, false);
            }
            
        }
        
        // Load Team Model
        $this->load->model('team');
        
        // Delete the user's team
        $this->team->delete_members( $this->user_id );
        
        // Load Activities Model
        $this->load->model('activities');
        
        // Delete the user's activities
        $this->activities->delete_activity( $this->user_id, 0 ); 
        
        // Delete user account
        if ( $this->user->delete_user($this->user_id) ) {
            
            // Deletes user's session
            $this->session->unset_userdata('username');
            $this->session->unset_userdata('member');
            $this->session->unset_userdata('autodelete');
            
            echo json_encode(array(
                'success' => TRUE,
                'message' => $this->lang->line('mm64')
            ));  
            
        } else {
            
            echo json_encode(array(
                'success' => FALSE,
                'message' => $this->lang->line('mm65')
            )); 
            
        }
        
    }
    
    /**
     * The private method _check_unconfirmed_account checks if the current user's account is confirmed
     * 
     * @return void
     */
    private function _check_unconfirmed_account() {
        
        if ( get_user_option('nonpaid') ) {
            
            redirect('/auth/upgrade');
            
        }
        
        if ( $this->user_status == 0 ) {
            
            redirect('/auth/confirmation');
            
        }
        
    }
    
    /**
     * The public method search_posts searches posts
     *
     * @param string $network displays accounts per network
     * @param integer $page is the number of page
     * @param string $search contains search key
     * 
     * @return void
     */
    public function show_accounts($network, $page, $search = null) {
        
        // Verify if session exists and if the user is admin
        $this->if_session_exists($this->user_role,0);
        
        $limit = 10;
        
        $page--;
        
        $total = $this->networks->count_all_accounts($this->user_id, $network, $search);
        
        $get_accounts = ($search) ? $this->networks->get_accounts($this->user_id, $network, $page * $limit, $search) : $this->networks->get_accounts($this->user_id, $network, $page * $limit);
        
        if ( $get_accounts ) {
            
            $data = ['total' => $total, 'accounts' => $get_accounts];
            echo json_encode($data);
            
        }
        
    }

    /**
     * The public method search_accounts searches accounts on database
     *
     * @param string $network is the network name
     * @param string $search is the search key
     * 
     * @return void
     */
    public function search_accounts($network, $search = null) {
        
        // Verify if session exists and if the user is admin
        $this->if_session_exists($this->user_role,0);
        
        $limit = 10;
        
        $page = 0;
        
        $search = str_replace('-', '/', $search);
        
        $search = $this->security->xss_clean(base64_decode($search));
        
        $total = $this->networks->count_all_accounts($this->user_id, $network, $search);
        
        $get_accounts = ($search) ? $this->networks->get_accounts($this->user_id, $network, 0, $search) : $this->networks->get_accounts($this->user_id, $network);
        
        if ( $get_accounts ) {
            
            $data = ['total' => $total, 'accounts' => $get_accounts];
            echo json_encode($data);
            
        }
        
    }
    
}

/* End of file Userarea.php */
