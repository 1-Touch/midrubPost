<?php
/**
 * Facebook Pages
 *
 * This file gets the insights for Facebook Pages
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Insights;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Interfaces as MidrubBaseUserAppsCollectionPostsInterfaces;

/*
 * Facebook_pages class loads the insigts
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */
class Facebook_pages implements MidrubBaseUserAppsCollectionPostsInterfaces\Insights {
    
   
    /**
     * Class variables
     *
     * @since 0.0.7.0
     */
    protected
            $CI, $url = MIDRUB_POSTS_FACEBOOK_GRAPH_URL;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.0
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }
    
    /**
     * Contains the Class's configurations
     *
     * @since 0.0.7.0
     * 
     * return array with class's configuration
     */
    public function configuration() {
        
        // Create the config array
        $config = array();
        
        // Set the post deletion
        $config['post_deletion'] = true;
        
        // Set the account's
        $config['account_insights'] = true;
        
        // Set the post's insights
        $config['post_insights'] = true;
        
        // Set the words
        $config['words'] = array (
            'reply' => $this->CI->lang->line('reply'),
            'delete' => $this->CI->lang->line('delete'),
            'insights' => $this->CI->lang->line('insights'),
            'delete_post' => $this->CI->lang->line('delete_post'),
            'no_posts_found' => $this->CI->lang->line('no_posts_found')
        );
        
        // Return config
        return $config;
        
    }
    
    /**
     * The public method get_account gets all accounts posts
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with posts or string with non found message
     */
    public function get_account($network) {
        
        $posts = json_decode( get( $this->url . $network[0]->net_id . '/feed?access_token=' . $network[0]->secret ), true );

        // Verify if posts exists
        if ( !empty($posts['data']) ) {
            
            $all_posts = array();
            
            foreach ( $posts['data'] as $post  ) {
                
                if ( empty($post['message']) ) {
                    continue;
                }
                
                $network[0]->post_id = $post['id'];
                
                $all_posts[] = array(
                    'id' => $post['id'],
                    'title' => '',
                    'content' => $post['message'],
                    'created_time' => $post['created_time'],
                    'reactions' => $this->get_reactions($network)
                );
                
            }
            
            return $all_posts;
            
        } else {
            
            return $this->CI->lang->line('no_comments');
            
        }
        
    }
    
    /**
     * The public method get_reactions gets the post's reactions
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with reactions or empty array
     */
    public function get_reactions($network) {
        
        // Create reactions array
        $reactions = array();
        
        // Get comments
        $get_comments = $this->get_comments($network);
        
        // Add comments to $reactions
        $reactions[0] = array(
            'name' => '<i class="icon-speech"></i> ' . $this->CI->lang->line('comments'),
            'slug' => 'comments',
            'response' => $get_comments,
            'placeholder' => $this->CI->lang->line('enter_comment'),
            'post_id' => $network[0]->post_id,
            'form' => true,
            'delete' => true,
            'reply' => true
        );
        
        // Get reactions
        $get_reactions = $this->get_likes($network);
        
        // Add reactions to $reactions
        $reactions[1] = array(
            'name' => '<i class="icon-people"></i> ' . $this->CI->lang->line('reactions'),
            'slug' => 'reactions',
            'response' => $get_reactions,
            'placeholder' => '',
            'post_id' => $network[0]->post_id,
            'form' => false,
            'delete' => false,
            'reply' => false
        );
        
        return $reactions;
        
    }    
    
    /**
     * The public method get_comments gets the post's comments
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with comments or string
     */
    public function get_comments($network) {
        
        $comments = json_decode( get( $this->url . $network[0]->post_id . '/comments?access_token=' . $network[0]->secret ), true );
        
        if ( !empty($comments['data']) ) {
            
            $all_comments = array();
            
            foreach ( $comments['data'] as $comment ) {

                $name = $network[0]->user_name;
                $id = $network[0]->net_id;

                if ( isset($comment['from']['name']) ) {
                    $id = $comment['from']['id'];
                    $name = $comment['from']['name'];

                }
                
                $all_comments[$comment['id']] = array(
                    'created_time' => $comment['created_time'],
                    'message' => $comment['message'],
                    'from' => array(
                        'name' => $name,
                        'id' => $id,
                        'link' => 'https://www.facebook.com/' . $id,
                        'user_picture' => 'https://graph.facebook.com/' . $id . '/picture?type=square&access_token=' . $network[0]->secret
                        
                    ),
                    'id' => $comment['id']
                );
                
                $replies = json_decode( get( $this->url . $comment['id'] . '/comments?access_token=' . $network[0]->secret ), true );
                
                if ( !empty($replies['data']) ) {

                    $all_replies = array();

                    foreach ( $replies['data'] as $reply ) {

                        $name = $network[0]->user_name;
                        $id = $network[0]->net_id;
        
                        if ( !empty($reply['from']['name']) ) {
                            $id = $reply['from']['id'];
                            $name = $reply['from']['name'];
        
                        }

                        $all_replies[] = array(
                            'created_time' => $reply['created_time'],
                            'message' => $reply['message'],
                            'from' => array(
                                'name' => $name,
                                'id' => $id,
                                'link' => 'https://www.facebook.com/' . $id,
                                'user_picture' => 'https://graph.facebook.com/' . $id . '/picture?type=square&access_token=' . $network[0]->secret

                            ),
                            'id' => $reply['id']
                        );

                    }
                    
                    $all_comments[$comment['id']]['replies'] = $all_replies;
                    
                }
                
            }
            
            return array_values($all_comments);
            
        } else {
            
            return $this->CI->lang->line('no_comments');
            
        }
        
    }
    
    /**
     * The public method get_likes gets the post's likes
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with likes or string
     */
    public function get_likes($network) {
        
        $reactions = json_decode( get( $this->url . $network[0]->post_id . '/reactions?access_token=' . $network[0]->secret ), true );
        
        if ( !empty($reactions['data']) ) {
            
            $all_reactions = array();
            
            foreach ( $reactions['data'] as $reaction ) {
                
                $all_reactions[$reaction['id']] = array(
                    'created_time' => '',
                    'message' => str_replace(array('LIKE', 'LOVE', 'WOW', 'HAHA', 'SAD', 'ANGRY', 'THANKFUL'), array('<i class="icon-like"></i>', '<i class="icon-heart"></i>', '<i class="far fa-hand-peace"></i>', '<i class="icon-emoticon-smile"></i>', '<i class="far fa-sad-tear"></i>', '<i class="far fa-angry"></i>', '<i class="far fa-handshake"></i>'),$reaction['type']),
                        'from' => array(
                            'name' => $reaction['name'],
                            'id' => $reaction['id'],
                            'link' => 'https://www.facebook.com/' . $reaction['id'],
                            'user_picture' => 'https://graph.facebook.com/' . $reaction['id'] . '/picture?type=square&access_token=' . $network[0]->secret

                        ),
                    'id' => $reaction['id']
                );
                
                $all_reactions[$reaction['id']]['replies'] = array();
                
            }
            
            return array_values($all_reactions);
            
        } else {
            
            return $this->CI->lang->line('no_reactions');
            
        }
        
    }
    
    /**
     * The public method get_insights gets the post's insights
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * @param object $type contains the insights type
     * 
     * @return array with insights or string
     */
    public function get_insights($network, $type) {
        
        // Display insights by type
        switch ( $type ) {
            
            case 'post':
        
                // Create insights array
                $insights = array();

                // Get post impressions
                $post_impressions = json_decode( get( $this->url . $network[0]->post_id . '/insights/post_impressions/lifetime?access_token=' . $network[0]->secret ), true );

                $impressions = 0;

                if ( !empty($post_impressions['data']) ) {

                    $impressions = $post_impressions['data'][0]['values'][0]['value'];

                }

                // Add impressions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_total_impressions'),
                    'value' => $impressions,
                    'background_color' => 'rgba(0, 99, 132, 0.6)',
                    'border_color' => 'rgba(0, 99, 132, 1)'
                );

                // Get paid post impressions
                $paid_impressions = json_decode( get( $this->url . $network[0]->post_id . '/insights/post_impressions_paid/lifetime?access_token=' . $network[0]->secret ), true );

                $pimpressions = 0;

                if ( !empty($paid_impressions['data']) ) {

                    $pimpressions = $paid_impressions['data'][0]['values'][0]['value'];

                }

                // Add impressions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_paid_impressions'),
                    'value' => $pimpressions,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
                );

                // Get organic post impressions
                $organic_impressions = json_decode( get( $this->url . $network[0]->post_id . '/insights/post_impressions_organic/lifetime?access_token=' . $network[0]->secret ), true );

                $oimpressions = 0;

                if ( !empty($organic_impressions['data']) ) {

                    $oimpressions = $organic_impressions['data'][0]['values'][0]['value'];

                }

                // Add impressions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_organic_impressions'),
                    'value' => $oimpressions,
                    'background_color' => 'rgba(60, 99, 132, 0.6)',
                    'border_color' => 'rgba(60, 99, 132, 1)'
                );   

                // Get fan impressions
                $fan_impressions = json_decode( get( $this->url . $network[0]->post_id . '/insights/post_impressions_fan/lifetime?access_token=' . $network[0]->secret ), true );

                $fimpressions = 0;

                if ( !empty($fan_impressions['data']) ) {

                    $fimpressions = $fan_impressions['data'][0]['values'][0]['value'];

                }

                // Add impressions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_fan_impressions'),
                    'value' => $fimpressions,
                    'background_color' => 'rgba(90, 99, 132, 0.6)',
                    'border_color' => 'rgba(90, 99, 132, 1)'
                );

                // Get engaged users
                $engaged_users = json_decode( get( $this->url . $network[0]->post_id . '/insights/post_engaged_users/lifetime?access_token=' . $network[0]->secret ), true );

                $enusers = 0;

                if ( !empty($engaged_users['data']) ) {

                    $enusers = $engaged_users['data'][0]['values'][0]['value'];

                }

                // Add engaged users to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_engaged_users'),
                    'value' => $enusers,
                    'background_color' => 'rgba(120, 99, 132, 0.6)',
                    'border_color' => 'rgba(120, 99, 132, 1)'
                );       

                // Get post clicks
                $post_clicks = json_decode( get( $this->url . $network[0]->post_id . '/insights/post_clicks/lifetime?access_token=' . $network[0]->secret ), true );

                $pclicks = 0;

                if ( !empty($post_clicks['data']) ) {

                    $pclicks = $post_clicks['data'][0]['values'][0]['value'];

                }

                // Add post clicks to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_posts_clicks'),
                    'value' => $pclicks,
                    'background_color' => 'rgba(150, 99, 132, 0.6)',
                    'border_color' => 'rgba(150, 99, 132, 1)'
                );

                // Get negative feedback
                $negative_feedback = json_decode( get( $this->url . $network[0]->post_id . '/insights/post_negative_feedback/lifetime?access_token=' . $network[0]->secret ), true );

                $nfeedback = 0;

                if ( !empty($negative_feedback['data']) ) {

                    $nfeedback = $negative_feedback['data'][0]['values'][0]['value'];

                }

                // Add post clicks to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_negative_feedback'),
                    'value' => $nfeedback,
                    'background_color' => 'rgba(180, 99, 132, 0.6)',
                    'border_color' => 'rgba(180, 99, 132, 1)'
                );        

                return $insights;
                
                break;
                
            case 'account':
                
                // Create insights array
                $insights = array();

                // Get page views
                $page_views = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_views_total/days_28?access_token=' . $network[0]->secret ), true );

                $views = 0;

                if ( !empty($page_views['data']) ) {
                    
                    foreach ( $page_views['data'][0]['values'] as $values ) {
                        
                        $views = $views + $values['value'];
                        
                    }

                }

                // Add views to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_views_28_days'),
                    'value' => $views,
                    'background_color' => 'rgba(0, 99, 132, 0.6)',
                    'border_color' => 'rgba(0, 99, 132, 1)'
                );
                
                // Get page fans
                $page_fans = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_fans/lifetime?access_token=' . $network[0]->secret ), true );

                $fans = 0;

                if ( !empty($page_fans['data']) ) {
                    
                    foreach ( $page_fans['data'][0]['values'] as $values ) {
                        
                        $fans = $fans + $values['value'];
                        
                    }

                }

                // Add fans to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_page_fans'),
                    'value' => $fans,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
                );
                
                // Get page video views
                $video_views = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_video_views/days_28?access_token=' . $network[0]->secret ), true );

                $vviews = 0;

                if ( !empty($video_views['data']) ) {
                    
                    foreach ( $video_views['data'][0]['values'] as $values ) {
                        
                        $vviews = $vviews + $values['value'];
                        
                    }

                }

                // Add views to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_video_views_28_days'),
                    'value' => $vviews,
                    'background_color' => 'rgba(60, 99, 132, 0.6)',
                    'border_color' => 'rgba(60, 99, 132, 1)'
                );
                
                // Get page engaged users
                $engaged_users = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_engaged_users/days_28?access_token=' . $network[0]->secret ), true );

                $engaged = 0;

                if ( !empty($engaged_users['data']) ) {
                    
                    foreach ( $engaged_users['data'][0]['values'] as $values ) {
                        
                        $engaged = $engaged + $values['value'];
                        
                    }

                }

                // Add engages to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_engaged_users_28_days'),
                    'value' => $engaged,
                    'background_color' => 'rgba(90, 99, 132, 0.6)',
                    'border_color' => 'rgba(90, 99, 132, 1)'
                );    
                
                // Get page page posts impressions
                $page_posts_impressions = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_posts_impressions/days_28?access_token=' . $network[0]->secret ), true );

                $impressions = 0;

                if ( !empty($page_posts_impressions['data']) ) {
                    
                    foreach ( $page_posts_impressions['data'][0]['values'] as $values ) {
                        
                        $impressions = $impressions + $values['value'];
                        
                    }

                }

                // Add engages to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_impressions_28_days'),
                    'value' => $impressions,
                    'background_color' => 'rgba(120, 99, 132, 0.6)',
                    'border_color' => 'rgba(120, 99, 132, 1)'
                );
                
                // Get page page total actions
                $total_actions = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_total_actions/days_28?access_token=' . $network[0]->secret ), true );

                $actions = 0;

                if ( !empty($total_actions['data']) ) {
                    
                    foreach ( $total_actions['data'][0]['values'] as $values ) {
                        
                        $actions = $actions + $values['value'];
                        
                    }

                }

                // Add actions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_cta_28_days'),
                    'value' => $actions,
                    'background_color' => 'rgba(150, 99, 132, 0.6)',
                    'border_color' => 'rgba(150, 99, 132, 1)'
                ); 
                
                // Get page page consumptions
                $page_consumptions = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_consumptions/days_28?access_token=' . $network[0]->secret ), true );

                $consumptions = 0;

                if ( $page_consumptions['data'] ) {
                    
                    foreach ( $page_consumptions['data'][0]['values'] as $values ) {
                        
                        $consumptions = $consumptions + $values['value'];
                        
                    }

                }

                // Add consumptions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_consumptions_28_days'),
                    'value' => $consumptions,
                    'background_color' => 'rgba(180, 99, 132, 0.6)',
                    'border_color' => 'rgba(180, 99, 132, 1)'
                );  
                
                // Get page page reactions
                $page_actions_post_reactions_total = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_actions_post_reactions_total/day?access_token=' . $network[0]->secret ), true );

                $reactions = 0;

                if ( !empty($page_actions_post_reactions_total['data']) ) {
                    
                    foreach ( $page_actions_post_reactions_total['data'][0]['values'] as $values ) {
                        
                        $react = array_values($values['value']);
                        
                        if ( isset($react[0]) ) {
                        
                            $reactions = $reactions + trim($react[0]);
                        }
                        
                    }

                }

                // Add reactions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_reactions_today'),
                    'value' => $reactions,
                    'background_color' => 'rgba(210, 99, 132, 0.6)',
                    'border_color' => 'rgba(210, 99, 132, 1)'
                );  
                
                // Get page page stories
                $page_content_activity = json_decode( get( $this->url . $network[0]->net_id . '/insights/page_content_activity/days_28?access_token=' . $network[0]->secret ), true );

                $page_content = 0;

                if ( !empty($page_content_activity['data']) ) {
                    
                    foreach ( $page_content_activity['data'][0]['values'] as $values ) {
                        
                        $page_content = $page_content + $values['value'];
                        
                    }

                }

                // Add stories to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_stories_28_days'),
                    'value' => $page_content,
                    'background_color' => 'rgba(240, 99, 132, 0.6)',
                    'border_color' => 'rgba(240, 99, 132, 1)'
                );                 
                
                return $insights;
                
                break;
                
        }
        
    }
    
    /**
     * The public method post send submit data to social network
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * @param string type contains the data type
     * @param string $msg contains the data to send
     * @param string $parent contains the parent
     * 
     * @return array with status or string
     */
    public function post($network, $type, $msg, $parent = NULL) {
        
        switch ($type) {
            
            case 'comments':
                
                $id = @$network[0]->post_id;
                
                if ( $parent ) {
                    $id = $parent;
                }
                
                $post = json_decode(post( $this->url . $id . '/comments', array('message' => $msg), $network[0]->secret), true);
                
                if ( isset($post['id']) ) {
                    
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('comment_published')
                    );
                    
                } else {
                    
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('comment_not_published')
                    );
                    
                }
            
                break;        
            
        }
        
    }
    
    /**
     * The public method delete deletes data from social network
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * @param string type contains the data type
     * @param string $parent contains the parent
     * 
     * @return array with status or string
     */
    public function delete($network, $type, $parent = NULL) {
        
        switch ($type) {
            
            case 'comments':
                
                $post = json_decode(delete( $this->url . $parent, $network[0]->secret), true);
                
                if ( isset($post['success']) ) {
                    
                    return $this->CI->lang->line('comment_deleted');
                    
                } else {
                    
                    return false;
                    
                }
            
                break;
                
            case 'post':
                
                $post = json_decode(delete( $this->url . $network[0]->post_id, $network[0]->secret), true);
                
                if ( isset($post['success']) ) {
                    
                    return $this->CI->lang->line('post_was_deleted');
                    
                } else {
                    
                    return false;
                    
                }
            
                break; 
            
        }
        
    }    

}
