<?php
/**
 * Instagram insights
 *
 * This file gets the insights for Instagram Insights
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
 * Instagram_insights class loads the insigts for Instagram
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */
class Instagram_insights implements MidrubBaseUserAppsCollectionPostsInterfaces\Insights {
    
   
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
        $config['post_deletion'] = false;
        
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
        
        $posts = json_decode( get( $this->url . $network[0]->net_id . '/media?fields=id,media_type,media_url,timestamp&access_token=' . $network[0]->token ) );

        // Verify if posts exists
        if ( @$posts->data ) {
            
            $all_posts = array();
            
            foreach ( $posts->data as $post  ) {
                
                $network[0]->post_id = $post->id;
                
                $content = '<img src="' . $post->media_url . '">';
                
                if ( $post->media_type !== 'IMAGE' ) {
                    $content = '<video controls><source src="' . $post->media_url . '" type="video/mp4"></video>';
                }
                
                $all_posts[] = array(
                    'id' => $post->id,
                    'title' => '',
                    'content' => $content,
                    'created_time' => $post->timestamp,
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
        
        $comments = json_decode( get( $this->url . $network[0]->post_id . '/comments?fields=user,username,text,timestamp&access_token=' . $network[0]->token ), true );

        if ( isset($comments['data']) ) {
            
            $all_comments = array();
            
            foreach ( $comments['data'] as $comment ) {
                
                if ( !isset($comment['username']) ) {
                    continue;
                }

                $all_comments[$comment['id']] = array(
                    'created_time' => $comment['timestamp'],
                    'message' => $comment['text'],
                    'from' => array(
                        'name' => $comment['username'],
                        'id' => $comment['id'],
                        'link' => 'https://www.instagram.com/' . $comment['username'],
                        'user_picture' => base_url() . 'assets/img/avatar-placeholder.png'
                        
                    ),
                    'id' => $comment['id']
                );

                $all_replies = array();
                
                $replies = json_decode( get( $this->url . $comment['id'] . '/replies?fields=user,username,text,timestamp&access_token=' . $network[0]->token ), true );
                
                if ( isset($replies['data']) ) {

                        foreach ( $replies['data'] as $reply ) {

                            $all_replies[] = array(
                            'created_time' => $reply['timestamp'],
                            'message' => $reply['text'],
                            'from' => array(
                                'name' => $reply['username'],
                                'id' => $reply['id'],
                                'link' => 'https://www.instagram.com/' . $reply['username'],
                                'user_picture' => base_url() . 'assets/img/avatar-placeholder.png'
                            ),
                            'id' => $reply['id']
                        );
                            
                    }
                    
                }
                
                $all_comments[$comment['id']]['replies'] = $all_replies;
                
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
        
        $reactions = json_decode( get( $this->url . $network[0]->post_id . '/reactions?access_token=' . $network[0]->token ) );
        
        if ( @$reactions->data ) {
            
            $all_reactions = array();
            
            foreach ( $reactions->data as $reaction ) {
                
                $all_reactions[$reaction->id] = array(
                    'created_time' => '',
                    'message' => str_replace(array('LIKE', 'LOVE', 'WOW', 'HAHA', 'SAD', 'ANGRY', 'THANKFUL'), array('<i class="icon-like"></i>', '<i class="icon-heart"></i>', '<i class="far fa-hand-peace"></i>', '<i class="icon-emoticon-smile"></i>', '<i class="far fa-sad-tear"></i>', '<i class="far fa-angry"></i>', '<i class="far fa-handshake"></i>'),$reaction->type),
                        'from' => array(
                            'name' => $reaction->name,
                            'id' => $reaction->id,
                            'link' => 'https://www.facebook.com/' . $reaction->id,
                            'user_picture' => 'https://graph.facebook.com/' . $reaction->id . '/picture?type=square&access_token=' . $network[0]->token

                        ),
                    'id' => $reaction->id
                );
                
                $all_reactions[$reaction->id]['replies'] = array();
                
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
                
                    // Get Media Insights
                    $media_insights = json_decode( get( $this->url . $network[0]->post_id . '/insights?metric=impressions,reach,engagement,saved&access_token=' . $network[0]->token ) );

                    $impressions = 0;

                    if ( @$media_insights->data ) {

                        foreach ( $media_insights->data[0]->values as $values ) {

                            $impressions = $impressions + $values->value;

                        }

                    }

                    // Add impressions to $insights
                    $insights[] = array(
                        'name' => $this->CI->lang->line('posts_impressions'),
                        'value' => $impressions,
                        'background_color' => 'rgba(0, 99, 132, 0.6)',
                        'border_color' => 'rgba(0, 99, 132, 1)'
                    );

                    $reach = 0;

                    if ( @$media_insights->data ) {

                        foreach ( $media_insights->data[1]->values as $values ) {

                            $reach = $reach + $values->value;

                        }

                    }

                    // Add reaches to $insights
                    $insights[] = array(
                        'name' => $this->CI->lang->line('posts_reaches'),
                        'value' => $reach,
                        'background_color' => 'rgba(30, 99, 132, 0.6)',
                        'border_color' => 'rgba(30, 99, 132, 1)'
                    );  
                    
                    $engagement = 0;

                    if ( @$media_insights->data ) {

                        foreach ( $media_insights->data[2]->values as $values ) {

                            $engagement = $engagement + $values->value;

                        }

                    }

                    // Add engagements to $insights
                    $insights[] = array(
                        'name' => $this->CI->lang->line('posts_engagements'),
                        'value' => $engagement,
                        'background_color' => 'rgba(60, 99, 132, 0.6)',
                        'border_color' => 'rgba(60, 99, 132, 1)'
                    ); 
                    
                    $saves = 0;

                    if ( @$media_insights->data ) {

                        foreach ( $media_insights->data[3]->values as $values ) {

                            $saves = $saves + $values->value;

                        }

                    }

                    // Add saves to $insights
                    $insights[] = array(
                        'name' => $this->CI->lang->line('posts_saves'),
                        'value' => $saves,
                        'background_color' => 'rgba(90, 99, 132, 0.6)',
                        'border_color' => 'rgba(90, 99, 132, 1)'
                    ); 

                return $insights;
                
                break;
                
            case 'account':
                
                // Create insights array
                $insights = array();

                // Get Account Insights for 28 days
                $account_impressions_reach = json_decode( get( $this->url . $network[0]->net_id . '/insights?metric=impressions,reach&period=days_28&access_token=' . $network[0]->token ) );

                $impressions = 0;

                if ( @$account_impressions_reach->data ) {
                    
                    foreach ( $account_impressions_reach->data[0]->values as $values ) {
                        
                        $impressions = $impressions + $values->value;
                        
                    }

                }

                // Add impressions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_impressions_28_days'),
                    'value' => $impressions,
                    'background_color' => 'rgba(0, 99, 132, 0.6)',
                    'border_color' => 'rgba(0, 99, 132, 1)'
                );

                $reach = 0;

                if ( @$account_impressions_reach->data ) {
                    
                    foreach ( $account_impressions_reach->data[1]->values as $values ) {
                        
                        $reach = $reach + $values->value;
                        
                    }

                }

                // Add reaches to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_reaches_28_days'),
                    'value' => $reach,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
                );  
                
                // Get Account Insights for 1 day
                $account_insights = json_decode( get( $this->url . $network[0]->net_id . '/insights?metric=profile_views,phone_call_clicks,email_contacts,website_clicks,follower_count&period=day&access_token=' . $network[0]->token ) );

                $profile_views = 0;

                if ( @$account_insights->data ) {
                    
                    foreach ( $account_insights->data[0]->values as $values ) {
                        
                        $profile_views = $profile_views + $values->value;
                        
                    }

                }

                // Add profile views to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_profile_views'),
                    'value' => $profile_views,
                    'background_color' => 'rgba(60, 99, 132, 0.6)',
                    'border_color' => 'rgba(60, 99, 132, 1)'
                );

                $phone_call_clicks = 0;

                if ( @$account_insights->data ) {
                    
                    foreach ( $account_insights->data[1]->values as $values ) {
                        
                        $phone_call_clicks = $phone_call_clicks + $values->value;
                        
                    }

                }

                // Add phone calls to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_phone_calls'),
                    'value' => $phone_call_clicks,
                    'background_color' => 'rgba(90, 99, 132, 0.6)',
                    'border_color' => 'rgba(90, 99, 132, 1)'
                );  
                
                $email_contacts = 0;

                if ( @$account_insights->data ) {
                    
                    foreach ( $account_insights->data[2]->values as $values ) {
                        
                        $email_contacts = $email_contacts + $values->value;
                        
                    }

                }

                // Add emails contacts to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_emails'),
                    'value' => $email_contacts,
                    'background_color' => 'rgba(120, 99, 132, 0.6)',
                    'border_color' => 'rgba(120, 99, 132, 1)'
                );  
                
                $website_clicks = 0;

                if ( @$account_insights->data ) {
                    
                    foreach ( $account_insights->data[3]->values as $values ) {
                        
                        $email_contacts = $email_contacts + $values->value;
                        
                    }

                }

                // Add website clicks to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_website_clicks'),
                    'value' => $website_clicks,
                    'background_color' => 'rgba(180, 99, 132, 0.6)',
                    'border_color' => 'rgba(180, 99, 132, 1)'
                );
                
                $follower_count = 0;

                if ( @$account_insights->data ) {
                    
                    foreach ( $account_insights->data[4]->values as $values ) {
                        
                        $follower_count = $follower_count + $values->value;
                        
                    }

                }

                // Add new followers to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_new_followers'),
                    'value' => $follower_count,
                    'background_color' => 'rgba(180, 99, 132, 0.6)',
                    'border_color' => 'rgba(180, 99, 132, 1)'
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

                $post = json_decode(post( $this->url . $parent . '/replies', array('message' => $msg), $network[0]->token), true);

                if ( !isset($post['id']) ) {
                    $post = json_decode(post( $this->url . $parent . '/comments', array('message' => $msg), $network[0]->token), true);
                }
                
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
                
                $post = json_decode(delete( $this->url . $parent, $network[0]->token), true);
                
                if ( isset($post['success']) ) {
                    
                    return $this->CI->lang->line('comment_deleted');
                    
                } else {
                    
                    return false;
                    
                }
            
                break;
            
        }
        
    }    

}
