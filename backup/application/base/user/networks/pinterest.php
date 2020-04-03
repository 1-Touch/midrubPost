<?php
/**
 * Pinterest
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Pinterest
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace MidrubBase\User\Networks;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;

// If session valiable doesn't exists will be called
if ( !isset( $_SESSION) ) {
    session_start();
}

/**
 * Pinterest class - allows users to connect to their Pinterest Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Pinterest implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $app_id, $app_secret, $pinterest, $loginurl;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Require the Pinterest Library
        require_once FCPATH . 'vendor/autoload.php';
        
        // Verify if is enabled the option for App Id and App Secret
        if ( !get_option('pinterest_user_api_key') ) {
            
            // Get Pinterest app_id
            $this->app_id = get_option('pinterest_app_id');
            
            // Get Pinterest app_secret
            $this->app_secret = get_option('pinterest_app_secret');
            
            // Call the Pinterest class
            $this->pinterest = new \DirkGroenen\Pinterest\Pinterest($this->app_id, $this->app_secret);
            
        }
        
    }
    
    /**
     * The public method check_availability checks if the Pinterest api is configured correctly.
     *
     * @return boolean true or empty
     */
    public function check_availability() {
        
        // Verify if is enabled the option for App Id and App Secret
        if ( get_option( 'pinterest_user_api_key' ) ) {
            
            return true;
            
        } else {
            
            if ( ( $this->app_id == '' ) AND ( $this->app_secret == '' ) ) {
                
                return false;
                
            } else {
                
                return true;
                
            }
        
        }
        
    }
    
    /**
     * The public method connect will redirect user to Pinterest login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Verify if is enabled the option for App Id and App Secret
        if (get_option('pinterest_user_api_key')) {
            
            // Check if data was submitted
            if ($this->CI->input->post()) {
                
                // Add form validation
                $this->CI->form_validation->set_rules('app_id', 'App ID', 'trim|required');
                $this->CI->form_validation->set_rules('app_secret', 'App Secret', 'trim|required');
                
                // Get post data
                $app_id = $this->CI->input->post('app_id');
                $app_secret = $this->CI->input->post('app_secret');
                
                // Verify if form data is valid
                if ($this->CI->form_validation->run() == false) {
                    
                    display_mess(45);
                    
                } else {
                    
                    $_SESSION['app_id'] = $app_id;
                    $_SESSION['app_secret'] = $app_secret;
                    
                }
                
            }
            
            if ( !isset($_SESSION['app_id']) ) {
                
                echo get_instance()->ecl('Social_login')->content('App ID', 'App Secret', 'Connect', $this->get_info(), 'pinterest', $this->CI->lang->line('networks_connect_pinterest'));

            } else {
                
                // Call the Pinterest class
                $this->pinterest = new \DirkGroenen\Pinterest\Pinterest($_SESSION['app_id'], $_SESSION['app_secret']);
                $this->loginurl = $this->pinterest->auth->getLoginUrl(str_replace(['http://', 'http://www.'], ['https://', 'https://www.'], base_url()) . 'user/callback/pinterest', array('read_public,write_public'));
                header('Location:' . $this->loginurl); 
                
            }
            
        } else {
            
            // Get redirect url
            $this->loginurl = $this->pinterest->auth->getLoginUrl(str_replace(['http://', 'http://www.'], ['https://', 'https://www.'], base_url()) . 'user/callback/pinterest', array('read_public,write_public'));
            
            // Redirect
            header('Location:' . $this->loginurl);            
            
        }
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     */
    public function save($token = NULL) {
        
        // Verify if code exists
        if ( $this->CI->input->get('code', TRUE) ) {
            
            // Verify if is enabled the option for App Id and App Secret
            if ( get_option('pinterest_user_api_key') ) {
                
                // Verify if session app_id exists
                if ( isset($_SESSION['app_id']) ) {
                    
                    // Call the Pinterest class
                    $this->pinterest = new \DirkGroenen\Pinterest\Pinterest($_SESSION['app_id'], $_SESSION['app_secret']);
                    
                } else {
                    
                    // Display the error message
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);   
                    exit();
                    
                }
                
            }
            
            // Get Pinterest access token
            $token = $this->pinterest->auth->getOAuthToken( $this->CI->input->get('code', TRUE) );
            
            // Set access token
            $this->pinterest->auth->setOAuthToken($token->access_token);
            
            // Get user data
            $me = $this->pinterest->users->me();
            
            // Get my boards
            $boards = $this->pinterest->users->getMeBoards();

            // Verify if boards exists
            if ( !$boards ) {

                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_pinterest_boards_connected') . '</p>', false);
                exit();

            }
            
            // Get access token
            if ( $token->access_token ) {
                
                // Get user's image
                $image = empty($me->image)?'':$me->image;
                
                // Get boards and save
                $y = 0;

                // List all boards
                foreach ($boards as $boardu) {
                    
                    $board = str_replace('https://www.pinterest.com/', '', @$boardu->url);
                    
                    if (substr($board, -1, strlen($board))) {
                        $board = substr($board, 0, -1);
                    }
                    
                    if (!$this->CI->networks->get_network_data('pinterest', $this->CI->user_id, $board)) {
                        
                        if ( !isset($_SESSION['app_id']) ) {
                            
                            $this->CI->networks->add_network('pinterest', @$board, $token->access_token, $this->CI->user_id, '', $boardu->name, $image);
                            
                             // Call the Pinterest class
                            $this->pinterest = new \DirkGroenen\Pinterest\Pinterest($this->app_id, $this->app_secret);
                            
                        } else {
                            
                            $this->CI->networks->add_network('pinterest', @$board, $token->access_token, $this->CI->user_id, '', $boardu->name, $image, '', $_SESSION['app_id'], $_SESSION['app_secret']);
                            unset($_SESSION['app_id']);
                            unset($_SESSION['app_secret']);
                            
                        }
                        
                    }

                    $y++;

                }
                
                // Display the success message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_pinterest_boards_connected') . '</p>', true); 
                
            }
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);   
            
        }
        
    }
    
    /**
     * The public method post publishes posts on Pinterest.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = NULL) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('pinterest', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('pinterest', $user_id, $args['account']);
            
        }
        
        // Verify if image exists
        if ( !$args['img'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('a_photo_is_required_to_publish_here')));

            // Then return false
            return false;
            
        }
        
        // Get user details
        if ($user_details) {
            
            // Verify if is enabled the option for App Id and App Secret
            if ( get_option('pinterest_user_api_key') ) {
                
                    // Call the Pinterest class
                    $this->pinterest = new \DirkGroenen\Pinterest\Pinterest( $user_details[0]->api_key, $user_details[0]->api_secret );
                
            }
            
            try {
                
                // Set access token
                $this->pinterest->auth->setOAuthToken($user_details[0]->token);
                
                // Create the post content
                $data = array(
                    'image_url' => $args['img'][0]['body'],
                    'board' => urldecode($user_details[0]->net_id)
                );
                
                $post = $args['post'];
                
                // Verify if url exists
                if ( $args['url'] ) {
                    $post = str_replace($args['url'], ' ', $post);
                    $data['link'] = short_url($args['url']);
                }
                
                $data['note'] = mb_substr($post, 0, 499);
                
                // Publish the post
                $pub = $this->pinterest->pins->create($data);
                
                if ( $pub ) {
                    
                    return true;
                    
                } else {
                    
                    return false;
                    
                }
                
                
            } catch (Exception $e) {

                // Save the error
                $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());

                // Then return falsed
                return false;

            }
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {

        $checked = '';

        if ( get_option('pinterest_user_api_key') ) {
            $checked = ' checked';
        }
        
        return array(
            'color' => '#be000f',
            'icon' => '<i class="fab fa-pinterest"></i>',
            'api' => array('app_id', 'app_secret'),
            'types' => array('post', 'rss'),
            'extra_content' => '<div class="form-group">'
                    . '<div class="row">'
                        . '<div class="col-lg-10 col-xs-6">'
                            . '<label for="menu-item-text-input">'
                                . 'Any user will use his App ID and App Secret'
                            . '</label>'
                        . '</div>'
                    . '<div class="col-lg-2 col-xs-6">'
                        . '<div class="checkbox-option pull-right">'
                            . '<input id="pinterest_user_api_key" name="pinterest_user_api_key" class="social-option-checkbox" type="checkbox" ' . $checked . '>'
                            . '<label for="pinterest_user_api_key"></label>'
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>'
        );
        
    }
    
    /**
     * The public method preview generates a preview for Pinterest.
     *
     * @param $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
    }
    
}

/* End of file pinterest.php */
