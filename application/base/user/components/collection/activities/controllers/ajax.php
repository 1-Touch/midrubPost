<?php
/**
 * Ajax Controller
 *
 * This file processes the app's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

// Define the page namespace
namespace MidrubBase\User\Components\Collection\Activities\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Ajaz class processes the app's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.7.9
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.9
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load language
        $this->CI->lang->load( 'activities_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_COMPONENTS_ACTIVITIES );
        
    }

    /**
     * The public method activities_load_activities loads available activities
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function activities_load_activities() {

        // Load Activities Model
        $this->CI->load->model('activities');
        
        // Get page's get input
        $page = $this->CI->input->get('page');
        
        $limit = 10;
        
        $page--;

        // Get saved activities
        $activities = $this->CI->activities->get_activities( $this->CI->user_id, $page * $limit, $limit );

        // Get total activities
        $total = $this->CI->activities->get_activities( $this->CI->user_id );
        
        // Verify if activities exists
        if ( $activities ) {
            
            // All activities array
            $all_activities = array();

            // List all activities
            foreach ( $activities as $activity ) {
                
                // Verify if the app exists
                if ( file_exists( MIDRUB_BASE_USER . 'apps/collection/' . $activity->app . '/activities/' . $activity->template . '.php' ) ) {
            
                    try {

                        // Create an array
                        $array = array(
                            'MidrubBase',
                            'User',
                            'Apps',
                            'Collection',
                            ucfirst($activity->app),
                            'activities',
                            ucfirst($activity->template)
                        );       
        
                        // Implode the array above
                        $cl = implode('\\',$array);
                        
                        // Instantiate the class
                        $response = (new $cl())->template( $activity->user_id, $activity->member_id, $activity->id );
                        
                        $response['time'] = $activity->created;
                        
                        $all_activities[] = $response;
                        
                    } catch (Exception $ex) {
                        
                        continue;
                        
                    }

                } else {

                    continue;

                }
                
            }

            // Verify if activities exists
            if ( $all_activities ) {

                $data = array(
                    'success' => TRUE,
                    'activities' => $all_activities,
                    'date' => time(),
                    'total' => $total,
                    'page' => $page
                );

                echo json_encode($data);

            } else {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('no_activities_found')
                );

                echo json_encode($data);            

            }
            
        } else {

            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('no_activities_found')
            );

            echo json_encode($data);            

        }

    }
    
}

/* End of file ajax.php */