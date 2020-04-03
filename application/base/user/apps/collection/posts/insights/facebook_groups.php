<?php
/**
 * Facebook Groups
 *
 * This file gets the insights for Facebook Groups
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
 * Facebook_groups class loads the insigts
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */
class Facebook_groups implements MidrubBaseUserAppsCollectionPostsInterfaces\Insights {
    
   
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
        $config['post_insights'] = false;
        
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
        
        $posts = json_decode( get( $this->url . $network[0]->net_id . '/feed?access_token=' . $network[0]->token ), true );
        
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
                    'created_time' => !empty($post['created_time'])?$post['created_time']:$post['updated_time'],
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
            'form' => false,
            'delete' => false,
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
        
        $comments = json_decode( get( $this->url . $network[0]->post_id . '/comments?access_token=' . $network[0]->token ), true );
        
        if ( !empty($comments['data']) ) {
            
            $all_comments = array();
            
            foreach ( $comments['data'] as $comment ) {
                
                if ( empty($comment['from']) ) {
                    $name = $network[0]->user_name;
                    $id = $network[0]->net_id;
                } else {
                    $name = $comment['from']['name'];
                    $id = $comment['from']['id'];
                }
                
                /*$all_comments[$comment['id']] = array(
                    'created_time' => $comment['created_time'],
                    'message' => $comment['message'],
                    'from' => array(
                        'name' => $name,
                        'id' => $id,
                        'link' => 'https://www.facebook.com/' . $id,
                        'user_picture' => 'https://graph.facebook.com/' . $id . '/picture?type=square&access_token=' . $network[0]['token']
                    ),
                    'id' => $comment['id']
                );*/

                $all_comments[$comment['id']] = array(
                    'created_time' => $comment['created_time'],
                    'message' => $comment['message'],
                    'from' => array(
                        'name' => $name,
                        'id' => $id,
                        'link' => 'https://www.facebook.com/' . $id,
                        'user_picture' => 'https://graph.facebook.com/' . $id . '/picture?type=square&access_token=' . $network[0]->token
                    ),
                    'id' => $comment['id']
                );
                
                $replies = json_decode( get( $this->url . $comment['id'] . '/comments?access_token=' . $network[0]->token ), true );
                
                if ( $replies ) {

                    $all_replies = array();

                    foreach ( $replies['data'] as $reply ) {
                        
                        if ( !empty($comment['from']) ) {
                            $name = $network[0]->user_name;
                            $id = $network[0]->net_id;
                        } else {
                            $name = $comment['from']['name'];
                            $id = $comment['from']['id'];
                        }

                        $all_replies[] = array(
                            'created_time' => $reply['created_time'],
                            'message' => $reply['message'],
                            'from' => array(
                                'name' => $name,
                                'id' => $id,
                                'link' => 'https://www.facebook.com/' . $id,
                                'user_picture' => 'https://graph.facebook.com/' . $id . '/picture?type=square&access_token=' . $network[0]->token
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
        
        $reactions = json_decode( get( $this->url . $network[0]->post_id . '/reactions?access_token=' . $network[0]->token ), true );
        
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
                            'user_picture' => 'https://graph.facebook.com/' . $reaction['id'] . '/picture?type=square&access_token=' . $network[0]->token

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
                
                return $insights;
                
                break;
                
            case 'account':
                
                // Create insights array
                $insights = array();

                // Get members
                $members = json_decode( get( $this->url . $network[0]->net_id . '/?fields=member_count&access_token=' . $network[0]->token ), true );

                $member_count = 0;

                if ( !empty($members['member_count']) ) {
                    
                    $member_count = $members['member_count'];

                }

                // Add views to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_total_members'),
                    'value' => $member_count,
                    'background_color' => 'rgba(0, 99, 132, 0.6)',
                    'border_color' => 'rgba(0, 99, 132, 1)'
                );
                
                // Get requests
                $requests = json_decode( get( $this->url . $network[0]->net_id . '/?fields=member_request_count&access_token=' . $network[0]->token ), true );

                $requests_count = 0;

                if ( !empty($requests['member_count']) ) {
                    
                    $requests_count = $requests['member_count'];

                }

                // Add views to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_member_requests'),
                    'value' => $requests_count,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
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

                $post = json_decode(post( $this->url . $id . '/comments', array('message' => $msg), !empty(trim($network[0]->secret)) ? $network[0]->secret : $network[0]->token), true);
                
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
                
            case 'post':
                
                $post = json_decode(delete( $this->url . $network[0]->post_id, $network[0]->token), true);
                
                if ( isset($post['success']) ) {
                    
                    return $this->CI->lang->line('post_was_deleted');
                    
                } else {
                    
                    return false;
                    
                }
            
                break; 
            
        }
        
    }    

}
