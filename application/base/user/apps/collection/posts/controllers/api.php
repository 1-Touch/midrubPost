<?php
/**
 * Api Controller
 *
 * This file loads the Api methods
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_APPS_POSTS') OR define('MIDRUB_BASE_USER_APPS_POSTS', MIDRUB_BASE_USER . 'apps/collection/posts/');
defined('MIDRUB_POSTS_FACEBOOK_GRAPH_URL') OR define('MIDRUB_POSTS_FACEBOOK_GRAPH_URL', 'https://graph.facebook.com/v3.3/');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Helpers as MidrubBaseUserAppsCollectionPostsHelpers;

/*
 * Api class loads the app api methods
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */
class Api {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load the app's models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Posts_model', 'posts_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Rss_model', 'rss_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Networks_model', 'networks_model' );
        
        // Load language
        $this->CI->lang->load( 'posts_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);
        
    }
    
    /**
     * The public method get_user_posts loads the user's posts
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function get_user_posts() {

        // Verify if access token is valid
        $user_id = rest_verify_token(array('user_posts'));
        
        // Get page's input
        $page = $this->CI->input->get('page', TRUE);
        
        // Get limit's input
        $limit = $this->CI->input->get('limit', TRUE);
        
        // Verify if limit exists
        if ( $limit && $limit < 101 ) {
            
            $display_limit = $limit;
            
        } else {
            
            $display_limit = 10;
            
        }
        
        // Verify if page exists exists
        if ( !is_numeric($page) || !$page ) {
            $page = 0;
        } else {
            $page--;
        }

        // Get total posts
        $total = $this->CI->posts_model->get_posts($user_id, '', '', '');

        // Get posts by page
        $get_posts = $this->CI->posts_model->get_posts($user_id, ($page * $display_limit), $display_limit, '');
        
        // Verify if posts exists
        if ( $get_posts ) {
            
            $all_posts = array();
            
            foreach ( $get_posts as $post ) {
                
                if ( get_user_option('settings_display_groups') && $post->status != '1' ) {

                    if ( is_numeric( $post->category ) ) {

                        // Load the lists model
                        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );

                        // Get social networks
                        $group_metas = $this->CI->lists_model->get_lists_meta( $post->user_id, $post->category );

                        $networks = array();

                        if ( $group_metas ) {

                            foreach ( $group_metas as $meta ) {

                                $array_meta = (array)$meta;
                                $array_meta['status'] = '0';
                                $array_meta['network_status'] = '';
                                $networks[] = $array_meta;

                            }

                        }

                    } else {

                        $networks = array();

                    }

                } else {

                    // Get social networks
                    $networks = $this->CI->posts_model->all_social_networks_by_post_id( $post->user_id, $post->post_id );

                }

                $profiles = array();

                $networks_icon = array();

                if ( $networks ) {

                    foreach ( $networks as $network ) {

                        if ( in_array( $network['network_name'], $networks_icon ) ) {

                            $profiles[] = array(
                                'network_id' => $network['network_id'],
                                'net_id' => $network['net_id'],
                                'user_name' => $network['user_name'],
                                'status' => $network['status'],
                                'network_status' => $network['network_status'],
                                'icon' => $networks_icon[$network['network_name']],
                                'network_slug' => $network['network_name'],   
                                'network_name' => ucwords( str_replace('_', ' ', $network['network_name']) )
                            );

                        } else {

                            $network_icon = (new MidrubBaseUserAppsCollectionPostsHelpers\Accounts)->get_network_icon($network['network_name']);

                            if ( $network_icon ) {

                                $profiles[] = array(
                                    'network_id' => $network['network_id'],
                                    'net_id' => $network['net_id'],
                                    'user_name' => $network['user_name'],
                                    'status' => $network['status'],
                                    'network_status' => $network['network_status'],
                                    'icon' => $network_icon,
                                    'network_slug' => $network['network_name'],   
                                    'network_name' => ucwords( str_replace('_', ' ', $network['network_name']) )
                                );

                                $networks_icon[$network['network_name']] = $network_icon;

                            }

                        }

                    }

                }

                // Get image
                $img = unserialize($post->img);

                // Get video
                $video = unserialize($post->video);

                // Verify if image exists
                if ( $img ) {
                    $images = get_post_media_array($post->user_id, $img );
                    if ($images) {
                        $img = $images;
                    }
                }

                // Verify if video exists
                if ( $video ) {
                    $videos = get_post_media_array($post->user_id, $video );
                    if ($videos) {
                        $video = $videos;
                    }
                }

                $time = $post->sent_time;

                if ( $post->status < 1 ) {
                    $time = $this->CI->lang->line('draft_post');
                }
                
                // Set post's content
                $all_posts[] = array(
                    'post_id' => $post->post_id,
                    'title' => htmlspecialchars_decode($post->title),
                    'body' => htmlspecialchars_decode($post->body),
                    'sent_time' => $post->sent_time,
                    'datetime' => $time,
                    'time' => time(),
                    'url' => $post->url,
                    'img' => $img,
                    'video' => $video,
                    'status' => $post->status,
                    'profiles' => $profiles,
                    'delete_post' => $this->CI->lang->line('delete_post')
                );
                
            }
            
            $data = array(
                'success' => TRUE,
                'posts' => $all_posts,
                'total' => $total,
                'page' => ($page + 1)
            );

            echo json_encode($data);
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_posts_found')
            );

            echo json_encode($data);            
            
        }
        
    }

        /**
     * The public method get_user_accounts gets user's accounts or groups
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function get_user_accounts() {

        // Verify if access token is valid
        $user_id = rest_verify_token(array('user_social_accounts'));

        // Get page's input
        $page = $this->CI->input->post('page', TRUE);

        if ( !$page ) {
            $page = 1;
        }
        
        $limit = 10;

        $page--;

        if ( get_user_option('settings_display_groups') ) {

            // Load the lists model
            $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model' );

            // Get groups list
            $groups_list = $this->CI->lists_model->get_groups($this->CI->user_id, ($page * $limit), $limit);

            // Get total groups list
            $total = $this->CI->lists_model->get_groups($this->CI->user_id, 0, 0);

            // Verify groups were found
            if ($groups_list) {

                $data = array(
                    'success' => TRUE,
                    'type' => 'groups',
                    'total' => $total,
                    'page' => ($page + 1),
                    'groups_list' => $groups_list
                );

                echo json_encode($data);

            } else {

                $data = array(
                    'success' => FALSE,
                    'type' => 'groups',
                    'message' => $this->CI->lang->line('no_groups_found')
                );

                echo json_encode($data);

            }

        } else {

            // Get accounts list
            $accounts_list = $this->CI->networks_model->get_accounts($user_id, ($page * $limit), $limit);

            // Verify accounts were found
            if ($accounts_list) {

                // Get total number of connected accounts
                $total = $this->CI->networks_model->get_accounts($user_id);

                $data = array(
                    'success' => TRUE,
                    'type' => 'accounts',
                    'accounts_list' => $accounts_list,
                    'page' => ($page + 1),
                    'total' => $total
                );

                echo json_encode($data);

            } else {

                $data = array(
                    'success' => FALSE,
                    'type' => 'accounts',
                    'message' => $this->CI->lang->line('no_accounts_found')
                );

                echo json_encode($data);

            }
        }
        
    }

    /**
     * The public methode create_post creates a post
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function create_post() {

        // Verify if access token is valid
        $user_id = rest_verify_token(array('user_posts'));

        // Get body input
        $body = $this->CI->input->post('body', TRUE);

        // Get time input
        $datetime = $this->CI->input->post('datetime', TRUE);

        // Get current user's time
        $current_date = $this->CI->input->post('current_time', TRUE);

        // Get image input
        $image = $this->CI->input->post('image', TRUE);

        // Get accounts input
        $accounts = $this->CI->input->post('accounts');

        $all_accounts = array();

        // Verify if accounts exists
        if ( $accounts ) {
            $all_accounts = json_decode($accounts, true);
        }

        // Get category
        $category = $this->CI->input->post('category');

        // Verify if category exists
        if ( !$category ) {
            $category = '';
        }

        // Load the Media model
        $this->CI->load->model('media');

        // Default image_id
        $last_media_id = 0;

        if ( $image ) {

            $decoded = base64_decode($image);

            if (is_numeric(strlen($decoded))) {

                // Get upload limit
                $upload_limit = get_option('upload_limit');
                
                if ( !$upload_limit ) {

                    $upload_limit = 6291456;

                } else {

                    $upload_limit = $upload_limit * 1048576;

                }
                
                if ( strlen($decoded) < $upload_limit ) {

                    // Get user storage
                    $user_storage = get_user_option('user_storage', $user_id);                    

                    // Get total storage
                    $total_storage = strlen($decoded) + ($user_storage ? $user_storage : 0);

                    // Verify if user has enough storage
                    if ( $total_storage < $this->CI->plans->get_plan_features($user_id, 'storage') ) {

                        // Save the cover on the server
                        $filename_path = $user_id . '-' . time() . '.png';

                        // Open the file
                        $fop = fopen(FCPATH . 'assets/share/' . $filename_path, 'wb');

                        // Save cover
                        fwrite($fop, $decoded);

                        // Close the opened file
                        fclose($fop);

                        if (file_exists(FCPATH . 'assets/share/' . $filename_path)) {

                            // Set read permission
                            chmod(FCPATH . 'assets/share/' .  $filename_path, 0644); 

                            // Update the user storage
                            update_user_option($user_id, 'user_storage', $total_storage);

                            // Save uploaded file data
                            $last_media_id = $this->CI->media->save_media($user_id, base_url() . 'assets/share/' . $filename_path, 'image', base_url() . 'assets/share/' . $filename_path, strlen($decoded));
                        }

                    }

                }

            }

        }

        $date = (is_numeric(strtotime($datetime))) ? strtotime($datetime) : time();
                
        $current_date = (is_numeric(strtotime($current_date))) ? strtotime($current_date) : time();

        $publish = 2;
        
        // If date is null or has invalid format will be converted to current time or null with strtotime
        if ( $date > $current_date ) {
            
            $d = $date - $current_date;
            
            $date = time() + $d;
            
        } else {
            
            $date = time();
            
        }

        if ( strlen($body) > 5 ) {

            $img = array();
            $video = array();

            // Verify if media id isn't empty
            if ( $last_media_id > 0 && is_numeric($last_media_id) ) {
                
                $img[] = $last_media_id;
                
            }
            
            // Serialize media
            $img = serialize($img);
            $video = serialize($video);

            // Get last post's id
            $lastId = $this->CI->posts_model->save_post($user_id, $body, "", $img, $video, $date, $publish, $category, '');

            // Verify if post was saved
            if ( $lastId ) {

                if ($all_accounts) {

                    // List all selected accounts
                    foreach ($all_accounts as $account) {

                        // Get network's data
                        $get_network = $this->CI->networks_model->get_account($account);

                        // Verify if accoun exists
                        if ($get_network) {

                            // Verify if the user is owner of the account
                            if ($user_id === $get_network[0]->user_id) {

                                // Save post meta
                                $this->CI->posts_model->save_post_meta($lastId, $account, $get_network[0]->network_name, 2, $user_id);
                            }

                        }

                    }

                }

                $data = array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('your_post_was_scheduled')
                );

                echo json_encode($data);

            } else {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('your_post_was_not_scheduled')
                );

                echo json_encode($data); 

            }

        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('your_post_too_short')
            );

            echo json_encode($data);
            exit();

        }
        
    }     

    /**
     * The public method delete_post deletes a post
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */
    public function delete_post() {

        // Verify if access token is valid
        $user_id = rest_verify_token(array('user_posts'));

        // Get post's id
        $post_id = $this->CI->input->post('post_id', TRUE);

        // Verify if post id is valid
        if ( !is_numeric($post_id) ) {

            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_post_found')
            );

            echo json_encode($data);
            exit();

        }
        
        // Get posts by page
        $get_posts = $this->CI->posts_model->delete_post($user_id, $post_id);
        
        // Verify if post was deleted
        if ( $get_posts ) {
            
            $data = array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('post_was_deleted')
            );

            echo json_encode($data);
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data);            
            
        }
        
    }    

}
