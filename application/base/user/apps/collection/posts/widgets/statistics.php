<?php
/**
 * Statistics Widget
 *
 * This file contains the class Statistics
 * with contains the Statistics widget
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Widgets;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Interfaces as MidrubBaseUserAppsCollectionPostsInterfaces;

/*
 * Statistics class provides the methods to process the Statistics widget
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Statistics implements MidrubBaseUserAppsCollectionPostsInterfaces\Widgets {
    
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
        $this->CI->lang->load( 'widgets', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER . 'apps/collection/posts/');
        
    }

    /**
     * The public method display_widget will return the widget html
     * 
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user's id
     * @param string $plan_end contains the plan's end period time
     * @param object $plan_data contains the user's plan's data
     * 
     * @return array with widget html
     */ 
    public function display_widget( $user_id, $plan_end, $plan_data ) {
        
        // Get the widget info
        $widget_info = $this->widget_info();
        
        // Load the Team Model
        $this->CI->load->model('team');

        // Load the Posts Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Posts_model', 'posts_model' );

        // Load the RSS Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Rss_model', 'rss_model' );
        
        // Get members
        $get_members = $this->CI->team->get_members( $this->CI->user_id, 0, 5 );
        
        $team_members = '';
        
        if ( $get_members ) {
            
            foreach ( $get_members as $member ) {
                
                $team_members .= '<div class="row">'
                                    . '<div class="col-xl-8 col-md-8 col-sm-6 col-6 clean">'							 
                                        . '<img src="//gravatar.com/avatar/' . md5($member->member_email) . '?s=200">'
                                        . '<p>' . $member->member_username . '</p>'		 		 
                                    . '</div>'
                                    . '<div class="col-xl-4 col-md-4 col-sm-6 col-6 clean text-right">'
                                        . '<a href="' . site_url('user/team') . '#' . $member->member_id . '" class="btn btn-outline-info"><i class="icon-login"></i> ' . $this->CI->lang->line('posts_manage') . '</a>'
                                    . '</div>'
                                . '</div>';
                
            }
            
        } else {
            
            $team_members = '<p class="no-results-found">' . $this->CI->lang->line('posts_no_members') . '</p>';
            
        }
        
        // Get scheduled posts
        $scheduled_posts = $this->CI->posts_model->get_scheduled_posts($this->CI->user_id, 5);
        
        $scheduled = '';
        
        if ( $scheduled_posts ) {
            
            $scheduled .= '<ul>';
            
            foreach ( $scheduled_posts as $sched ) {
                
                $post = '';
                
                if ( strlen($sched->body) > 50 ) {
                    
                    $post = mb_substr(strip_tags($sched->body), 0, 45) . "...";
                    
                } else {
                    
                    $post = strip_tags($sched->body);
                    
                }
                
                $scheduled .= '<li>'
                                . '<div class="row">'
                                    . '<div class="col-xl-8 col-6">'
                                        . '<h4>'
                                            . '<i class="icon-clock"></i>'
                                            . $post
                                        . '</h4>'
                                        . '<p>' . calculate_time($sched->sent_time, time()) . '</p>'
                                    . '</div>'
                                    . '<div class="col-xl-4 col-6 clean text-right">'
                                        . '<a href="#" class="btn btn-outline-info delete-scheduled-post" data-id="' . $sched->post_id . '"><i class="icon-trash"></i> ' . $this->CI->lang->line('posts_delete') . '</a>'
                                    . '</div>'                                                            
                                . '</div>'
                            . '</li>';
                
            }
            
            $scheduled .= '</ul>';
            
        } else {
            
            $scheduled = '<p class="no-results-found">' . $this->CI->lang->line('posts_no_found') . '</p>';
            
        }
        
        // Get published rss posts
        $published_posts = $this->CI->rss_model->get_posts($this->CI->user_id, 0, 5);
        
        $published = '';
        
        if ( $published_posts ) {
            
            $published .= '<ul>';
            
            foreach ( $published_posts as $pub ) {
                
                $title = '';
                
                if ( strlen($pub->title) > 50 ) {
                    
                    $title = mb_substr(strip_tags($pub->title), 0, 45) . "...";
                    
                } else {
                    
                    $title = strip_tags($pub->title);
                    
                }
                
                $published .= '<li>'
                                . '<div class="row">'
                                    . '<div class="col-xl-8 col-6">'
                                        . '<h4>'
                                            . '<i class="icon-feed"></i>'
                                            . $title
                                        . '</h4>'
                                        . '<p>' . calculate_time($pub->published, time()) . '</p>'
                                    . '</div>'
                                    . '<div class="col-xl-4 col-6 clean text-right">'
                                        . '<a href="' . site_url('user/app/posts?q=rss&rss_id=' . $pub->rss_id). '" class="btn btn-outline-info"><i class="icon-login"></i> ' . $this->CI->lang->line('posts_manage') . '</a>'
                                    . '</div>'                                                          
                                . '</div>'
                            . '</li>';
                
            }
            
            $published .= '</ul>';
            
        } else {
            
            $published = '<p class="no-results-found">' . $this->CI->lang->line('no_posts_found') . '</p>';
            
        }
        
        $team = '';
        
        $format = '6';
        
        if ( !$this->CI->session->userdata( 'member' ) ) {
            
            $team = '<div class="col-xl-4 stats-single">'
                        . '<div class="col-xl-12 theme-box">'
                            . '<div class="row">'
                                . '<div class="col-xl-12">'
                                    . '<h3>'
                                        . $this->CI->lang->line('posts_my_team')
                                        . '<div class="dropdown float-right show">'
                                            . '<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                                . '<i class="icon-arrow-down"></i>'
                                            . '</a>'

                                            . '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">'
                                                . '<a class="dropdown-item" href="' . site_url('user/team') . '">' . $this->CI->lang->line('posts_new_member') . '</a>'
                                            . '</div>'
                                        . '</div>'
                                    . '</h3>'
                                . '</div>'
                            . '</div>'
                            . '<div class="team-list">'
                                . $team_members
                            . '</div>'
                        . '</div>'
                    . '</div>';
            
            $format = '4';
            
        }

        // Set the Widget's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/posts/styles/css/widgets.css?ver=' . MD_VER), 'text/css', 'all'));

        // Set the Widget Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/posts/js/widgets.js?ver=' . MD_VER)));

        return array(
            'widget' => ''
            . $team
            . '<div class="col-xl-' . $format . ' stats-single">'
                . '<div class="col-xl-12 theme-box">'
                    . '<div class="row">'
                        . '<div class="col-xl-12">'
                            . '<h3>'
                                . $this->CI->lang->line('posts_scheduled_posts')
                            . '</h3>'
                        . '</div>'
                    . '</div>'
                    . '<div class="schedule-list">'
                        . '<div class="row">'
                            . '<div class="col-xl-12">'
                                . $scheduled
                            . '</div>'
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>'
            . '<div class="col-xl-' . $format . ' stats-single">'
                . '<div class="col-xl-12 theme-box">'
                    . '<div class="row">'
                        . '<div class="col-xl-12">'
                            . '<h3>'
                                . $this->CI->lang->line('posts_last_rss_posts')
                            . '</h3>'
                        . '</div>'
                    . '</div>'
                    . '<div class="published-list">'
                        . '<div class="row">'
                            . '<div class="col-xl-12">'
                                . $published
                            . '</div>'
                        . '</div>'
                    . '</div> '
                . '</div>'
            . '</div>',
            'order' => $widget_info['order']);
        
    }
    
    /**
     * The public method widget_helper processes the widget content
     * 
     * @since 0.0.7.0
     * 
     * @param integer $user_id contains the user's id
     * @param string $plan_end contains the plan's end period time
     * @param object $plan_data contains the user's plan's data
     * 
     * @return array with widget's content
     */ 
    public function widget_helper( $user_id, $plan_end, $plan_data ) {
        
    }
    
    /**
     * The public method widget_info contains the widget options
     * 
     * @since 0.0.7.0
     * 
     * @return array with widget information
     */ 
    public function widget_info() {
        
        return array(
            'name' => $this->CI->lang->line('posts_statistics'),
            'slug' => 'app_posts_posts_statistics',
            'order' => 2
        );
        
    }

}

