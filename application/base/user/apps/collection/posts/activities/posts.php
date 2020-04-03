<?php
/**
 * Posts Template
 *
 * This file contains the class Posts
 * with contains the Posts template
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Activities;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Interfaces as MidrubBaseUserAppsCollectionPostsInterfaces;
use MidrubBase\User\Apps\Collection\Posts\Helpers as MidrubBaseUserAppsCollectionPostsHelpers;

/*
 * Posts class provides the methods to process the posts template
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Posts implements MidrubBaseUserAppsCollectionPostsInterfaces\Activities {
    
    /**
     * Class variables
     *
     * @since 0.0.7.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load language
        $this->CI->lang->load( 'posts_activities', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_POSTS);

        // Load the app's models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Posts_model', 'posts_model' );
        
    }

    /**
     * The public method template returns the Ativities template
     * 
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user's ID
     * @param integer $member_id contains the member's ID
     * @param integer $id contains the identificator for the requested template
     * 
     * @return array with template's data
     */ 
    public function template( $user_id, $member_id, $id ) {
        
        // Define the activity's array
        $activity = array();
        
        // Define the activity's header
        $header = '';
        
        // Define the header text
        $has_published_post = $this->CI->lang->line('has_published_post');
        
        // Get post data by user id and post id
        $get_post = $this->CI->posts_model->get_post( $user_id, $id );
        
        // Verify if post exists
        if ( $get_post ) {
            
            // Get the post's time
            $time = $get_post['time'];

            // Verify if the post was published
            if ( $get_post['status'] < 1 ) {

                $time = $this->CI->lang->line('draft_post');
                $has_published_post = $this->CI->lang->line('has_drafted_post');

            } else if ( $time > time() ) {

                $has_published_post = $this->CI->lang->line('has_scheduled_post') . ' ' . strip_tags(calculate_time($time,time()));

            }
            
            // Verify if team's member exists
            if ( $member_id > 0 ) {

                // Load Team Model
                $this->CI->load->model('team');

                // Get team's member information
                $member = $this->CI->team->get_member($user_id, $member_id);

                // If the post was created by a member's team get the data
                if ( $member ) {

                    $header = $member[0]->member_username . ' ' . $has_published_post;

                } else {

                    $header = $this->CI->session->userdata['username'] . ' ' . $has_published_post;

                }

            } else {

                $header = $this->CI->session->userdata['username'] . ' ' . $has_published_post;

            }

            // Set the activity header
            $activity['header'] = $header;
            
            // Set the activity content
            $activity['body'] = $get_post['title'] . ' ' . $get_post['body']; 
                        
            // Get image
            $img = unserialize($get_post['img']);

            // Get video
            $video = unserialize($get_post['video']);
            
            // Set medias
            $activity['medias'] = '<div class="col-xl-12 clean">'
                                    . '<div class="post-history-media">';  
                                    
            // Default images and videos array
            $images = array();
            $videos = array();

            // Verify if image exists
            if ( $img ) {
                
                // Get images
                $images = get_post_media_array($this->CI->user_id, $img );
                
                // Verify if images exists
                if ($images) {
                    
                    foreach ( $images as $image ) {
                        
                        $activity['medias'] .= '<img src="' . $image['body'] . '">';
                        
                    }                  
                    
                }
                
            }

            // Verify if video exists
            if ( $video ) {
                
                $videos = get_post_media_array($this->CI->user_id, $video );
                
                if ($videos) {

                    foreach ( $videos as $vid ) {
                    
                        $activity['medias'] .= '<video controls=""><source src="' . $vid['body'] . '" type="video/mp4"></video>';
                        
                    }
                    
                }
                
            }
            
            if ( !$images && !$videos ) {

                $activity['medias'] = '';
            } else {
                $activity['medias'] .= '</div>'
                                        . '</div>';  
            }
            
            // Get social networks
            $networks = $this->CI->posts_model->all_social_networks_by_post_id( $this->CI->user_id, $id );

            // Profiles array
            $profiles = array();
            
            // Networks icon array
            $networks_icon = array();

            // Verify if networks exists
            if ( $networks ) {

                // List all networks
                foreach ( $networks as $network ) {

                    // Verify if the network's icon was got before
                    if ( in_array( $network['network_name'], $networks_icon ) ) {

                        $profiles[] = array(
                            'user_name' => $network['user_name'],
                            'status' => $network['status'],
                            'icon' => $networks_icon[$network['network_name']],
                            'network_name' => ucwords( str_replace('_', ' ', $network['network_name']) )
                        );

                    } else {

                        // Get the network's icon
                        $network_icon = (new MidrubBaseUserAppsCollectionPostsHelpers\Accounts)->get_network_icon($network['network_name']);

                        // Verify if network's icon exists
                        if ( $network_icon ) {
                            
                            $profiles[] = array(
                                'user_name' => $network['user_name'],
                                'status' => $network['status'],
                                'icon' => $network_icon,
                                'network_name' => ucwords( str_replace('_', ' ', $network['network_name']) )
                            );
                            
                            $networks_icon[$network['network_name']] = $network_icon;
                            
                        }

                    }

                }

            }
            
            // Verify if the post has profiles
            if ( $profiles ) {
                
                // Set the activity's footer
                $activity['footer'] = '<ul class="activities-post-social-accounts">';
                
                // List all profiles
                foreach ( $profiles as $profile ) {
                    
                    // Set success status as default
                    $status = '<i class="icon-check"></i>';
                    
                    // Verify if there is an error
                    if ( $profile['status'] != '1' ) {
                        $status = '<i class="icon-close"></i>';
                    }
                    
                    // Set account to a footer
                    $activity['footer'] .= '<li>'
                                            . $profile['icon'] . ' ' . $profile['user_name'] . ' ' . $status
                                        . '</li>';
                    
                }
                
                $activity['footer'] .= '<ul>';
                
            } else {
                
                $activity['footer'] = '';
                
            }
            
        } else {
            
            // Define the header text
            $has_published_post = $this->CI->lang->line('has_published_drafted_scheduled_post');
            
            // Verify if team's member exists
            if ( $member_id > 0 ) {

                // Load Team Model
                $this->CI->load->model('team');

                // Get team's member information
                $member = $this->CI->team->get_member($user_id, $member_id);

                if ( $member ) {

                    $header = $member[0]->member_username . ' ' . $has_published_post;

                } else {

                    $header = $this->CI->session->userdata['username'] . ' ' . $has_published_post;

                }

            } else {

                $header = $this->CI->session->userdata['username'] . ' ' . $has_published_post;

            }

            $activity['header'] = $header;
            
            $activity['body'] = '';
            
            $activity['medias'] = '';
            
            $activity['footer'] = '';
            
        }
        
        // Set the Posts icon
        $activity['icon'] = '<i class="icon-layers"></i>';

        // Return activity
        return $activity;
        
    }
    
    /**
     * The public method adapter adapts database content for template
     * 
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user's ID
     * @param integer $member_id contains the member's ID
     * @param integer $id contains the identificator for the requested template
     * 
     * @return array with db's data
     */ 
    public function adapter( $user_id, $member_id, $id ) {
        
    }

}

/* End of file posts.php */