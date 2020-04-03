<?php
/**
 * Boost Helper
 *
 * This file contains the class Boost Helper
 * with methods to process the posts boost methods
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Facebook_ads\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Boost class provides the methods to process the posts boost methods
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
*/
class Boost {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected $CI, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Get the CodeIgniter super object
        $this->CI = & get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_FACEBOOK_ADS . 'models/', 'Ads_networks_model', 'ads_networks_model' );
            
        // Get the Facebook App ID
        $this->app_id = get_option('facebook_pages_app_id');

        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_pages_app_secret');
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
        // Verify if Facebook Pages was configured
        if (($this->app_id != '') AND ( $this->app_secret != '')) {
            
            $this->fb = new \Facebook\Facebook([
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => MIDRUB_ADS_FACEBOOK_GRAPH_VERSION,
                'default_access_token' => '{access-token}',
            ]);
            
        }
        
    }

    /**
     * The public method load_posts_for_boosting search posts for boosting
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function load_posts_for_boosting() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('network', 'Network', 'trim|required');
            $this->CI->form_validation->set_rules('fb_page_id', 'Facebook Page ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('instagram_id', 'Instagram ID', 'trim');
            
            // Get data
            $key = $this->CI->input->post('key'); 
            $network = $this->CI->input->post('network');
            $fb_page_id = $this->CI->input->post('fb_page_id');
            $instagram_id = $this->CI->input->post('instagram_id');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get selected account
                $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                
                switch ( $network ) {
                    
                    case 'facebook':
                        
                        if ( $key ) {

                            // Get page's posts
                            $account_posts = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $fb_page_id . '/feed?fields=picture,message&limit=10&access_token=' . $account[0]->token), true);
                            
                            if ( $account_posts['data'] ) {

                                $posts = array();

                                $i = 0;

                                foreach ( $account_posts['data'] as $post ) {

                                    if ( preg_match("/{$key}/i", @$post['message'] ) ) {

                                        $picture = base_url('assets/img/no-image.png');

                                        if ( isset($post['picture']) ) {
                                            $picture = $post['picture'];
                                        }

                                        $message = $this->CI->lang->line('no_text_fond');

                                        if ( isset($post['message']) ) {
                                            $message = $post['message'];
                                        }

                                        $posts[] = array(
                                            'id' => $post['id'],
                                            'picture' => $picture,
                                            'message' => $message
                                        );

                                        $i++;

                                    }

                                    if ( $i > 9 ) {
                                        break;
                                    }

                                }

                                if ( isset($posts) ) {

                                    $data = array(
                                        'success' => TRUE,
                                        'posts' => $posts
                                    );

                                    echo json_encode($data);
                                    exit();

                                }

                            }                        

                        } else {
                        
                            // Get page's posts
                            $account_posts = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $fb_page_id . '/feed?fields=picture,message&limit=10&access_token=' . $account[0]->token), true);

                            if ( $account_posts['data'] ) {

                                $posts = array();

                                foreach ( $account_posts['data'] as $post ) {

                                    $picture = base_url('assets/img/no-image.png');

                                    if ( isset($post['picture']) ) {
                                        $picture = $post['picture'];
                                    }

                                    $message = $this->CI->lang->line('no_text_fond');

                                    if ( isset($post['message']) ) {
                                        $message = $post['message'];
                                    }

                                    $posts[] = array(
                                        'id' => $post['id'],
                                        'picture' => $picture,
                                        'message' => $message
                                    );

                                }

                                $data = array(
                                    'success' => TRUE,
                                    'posts' => $posts
                                );

                                echo json_encode($data);
                                exit();

                            }
                            
                        }
                        
                        break;
                    
                    case 'instagram':
                        
                        if ( $instagram_id ) {
                            
                            // Get connected instagram account
                            $connected_accounts = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . 'me/accounts?fields=connected_instagram_account&access_token=' . $account[0]->token), true);

                            // Get Instagram account
                            $instagram_account_id = '';
                            
                            if ( $connected_accounts ) {
                                
                                foreach ( $connected_accounts['data'] as $connected_account ) {
                                    
                                    if ( $connected_account['id'] === $fb_page_id ) {
                                        
                                        $instagram_account_id = $connected_account['connected_instagram_account']['id'];
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            if ( $instagram_account_id ) {
                                
                                if ( $key ) {

                                    $account_posts = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $instagram_account_id . '/media?fields=id,media_type,media_url,timestamp,caption,thumbnail_url&limit=1000&access_token=' . $account[0]->token), true);

                                    if ( $account_posts['data'] ) {

                                        $posts = array();

                                        $i = 0;

                                        foreach ( $account_posts['data'] as $post ) {

                                            if ( preg_match("/{$key}/i", @$post['caption'] ) ) {
                                                
                                                $picture = base_url('assets/img/no-image.png');

                                                if ( isset($post['media_url']) ) {
                                                    $picture = $post['media_url'];
                                                }

                                                if ( isset($post['thumbnail_url']) ) {
                                                    $picture = $post['thumbnail_url'];
                                                }

                                                $message = $this->CI->lang->line('no_text_fond');

                                                if ( isset($post['caption']) ) {
                                                    $message = $post['caption'];
                                                }

                                                $posts[] = array(
                                                    'id' => $post['id'],
                                                    'picture' => $picture,
                                                    'message' => $message
                                                );
                                                
                                                $i++;
                                                
                                            }

                                            if ( $i > 9 ) {
                                                break;
                                            }

                                        }

                                        if ( isset($posts) ) {

                                            $data = array(
                                                'success' => TRUE,
                                                'posts' => $posts
                                            );

                                            echo json_encode($data);
                                            exit();

                                        }

                                    }                        

                                } else {

                                    // Get account's posts
                                    $account_posts = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $instagram_account_id . '/media?fields=id,media_type,media_url,timestamp,caption,thumbnail_url&limit=10&access_token=' . $account[0]->token), true);

                                    if ( $account_posts['data'] ) {

                                        $posts = array();

                                        foreach ( $account_posts['data'] as $post ) {

                                            $picture = base_url('assets/img/no-image.png');

                                            if ( isset($post['media_url']) ) {
                                                $picture = $post['media_url'];
                                            }

                                            if ( isset($post['thumbnail_url']) ) {
                                                $picture = $post['thumbnail_url'];
                                            }

                                            $message = $this->CI->lang->line('no_text_fond');

                                            if ( isset($post['caption']) ) {
                                                $message = $post['caption'];
                                            }

                                            $posts[] = array(
                                                'id' => $post['id'],
                                                'picture' => $picture,
                                                'message' => $message
                                            );

                                        }

                                        $data = array(
                                            'success' => TRUE,
                                            'posts' => $posts
                                        );

                                        echo json_encode($data);
                                        exit();

                                    }
                                    
                                }

                            }

                        }
                        
                        break;                    
                    
                }
                
            }
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_posts_fond')
            );            

            echo json_encode($data);
            exit();
            
        }
        
    }
    
    /**
     * The public method get_post_data_for_boost get post for boosting
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function get_post_data_for_boost() {
        
        // Get post_id's input
        $post_id = $this->CI->input->get('post_id', TRUE);
        
        // Get network's input
        $network = $this->CI->input->get('network', TRUE);
        
        if ( $post_id ) {
            
            // Get selected account
            $account = $this->CI->ads_account_model->get_account($this->CI->user_id, 'facebook');
                
            switch ( $network ) {
                    
                case 'facebook':
                    
                    // Get post's data
                    $post = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $post_id . '?fields=is_eligible_for_promotion,message,picture&access_token=' . $account[0]->token), true);
                    
                    if ( isset($post['is_eligible_for_promotion']) ) {
                        
                        if ( $post['is_eligible_for_promotion'] && isset($post['message']) ) {
                            
                            $data = array(
                                'success' => TRUE,
                                'picture' => isset($post['picture'])?$post['picture']:'',
                                'message' => $post['message'],
                                'link' => '',
                                'post_id' => $post_id
                            );

                            echo json_encode($data);
                            exit();
                            
                        } else {
                            
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('this_post_is_not_eligible')
                            );

                            echo json_encode($data);
                            exit();
                            
                        }
                        
                    }
                    
                    break;
                    
                case 'instagram':

                    // Get post
                    $post = json_decode(get(MIDRUB_ADS_FACEBOOK_GRAPH_URL . $post_id . '?fields=id,media_type,media_url,timestamp,caption,thumbnail_url&access_token=' . $account[0]->token), true);
                          
                    if ( $post ) {
                        
                        $picture = base_url('assets/img/no-image.png');

                        if ( isset($post['media_url']) ) {
                            $picture = $post['media_url'];
                        }

                        if ( isset($post['thumbnail_url']) ) {
                            $picture = $post['thumbnail_url'];
                        }

                        $message = $this->CI->lang->line('no_text_fond');

                        if ( isset($post['caption']) ) {
                            $message = $post['caption'];
                        }
                        
                        $data = array(
                            'success' => TRUE,
                            'picture' => $picture,
                            'message' => $message,
                            'link' => '',
                            'post_id' => $post_id
                        );

                        echo json_encode($data);
                        exit();
                    
                    }
                    
                    break;                    
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
    }
    
}

/* End of file boost.php */
