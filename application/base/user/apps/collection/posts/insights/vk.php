<?php
/**
 * VK
 *
 * This file gets the insights for VK
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Insights;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Interfaces as MidrubBaseUserAppsCollectionPostsInterfaces;

/*
 * Vk class loads the insigts
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */
class Vk implements MidrubBaseUserAppsCollectionPostsInterfaces\Insights {
    
   
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected
            $CI, $url = 'https://api.vk.com/method/', $version='5.92', $redirect_uri, $client_id, $client_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.4
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
        // Get VK client_id
        $this->client_id = get_option('vk_client_id');
        
        // Get VK client_secret
        $this->client_secret = get_option('vk_client_secret');
        
        // Set redirect_url
        $this->redirect_uri = base_url() . 'user/callback/vk';
        
    }
    
    /**
     * Contains the Class's configurations
     *
     * @since 0.0.7.4
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
     * @since 0.0.7.4
     * 
     * @param object $network contains the network details
     * 
     * @return array with posts or string with non found message
     */
    public function get_account($network) {
        
        // Set params for getting all posts
        $params = array(
            'owner_id' => $network[0]->net_id,
            'count' => '100',
            'access_token' => $network[0]->token,
            'v' => $this->version
        );

        // Get cURL resource
        $curl = curl_init();

        // Set some options to in a useragent
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'wall.get' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

        // Get response
        $posts = json_decode(curl_exec($curl), true);

        // Close request to clear up some resources
        curl_close($curl);

        // Verify if posts exists
        if ( $posts['response']['count'] > 0 ) {
            
            $all_posts = array();
            
            foreach ( $posts['response']['items'] as $post  ) {
                
                $network[0]->post_id = $post['id'];
                
                $image = '';
                
                if ( isset($post['attachments'][0]['link']['photo']['sizes'][0]['url']) ) {
                    $image = '<p><img src="' . $post['attachments'][0]['link']['photo']['sizes'][0]['url'] . '"></p>';
                }
                
                if ( isset($post['attachments'][0]['video']['photo_320']) ) {
                    $image = '<img src="' . $post['attachments'][0]['video']['photo_320'] . '">';
                }
                
                $url = '';
                
                if ( isset($post['attachments'][0]['link']) ) {
                    $url = '<p><a href="' . $post['attachments'][0]['link']['url'] . '" target="_blank">' . $post['attachments'][0]['link']['url'] . '</a></p>';
                }
                
                $all_posts[] = array(
                    'id' => $post['id'],
                    'title' => '',
                    'content' => $post['text'] . $image . $url,
                    'created_time' => date('Y-m-d H:i:s', $post['date']),
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
     * @since 0.0.7.4
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
     * @since 0.0.7.4
     * 
     * @param object $network contains the network details
     * 
     * @return array with comments or string
     */
    public function get_comments($network) {
        
        // Set params for getting all comments
        $params = array(
            'owner_id' => $network[0]->net_id,
            'post_id' => $network[0]->post_id,
            'fields' => 'first_name,last_name,photo_100',
            'extended' => 1,
            'count' => '100',
            'access_token' => $network[0]->token,
            'v' => $this->version
        );

        // Get cURL resource
        $curl = curl_init();

        // Set some options to in a useragent
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'wall.getComments' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

        // Get response
        $comments = json_decode(curl_exec($curl), true);

        // Close request to clear up some resources
        curl_close($curl);
        
        // Verify if comments exists
        if ( isset($comments['response']['items']) ) {
            
            $all_comments = array();
            
            $profiles = array();
            
            foreach ( $comments['response']['profiles'] as $profile ) {
                
                $profiles[$profile['id']] = array(
                    'first_name' => $profile['first_name'],
                    'last_name' => $profile['last_name'],
                    'photo_100' => $profile['photo_100']
                );
                
            }
            
            foreach ( $comments['response']['groups'] as $group ) {
                
                $profiles[$group['id']] = array(
                    'first_name' => $group['name'],
                    'last_name' => ' ',
                    'photo_100' => $group['photo_100']
                );
                
            }            
            
            foreach ( $comments['response']['items'] as $comment ) {
                
                if ( !isset($comment['from_id']) ) {
                    continue;
                }
                
                $from = array(
                    'name' => $profiles[$comment['from_id']]['first_name'] . ' ' . $profiles[$comment['from_id']]['last_name'],
                    'id' => $comment['from_id'],
                    'link' => 'https://vk.com/id' . $comment['from_id'],
                    'user_picture' => $profiles[$comment['from_id']]['photo_100']
                );
                
                $all_comments[$comment['id']] = array(
                    'created_time' => date('Y-m-d H:i:s', $comment['date']),
                    'message' => $comment['text'],
                    'from' => $from,
                    'id' => $comment['id']
                );
                
                // Set params for getting all comment's replies
                $params = array(
                    'owner_id' => $network[0]->net_id,
                    'post_id' => $network[0]->post_id,
                    'fields' => 'first_name,last_name,photo_100',
                    'extended' => 1,
                    'comment_id' => $comment['id'],
                    'count' => '100',
                    'access_token' => $network[0]->token,
                    'v' => $this->version
                );

                // Get cURL resource
                $curl = curl_init();

                // Set some options to in a useragent
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'wall.getComments' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

                // Get response
                $replies = json_decode(curl_exec($curl), true);

                // Close request to clear up some resources
                curl_close($curl);

                // Verify if replies exists
                if ( isset($replies['response']['items']) ) {

                    $all_replies = array();

                    foreach ( $replies['response']['profiles'] as $profile ) {

                        if ( !isset($profiles[$profile['id']]) ) {

                            $profiles[$profile['id']] = array(
                                'first_name' => $profile['first_name'],
                                'last_name' => $profile['last_name'],
                                'photo_100' => $profile['photo_100']
                            );

                        }
                        
                        foreach ( $replies['response']['groups'] as $group ) {

                            if ( !isset($profiles[$profile['id']]) ) { 
                                
                                $profiles[$group['id']] = array(
                                    'first_name' => $group['first_name'],
                                    'last_name' => $group['last_name'],
                                    'photo_100' => $group['photo_100']
                                );
                                
                            }

                        } 

                    }

                    foreach ( $replies['response']['items'] as $reply ) {

                        $from = array(
                            'name' => $profiles[$reply['from_id']]['first_name'] . ' ' . $profiles[$reply['from_id']]['last_name'],
                            'id' => $reply['from_id'],
                            'link' => 'https://vk.com/id' . $reply['from_id'],
                            'user_picture' => $profiles[$reply['from_id']]['photo_100']

                        );

                        $all_replies[] = array(
                            'created_time' => date('Y-m-d H:i:s', $reply['date']),
                            'message' => $reply['text'],
                            'from' => $from,
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
     * @since 0.0.7.4
     * 
     * @param object $network contains the network details
     * 
     * @return array with likes or string
     */
    public function get_likes($network) {
        
        // Set params for getting all comments
        $params = array(
            'owner_id' => $network[0]->net_id,
            'item_id' => $network[0]->post_id,
            'fields' => 'first_name,last_name,photo_100',
            'extended' => 1,
            'count' => '100',
            'type' => 'post',
            'access_token' => $network[0]->token,
            'v' => $this->version
        );

        // Get cURL resource
        $curl = curl_init();

        // Set some options to in a useragent
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'likes.getList' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

        // Get response
        $reactions = json_decode(curl_exec($curl), true);

        // Close request to clear up some resources
        curl_close($curl);
        
        // Verify if comments exists
        if ( !empty($reactions['response']['count']) ) {
            
            $all_reactions = array();
            
            foreach ( $reactions['response']['items'] as $reaction ) {
                
                $from = array(
                    'name' => $reaction['first_name'] . ' ' . $reaction['last_name'],
                    'id' => $reaction['id'],
                    'link' => 'https://vk.com/id' . $reaction['id'],
                    'user_picture' => $reaction['photo_100']

                );
                
                $all_reactions[1] = array(
                    'created_time' => '',
                    'message' => '<i class="icon-like"></i>',
                    'from' => $from,
                    'id' => 1
                );
                
            }
            
            return array_values($all_reactions);
            
        } else {
            
            return $this->CI->lang->line('no_reactions');
            
        }
        
    }
    
    /**
     * The public method get_insights gets the post's insights
     * 
     * @since 0.0.7.4
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

                // Set params to get the comment data
                $params = array(
                    'posts' => $network[0]->net_id . '_' . $network[0]->post_id,
                    'access_token' => $network[0]->token,
                    'v' => $this->version
                );

                // Get cURL resource
                $curl = curl_init();

                // Set some options to in a useragent
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'wall.getById' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

                // Get response
                $response = json_decode(curl_exec($curl), true);

                // Close request to clear up some resources
                curl_close($curl);
                
                // Define default number of likes
                $likes = 0;

                if ( isset($response['response'][0]['likes']) ) {
                    $likes = $response['response'][0]['likes']['count'];
                }

                // Add number of likes to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('likes'),
                    'value' => $likes,
                    'background_color' => 'rgba(0, 99, 132, 0.6)',
                    'border_color' => 'rgba(0, 99, 132, 1)'
                ); 
                
                // Define default number of comments
                $comments = 0;

                if ( isset($response['response'][0]['comments']) ) {
                    $comments = $response['response'][0]['comments']['count'];
                }

                // Add number of comments to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('comments'),
                    'value' => $comments,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
                ); 
                
                // Define default number of reposts
                $reposts = 0;

                if ( isset($response['response'][0]['comments']) ) {
                    $reposts = $response['response'][0]['comments']['count'];
                }

                // Add number of members to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('reposts'),
                    'value' => $reposts,
                    'background_color' => 'rgba(60, 99, 132, 0.6)',
                    'border_color' => 'rgba(60, 99, 132, 1)'
                );
                
                // Define default number of views
                $views = 0;

                if ( isset($response['response'][0]['views']) ) {
                    $views = $response['response'][0]['views']['count'];
                }

                // Add number of views to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('views'),
                    'value' => $views,
                    'background_color' => 'rgba(90, 99, 132, 0.6)',
                    'border_color' => 'rgba(90, 99, 132, 1)'
                );

                return $insights;
                
            case 'account':
                
                // Create insights array
                $insights = array();
                
                // Set params to get the friends data
                $params = array(
                    'user_id' => $network[0]->net_id,
                    'access_token' => $network[0]->token,
                    'v' => $this->version
                );

                // Get cURL resource
                $curl = curl_init();

                // Set some options to in a useragent
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'friends.getLists' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

                // Get response
                $response = json_decode(curl_exec($curl), true);

                // Close request to clear up some resources
                curl_close($curl);
                
                // Define default number of friends
                $friends = 0;

                if ( isset($response['response']['count']) ) {
                    
                    $friends = $response['response']['count'];

                }

                // Add number of friends to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('friends'),
                    'value' => $friends,
                    'background_color' => 'rgba(0, 99, 132, 0.6)',
                    'border_color' => 'rgba(0, 99, 132, 1)'
                );
                
                // Set params to get the groups data
                $params = array(
                    'user_id' => $network[0]->net_id,
                    'access_token' => $network[0]->token,
                    'v' => $this->version
                );

                // Get cURL resource
                $curl = curl_init();

                // Set some options to in a useragent
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'groups.get' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

                // Get response
                $response = json_decode(curl_exec($curl), true);

                // Close request to clear up some resources
                curl_close($curl);
                
                // Define default number of groups
                $groups = 0;

                if ( isset($response['response']['count']) ) {
                    
                    $groups = $response['response']['count'];

                }

                // Add number of groups to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('groups'),
                    'value' => $groups,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
                ); 
                
                // Set params to get the groups invitatons data
                $params = array(
                    'access_token' => $network[0]->token,
                    'v' => $this->version
                );

                // Get cURL resource
                $curl = curl_init();

                // Set some options to in a useragent
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'groups.getInvites' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

                // Get response
                $response = json_decode(curl_exec($curl), true);

                // Close request to clear up some resources
                curl_close($curl);
                
                // Define default number of invitations
                $invitations = 0;

                if ( isset($response['response']['count']) ) {
                    
                    $invitations = $response['response']['count'];

                }

                // Add number of invitations to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('groups_invitations'),
                    'value' => $invitations,
                    'background_color' => 'rgba(60, 99, 132, 0.6)',
                    'border_color' => 'rgba(60, 99, 132, 1)'
                );                 
                
                return $insights;
                
        }
        
    }
    
    /**
     * The public method post send submit data to social network
     * 
     * @since 0.0.7.4
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
                
                // Set the params for bew comment
                $post_params = array(
                    'owner_id' => $network[0]->net_id,
                    'message' => $msg,
                    'access_token' => $network[0]->token,
                    'v' => $this->version
                );
                    
                // Set params to get the comment data
                $params = array(
                    'owner_id' => $network[0]->net_id,
                    'access_token' => $network[0]->token,
                    'comment_id' => $parent,
                    'v' => $this->version
                );

                // Get cURL resource
                $curl = curl_init();

                // Set some options to in a useragent
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'wall.getComment' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

                // Get response
                $response = json_decode(curl_exec($curl), true);

                // Close request to clear up some resources
                curl_close($curl);

                if ( isset($response['response']['items'][0]['post_id']) && @$response['response']['items'][0]['post_id'] !== $parent ) {
                    $post_params['reply_to_comment'] = $parent;
                    $post_params['post_id'] = $response['response']['items'][0]['post_id']; 
                } else {
                    if (@$network[0]->post_id) {
                        $post_params['post_id'] = $network[0]->post_id;
                    } else {
                        $post_params['post_id'] = $parent;
                    }
                }
                
                // Get cURL resource
                $curl = curl_init();
                
                // Set some options to in a useragent
                curl_setopt($curl, CURLOPT_URL, $this->url . 'wall.createComment');
                
                // Set POST type
                curl_setopt($curl, CURLOPT_POST, TRUE);
                
                // Set POST's fields
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_params);
                
                // We expect response
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                
                // Set safe upload
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, TRUE);

                // Decode the response
                $resp = json_decode(curl_exec($curl), true);

                if ( isset($resp['response']['comment_id']) ) {
                    
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
     * @since 0.0.7.4
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
                
                // Set the params for the new post
                $post_params = array(
                    'owner_id' => $network[0]->net_id,
                    'comment_id' => $parent,
                    'access_token' => $network[0]->token,
                    'v' => $this->version
                );
                
                // Get cURL resource
                $curl = curl_init();
                
                // Set some options to in a useragent
                curl_setopt($curl, CURLOPT_URL, $this->url . 'wall.deleteComment');
                
                // Set POST type
                curl_setopt($curl, CURLOPT_POST, TRUE);
                
                // Set POST's fields
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_params);
                
                // We expect response
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                
                // Set safe upload
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, TRUE);

                // Decode the response
                $resp = json_decode(curl_exec($curl), true);

                if ( $resp['response'] ) {
                    
                    return $this->CI->lang->line('comment_deleted');
                    
                } else {
                    
                    return false;
                    
                }
                
                break;
                
            case 'post':
                
                // Set the params for the new post
                $post_params = array(
                    'owner_id' => $network[0]->net_id,
                    'post_id' => $network[0]->post_id,
                    'access_token' => $network[0]->token,
                    'v' => $this->version
                );
                
                // Get cURL resource
                $curl = curl_init();
                
                // Set some options to in a useragent
                curl_setopt($curl, CURLOPT_URL, $this->url . 'wall.delete');
                
                // Set POST type
                curl_setopt($curl, CURLOPT_POST, TRUE);
                
                // Set POST's fields
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_params);
                
                // We expect response
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                
                // Set safe upload
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, TRUE);

                // Decode the response
                $resp = json_decode(curl_exec($curl), true);

                if ( $resp['response'] ) {
                    
                    return $this->CI->lang->line('post_was_deleted');
                    
                } else {
                    
                    return false;
                    
                }
            
                break; 
            
        }
        
    }    

}
