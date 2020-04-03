<?php
/**
 * Start Helper
 *
 * This file contains the class Start
 * with methods to process when the Posts's page loads
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Helpers as MidrubBaseUserAppsCollectionHelpers;

/*
 * Start class provides the methods to process the methods when the Posts's page loads
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
*/
class Start {
    
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.4
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
    }

    /**
     * The public method scheduler_display_all_posts displays all scheduled posts
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function scheduler_display_all_posts() {
        
        // Get start's input
        $start = $this->CI->input->get('start', TRUE);
        
        // Get end's input
        $end = $this->CI->input->get('end', TRUE);
        
        // Get published and scheduled posts
        $all_posts = $this->CI->posts_model->get_published_posts( $this->CI->user_id, $start, $end );
        
        if ( $all_posts ) {
            
            $posts = array();
            
            $networks_icon = array();
            
            foreach ( $all_posts as $post ) {
                
                // Get social networks
                $networks = $this->CI->posts_model->all_social_networks_by_post_id( $this->CI->user_id, $post->post_id, 1 );
                
                $icons = array();
                
                if ( $networks ) {
                    
                    foreach ( $networks as $network ) {
                        
                        if ( count($icons) > 6 ) {
                            continue;
                        }
                        
                        if ( in_array( $network['network_name'], $networks_icon ) ) {
                            
                            $icons[] = $networks_icon[$network['network_name']];
                            
                        } else {
                        
                            $network_icon = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_network_icon($network['network_name']);

                            if ( $network_icon ) {
                                $icons[] = $network_icon;
                                $networks_icon[$network['network_name']] = $network_icon;
                            }
                        
                        }
                        
                    }
                    
                }
                
                // Verify if image exists
                if ( $post->img ) {

                    // Get image
                    $img = unserialize($post->img);
                
                } else {
                    
                    $img = array();
                    
                }
                
                // Verify if video exists
                if ( $post->video ) {
                
                    // Get video
                    $video = unserialize($post->video);
                
                } else {
                    
                    $video = array();
                    
                }
                
                // Verify if image exists
                if ( $img ) {
                    $images = get_post_media_array($this->CI->user_id, $img );
                    if ($images) {
                        $img = $images[0]['cover'];
                    }
                }
                
                // Verify if video exists
                if ( $video ) {
                    $videos = get_post_media_array($this->CI->user_id, $video );
                    if ($videos) {
                        $video = $videos[0]['cover'];
                    }
                }                
                
                // Get posts
                $posts[] = array(
                    'post_id' => $post->post_id,
                    'body' => $post->body,
                    'datetime' => $post->datetime,
                    'time' => strtotime($post->datetime),
                    'img' => $img,
                    'video' => $video,
                    'icons' => $icons
                );
                        
            }
            
            $data = array(
                'success' => TRUE,
                'posts' => $posts,
                'time' => time()
            );

            echo json_encode($data);
            
        } else {
            
            $data = array(
                'success' => FALSE
            );

            echo json_encode($data);
            
        }
        
    }
    
    /**
     * The public method insights_display_all_posts will display's user's posts
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_display_all_posts() {
        
        // Get page's input
        $page = $this->CI->input->get('page', TRUE);
        
        // Get search's key input
        $search = $this->CI->input->get('key', TRUE);        
        
        $limit = 10;
        
        $page--;
        
        // Get total posts
        $total = $this->CI->posts_model->get_posts_by_meta($this->CI->user_id, '', '', $search);
        
        // Get posts by page
        $get_posts = $this->CI->posts_model->get_posts_by_meta($this->CI->user_id, ($page * $limit), $limit, $search);
        
        // Verify if posts exists
        if ( $get_posts ) {
            
            $posts = array();
            
            $networks_icon = array();

            foreach ( $get_posts as $post ) {

                if ( in_array( $post->network_name, $networks_icon ) ) {

                    $posts[] = array(
                        'post_id' => $post->post_id,
                        'body' => $post->body,
                        'icon' => $networks_icon[$post->network_name],
                        'sent_time' => $post->sent_time,
                        'meta_id' => $post->meta_id,
                        'network_name' => ucwords( str_replace('_', ' ', $post->network_name) )
                    );

                } else {

                    $network_icon = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_network_icon($post->network_name);

                    if ( $network_icon ) {

                        $posts[] = array(
                            'post_id' => $post->post_id,
                            'body' => $post->body,
                            'icon' => $network_icon,
                            'sent_time' => $post->sent_time,
                            'meta_id' => $post->meta_id,
                            'network_name' => ucwords( str_replace('_', ' ', $post->network_name) )
                        );

                        $networks_icon[$post->network_name] = $network_icon;

                    }

                }

            }
            
            $data = array(
                'success' => TRUE,
                'total' => $total,
                'date' => time(),
                'page' => ($page + 1),
                'posts' => $posts,
                'insights' => $this->CI->lang->line('insights')
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
     * The public method insights_display_all_accounts gets accounts for the Insights tab
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_display_all_accounts() {
        
        // Get page's input
        $page = $this->CI->input->get('page', TRUE);
        
        $limit = 10;
        
        $page--;
        
        // Set default key
        $key = '';
        
        // Get key's input
        $search_key = $this->CI->input->get('key', TRUE);
        
        // Verify if key exists
        if ( $search_key ) {
            $key = $search_key;
        }

        // Get accounts list
        $accounts_list = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->list_accounts_for_composer($this->CI->networks_model->get_accounts( $this->CI->user_id, ($page * $limit), $limit, $key, 1 ));
        
        // Verify accounts were found
        if ( $accounts_list ) {
            
            // Get total accounts list
            $total = $this->CI->networks_model->get_accounts( $this->CI->user_id, 0, 0, $key, 1 );            
            
            $data = array(
                'success' => TRUE,
                'total' => $total,
                'page' => ($page + 1),
                'accounts_list' => $accounts_list,
                'insights' => $this->CI->lang->line('insights')
            );
         
            echo json_encode($data);
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_accounts_found')
            );
         
            echo json_encode($data);            
            
        }
        
    }
    
}

/* End of file start.php */