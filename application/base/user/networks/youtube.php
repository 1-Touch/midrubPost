<?php
/**
 * Youtube
 *
 * PHP Version 7.2
 *
 * Connect and Publish to Youtube
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
 * Youtube class - allows users to connect to their Youtube channels and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Youtube implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $client, $CI, $clientId, $clientSecret, $apiKey, $youtube, $appName, $scriptUri;

    /**
     * Load networks and user model.
     */
    public function __construct() {
                
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get the Google's client_id
        $this->clientId = get_option('youtube_client_id');
        
        // Get the Google's client_secret
        $this->clientSecret = get_option('youtube_client_secret');
        
        // Get the Google's api key
        $this->apiKey = get_option('youtube_api_key');
        
        // Get the Google's application name
        $this->appName = get_option('youtube_google_application_name');

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
                
        // Require the  vendor's libraries
        require_once FCPATH . 'vendor/autoload.php';
        
        // Youtube CallBack
        $this->scriptUri = site_url('user/callback/youtube');
        
    }

    /**
     * The public method check_availability checks if the Youtube api is configured correctly.
     *
     * @return will be true if the client_id, apiKey, and client_secret is not empty
     */
    public function check_availability() {
        
        // Verify if clientId, clientSecret and apiKey exists
        if ( ($this->clientId != '') and ( $this->clientSecret != '') and ( $this->apiKey != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }

    /**
     * The public method connect will redirect user to Google login page.
     * 
     * @return void
     */
    public function connect() {
        
        // Call the class Google_Client
        $this->client = new \Google_Client();
        
        // Offline because we need to get refresh token
        $this->client->setAccessType('offline');
        
        // Name of the google application
        $this->client->setApplicationName($this->appName);
        
        // Set the client_id
        $this->client->setClientId($this->clientId);
        
        // Set the client_secret
        $this->client->setClientSecret($this->clientSecret);
        
        // Redirects to same url
        $this->client->setRedirectUri($this->scriptUri);
        
        // Set the api key
        $this->client->setDeveloperKey($this->apiKey);
        
        // Set approval prompt to force
        $this->client->setApprovalPrompt('force');
        
        // Load required scopes
        $this->client->setScopes(array(
            'https://www.googleapis.com/auth/youtube.upload https://www.googleapis.com/auth/youtube https://www.googleapis.com/auth/youtubepartner https://www.googleapis.com/auth/userinfo.profile'
        ));
        
        // Generate redirect url
        $authUrl = $this->client->createAuthUrl();
        
        // Redirect
        header('Location:' . $authUrl);
        
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
        
        // Verify if code exists
        if ( $this->CI->input->get('code', TRUE) ) {
            
            // Call the class Google_Client
            $this->client = new \Google_Client();

            // Name of the google application
            $this->client->setApplicationName($this->appName);

            // Set the client_id
            $this->client->setClientId($this->clientId);

            // Set the client_secret
            $this->client->setClientSecret($this->clientSecret);

            // Redirects to same url
            $this->client->setRedirectUri($this->scriptUri);

            // Set the api key
            $this->client->setDeveloperKey($this->apiKey);
            
            // Send the received code
            $this->client->authenticate( $this->CI->input->get('code', TRUE) );
            
            // Get access token
            $token = $this->client->getAccessToken();

            // Set access token
            $this->client->setAccessToken($token);
            
            // Verify if token exists
            if ( isset($token['access_token']) ) {
                
                // Get refresh token
                $refresh = $token['refresh_token'];
                
                // Get access token
                $token = $token['access_token'];
                
                // we will use the token to get user data
                $curl = curl_init();
                
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token,
                    CURLOPT_HEADER => false
                ));
                
                // Send the request & save response to $resp
                $udata = curl_exec($curl);
                
                // Close request to clear up some resources
                curl_close($curl);
                
                // Veify if response is valid
                if ($udata) {
                    
                    // Decode response
                    $udecode = json_decode($udata, true);
                    
                    // Verify if account exists
                    if ( isset($udecode['sub']) ) {
                        
                        // Get user name
                        $name = $udecode['name'];
                        
                        // Get user picture
                        $picture = !empty($udecode['picture'])?$udecode['picture']:'';
                        
                        // Verify if social network was already added
                        if ( !$this->CI->networks->get_network_data('youtube', $this->CI->user_id, $udecode['sub']) ) {
                            
                            $this->CI->networks->add_network('youtube', $udecode['sub'], $token, $this->CI->user_id, '', $name, $picture, $refresh);
                            
                            $check = 1;
                            
                        } else {
                            
                            $check = 2;
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
        if ( $check === 1 ) {
            
            // Display the success message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . $this->CI->lang->line('networks_your_account_was_connected') . '</p>', true); 
            
        } elseif ( $check === 2 ) {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_your_account_was_not_connected') . '</p>', false);             
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('networks_an_error_occurred') . '</p>', false);             
            
        }
        
    }

    /**
     * The public method post publishes posts on Youtube.
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
            $con = $this->CI->networks->get_network_data('youtube', $user_id, $args ['account']);
            
        } else {
            
            // Set user's ID
            $user_id = $this->CI->user_id;

            // Get account details
            $con = $this->CI->networks->get_network_data('youtube', $user_id, $args ['account']);
            
        }
        
        // Verify if video exists
        if ( !$args['video'] ) {
            
            // Save the error
            $this->CI->user_meta->update_user_meta($user_id, 'last-social-error', json_encode($this->CI->lang->line('a_video_is_required_to_publish_here')));

            // Then return false
            return false;
            
        }
        
        // Verify if user has the social account
        if ($con) {
            
            // Verify if secret exists
            if ($con [0]->secret) {
                
                try {
                    
                    // Verify if the class Google_Client was already called
                    if ( !class_exists( 'Google_Client', false ) ) {

                        require_once FCPATH . 'vendor/google/src/Google_Client.php';

                    }

                    require_once FCPATH . 'vendor/google/src/contrib/Google_YouTubeService.php';
                    require_once FCPATH . 'vendor/google/src/service/Google_MediaFileUpload.php';
                    
                    // Get video
                    $video = str_replace(base_url(), FCPATH, $args['video'][0]['body']);
                    
                    // Call the class Google_Client
                    $this->client = new \Google_Client();

                    // Offline because we need to get refresh token
                    $this->client->setAccessType('offline');

                    // Name of the google application
                    $this->client->setApplicationName($this->appName);

                    // Set the client_id
                    $this->client->setClientId($this->clientId);

                    // Set the client_secret
                    $this->client->setClientSecret($this->clientSecret);

                    // Redirects to same url
                    $this->client->setRedirectUri($this->scriptUri);

                    // Set the api key
                    $this->client->setDeveloperKey($this->apiKey);

                    // Load required scopes
                    $this->client->setScopes(array(
                        'https://www.googleapis.com/auth/youtube.upload https://www.googleapis.com/auth/youtube https://www.googleapis.com/auth/youtubepartner https://www.googleapis.com/auth/userinfo.profile'
                    ));

                    // Call the Youtube Services
                    $this->youtube = new \Google_YouTubeService($this->client);
                    
                    // Refresh token
                    $this->client->refreshToken($con[0]->secret);
                    
                    // Get access token
                    $newtoken = $this->client->getAccessToken();
                    
                    // Set access token
                    $this->client->setAccessToken($newtoken);
                    $file_info = finfo_open(FILEINFO_MIME_TYPE);
                    $mime_type = finfo_file($file_info, $video);
                    $video_snippet = new \Google_VideoSnippet();
                    
                    $post = $args['post'];

                    // Verify if url exists
                    if ( $args['url'] ) {
                        $post = str_replace($args['url'], ' ', $post) . ' ' . short_url($args['url']);
                    }
                    
                    // Verify if title is not empty
                    if ( $args ['title'] ) {
                        
                        $video_snippet->setTitle($args['title']);
                        $video_snippet->setDescription($post);
                        
                    } else {
                        
                        $video_snippet->setTitle($post);
                        
                    }
                    
                    // Verify if category exists
                    if ( $args['category'] ) {
                        
                        $category = json_decode($args['category']);
                        
                        if (@$category->$args['account']) {
                            $video_snippet->setCategoryId([$category->$args['account']]);
                        }
                        
                    }
                    
                    // Publish the video
                    $status = new \Google_VideoStatus();
                    $status->setPrivacyStatus('public');
                    $google_video = new \Google_Video();
                    $google_video->setSnippet($video_snippet);
                    $google_video->setStatus($status);
                    $upload = $this->youtube->videos->insert('snippet,status', $google_video, array(
                        'data' => file_get_contents($video),
                        'mimeType' => $mime_type,
                    ));
                    
                    if ( $upload ) {
                        
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
        
    }

    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's data
     */
    public function get_info() {

        return array(
            'color' => '#ca3737',
            'icon' => '<i class="icon-social-youtube"></i>',
            'api' => array(
                'client_id',
                'client_secret',
                'api_key',
                'google_application_name'
            ),
            'types' => array('post', 'categories')
        );
        
    }

    /**
     * The public method preview generates a preview for Youtube.
     *
     * @param array $args contains the video or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
    }

}

/* End of file youtube.php */
