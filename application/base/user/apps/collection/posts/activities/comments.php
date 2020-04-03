<?php
/**
 * Comments Template
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

/*
 * Comments class provides the methods to process the comments template
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.0
*/
class Comments implements MidrubBaseUserAppsCollectionPostsInterfaces\Activities {
    
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
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Networks_model', 'networks_model' );
        
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
        $has_published_comment = $this->CI->lang->line('has_published_comment');

        // Verify if team's member exists
        if ( $member_id > 0 ) {

            // Load Team Model
            $this->CI->load->model('team');

            // Get team's member information
            $member = $this->CI->team->get_member($user_id, $member_id);

            // Verify if comment was published by a team's member
            if ( $member ) {

                $header = $member[0]->member_username . ' ' . $has_published_comment;

            } else {

                $header = $this->CI->session->userdata['username'] . ' ' . $has_published_comment;

            }

        } else {

            $header = $this->CI->session->userdata['username'] . ' ' . $has_published_comment;

        }
        
        // Get network
        $network_data = $this->CI->networks_model->get_account($id);
        
        // Verify if data exists
        if ( $network_data ) {

            // Check if the $network exists
            if ( file_exists(MIDRUB_BASE_USER . 'networks/' . $network_data[0]->network_name . '.php') ) {

                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Networks',
                    ucfirst($network_data[0]->network_name)
                );

                // Implode the array above
                $cl = implode('\\', $array);

                // Get method
                $get = (new $cl());

                // Add network info in the array
                $info = $get->get_info();

                // Set header
                $header = $header . ' ' . $info['icon'];
                
            }

            $header = $header . '<strong>' . $network_data[0]->user_name . '</strong>';
            
        }

        $activity['header'] = $header;
        
        $activity['body'] = '';

        $activity['medias'] = '';

        $activity['footer'] = '';
        
        $activity['icon'] = '<i class="icon-layers"></i>';

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

/* End of file comments.php */