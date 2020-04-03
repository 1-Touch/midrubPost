<?php
/**
 * Insights Helpers
 *
 * This file contains the class Insights
 * with methods to process the insights data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Posts\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Insights class provides the methods to process the insights data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Insights {
    
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
        
    }

    /**
     * The public method list_accounts_for_composer prepares the list with accounts for posts composer
     * 
     * @since 0.0.7.0
     * 
     * @param object $network contains the network details
     * 
     * @return array with comments or false
     */ 
    public function get_comments($accounts) {
        
        if ( $accounts ) {
            
            // Create array for all accounts networks
            $networks = array();
            
            // Create the accounts_list array
            $accounts_list = array();
            
            // List all accounts
            foreach ( $accounts as $account ) {

                // Check if the $network exists
                if ( file_exists(MIDRUB_BASE_USER . 'networks/' . $account->network_name . '.php') ) {
                    
                    // Verify if same networks was called before
                    if ( isset( $networks[$account->network_name] ) ) {
                        
                        $accounts_list[] = array(
                            'network_info' => $networks[$account->network_name],
                            'network_id' => $account->network_id,
                            'user_name' => $account->user_name,
                            'user_avatar' => $account->user_avatar,
                            'network_name' => $account->network_name,
                            'network' => $account->network_name
                        );
                        
                        continue;
                        
                    }

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Networks',
                        ucfirst($account->network_name)
                    );

                    // Implode the array above
                    $cl = implode('\\', $array);

                    // Get method
                    $get = (new $cl());
                    
                    // Add network info in the array
                    $networks[$account->network_name] = $get->get_info();

                    // Return array with network info and accounts
                    $accounts_list[] = array(
                        'network_info' => $get->get_info(),
                        'network_id' => $account->network_id,
                        'user_name' => $account->user_name,
                        'user_avatar' => $account->user_avatar,
                        'network_name' => $account->network_name,
                        'network' => $account->network_name
                    );

                }
            
            }
            
            return $accounts_list;
            
        } else {
            
            return array();
            
        }
        
    }
    
    /**
     * The public method insights_display_account_details gets account insights
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_display_account_details() {
                
        // Get id's input
        $id = $this->CI->input->get('id', TRUE);
        
        if ( is_numeric( $id ) ) {

            // Get account data
            $network_data = $this->CI->networks_model->get_account( $id );

            // Verify if network exists
            if ( $network_data ) {
                
                // Verify if current user is the owner of the selected account
                if ( $this->CI->user_id != $network_data[0]->user_id ) {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('error_occurred')
                    );

                    echo json_encode($data);
                    exit();
                    
                }

                // Set default avatar
                $user_picture = base_url('assets/img/avatar-placeholder.png');

                if ( $network_data[0]->user_avatar ) {
                    $user_picture = $network_data[0]->user_avatar;
                }
                
                if ( $network_data[0]->network_name === 'facebook_pages' ) {
                    $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->secret;
                } else if ( $network_data[0]->network_name === 'facebook_groups' ) {
                    $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->token;
                } else if ( $network_data[0]->network_name === 'instagram_insights' ) {
                    $avatar = json_decode(get('https://graph.facebook.com/' . $network_data[0]->net_id . '?fields=profile_picture_url&access_token=' . $network_data[0]->token), true);
                    if ( isset($avatar['profile_picture_url']) ) {
                        $user_picture = $avatar['profile_picture_url'];
                    }
                    
                }

                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Apps',
                    'Collection',
                    'Posts',
                    'Insights',
                    ucfirst($network_data[0]->network_name)
                );       

                // Implode the array above
                $cl = implode('\\',$array);

                // Get account data
                $get_account = (new $cl())->get_account($network_data);

                // Get insights
                $insights = (new $cl())->get_insights($network_data, 'account');
                
                // Get configuration
                $configuration = (new $cl())->configuration();

                if ( $get_account ) {

                    $data = array(
                        'success' => TRUE,
                        'network_id' => $id,
                        'posts' => $get_account,
                        'insights' => $insights,
                        'user_name' => $network_data[0]->user_name,
                        'user_picture' => $user_picture,
                        'network_name' => ucwords(str_replace('_', ' ', $network_data[0]->network_name)),
                        'configuration' => $configuration
                    );

                    echo json_encode($data);                            

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('error_occurred')
                    );

                    echo json_encode($data);

                }

            } else {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);

            }
            
        }
        
    }
    
    /**
     * The public method insights_display_send_react displays the post's insights
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_display_send_react() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            $this->CI->form_validation->set_rules('msg', 'msg', 'trim|required');
            $this->CI->form_validation->set_rules('parent', 'Parent', 'trim');
            
            if ( $this->CI->form_validation->run() == false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('message_too_short')
                );

                echo json_encode($data);   
                
            } else {
                
                $type = $this->CI->input->post('type');
                $id = $this->CI->input->post('id');
                $msg = $this->CI->input->post('msg');
                $parent = $this->CI->input->post('parent');

                // Gets meta's data by meta_id
                $get_meta = $this->CI->posts_model->get_post_meta($id);

                // Verify if post's meta exists
                if ( $get_meta ) {

                    // Get post's ID
                    $post_id = $get_meta[0]['post_id'];

                    // Get post data by user id and post id
                    $get_post = $this->CI->posts_model->get_post($this->CI->user_id, $post_id);

                    // Verify if post exists
                    if ( $get_post ) {

                        $network_data = $this->CI->networks_model->get_account( $get_meta[0]['network_id'] );

                        if ( $network_data ) {

                            // Set default avatar
                            $user_picture = base_url('assets/img/avatar-placeholder.png');

                            if ( $network_data[0]->user_avatar ) {
                                $user_picture = $network_data[0]->user_avatar;
                            }

                            // Create an array
                            $array = array(
                                'MidrubBase',
                                'User',
                                'Apps',
                                'Collection',
                                'Posts',
                                'Insights',
                                ucfirst($network_data[0]->network_name)
                            );       

                            // Implode the array above
                            $cl = implode('\\',$array);

                            // Set post id
                            $network_data[0]->post_id = $get_meta[0]['published_id'];

                            // Instantiate the class
                            $publish = (new $cl())->post($network_data, $type, $msg, $parent);
                            
                            // Get configuration
                            $configuration = (new $cl())->configuration();

                            if ( $publish['success'] ) {
                                
                                $member_id = 0;

                                if ( $this->CI->session->userdata( 'member' ) ) {

                                    // Load Team model
                                    $this->CI->load->model( 'Team', 'team' );

                                    // Get member team info
                                    $member_info = $this->CI->team->get_member( $user_id, 0, $this->CI->session->userdata( 'member' ) );

                                    if ( $member_info ) {

                                        $member_id = $member_info[0]->member_id;

                                    }

                                }
                                
                                $this->CI->load->model( 'Activities', 'activities' );

                                $this->CI->activities->save_activity( 'posts', 'comments', $network_data[0]->network_id, $this->CI->user_id, $member_id );

                                // Instantiate the class
                                $reactions = (new $cl())->get_reactions($network_data);

                            } else {

                                $data = array(
                                    'success' => FALSE,
                                    'message' => $publish['message']
                                );

                                echo json_encode($data);

                                exit();

                            }
                            
                            // Get image
                            $img = unserialize($get_post['img']);

                            // Get video
                            $video = unserialize($get_post['video']);

                            // Verify if image exists
                            if ( $img ) {
                                $images = get_post_media_array($this->CI->user_id, $img );
                                if ($images) {
                                    $img = $images;
                                }
                            }

                            // Verify if video exists
                            if ( $video ) {
                                $videos = get_post_media_array($this->CI->user_id, $video );
                                if ($videos) {
                                    $video = $videos;
                                }
                            }

                            $time = $get_post['time'];

                            if ( $get_post['status'] < 1 ) {
                                $time = $this->CI->lang->line('draft_post');
                            } 
                            
                            if ( $network_data[0]->network_name === 'facebook_pages' ) {
                                $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->secret;
                            } else if ( $network_data[0]->network_name === 'facebook_groups' ) {
                                $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->token;
                            } else if ( $network_data[0]->network_name === 'instagram_insights' ) {
                                $avatar = json_decode(get('https://graph.facebook.com/' . $network_data[0]->net_id . '?fields=profile_picture_url&access_token=' . $network_data[0]->token), true);
                                if ( isset($avatar['profile_picture_url']) ) {
                                    $user_picture = $avatar['profile_picture_url'];
                                }
                            }

                            // Get post content
                            $post = array(
                                'post_id' => $post_id,
                                'title' => $get_post['title'],
                                'body' => $get_post['body'],
                                'datetime' => $time,
                                'time' => time(),
                                'img' => $img,
                                'video' => $video,
                                'user_name' => $network_data[0]->user_name,
                                'user_picture' => $user_picture,
                                'reactions' => $reactions,
                                'delete_post' => $this->CI->lang->line('delete_post'),
                                'configuration' => $configuration
                            );

                            $data = array(
                                'success' => TRUE,
                                'content' => $post,
                                'meta_id' => $id,
                                'message' => $publish['message']
                            );

                            echo json_encode($data);

                        } else {

                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('no_post_found')
                            );

                            echo json_encode($data); 

                        }

                    }
 
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method insights_display_delete_react delete a reaction
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_display_delete_react() {
                
        // Get id's input
        $id = $this->CI->input->get('id', TRUE);
        
        // Get type's input
        $type = $this->CI->input->get('type', TRUE);
        
        // Get parent's input
        $parent = $this->CI->input->get('parent', TRUE);

        // Gets meta's data by meta_id
        $get_meta = $this->CI->posts_model->get_post_meta($id);

        // Verify if post's meta exists
        if ( $get_meta ) {

            // Get post's ID
            $post_id = $get_meta[0]['post_id'];

            // Get post data by user id and post id
            $get_post = $this->CI->posts_model->get_post($this->CI->user_id, $post_id);

            // Verify if post exists
            if ( $get_post ) {

                $network_data = $this->CI->networks_model->get_account( $get_meta[0]['network_id'] );

                if ( $network_data ) {

                    // Set default avatar
                    $user_picture = base_url('assets/img/avatar-placeholder.png');

                    if ( $network_data[0]->user_avatar ) {
                        $user_picture = $network_data[0]->user_avatar;
                    }

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Posts',
                        'Insights',
                        ucfirst($network_data[0]->network_name)
                    );       

                    // Implode the array above
                    $cl = implode('\\',$array);

                    // Set post id
                    $network_data[0]->post_id = $get_meta[0]['published_id'];

                    // Delete reaction
                    $delete = (new $cl())->delete($network_data, $type, $parent);
                    
                    // Get configuration
                    $configuration = (new $cl())->configuration();

                    if ( $delete ) {

                        // Get all reactions
                        $reactions = (new $cl())->get_reactions($network_data);

                    } else {

                        $data = array(
                            'success' => FALSE,
                            'message' => $reactions
                        );

                        echo json_encode($data);

                        exit();

                    }

                    // Get image
                    $img = unserialize($get_post['img']);

                    // Get video
                    $video = unserialize($get_post['video']);

                    // Verify if image exists
                    if ( $img ) {
                        $images = get_post_media_array($this->CI->user_id, $img );
                        if ($images) {
                            $img = $images;
                        }
                    }

                    // Verify if video exists
                    if ( $video ) {
                        $videos = get_post_media_array($this->CI->user_id, $video );
                        if ($videos) {
                            $video = $videos;
                        }
                    }

                    $time = $get_post['time'];

                    if ( $get_post['status'] < 1 ) {
                        $time = $this->CI->lang->line('draft_post');
                    } 
                    
                    if ( $network_data[0]->network_name === 'facebook_pages' ) {
                        $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->secret;
                    } else if ( $network_data[0]->network_name === 'facebook_groups' ) {
                        $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->token;
                    } else if ( $network_data[0]->network_name === 'instagram_insights' ) {
                        $avatar = json_decode(get('https://graph.facebook.com/' . $network_data[0]->net_id . '?fields=profile_picture_url&access_token=' . $network_data[0]->token), true);
                        if ( isset($avatar['profile_picture_url']) ) {
                            $user_picture = $avatar['profile_picture_url'];
                        }
                    }

                    // Get post content
                    $post = array(
                        'post_id' => $post_id,
                        'title' => $get_post['title'],
                        'body' => $get_post['body'],
                        'datetime' => $time,
                        'time' => time(),
                        'img' => $img,
                        'video' => $video,
                        'user_name' => $network_data[0]->user_name,
                        'user_picture' => $user_picture,
                        'reactions' => $reactions,
                        'delete_post' => $this->CI->lang->line('delete_post'),
                        'configuration' => $configuration
                    );

                    $data = array(
                        'success' => TRUE,
                        'content' => $post,
                        'meta_id' => $id,
                        'message' => $delete
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

        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data);
            
        }
        
    }
    
    /**
     * The public method insights_post_delete_post deletes a post
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_post_delete_post() {
                
        // Get id's input
        $id = $this->CI->input->get('id', TRUE);
        
        // Get type's input
        $type = $this->CI->input->get('type', TRUE);

        // Gets meta's data by meta_id
        $get_meta = $this->CI->posts_model->get_post_meta($id);

        // Verify if post's meta exists
        if ( $get_meta ) {

            // Get post's ID
            $post_id = $get_meta[0]['post_id'];

            // Get post data by user id and post id
            $get_post = $this->CI->posts_model->get_post($this->CI->user_id, $post_id);

            // Verify if post exists
            if ( $get_post ) {

                $network_data = $this->CI->networks_model->get_account( $get_meta[0]['network_id'] );

                if ( $network_data ) {

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Posts',
                        'Insights',
                        ucfirst($network_data[0]->network_name)
                    );       

                    // Implode the array above
                    $cl = implode('\\',$array);

                    // Set post id
                    $network_data[0]->post_id = $get_meta[0]['published_id'];

                    // Delete post
                    $deleted = (new $cl())->delete($network_data, $type);

                    if ( $deleted ) {

                        // Delete the published id
                        $this->CI->posts_model->empty_published_id($id);

                        $data = array(
                            'success' => TRUE,
                            'message' => $deleted
                        );

                        echo json_encode($data);                            

                    } else {

                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('error_occurred')
                        );

                        echo json_encode($data);

                    }

                } else {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('error_occurred')
                    );

                    echo json_encode($data);

                }

            }

        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data);
            
        }
        
    }
    
    /**
     * The public method insights_accounts_send_react publishes on accounts
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_accounts_send_react() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            $this->CI->form_validation->set_rules('msg', 'msg', 'trim|required');
            
            if ( $this->CI->form_validation->run() == false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('message_too_short')
                );

                echo json_encode($data);   
                
            } else {
                
                $type = $this->CI->input->post('type');
                $id = $this->CI->input->post('id');
                $msg = $this->CI->input->post('msg');
                $parent = $this->CI->input->post('parent');
                
                $network_data = $this->CI->networks_model->get_account( $id );

                if ( $network_data ) {
                    
                    // Verify if current user is the owner of the selected account
                    if ( $this->CI->user_id != $network_data[0]->user_id ) {

                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('error_occurred')
                        );

                        echo json_encode($data);
                        exit();

                    }

                    // Set default avatar
                    $user_picture = base_url('assets/img/avatar-placeholder.png');

                    if ( $network_data[0]->user_avatar ) {
                        $user_picture = $network_data[0]->user_avatar;
                    }

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Posts',
                        'Insights',
                        ucfirst($network_data[0]->network_name)
                    );       

                    // Implode the array above
                    $cl = implode('\\',$array);

                    // Instantiate the class
                    $publish = (new $cl())->post($network_data, $type, $msg, $parent);

                    // verify if publish was done successfully
                    if ( $publish['success'] ) {
                        
                        $member_id = 0;

                        if ( $this->CI->session->userdata( 'member' ) ) {

                            // Load Team model
                            $this->CI->load->model( 'Team', 'team' );

                            // Get member team info
                            $member_info = $this->CI->team->get_member( $user_id, 0, $this->CI->session->userdata( 'member' ) );

                            if ( $member_info ) {

                                $member_id = $member_info[0]->member_id;

                            }

                        }
                        
                        $this->CI->load->model( 'Activities', 'activities' );

                        $this->CI->activities->save_activity( 'posts', 'comments', $id, $this->CI->user_id, $member_id );

                        // Get account data
                        $get_account = (new $cl())->get_account($network_data);

                        // Get insights
                        $insights = (new $cl())->get_insights($network_data, 'account');
                        
                        // Get configuration
                        $configuration = (new $cl())->configuration();
                        
                        if ( $network_data[0]->network_name === 'facebook_pages' ) {
                            $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->secret;
                        } else if ( $network_data[0]->network_name === 'facebook_groups' ) {
                            $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->token;
                        } else if ( $network_data[0]->network_name === 'instagram_insights' ) {
                            $avatar = json_decode(get('https://graph.facebook.com/' . $network_data[0]->net_id . '?fields=profile_picture_url&access_token=' . $network_data[0]->token), true);
                            if ( isset($avatar['profile_picture_url']) ) {
                                $user_picture = $avatar['profile_picture_url'];
                            }
                        }

                        $data = array(
                            'success' => TRUE,
                            'network_id' => $id,
                            'posts' => $get_account,
                            'user_name' => $network_data[0]->user_name,
                            'user_picture' => $user_picture,
                            'network_name' => ucwords(str_replace('_', ' ', $network_data[0]->network_name)),
                            'message' => $publish['message'],
                            'insights' => $insights,
                            'configuration' => $configuration
                        );

                        echo json_encode($data);

                    } else {

                        $data = array(
                            'success' => FALSE,
                            'message' => $publish['message']
                        );

                        echo json_encode($data);

                    }

                }
                
            }
            
        }
        
    }
    
    /**
     * The public method insights_accounts_delete_react deletes an post reaction
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_accounts_delete_react() {
                
        // Get id's input
        $id = $this->CI->input->get('id', TRUE);
        
        // Get type's input
        $type = $this->CI->input->get('type', TRUE);
        
        // Get parent's input
        $parent = $this->CI->input->get('parent', TRUE);

        $network_data = $this->CI->networks_model->get_account( $id );

        if ( $network_data ) {

            // Verify if current user is the owner of the selected account
            if ( $this->CI->user_id != $network_data[0]->user_id ) {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);
                exit();

            }

            // Set default avatar
            $user_picture = base_url('assets/img/avatar-placeholder.png');

            if ( $network_data[0]->user_avatar ) {
                $user_picture = $network_data[0]->user_avatar;
            }

            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Apps',
                'Collection',
                'Posts',
                'Insights',
                ucfirst($network_data[0]->network_name)
            );       

            // Implode the array above
            $cl = implode('\\',$array);

            // Delete reaction
            $deleted = (new $cl())->delete($network_data, $type, $parent);

            if ( $deleted ) {

                // Get account data
                $get_account = (new $cl())->get_account($network_data);

                // Get insights
                $insights = (new $cl())->get_insights($network_data, 'account');
                
                // Get configuration
                $configuration = (new $cl())->configuration();
                
                if ( $network_data[0]->network_name === 'facebook_pages' ) {
                    $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->secret;
                } else if ( $network_data[0]->network_name === 'facebook_groups' ) {
                    $user_picture = 'https://graph.facebook.com/' . $network_data[0]->net_id . '/picture?type=square&access_token=' . $network_data[0]->token;
                } else if ( $network_data[0]->network_name === 'instagram_insights' ) {
                    $avatar = json_decode(get('https://graph.facebook.com/' . $network_data[0]->net_id . '?fields=profile_picture_url&access_token=' . $network_data[0]->token), true);
                    if ( isset($avatar['profile_picture_url']) ) {
                        $user_picture = $avatar['profile_picture_url'];
                    }
                }

                $data = array(
                    'success' => TRUE,
                    'network_id' => $id,
                    'posts' => $get_account,
                    'user_name' => $network_data[0]->user_name,
                    'user_picture' => $user_picture,
                    'network_name' => ucwords(str_replace('_', ' ', $network_data[0]->network_name)),
                    'message' => $deleted,
                    'insights' => $insights,
                    'configuration' => $configuration
                );

                echo json_encode($data);

            } else {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);

            }

        } else {

            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data);

        }
        
    }
    
    /**
     * The public method insights_account_delete_post deletes an account's post
     *
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function insights_account_delete_post() {
                
        // Get id's input
        $id = $this->CI->input->get('id', TRUE);
        
        // Get type's input
        $type = $this->CI->input->get('type', TRUE);
        
        // Get account's input
        $account = $this->CI->input->get('account', TRUE);

        $network_data = $this->CI->networks_model->get_account( $account );

        if ( $network_data ) {
            
            // Verify if current user is the owner of the selected account
            if ( $this->CI->user_id != $network_data[0]->user_id ) {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);
                exit();

            }

            // Set default avatar
            $user_picture = base_url('assets/img/avatar-placeholder.png');

            if ( $network_data[0]->user_avatar ) {
                $user_picture = $network_data[0]->user_avatar;
            }

            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Apps',
                'Collection',
                'Posts',
                'Insights',
                ucfirst($network_data[0]->network_name)
            );       

            // Implode the array above
            $cl = implode('\\',$array);

            // Set post id
            $network_data[0]->post_id = $id;

            // Delete post
            $deleted = (new $cl())->delete($network_data, $type);

            if ( $deleted ) {

                // Get account data
                $get_account = (new $cl())->get_account($network_data);

                // Get insights
                $insights = (new $cl())->get_insights($network_data, 'account');
                
                // Get configuration
                $configuration = (new $cl())->configuration();

                $data = array(
                    'success' => TRUE,
                    'network_id' => $id,
                    'posts' => $get_account,
                    'user_name' => $network_data[0]->user_name,
                    'user_picture' => $user_picture,
                    'network_name' => ucwords(str_replace('_', ' ', $network_data[0]->network_name)),
                    'message' => $deleted,
                    'insights' => $insights,
                    'configuration' => $configuration
                );

                echo json_encode($data);                           

            } else {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('error_occurred')
                );

                echo json_encode($data);

            }

        } else {

            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data);

        }
        
    }

}

/* End of file insights.php */