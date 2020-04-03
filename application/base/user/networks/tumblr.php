<?php
/**
 * Tumblr
 *
 * PHP Version 5.6
 *
 * Connect and Publish to Tumblr
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
if ( !isset($_SESSION) ) {
    session_start();
}

/**
 * Tumblr class - allows users to connect to their Tumblr Account and publish posts.
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://elements.envato.com/license-terms
 * @link     https://www.midrub.com/
 */
class Tumblr implements MidrubBaseUserInterfaces\Networks {

    /**
     * Class variables
     */
    protected $client, $request, $consumerKey, $consumerSecret;
    
    /**
     * Load networks and user model.
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Get Tumblr's consumer_key
        $this->consumerKey = get_option('tumblr_consumer_key');
        
        // Get Tumblr's consumer_secret
        $this->consumerSecret = get_option('tumblr_consumer_secret');
        
        // Require the vendor autoload
        include_once FCPATH . 'vendor/autoload.php';

        // Load the networks language's file
        $this->CI->lang->load( 'default_networks', $this->CI->config->item('language') );
        
    }
    
    /**
     * The public method check_availability check if the Tumblr api is configured correctly.
     *
     * @return will be true if the consumerKey and consumerSecret is not empty
     */
    public function check_availability() {
        
        // First function check if the Tumblr api is configured correctly
        if ( ($this->consumerKey != '') AND ( $this->consumerSecret != '') ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method connect will redirect user to Tumblr login page.
     * 
     * @return void
     */
    public function connect() {

        // Call the Tumblr's Client
        $this->client = new \Tumblr\API\Client($this->consumerKey, $this->consumerSecret);
        $this->request = $this->client->getRequestHandler();
        $this->request->setBaseUrl('https://www.tumblr.com/');
        
        // Generate redirect url
        $url = $this->request->request(
            'POST', 'oauth/request_token', array(
            'oauth_callback' => base_url() . 'user/callback/tumblr'
            )
        );
        
        // get the oauth_token
        $out = $url->body;
        $data = array();
        parse_str($out, $data);
        unset($_SESSION['tmp_oauth_token']);
        unset($_SESSION['tmp_oauth_token_secret']);
        $_SESSION['oauth_token'] = $data['oauth_token'];
        $_SESSION['oauth_token_secret'] = $data['oauth_token_secret'];
        
        // Redirect
        header('Location: https://www.tumblr.com/oauth/authorize?oauth_token=' . $data['oauth_token']);
        
    }
    
    /**
     * The public method save will get access token.
     *
     * @param string $token contains the token for some social networks
     * 
     * @return void
     */
    public function save($token = null) {
        
        // Verify if oauth_verifier exists
        if ( $this->CI->input->get('oauth_verifier', TRUE) ) {
            
            // Set $verifier
            $verifier = $this->CI->input->get('oauth_verifier', TRUE);

            // Call the Tumblr's Client
            $this->client = new \Tumblr\API\Client($this->consumerKey, $this->consumerSecret);
            
            // Set access token request
            $this->client->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

            $this->request = $this->client->getRequestHandler();
            $this->request->setBaseUrl('https://www.tumblr.com/');
            
            // Get Tumblr's access token
            $response = $this->request->request('POST', 'oauth/access_token', array('oauth_verifier' => $verifier));
            
            // Get response
            $out = $response->body;
            
            $data = array();
            parse_str($out, $data);
            
            // Get first blog name
            
            if ( $data ) {
                
                // Call the Tumblr class
                $client = new \Tumblr\API\Client($this->consumerKey, $this->consumerSecret, $data['oauth_token'], $data['oauth_token_secret']);
                
                // Get user information
                $clientInfo = $client->getUserInfo();
                
            }
            
            // Verify if user has at least a blog
            if ( @$clientInfo->user->blogs[0]->name ) {
                
                // Get user_id
                $user_id = $this->CI->user->get_user_id_by_username($this->CI->session->userdata['username']);
                
                // Save blogs
                for ($y = 0; $y < count($clientInfo->user->blogs); $y++) {
                    
                    if ( !$this->CI->networks->get_network_data('tumblr', $this->CI->user->get_user_id_by_username($this->CI->session->userdata['username']), $clientInfo->user->blogs[$y]->name) ) {
                        
                        $this->CI->networks->add_network('tumblr', $clientInfo->user->blogs[$y]->name, $data['oauth_token'], $user_id, '', $clientInfo->user->blogs[$y]->name, '', $data['oauth_token_secret']);
                        
                    }
                }
                
                // Display the success message
                echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-success">' . strip_tags(display_mess(84)) . '</p>', true);
                
            }
            
        } else {
            
            // Display the error message
            echo $this->CI->ecl('Social_login_connect')->view($this->CI->lang->line('social_connector'), '<p class="alert alert-error">' . $this->CI->lang->line('mm3') . '</p>', false); 
            
        }
        
    }
    
    /**
     * The public method post publishes posts on Tumblr.
     *
     * @param array $args contains the post data.
     * @param integer $user_id is the ID of the current user
     * @return boolean true if post was published
     */
    public function post($args, $user_id = null) {
        
        // Get user details
        if ($user_id) {
            
            // if the $user_id variable is not null, will be published a scheduled post
            $con = $this->CI->networks->get_network_data('tumblr', $user_id, $args['account']);
            
        } else {
            
            // Get the user ID
            $user_id = $this->CI->user->get_user_id_by_username($this->CI->session->userdata['username']);
            $con = $this->CI->networks->get_network_data('tumblr', $user_id, $args['account']);
            
        }
        
        // Verify if user has the social account
        if ($con) {
            
            try {
                
                // Call the Tumblr's Client
                $client = new \Tumblr\API\Client($this->consumerKey, $this->consumerSecret, $con[0]->token, $con[0]->secret);
                
                // Create the post
                $data = [];
                $post = $args['post'];
                
                // Verify if url is empty
                if ( $args['url'] ) {
                    $post = str_replace($args['url'], ' ', $post);
                    $url = short_url($args['url']);
                }
                
                preg_match_all('/#([^\s]+)/', $post, $hashtags);

                if (isset($hashtags[0][0])) {

                    $tags = array();

                    foreach ($hashtags as $hashtag) {

                        $tags[] = str_replace('#', '', $hashtag);
                        $post = str_replace($hashtag, '', $post);
                    }

                    // Set tags
                    $data['tags'] = implode( ',', $tags[0]);
                }

                // Verify if the image is not empty
                if ( $args['img'] ) {
                    
                    $data['caption'] = $post;
                    $data['source'] = $args['img'][0]['body'];
                    $data['type'] = 'photo';
                    
                    if ($args['url']) {

                        $data['caption'] = $data['caption'] . '<br><a href="' . $url . '">' . $url . '</a>';
                    }
                    
                } else {
                    
                    if ($args ['title']) {
                        $data['title'] = $args ['title'];
                        $data['body'] = nl2br($post);
                        
                        if ($args['url']) {

                            $data['body'] = $data['body'] . '<br><a href="' . $url . '">' . $url . '</a>';
                        }
                        
                    } else {

                        $data['title'] = $post;

                        if ($args['url']) {

                            $data['body'] = '<a href="' . $url . '">' . $url . '</a>';
                        }
                        
                    }

                    $data['type'] = 'text';
                    
                }

                // Publish the post
                $create = $client->createPost($con[0]->net_id, $data);
                
                if ( $create ) {
                    
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
        } else {
            
            return false;
            
        }
    }
    /**
     * The public method get_info displays information about this class.
     * 
     * @return array with network's details
     */
    public function get_info() {
        
        return array(
            'color' => '#529ecc',
            'icon' => '<i class="fab fa-tumblr-square"></i>',
            'api' => array('consumer_key', 'consumer_secret'),
            'types' => array('post', 'rss')
        );
        
    }
    
    /**
     * The public method preview generates a preview for Tumblr.
     *
     * @param array $args contains the img or url.
     * 
     * @return array with html content
     */
    public function preview($args) {
    }

}

/* End of file tumblr.php */
