<?php
/**
 * Instagram
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Instagram
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
    
// Limits the maximum execution time to unlimited
set_time_limit(0);

// Sets the default timezone used by all date/time functions
date_default_timezone_set('UTC');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;

/**
 * Instagram_stories class - allows users to connect to their Instagram Account and publish stories
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Instagram implements MidrubBaseUserInterfaces\Networks {
    
    /**
     * Class variables
     */
    protected $CI, $instagram;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
        // Require the vendor autoload
        include_once FCPATH . 'vendor/autoload.php';
        
    }
    
    /**
     * The public method check_availability doesn't check if Instagram api was configured correctly.
     *
     * @return will be true
     */
    public function check_availability() {
        
        return true;
        
    }
    
    /**
     * The public method connect will show a form where the user can add the Instagram's username and password.
     *
     * @return void
     */
    public function connect() {
        
        if ( $this->CI->input->post() ) {
            
            $this->CI->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->CI->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->CI->form_validation->set_rules('proxy', 'Proxy', 'trim');
            
            // Get data
            $username = $this->CI->input->post('username');
            $password = $this->CI->input->post('password');
            $proxy = $this->CI->input->post('proxy');
            
            if ( $this->CI->form_validation->run() == false ) {
                
                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);
                
            } else {

                $check = new \InstagramAPI\Instagram(false, false);

                if ($proxy) {

                    $check->setProxy($proxy);
                } else {

                    $user_proxy = $this->CI->user->get_user_option($this->CI->user_id, 'proxy');

                    if ($user_proxy) {

                        $check->setProxy($user_proxy);

                    } else {

                        $proxies = @trim(get_option('instagram_proxy'));

                        if ($proxies) {

                            $proxies = explode('<br>', nl2br($proxies, false));

                            $rand = rand(0, count($proxies));

                            if (@$proxies[$rand]) {

                                $check->setProxy($proxies[$rand]);

                            }

                        }

                    }

                }

                try {

                    $check->login($username, $password);

                    // Verify if account was already saved
                    if (!$this->CI->networks->get_network_data('instagram', $this->CI->user_id, $username)) {

                        $this->CI->networks->add_network('instagram', $username, $password, $this->CI->user_id, '', $username, '', $proxy);

                        // Display the success message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true);
                    
                    } else {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', false);
                    
                    }

                } catch (Exception $e) {

                    $check = $e->getMessage();

                    if (preg_match('/required/i', $check)) {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_please_go_to_instagram') . '</p>', false);
                    
                    } else if (preg_match('/password/i', $check)) {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_username_or_password_incorrect') . '</p>', false);
                    
                    } else {

                        // Display the error message
                        echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $check . '</p>', false);
                    
                    }

                }
                
            }
            
        } else {
            
            // Display the login form
            echo get_instance()->ecl('Social_login')->content('Username', 'Password', 'Connect', $this->get_info(), 'instagram', $this->CI->lang->line('networks_connect_instagram_accounts'));
            
        }
        
    }
    
    /**
     * The public method save was added only to follow the interface.
     *
     * @param $token contains the token for some social networks
     *
     * @return void
     */
    public function save($token = null) {
        
    }
    
    /**
     * The public method post publishes posts on Instagram.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     *
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Verify if user_id exists
        if ( $user_id ) {
            
            // Get account details
            $user_details = $this->CI->networks->get_network_data('instagram', $user_id, $args['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $user_details = $this->CI->networks->get_network_data('instagram', $user_id, $args['account']);
            
        }
        
        // Verify if image exists
        if ( !$args['img'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_a_photo_is_required_to_publish_here')));
            
            // Then return false
            return false;
            
        }
        
        // Verify if the image is loaded on server
        $im = explode(base_url(), $args['img'][0]['body']);
        
        // If image is on server
        if ( @$im[1] ) {
            
            // Get the path
            $filename = str_replace(base_url(), FCPATH, $args['img'][0]['body']);
            
            // Verify format
            if ( exif_imagetype($filename) != IMAGETYPE_JPEG ) {
                
                $in = get($args['img'][0]['body']);
                
                if ($in) {
                    
                    $filename = FCPATH . 'assets/share/' . uniqid() . time() . '.jpg';
                    
                    file_put_contents($filename, $in);
                    
                    if ( file_exists($filename) ) {
                        
                        $file = $filename;
                        
                    } else {
                        
                        return false;
                        
                    }
                    
                } else {
                    
                    return false;
                    
                }
                
            }
            
            $file = $filename;
            
        } else {
            
            $in = get($args['img'][0]['body']);
            
            if ( $in ) {
                
                $filename = FCPATH . 'assets/share/' . uniqid() . time() . '.jpg';
                
                // Save image on server
                file_put_contents($filename, $in);
                
                // Verify if image was saved
                if ( file_exists($filename) ) {
                    
                    $file = $filename;
                    
                } else {
                    
                    return false;
                    
                }
                
            } else {
                
                return false;
                
            }
            
        }
        
        // Get the post content
        $post = $args['post'];
        
        // If title is not empty
        if ( $args['title'] ) {
            
            $post = $args['title'] . ' ' . $post;
            
        }
        
        // Verify if url exists
        if ( $args['url'] ) {
            $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
        }
        
        // Set the photo
        $photo = $file;
        
        // Set the caption
        $caption = $post;
        
        // Call the Instagram class
        $check = new \InstagramAPI\Instagram(false, false);
        
        // Verify if for this account was added a proxy
        if ( trim($user_details[0]->secret) ) {
            
            $check->setProxy($user_details[0]->secret);
            
        } else {
            
            // Get proxy if exists
            $user_proxy = $this->CI->user->get_user_option($user_id, 'proxy');
            
            // Verify if proxy exists
            if ( $user_proxy ) {
                
                $check->setProxy($user_proxy);
                
            } else {
                
                // Get global proxy
                $proxies = @trim(get_option('instagram_proxy'));
                
                // Verify if proxy exists
                if ($proxies) {
                    
                    $proxies = explode('<br>', nl2br($proxies, false));
                    
                    $rand = rand(0, count($proxies));
                    
                    if ( @$proxies[$rand] ) {
                        
                        $check->setProxy($proxies[$rand]);
                        
                    }
                    
                }
                
            }
            
        }
        
        // Login
        $check->login($user_details[0]->net_id, $user_details[0]->token);
        
        // Prepare the photo
        $resizer = new \InstagramAPI\Media\Photo\InstagramPhoto($photo);
        
        // Upload the photo
        try {
            
            $myphoto = $check->timeline->uploadPhoto($resizer->getFile(), ['caption' => $caption]);
            
            if ( $myphoto ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        } catch (Exception $e) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', $e->getMessage());
            
            // Then return false
            return false;
            
        }
        
    }
    
    /**
     * The public method get_info displays information about this class.
     *
     * @return array with network's data
     */
    public function get_info() {

        $value = '';

        if ( get_option('instagram_proxy') ) {
            $value = get_option('instagram_proxy');
        }

        return array(
            'color' => '#c9349a',
            'icon' => '<i class="icon-social-instagram"></i>',
            'api' => array(),
            'types' => array('post', 'rss'),
            'extra_content' => '<div class="form-group">'
                    . '<div class="row">'
                        . '<div class="col-lg-10 col-xs-6">'
                            . '<label for="menu-item-text-input">'
                                . 'Proxy'
                            . '</label>'
                        . '</div>'
                    . '<div class="col-lg-2 col-xs-6">'
                        . '<div class="checkbox-option pull-right">'
                            . '<textarea class="optionvalue form-control social-text-input" id="instagram_proxy" placeholder="Enter one IP per line(example: http://00.00.00.00:(port) or with ssl)">'
                                . $value
                            . '</textarea>'
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>'
        );
        
    }
    
    /**
     * The public method preview generates a preview for Instagram.
     *
     * @param $args contains the img or url.
     *
     * @return array with html
     */
    public function preview($args) {
    }
    
}

/* End of file Instagram.php */
