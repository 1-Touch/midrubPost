<?php
/**
 * Statistics Controller
 *
 * PHP Version 5.6
 *
 * Statistics contains the Statistics class for Admin Statistics
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
 * Statistics class - contains all methods for Admin Statistics
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Statistics extends MY_Controller {
    
    /**
     * Class variables
     */   
    private $user_id, $user_role;
    
    /**
     * Initialise the Statistics controller
     */
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
        
        // Load Tickets Model
        $this->load->model('tickets');
        
        // Load Posts Model
        $this->load->model('posts');
        
        // Load Templates Model
        $this->load->model('templates');
        
        // Load Main Helper
        $this->load->helper('main_helper');
        
        // Load Admin Helper
        $this->load->helper('admin_helper');
        
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
        
        if ( isset($this->session->userdata['username']) ) {
            
            // Set user_id
            $this->user_id = $this->user->get_user_id_by_username($this->session->userdata['username']);
            
            // Set user_role
            $this->user_role = $this->user->check_role_by_username($this->session->userdata['username']);
            
            // Set user_status
            $this->user_status = $this->user->check_status_by_username($this->session->userdata['username']);
            
        }
        
        // Verify if exist a customized language file
        if ( file_exists( APPPATH . 'language/' . $this->config->item('language') . '/default_alerts_lang.php') ) {
            
            // load the alerts language file
            $this->lang->load( 'default_alerts', $this->config->item('language') );
            
        }
        
        // Verify if exist a customized language file
        if ( file_exists( APPPATH . 'language/' . $this->config->item('language') . '/default_admin_lang.php') ) {
            
            // load the admin language file
            $this->lang->load( 'default_admin', $this->config->item('language') );
            
        }
        
    }
    
    /**
     * The public method all_tickets displays the admin's tickets page
     * 
     * @param integer $period contains the period of time
     * 
     * @return void
     */
    public function user_activities( $period ) {
        
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        
        // Get statistics template
        $this->body = 'admin/statistics';
        
        // Load the admin layout
        $this->admin_layout();
        
    }
    
    /**
     * The public method user_data display's the users activity
     * 
     * @param integer $type contains the activity type
     * @param integer $user_id contains the user_id
     * @param integer $page contains the page's number
     * 
     * @return void
     */
    public function user_data( $type, $user_id, $page ) {
        
        // Verify if session exists and if the user is admin
        $this->if_session_exists($this->user_role,1);
        
        $limit = 10;
        $page--;
        $current_page = $page * $limit;
        
        // By the parameter $type will be displayed activity
        switch ( $type ) {
            case '1':
                
                // Get number of total posts
                $total = $this->posts->count_all_posts($user_id);
                
                // Get posts based on pagination
                $getposts = $this->posts->get_posts( $user_id, $current_page, $limit );
                
                // If the user has posts, will be created a json object
                if ( $getposts ) {
                    
                    $data = ['total' => $total, 'date' => time(), 'posts' => $getposts];
                    echo json_encode($data);
                    
                }
                
                break;
                
            case '2':

                // Verify if the app exists
                if (is_dir(APPPATH . '/apps/collection/posts/')) {

                    // Require the Apps class
                    $this->load->file(APPPATH . '/apps/main.php');

                    // Call the apps class
                    new MidrubApps\MidrubApps();

                    $this->load->ext_model( APPPATH . 'apps/collection/posts/models/', 'Rss_model', 'rss_model' );

                }

                // Get total number of rss feeds
                $total = $this->rss_model->get_rss_feeds( $user_id, 0, 0, '' );

                // Get the RSS's feeds by page
                $get_feeds = $this->rss_model->get_rss_feeds( $user_id, ($page * $limit), $limit, '' );
                
                // If the user has RSS Feeds, will be created a json object
                if ($get_feeds) {
                    
                    $data = ['total' => $total, 'feeds' => $get_feeds];
                    echo json_encode($data);
                    
                }
                
                break;
                
            case '3':
                
                // Gets number of total Email Templates
                $total = $this->templates->get_all_templates( $user_id, 0, 0, 1 );
                
                // Gets Email Templates based on pagination 
                $get_templates = $this->templates->get_all_templates($user_id, $current_page, $limit);
                
                // If the user has Email Templates, will be created a json object
                if ( $get_templates ) {
                    
                    $data = ['total' => $total, 'templates' => $get_templates];
                    echo json_encode($data);
                    
                }
                
                break;
                
        }
        
    }    
    
}

/* End of file Statistics.php */
