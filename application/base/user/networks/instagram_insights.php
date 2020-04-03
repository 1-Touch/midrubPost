<?php
/**
 * Instagram_insights
 *
 * PHP Version 7.2
 *
 * Connect and get Instagram's insights
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

/**
 * Instagran_insights class - allow to get Instagram's insights
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Instagram_insights implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    public $fb, $app_id, $app_secret;

    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Facebook App ID
        $this->app_id = get_option('instagram_insights_app_id');
        
        // Get the Facebook App Secret
        $this->app_secret = get_option('instagram_insights_app_secret');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
            
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';

        // Set required args
        $args = array(
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => 'v3.3',
            'default_access_token' => '{access-token}',
        );


        if ( ($this->app_id != '') && ( $this->app_secret != '') ) {

            // Load the Facebook Class
            $this->fb = new \Facebook\Facebook($args);

        }
        
    }

    /**
     * The public method check_availability checks if the Facebook api is configured correctly.
     *
     * @return boolean true or false
     */
    public function check_availability() {
        
        // Verify if app_id and app_secret exists
        if ( ($this->app_id != '') && ( $this->app_secret != '') ) {
            
            return true;
            
        } else {

            return false;

        }
        
    }

    /**
     * The public method connect will redirect user to facebook login page.
     * 
     * @return void
     */
    public function connect() {
            
        // Redirect use to the login page
        $helper = $this->fb->getRedirectLoginHelper();

        // Permissions to request
        $permissions = array(
            'instagram_basic',
            'instagram_manage_comments',
            'instagram_manage_insights',
            'manage_pages');

        // Get redirect url
        $loginUrl = $helper->getLoginUrl(site_url('user/callback/instagram_insights'), $permissions);

        // Redirect
        header('Location:' . $loginUrl);
    }

    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return boolean true or false
     */
    public function save($token = null) {
        
        // Define the callback status
        $check = 0;
        
        // Obtain the user access token from redirect
        $helper = $this->fb->getRedirectLoginHelper();
        
        // Get the user access token
        $access_token = $helper->getAccessToken(site_url('user/callback/instagram_insights'));
        
        // Convert it to array
        $access_token = (array) $access_token;
        
        // Get array value
        $access_token = array_values($access_token);

        // Verify if access token exists
        if ( isset($access_token[0]) ) {
            
            // Get user data
            $getUserdata = json_decode(get('https://graph.facebook.com/me/accounts?fields=instagram_business_account{ig_id,username}&access_token=' . $access_token[0]), true);
            
            if ( $getUserdata['data'] ) {
                
                foreach ( $getUserdata['data'] as $data ) {
                    
                    if ( isset($data['instagram_business_account']) ) {
                        
                        // Calculate expire token period
                        $expires = '';

                        // Verify if the account was saved
                        if ( !$this->CI->networks->check_account_was_added('instagram_insights', $data['instagram_business_account']['id'], $this->CI->user_id) ) {

                            // Add new account
                            if ( $this->CI->networks->add_network('instagram_insights', $data['instagram_business_account']['id'], $access_token[0], $this->CI->user_id, $expires, $data['instagram_business_account']['username'], '') ) {
                                $check++;
                            }

                        }
                        
                    }
                    
                }

                if ( $check < 1 ) {
            
                    // Display the error message
                    echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_no_business_instagram_accounts') . '</p>', true);             
                    exit();
                    
                }
                
            }
            
        }
        
        if ( $check > 0 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_instagram_accounts_connected') . '</p>', true); 
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }
        
    }

    /**
     * The public method post publishes posts on facebook.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {

        // Get user details
        if ( !$user_id ) {
            
            $user_id = $this->CI->user_id;
            
        }

        // Verify if user wants groups instead accounts
        if ( !get_user_option('mobile_installed', $user_id) ) {

            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_the_mobile_client_should_be_installed_in_your_device')));

            // Then return false
            return false;

        }
 
        // Verify if image exists
        if ( !isset($args['img'][0]['body']) && !isset($args['video'][0]['body']) ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_a_photo_or_video_required')));

            // Then return false
            return false;
            
        }

        return true;


    }

    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network data
     */
    public function get_info() {
        
        return array(
            'color' => '#c9349a',
            'icon' => '<i class="icon-social-instagram"></i>',
            'preview_icon' => '<i class="icon-screen-smartphone"></i>',
            'api' => array(
                'app_id',
                'app_secret'
            ),
            'types' => array('insights', 'inbox', 'post', 'preview')
        );
        
    }

    /**
     * The public method preview generates a preview for facebook.
     *
     * @param array $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {

        // Default body value
        $body = '';

        // Default media value
        $media = '';

        // Default url value
        $url = '';

        if ( $args ) {

            if ( isset($args['body']) ) {

                $body = '<tr>'
                            . '<td colspan="2" class="post-preview-body" style="font-weight: 600; font-family: \'Open Sans\', sans-serif, \'Arimo\'; font-size: 13px; padding: 0 9px; padding-bottom: 50px; min-height: 150px">'
                                . $args['body']
                            . '</td>'  
                        . '</tr>';                

            }

            if ( isset($args['medias']) ) {

                foreach ( $args['medias'] as $medi ) {
                                
                    if ( $medi['type'] === 'image' ) {

                        $media .= '<tr>'                                         
                                    . '<td colspan="2">'
                                        . '<div data-id="' . $medi['id'] . '" data-type="' . $medi['type'] . '">'
                                            . '<img src="' . $medi['url'] . '" style="width: 100%; height: 269px;">'
                                            . '<a href="#" class="btn-delete-post-media" style="position: absolute; right: 65px; margin-top: 5px; font-size: 20px; border: 0 !important;">'
                                                . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                            . '</a>'
                                        . '</div>'
                                    . '</td>'
                                . '</tr>';

                    } else {

                        $media .= '<tr>'                                         
                                    . '<td colspan="2">'
                                        . '<div data-id="' . $medi['id'] . '" data-type="' . $medi['type'] . '">'
                                            . '<video style="width: 100%; height: 269px;" controls="">'
                                                . '<source src="' . $medi['url'] . '" type="video/mp4">'
                                            . '</video>'
                                            . '<a href="#" class="btn-delete-post-media" style="position: absolute; right: 65px; margin-top: 5px; font-size: 20px; border: 0 !important;">'
                                                . '<i class="icon-close" style="background-color: #343a40; color: #FFFFFF; border-radius: 50%;"></i>'
                                            . '</a>'
                                        . '</div>'
                                    . '</td>'
                                . '</tr>';

                    }

                }               

            }

        }

        return array(
            'body' => '<table style="width: calc(100% - 60px); box-shadow: rgba(0, 0, 0, 0.06) 0px 7px 8px; margin-left: 30px; margin-bottom: 30px;">'
                        . '<thead>'
                            . '<tr>'
                                . '<th colspan="3" style="padding: 0 15px 9px;">'
                                    . '<img src="' . base_url('assets/img/avatar-placeholder.png') . '" style="width: 30px; border-radius: 50%; float: left; margin-top: 15px; margin-right: 10px;">'
                                    . '<h3 style="padding-top: 14px; float: left;">'
                                        . '<a href="#" style="display: block; font-style: normal; margin-top: -2px; pointer-events: none; font-weight: bold; line-height: normal; font-size: 13px; color: rgb(20, 24, 35);">'
                                            . $this->CI->lang->line('networks_your_page_name')
                                        . '</a>'
                                        . '<span style="font-size: 12px; margin-top: 2px; display: block; color: rgb(97, 103, 112);">'
                                            . $this->CI->lang->line('networks_now') . '&nbsp;&nbsp;-&nbsp;&nbsp;<i class="fas fa-globe-americas"></i>'
                                        . '</span>'
                                    . '</h3>'
                                    . '<i class="fas fa-ellipsis-h" style="float: right; margin-top: 25px;"></i>'
                                . '</th>'
                            . '</tr>'
                        . '</thead>'
                        . '<tbody>'
                            . '<tr>'
                                . '<td colspan="3" class="clean">'
                                    . '<table class="full" style="width: 100%;">'
                                        . '<tbody>'
                                            . $media
                                            . '<tr>'
                                                . '<td style="padding: 10px; position: relative;">'
                                                    . '<a href="#" style="color: #3397f0; font-weight: 600; font-size: 13px; font-family: "Open Sans", sans-serif, "Arimo";">'
                                                        . $this->CI->lang->line('networks_learn_more')
                                                    . '</a>'
                                                    . '<i class="fas fa-angle-right" style="float: right; margin-top: 5px;"></i>'
                                                . '</td>'
                                            . '</tr>'
                                        . '</tbody>'
                                    . '</table>'
                                . '</td>'
                            . '</tr>'
                        . '</tbody>'
                        . '<tfoot style="border-top: 1px solid #e5e5e5;">'
                            . '<tr>'
                                . '<td colspan="2" style="text-align: left; color: #606770; flex: 1 0; height: 30px; line-height: 30px; padding: 0 2px; text-decoration: none;">'
                                    . '<i class="icon-heart" style="font-size: 18px; margin: 15px 10px;"></i>'
                                    . '<i class="icon-bubble" style="font-size: 18px; margin: 15px 10px;"></i>'
                                    . '<i class="icon-paper-plane" style="font-size: 18px; margin: 15px 10px;"></i>'
                                . '</td>'
                                . '<td class="text-right" style="text-align: left; color: #606770; flex: 1 0; height: 30px; line-height: 30px; padding: 0 2px; text-decoration: none;">'
                                    . '<i class="far fa-bookmark" style="font-size: 18px; margin: 15px 10px;"></i>'
                                .'</td>'
                            . '</tr>'
                            . $body
                        . '</tfoot>'
                    . '</table>'

        );

    }
  
}

/* End of file instagram_insights.php */
