<?php
/**
 * Twitter insights
 *
 * This file gets the insights for Twitter
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Insights;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Posts\Interfaces as MidrubBaseUserAppsCollectionPostsInterfaces;
use Abraham\TwitterOAuth\TwitterOAuth;

/*
 * Twitter class loads the insigts for Twitter
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Twitter implements MidrubBaseUserAppsCollectionPostsInterfaces\Insights {
    
   
    /**
     * Class variables
     *
     * @since 0.0.7.8
     */
    protected
            $CI, $connection, $twitter_key, $twitter_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.8
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();

        // Get the Twitter app_id
        $this->twitter_key = get_option('twitter_app_id');
        
        // Get the Twitter app_secret
        $this->twitter_secret = get_option('twitter_app_secret');
        
        // Require the vendor autoload
        require_once FCPATH . 'vendor/autoload.php';
        
        // Call the TwitterOAuth
        $this->connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret);
        
    }
    
    /**
     * Contains the Class's configurations
     *
     * @since 0.0.7.8
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
            'delete_post' => $this->CI->lang->line('posts_delete_tweet'),
            'no_posts_found' => $this->CI->lang->line('posts_no_tweets_found')
        );
        
        // Return config
        return $config;
        
    }
    
    /**
     * The public method get_account gets all accounts tweets
     * 
     * @since 0.0.7.8
     * 
     * @param object $network contains the network details
     * 
     * @return array with tweets or string with non found message
     */
    public function get_account($network) {

        // Connect to Twitter
        $this->connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network[0]->token, $network[0]->secret);

        // Get tweets by account
        $tweets = $this->connection->get('statuses/user_timeline',

            array(
                'screen_name' => $network[0]->user_name,
                'count' => 10
            )

        );

        // Verify if tweets exists
        if ( $tweets ) {
            
            $all_tweets = array();
            
            foreach ( $tweets as $tweet ) {

                $content = '';
                
                // List media if exists
                if ( !empty($tweet->entities->media[0]) ) {
                    
                    $content .= '<p data-type="stream-item-media">'
                                . '<img src="' . $tweet->entities->media[0]->media_url_https . '">'
                            . '</p>';
                    
                }
                
                // List hashtags if exists
                if ( !empty($tweet->entities->hashtags[0]) ) {
                    
                    $content .= '<p>';
                    
                    foreach ( $tweet->entities->hashtags as $hashtag ) {
                    
                        $content .= '<a href="https://twitter.com/hashtag/' . $hashtag->text . '" target="_blank">#' . $hashtag->text . '</a> ';
                    
                    }
                    
                    $content .= '</p>';
                    
                } 

                // Verify if twitter has content
                if ( !empty($tweet->text) ) {

                    $content = '<p>'
                        . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $tweet->text)
                    . '</p>';

                }
                
                // Set tweet's id
                $network[0]->post_id = $tweet->id;
                
                // Create return content
                $all_tweets[] = array(
                    'id' => ' ' . $tweet->id,
                    'title' => '',
                    'content' => $content,
                    'created_time' => strtotime($tweet->created_at),
                    'reactions' => $this->get_reactions($network)
                );
                
            }
            
            return $all_tweets;
            
        } else {
            
            return $this->CI->lang->line('posts_no_tweets_found');
            
        }
        
    }
    
    /**
     * The public method get_reactions gets the post's reactions
     * 
     * @since 0.0.7.8
     * 
     * @param object $network contains the network details
     * 
     * @return array with reactions or empty array
     */
    public function get_reactions($network) {
        
        // Create reactions array
        $reactions = array();
        
        // Get Tweet's retweets
        $get_retweets = $this->get_comments($network);
        
        // Add retweets to $reactions
        $reactions[0] = array(
            'name' => '<i class="icon-loop"></i> ' . $this->CI->lang->line('posts_retweets'),
            'slug' => 'retweets',
            'response' => $get_retweets,
            'placeholder' => $this->CI->lang->line('posts_enter_message'),
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
     * @since 0.0.7.8
     * 
     * @param object $network contains the network details
     * 
     * @return array with comments or string
     */
    public function get_comments($network) {

        // Get tweet's retweets
        $retweets = $this->connection->get('statuses/retweets',

            array(
                'id' => $network[0]->post_id,
                'count' => 10
            )

        );

        // Verify if retweets exists
        if ( isset($retweets[0]) ) {
            
            $all_tweets = array();
            
            foreach ( $retweets as $retweet ) {

                $content = '';
                
                // List media if exists
                if ( @$retweet->entities->media[0] ) {
                    
                    $content .= '<p data-type="stream-item-media">'
                                . '<img src="' . $retweet->entities->media[0]->media_url_https . '">'
                            . '</p>';
                    
                }
                
                // List hashtags if exists
                if ( @$retweet->entities->hashtags[0] ) {
                    
                    $content .= '<p>';
                    
                    foreach ( $retweet->entities->hashtags as $hashtag ) {
                    
                        $content .= '<a href="https://twitter.com/hashtag/' . $hashtag->text . '" target="_blank">#' . $hashtag->text . '</a> ';
                    
                    }
                    
                    $content .= '</p>';
                    
                } 

                // Verify if twitter has content
                if ( @$retweet->text ) {

                    $content = '<p>'
                        . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $retweet->text)
                    . '</p>';

                }

                // Create return content
                $all_tweets[$network[0]->post_id] = array(
                    'id' => $retweet->id,
                    'message' => $content,
                    'created_time' => date('Y-m-d H:i:s', strtotime($retweet->created_at)),
                    'from' => array(
                        'name' => $retweet->user->name,
                        'id' => $retweet->user->id,
                        'link' => 'https://twitter.com/' . $retweet->user->screen_name,
                        'user_picture' => $retweet->user->profile_image_url
                    ),
                );
                
            }
            
            return array_values($all_tweets);
            
        } else {
            
            return $this->CI->lang->line('posts_no_retweets_found');
            
        }
        
    }
    
    /**
     * The public method get_likes gets the post's likes
     * 
     * @since 0.0.7.8
     * 
     * @param object $network contains the network details
     * 
     * @return array with likes or string
     */
    public function get_likes($network) {
    }
    
    /**
     * The public method get_insights gets the post's insights
     * 
     * @since 0.0.7.8
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
                
                // Connect to Twitter
                $this->connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network[0]->token, $network[0]->secret);

                // Get tweet by id
                $tweet = $this->connection->get(
                    'statuses/show',

                    array(
                        'id' => $network[0]->post_id
                    )

                );

                // Default retweets count
                $retweet_count = 0;

                if ( $tweet ) {
                    $retweet_count = $tweet->retweet_count;
                }

                // Add retweets to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_retweets'),
                    'value' => $retweet_count,
                    'background_color' => 'rgba(0, 99, 132, 0.6)',
                    'border_color' => 'rgba(0, 99, 132, 1)'
                );

                // Default favorites count
                $favorite_count = 0;

                if ( $tweet ) {
                    $favorite_count = $tweet->favorite_count;
                }

                // Add favorites to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_favorites'),
                    'value' => $favorite_count,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
                );  
                
                // Default symbols count
                $symbols = 0;

                if ( @$tweet->symbols ) {
                    $symbols = count($tweet->symbols);
                }

                // Add tweet symbols to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_symbols'),
                    'value' => $symbols,
                    'background_color' => 'rgba(60, 99, 132, 0.6)',
                    'border_color' => 'rgba(60, 99, 132, 1)'
                );  
                
                // Default user mentions variable
                $user_mentions = 0;

                if ( @$tweet->user_mentions ) {
                    $user_mentions = count($tweet->user_mentions);
                }

                // Add user mentions to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_user_mentions'),
                    'value' => $user_mentions,
                    'background_color' => 'rgba(90, 99, 132, 0.6)',
                    'border_color' => 'rgba(90, 99, 132, 1)'
                );

                return $insights;
                
                break;
                
            case 'account':

                // Connect to Twitter
                $this->connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network[0]->token, $network[0]->secret);

                // Get tweets by account
                $tweets = $this->connection->get(
                    'statuses/user_timeline',

                    array(
                        'screen_name' => $network[0]->user_name,
                        'count' => 1
                    )

                );
                
                // Create insights array
                $insights = array();

                // Default followers variable
                $followers = 0;

                if ( $tweets ) {
                    $followers = $tweets[0]->user->followers_count;
                }

                // Add followers to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_followers'),
                    'value' => $followers,
                    'background_color' => 'rgba(0, 99, 132, 0.6)',
                    'border_color' => 'rgba(0, 99, 132, 1)'
                );

                // Default friends variable
                $friends = 0;

                if ( $tweets ) {
                    $friends = $tweets[0]->user->friends_count;
                }

                // Add friends to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_friends'),
                    'value' => $friends,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
                );  
                
                // Default member lists variable
                $member_lists = 0;

                if ( $tweets ) {
                    $member_lists = $tweets[0]->user->listed_count;
                }

                // Add member lists to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_member_lists'),
                    'value' => $member_lists,
                    'background_color' => 'rgba(60, 99, 132, 0.6)',
                    'border_color' => 'rgba(60, 99, 132, 1)'
                );  
                
                // Default statuses variable
                $statuses = 0;

                if ( $tweets ) {
                    $statuses = $tweets[0]->user->statuses_count;
                }

                // Add member lists to $insights
                $insights[] = array(
                    'name' => $this->CI->lang->line('posts_total_tweets'),
                    'value' => $statuses,
                    'background_color' => 'rgba(90, 99, 132, 0.6)',
                    'border_color' => 'rgba(90, 99, 132, 1)'
                );                 
                
                return $insights;
                
                break;
                
        }
        
    }
    
    /**
     * The public method post send submit data to social network
     * 
     * @since 0.0.7.8
     * 
     * @param object $network contains the network details
     * @param string type contains the data type
     * @param string $msg contains the data to send
     * @param string $parent contains the parent
     * 
     * @return array with status or string
     */
    public function post($network, $type, $msg, $parent = NULL) {
        
    }
    
    /**
     * The public method delete deletes data from social network
     * 
     * @since 0.0.7.8
     * 
     * @param object $network contains the network details
     * @param string type contains the data type
     * @param string $parent contains the parent
     * 
     * @return array with status or string
     */
    public function delete($network, $type, $parent = NULL) {

        switch ($type) {
                
            case 'post':

                // Connect to Twitter
                $this->connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network[0]->token, $network[0]->secret);

                // Delete tweet
                $delete = $this->connection->post('statuses/destroy', array('id'=> $network[0]->post_id));
                
                if ( $delete ) {
                    
                    return $this->CI->lang->line('posts_tweet_was_deleted');
                    
                } else {
                    
                    return false;
                    
                }
            
                break; 
            
        }
        
    }    

}
