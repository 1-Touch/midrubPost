<?php
/**
 * Facebook live
 *
 * PHP Version 7.2
 *
 * Connect and Publish Live Videos on Facebook Page
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
use Cocur\BackgroundProcess\BackgroundProcess;

/**
 * Facebook_live class - allows users to connect to their Facebook Pages and publish live videos
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Facebook_live implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    public $fb, $app_id, $app_secret;

    /**
     * Load networks and user model.
     */
    public function __construct() {

        // Get the CodeIgniter super object
        $this->CI = &get_instance();

        // Get the Facebook App ID
        $this->app_id = get_option('facebook_live_app_id');

        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_live_app_secret');

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
        if (($this->app_id != '') and ($this->app_secret != '')) {

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
            'manage_pages',
            'publish_pages'
        );

        // Get redirect url
        $loginUrl = $helper->getLoginUrl(site_url('user/callback/facebook_live'), $permissions);

        // Redirect
        header('Location:' . $loginUrl);

    }

    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return void
     */
    public function save($token = null) {

        // Define the callback status
        $check = 0;

        // Obtain the user access token from redirect
        $helper = $this->fb->getRedirectLoginHelper();

        // Get the user access token
        $access_token = $helper->getAccessToken(site_url('user/callback/facebook_live'));

        // Convert it to array
        $access_token = (array)$access_token;

        // Get array value
        $access_token = array_values($access_token);

        // Verify if access token exists
        if ( isset($access_token[0]) ) {

            // Get user data
            $response = json_decode(get('https://graph.facebook.com/me/accounts?limit=500&access_token=' . @$access_token[0]), true);

            // Verify if user has pages
            if (isset($response['data'][0]['id'])) {

                // Calculate expire token period
                $expires = '';

                // Save page
                for ($y = 0; $y < count($response['data']); $y++) {

                    $this->CI->networks->add_network('facebook_live', $response['data'][$y]['id'], $access_token[0], $this->CI->user_id, $expires, $response['data'][$y]['name'], '', $response['data'][$y]['access_token']);
                }

                $check++;

            } else {

                // Display the error message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_don_has_pages') . '</p>', false);
                exit();

            }

        }

        if ( $check > 0 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_all_facebook_pages_added') . '</p>', true); 
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }

    }

    /**
     * The public method post publishes posts on Facebook Groups.
     *
     * @param array $args contains the post data
     * @param integer $user_id is the ID of the current user
     * 
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {

        // Get user details
        if ($user_id) {

            // Get account data
            $user_details = $this->CI->networks->get_network_data('facebook_live', $user_id, $args['account']);

        } else {

            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account data
            $user_details = $this->CI->networks->get_network_data('facebook_live', $user_id, $args['account']);

        }

        // Verify if video exists
        if ( !$args['video'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('networks_a_video_is_required_to_publish_here')));

            // Then return false
            return false;
            
        }

        // Require Cocur\BackgroundProcess
        require_once FCPATH . 'vendor/cocur/background-process/src/BackgroundProcess.php';

        // Set params for live
        $params = array(
            'status' => 'LIVE_NOW'
        );

        // Verify if the title is not empty
        if ($args['title']) {
            $params['title'] = $args['title'];
            $params['description'] = $args['post'];
        } else {
            $params['title'] = $args['post'];
        }

        try {

            // Start live
            $response = $this->fb->post('/' . $user_details[0]->net_id . '/live_videos', $params, $user_details[0]->secret);
            $graphNode = $response->getGraphNode();

            if (isset($graphNode['stream_url'])) {

                $live = str_replace(' ', '%20', $graphNode['stream_url']);

                $response = false;
                $streamCommand_string = "";

                $streamCommand_string .= 'ffmpeg -re -i ';
                $streamCommand_string .= '"' . $args['video'][0]['body'] . '"';
                $streamCommand_string .= ' -acodec copy -vcodec copy -f flv ';
                $streamCommand_string .= '"' . $live . '"';

                $process = new BackgroundProcess($streamCommand_string);
                $process->run();

                if ($process) {

                    $CI = &get_instance();
                    $CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

                    $processes = array();
                    $processes[$process->getPid()] = time();
                    $CI->cache->save('processes', $processes, 90000);

                    if (is_numeric($process->getPid())) {

                        return true;
                    }

                }

            }

        } catch (Exception $e) {

            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($e->getMessage()));

            // Then return false
            return false;

        }
        
    }

    /**
     * The public method get_info displays information about this class
     * 
     * @return array with network data
     */
    public function get_info()
    {

        return array(
            'color' => '#3b5998',
            'icon' => '<i class="fab fa-facebook"></i>',
            'api' => array('app_id', 'app_secret'),
            'types' => array('post')
        );
    }

    /**
     * The public method preview generates a preview for facebook instant articles
     *
     * @param $args contains the img or url
     * 
     * @return array with html content
     */
    public function preview($args)
    {

    }
}

/* End of file facebook_live.php */
