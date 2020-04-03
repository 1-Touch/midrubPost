<?php
/**
 * Hashtags Helpers
 *
 * This file contains the class Hashtags
 * with methods to search for hashtags
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Namespaces to use
use Abraham\TwitterOAuth\TwitterOAuth;

/*
 * Save_images class provides the methods to download images from url
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Hashtags {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI, $twitter_key, $twitter_secret, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Get the Twitter app_id
        $this->twitter_key = get_option('twitter_app_id');
        
        // Get the Twitter app_secret
        $this->twitter_secret = get_option('twitter_app_secret');
        
        // Get the Facebook App ID
        $this->app_id = get_option('instagram_insights_app_id');
        
        // Get the Facebook App Secret
        $this->app_secret = get_option('instagram_insights_app_secret');
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';

        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Networks_model', 'networks_model' );
        
    }
    
    /**
     * The public method search_for_hashtags searches for hashtags
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function search_for_hashtags() {
        
        // Check if data was submitted
        if ( $this->CI->input->post() ) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('network', 'Network', 'trim|required');
            $this->CI->form_validation->set_rules('word', 'Word', 'trim');
            
            // Get data
            $network = $this->CI->input->post('network');
            $word = $this->CI->input->post('word');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                switch ( $network ) {
                    
                    case 'twitter':
                        
                        if ( $word ) {
                            
                            $network_details = $this->CI->networks_model->get_all_accounts($this->CI->user_id, 'twitter');
                            
                            if ( $network_details ) {
                        
                                $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network_details[0]->token, $network_details[0]->secret);

                                $word = urlencode(str_replace(' ', '+', $word));

                                $tweets = $connection->get('search/tweets',

                                    array(
                                        'q' => $word,
                                        'result_type' => 'popular'
                                    )

                                );
                                
                                $hashtags = array();
                                
                                if ( $tweets ) {
                                    
                                    foreach ( $tweets->statuses as $tweet ) {

                                        if ( $tweet->entities->hashtags ) {
                                            
                                            foreach ( $tweet->entities->hashtags as $hash ) {

                                                if ( !in_array($hash->text, $hashtags) ) {
                                                    $hashtags[] = $hash->text;
                                                }
                                                
                                            }
                                            
                                        }
                                        
                                    }
                                    
                                    if ( $hashtags ) {
                                        
                                        $data = array(
                                            'success' => TRUE,
                                            'hashtags' => $hashtags
                                        );

                                        echo json_encode($data);                                         
                                        
                                    } else {
                                        
                                        $data = array(
                                            'success' => FALSE,
                                            'message' => $this->CI->lang->line('no_hashtags_found')
                                        );

                                        echo json_encode($data);                                           
                                        
                                    }
                                    
                                }
                                
                                exit();
                                
                            } else {
                                
                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('please_connect_twitter_account')
                                );

                                echo json_encode($data);
                                exit();    
                                
                            }
                            
                        }
                        
                        break;
                        
                    case 'instagram':
                        
                        // Load the Facebook Class
                        $this->fb = new \Facebook\Facebook(
                            array(
                                'app_id' => $this->app_id,
                                'app_secret' => $this->app_secret,
                                'default_graph_version' => 'v3.0',
                                'default_access_token' => '{access-token}',
                            )
                        );
                        
                        if ( $word ) {
                            
                            $network_details = $this->CI->networks_model->get_all_accounts($this->CI->user_id, 'instagram_insights');
                            
                            if ( $network_details ) {
                                
                                try {

                                    $response = $this->fb->get(
                                        '/ig_hashtag_search?q=' . strtolower($word) . '&user_id=' . $network_details[0]->net_id,
                                        $network_details[0]->token
                                    );

                                } catch (\Facebook\Exceptions\FacebookResponseException $e) {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('hashtag_not_valid')
                                    );

                                    echo json_encode($data);
                                    exit(); 

                                }
                                
                                $graphNode = $response->getGraphEdge();

                                $hash = $graphNode->asArray();
                                
                                $medias = $this->fb->get(
                                    '/' . $hash[0]['id'] . '/top_media?fields=caption,media_url,media_type,comments_count,like_count,permalink&limit=10&user_id=' . $network_details[0]->net_id,
                                    $network_details[0]->token
                                );
                                
                                $graphNode = $medias->getGraphEdge();

                                $all_hashtags = array();

                                if ( $graphNode->asArray() ) {
                                    
                                    foreach ( $graphNode->asArray() as $media ) {
                                        
                                        if (isset($media['caption'])) {
                                            
                                            preg_match_all('/#([^\s]+)/', $media['caption'], $hashtags);

                                            if ( isset($hashtags[0][0]) ) {

                                                foreach ( $hashtags as $hashtag ) {
                                                    
                                                    if ( !in_array($hashtag[0], $all_hashtags) ) {

                                                        $all_hashtags[] = str_replace( '#', '', trim($hashtag[0]) );
                                                    
                                                    }

                                                }

                                            }
                                            
                                        }
                                        
                                    }
                                    
                                }
                                
                                if ( $all_hashtags ) {

                                    $data = array(
                                        'success' => TRUE,
                                        'hashtags' => $all_hashtags
                                    );

                                    echo json_encode($data);                                         

                                } else {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('no_hashtags_found')
                                    );

                                    echo json_encode($data);                                           

                                }
                        
                                exit();
                                
                            } else {
                                
                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('please_connect_instagram_account')
                                );

                                echo json_encode($data);
                                exit();    
                                
                            }
                            
                        }
                        
                        break;
                    
                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data);  
        
    }

}

/* End of file hashtags.php */