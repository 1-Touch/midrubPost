<?php
/**
 * Suggestions Helpers
 * 
 * PHP Version 7.3
 *
 * This file contains the class suggestions
 * with methods to process the suggestions data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Chatbot\Helpers;

// Constats
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Suggestions class provides the methods to process the suggestions data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
*/
class Suggestions {
    
    /**
     * Class variables
     *
     * @since 0.0.8.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load the FB Chatbot Suggestions Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_CHATBOT . 'models/', 'Fb_chatbot_suggestions_model', 'fb_chatbot_suggestions_model' );
        
    }

    //-----------------------------------------------------
    // Main class's methods
    //-----------------------------------------------------
    
    /**
     * The public method save_suggestions saves a suggestions group
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function save_suggestions() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('group_name', 'Group Name', 'trim|required');
            $this->CI->form_validation->set_rules('suggestions', 'Suggestions', 'trim');
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');
            $this->CI->form_validation->set_rules('group_id', 'Group ID', 'trim');

            // Get data
            $group_name = $this->CI->input->post('group_name', TRUE);
            $suggestions = $this->CI->input->post('suggestions', TRUE);
            $categories = $this->CI->input->post('categories', TRUE);
            $group_id = $this->CI->input->post('group_id', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Verify if group's id exists
                if ( $group_id ) {

                    // Use the base model for a simply sql query
                    $get_suggestions = $this->CI->base_model->get_data_where(
                        'chatbot_suggestions',
                        'suggestion_id',
                        array(
                            'group_id' => $group_id,
                            'user_id' => $this->CI->user_id
                        )
                    );

                    // Verify if suggestions exists
                    if ( $get_suggestions ) {

                        // Delete group's categories
                        $this->CI->base_model->delete('chatbot_suggestions_categories', array('group_id' => $group_id));

                        // List all suggestions
                        foreach ($get_suggestions as $get_suggestion) {

                            // Delete suggestions
                            $this->CI->base_model->delete('chatbot_suggestions', array('suggestion_id' => $get_suggestion['suggestion_id']));
                            $this->CI->base_model->delete('chatbot_suggestions_meta', array('suggestion_id' => $get_suggestion['suggestion_id']));

                        }

                    }

                } else {

                    // Try to create the group
                    $group = array(
                        'user_id' => $this->CI->user_id,
                        'group_name' => $group_name,
                        'created' => time()
                    );

                    // Save Group by using the Base's Model
                    $group_id = $this->CI->base_model->insert('chatbot_groups', $group);   

                }             

                // Verify if suggestions exists
                if ( is_numeric($group_id) ) {

                    // Verify if categories exists
                    if (  $categories ) {

                        // List all categories
                        foreach ( $categories as $category ) {

                            // Category should be numeric
                            if ( is_numeric($category) ) {

                                // Category to save
                                $category_array = array(
                                    'group_id' => $group_id,
                                    'category_id' => $category
                                );

                                // Save category by using the basic model
                                $this->CI->base_model->insert('chatbot_suggestions_categories', $category_array); 

                            }

                        }

                    }

                    // Suggestions aren't required
                    if ( $suggestions ) {

                        // List all suggestions
                        for ( $m = 0; $m < count($suggestions); $m++ ) {

                            // Verify if the template's type exists
                            if ( isset($suggestions[0]['type']) ) {

                                // Template's
                                $template = array(
                                    'group_id' => $group_id,
                                    'user_id' => $this->CI->user_id,
                                    'template_type' => $suggestions[0]['type'],
                                    'created' => time()
                                );

                                // Save Suggestions Type
                                $main_id = $this->CI->base_model->insert('chatbot_suggestions', $template);

                                // Verify if the main suggestions group was saved
                                if ( $main_id ) {

                                    // Set suggestion's ID
                                    $suggestions[$m]['suggestion_id'] = $main_id;

                                    // Save header
                                    $this->save_header($suggestions[$m]);

                                    // Save body
                                    $this->save_body($suggestions[$m]);

                                    // Verify if suggestions exists
                                    if ( !empty($suggestions[$m]['suggestions']) ) {

                                        // List all suggestions
                                        for ( $s = 0; $s < count($suggestions[$m]['suggestions']); $s++ ) {

                                            // Template's
                                            $template = array(
                                                'group_id' => $group_id,
                                                'user_id' => $this->CI->user_id,
                                                'template_type' => $suggestions[$m]['suggestions'][$s]['type'],
                                                'created' => time(),
                                                'parent_id' => $main_id
                                            );

                                            // Save Suggestions Type
                                            $second_id = $this->CI->base_model->insert('chatbot_suggestions', $template);

                                            // Verify if the main suggestions group was saved
                                            if ( $second_id ) {

                                                // Set suggestion's ID
                                                $suggestions[$m]['suggestions'][$s]['suggestion_id'] = $second_id;

                                                // Save header
                                                $this->save_header($suggestions[$m]['suggestions'][$s]);

                                                // Save body
                                                $this->save_body($suggestions[$m]['suggestions'][$s]);

                                                // Verify if suggestions exists
                                                if ( !empty($suggestions[$m]['suggestions'][$s]['suggestions']) ) {

                                                    // List all suggestions
                                                    for ( $t = 0; $t < count($suggestions[$m]['suggestions'][$s]['suggestions']); $t++ ) {

                                                        // Template's
                                                        $template = array(
                                                            'group_id' => $group_id,
                                                            'user_id' => $this->CI->user_id,
                                                            'template_type' => $suggestions[$m]['suggestions'][$s]['suggestions'][$t]['type'],
                                                            'created' => time(),
                                                            'parent_id' => $second_id
                                                        );

                                                        // Save Suggestions Type
                                                        $third_id = $this->CI->base_model->insert('chatbot_suggestions', $template);

                                                        // Verify if the main suggestions group was saved
                                                        if ( $third_id ) {

                                                            // Set suggestion's ID
                                                            $suggestions[$m]['suggestions'][$s]['suggestions'][$t]['suggestion_id'] = $third_id;

                                                            // Save header
                                                            $this->save_header($suggestions[$m]['suggestions'][$s]['suggestions'][$t]);

                                                            // Save body
                                                            $this->save_body($suggestions[$m]['suggestions'][$s]['suggestions'][$t]);

                                                            // Verify if suggestions exists
                                                            if (!empty($suggestions[$m]['suggestions'][$s]['suggestions'][$t]['suggestions'])) {

                                                                // List all suggestions
                                                                for ($f = 0; $f < count($suggestions[$m]['suggestions'][$s]['suggestions'][$t]['suggestions']); $f++) {

                                                                    // Template's
                                                                    $template = array(
                                                                        'group_id' => $group_id,
                                                                        'user_id' => $this->CI->user_id,
                                                                        'template_type' => $suggestions[$m]['suggestions'][$s]['suggestions'][$t]['suggestions'][$f]['type'],
                                                                        'created' => time(),
                                                                        'parent_id' => $third_id
                                                                    );

                                                                    // Save Suggestions Type
                                                                    $fourth_id = $this->CI->base_model->insert('chatbot_suggestions', $template);

                                                                    // Verify if the main suggestions group was saved
                                                                    if ( $fourth_id ) {

                                                                        // Set suggestion's ID
                                                                        $suggestions[$m]['suggestions'][$s]['suggestions'][$t]['suggestions'][$f]['suggestion_id'] = $fourth_id;

                                                                        // Save header
                                                                        $this->save_header($suggestions[$m]['suggestions'][$s]['suggestions'][$t]['suggestions'][$f]);

                                                                        // Save body
                                                                        $this->save_body($suggestions[$m]['suggestions'][$s]['suggestions'][$t]['suggestions'][$f]);

                                                                    }

                                                                }
                                                                
                                                            }

                                                        }

                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }

                        }

                    }

                    // Prepare success response
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('group_was_saved'),
                        'group_id' => $group_id
                    );

                    // Display response
                    echo json_encode($data);
                    exit();

                }
                
            }
            
        }

        // Prepare no category found message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('group_was_not_saved')
        );

        // Display response
        echo json_encode($data);
        
    }

    /**
     * The public method load_suggestions loads suggestions based on suggestion's group id
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function load_suggestions() {

        // Get group's ID
        $group_id = $this->CI->input->get('group_id', TRUE);

        // If group's ID is numeric
        if ( is_numeric($group_id) ) {

            // Get Suggestions By Group ID
            $suggestions = $this->CI->fb_chatbot_suggestions_model->get_suggestions($group_id, $this->CI->user_id);

            // Verify if suggestions exists
            if ( $suggestions ) {

                // All suggestions
                $all_suggestions = array();

                // Level
                $level = 0;

                // Sublevel
                $sublevel = 0;
            
                // Last parent
                $last_parent = 0;

                // Set first children
                $all_suggestions[$level] = array(
                    'header' => array(
                        array(
                            'header' => array()
                        )
                    ),
                    'body' => array(
                        array()
                    ),
                    'type' => ''
                );

                // List all suggestions
                for ( $m = 0; $m < count($suggestions); $m++ ) {
                    
                    if ( $suggestions[$m]['parent_id'] < 1 ) {

                        if ( $suggestions[$m]['field_type'] === 'header' ) {

                            if ( isset($all_suggestions[$level]['header'][0]['header'][$suggestions[$m]['field_name']]) ) {

                                $level++;
                                
                                $all_suggestions[$level] = array(
                                    'header' => array(
                                        array(
                                            'header' => array()
                                        )
                                    ),
                                    'body' => array(
                                        array()
                                    ),
                                    'type' => ''
                                );

                            }

                            // Set header
                            $all_suggestions[$level]['header'][0]['header'][$suggestions[$m]['field_name']] = $suggestions[$m]['field_value'];

                            // Set type
                            $all_suggestions[$level]['type'] = $suggestions[$m]['template_type'];

                            // Set Suggestion ID
                            $all_suggestions[$level]['suggestion_id'] = $suggestions[$m]['suggestion_id'];

                        } else if ($suggestions[$m]['field_type'] === 'body') {

                            // Set body
                            $all_suggestions[$level]['body'][0][$suggestions[$m]['field_name']] = $suggestions[$m]['field_value'];

                        }

                    } else {
                        
                        if ( $last_parent !== $suggestions[$m]['parent_id'] ) {

                            for ( $fm = 0; $fm < count($all_suggestions); $fm++ ) {

                                if ( $all_suggestions[$fm]['suggestion_id'] === $suggestions[$m]['parent_id'] ) {

                                        if ( !isset($all_suggestions[$fm]['suggestions']) ) {

                                            $sublevel = 0;

                                        }

                                        if ( $suggestions[$m]['field_type'] === 'header' ) {

                                            if ( isset($all_suggestions[$fm]['suggestions']) ) {

                                                for ($sa = 0; $sa < count($all_suggestions[$fm]['suggestions']); $sa++) {

                                                    if (isset($all_suggestions[$fm]['suggestions'][$sa]['suggestion_id']) && isset($all_suggestions[$fm]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']])) {

                                                        if ($sublevel < 1) {

                                                            $sublevel = $sa + 1;

                                                        } else {

                                                            break 1;

                                                        }

                                                    }

                                                }

                                            }

                                            if ( isset($all_suggestions[$fm]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']]) ) {
                
                                                $sublevel++;
                
                                            }

                                            // Set header
                                            $all_suggestions[$fm]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']] = $suggestions[$m]['field_value'];
                
                                            // Set type
                                            $all_suggestions[$fm]['suggestions'][$sublevel]['type'] = $suggestions[$m]['template_type'];
                
                                            // Set Suggestion ID
                                            $all_suggestions[$fm]['suggestions'][$sublevel]['suggestion_id'] = $suggestions[$m]['suggestion_id'];


                
                                        } else if ($suggestions[$m]['field_type'] === 'body') {
                
                                            // Set body
                                            $all_suggestions[$fm]['suggestions'][$sublevel]['body'][0][$suggestions[$m]['field_name']] = $suggestions[$m]['field_value'];
                
                                        }

                                } else {

                                    if (isset($all_suggestions[$fm]['suggestions'])) {
                                        
                                        for ($fs = 0; $fs < count($all_suggestions[$fm]['suggestions']); $fs++) {

                                            if ( $all_suggestions[$fm]['suggestions'][$fs]['suggestion_id'] === $suggestions[$m]['parent_id'] ) {

                                                if ( !isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions']) ) {
                                                    $sublevel = 0;
                                                }
        
                                                if ( $suggestions[$m]['field_type'] === 'header' ) {
        
                                                    if ( isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions']) ) {
        
                                                        for ($ss = 0; $ss < count($all_suggestions[$fm]['suggestions'][$fs]['suggestions']); $ss++) {
        
                                                            if (isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ss]['suggestion_id']) && isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']])) {
        
                                                                if ($sublevel < 1) {
        
                                                                    $sublevel = $ss;
        
                                                                } else {
        
                                                                    break 2;
        
                                                                }
        
                                                            }
        
                                                        }
        
                                                    }
        
                                                    if ( isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']]) ) {
                        
                                                        $sublevel++;
                        
                                                    }
        
                                                    // Set header
                                                    $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']] = $suggestions[$m]['field_value'];
                        
                                                    // Set type
                                                    $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$sublevel]['type'] = $suggestions[$m]['template_type'];
                        
                                                    // Set Suggestion ID
                                                    $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$sublevel]['suggestion_id'] = $suggestions[$m]['suggestion_id'];
        
        
                        
                                                } else if ($suggestions[$m]['field_type'] === 'body') {
                        
                                                    // Set body
                                                    $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$sublevel]['body'][0][$suggestions[$m]['field_name']] = $suggestions[$m]['field_value'];
                        
                                                }

                                            }

                                            if (isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'])) {                                                
                                                
                                                for ($ft = 0; $ft < count($all_suggestions[$fm]['suggestions'][$fs]['suggestions']); $ft++) {

                                                    if ( $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestion_id'] === $suggestions[$m]['parent_id'] ) {

                                                        if ( !isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions']) ) {
                                                            $sublevel = 0;
                                                        }
                
                                                        if ( $suggestions[$m]['field_type'] === 'header' ) {
                
                                                            if ( isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions']) ) {
                
                                                                for ($ss = 0; $ss < count($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions']); $ss++) {
                
                                                                    if (isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions'][$ss]['suggestion_id']) && isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']])) {
                
                                                                        if ($sublevel < 1) {
                
                                                                            $sublevel = $ss;
                
                                                                        } else {

                                                                            break 2;
                
                                                                        }
                
                                                                    }
                
                                                                }
                
                                                            }
                
                                                            if ( isset($all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']]) ) {
                                
                                                                $sublevel++;
                                
                                                            }
                
                                                            // Set header
                                                            $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions'][$sublevel]['header'][0]['header'][$suggestions[$m]['field_name']] = $suggestions[$m]['field_value'];
                                
                                                            // Set type
                                                            $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions'][$sublevel]['type'] = $suggestions[$m]['template_type'];
                                
                                                            // Set Suggestion ID
                                                            $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions'][$sublevel]['suggestion_id'] = $suggestions[$m]['suggestion_id'];
                
                
                                
                                                        } else if ($suggestions[$m]['field_type'] === 'body') {
                                
                                                            // Set body
                                                            $all_suggestions[$fm]['suggestions'][$fs]['suggestions'][$ft]['suggestions'][$sublevel]['body'][0][$suggestions[$m]['field_name']] = $suggestions[$m]['field_value'];
                                
                                                        }
        
                                                    }

                                                }

                                            }

                                        }

                                    }

                                }

                            }

                        }

                    }

                }

                // Prepare suggestions list
                $suggestions = array(
                    'success' => TRUE,
                    'suggestions' => $all_suggestions,
                    'group_name' => $suggestions[0]['group_name']

                );

                // Display suggestions
                echo json_encode($suggestions);
                exit();

            }
            
        }

        // Prepare no suggestions response
        $suggestions = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('no_suggestions_found')
        );

        // Display message
        echo json_encode($suggestions);
        
    }

    //-----------------------------------------------------
    // Methods used as helpers for the the class's methods
    //-----------------------------------------------------

    /**
     * The protected method save_header saves a suggestion's header
     * 
     * @param $suggestion contains a suggestion
     * 
     * @since 0.0.8.0
     * 
     * @return boolean with true or false
     */ 
    protected function save_header($suggestion) {

        // Verify if header exist
        if ( !empty($suggestion['header'][0]['header']) ) {

            // If header is an array
            if ( is_array($suggestion['header'][0]['header']) ) {

                // Count save
                $count = 0;

                // List all header's fields
                foreach ( $suggestion['header'][0]['header'] as $key => $value ) {

                    // Set suggestion's header
                    $suggestion_header = array(
                        'suggestion_id' => $suggestion['suggestion_id'],
                        'field_type' => 'header',
                        'field_name' => $key,
                        'field_value' => $value
                    );

                    // Use the basic model to save data
                    if ( $this->CI->base_model->insert('chatbot_suggestions_meta', $suggestion_header) ) {
                        $count++;
                    }

                }

                // Verify if at least one header was saved
                if ( $count > 0 ) {
                    return true;
                }

            }

        }

        return false;

    }

    /**
     * The protected method save_body saves a suggestion's body
     * 
     * @param $suggestion contains a suggestion
     * 
     * @since 0.0.8.0
     * 
     * @return boolean true or false
     */ 
    protected function save_body($suggestion) {

        // Verify if body exist
        if ( !empty($suggestion['body'][0]) ) {

            // If body is an array
            if ( is_array($suggestion['body'][0]) ) {

                // Count save
                $count = 0;

                // List all body's fields
                foreach ( $suggestion['body'][0] as $key => $value ) {

                    // Set suggestion's body
                    $suggestion_body = array(
                        'suggestion_id' => $suggestion['suggestion_id'],
                        'field_type' => 'body',
                        'field_name' => $key,
                        'field_value' => $value
                    );

                    // Use the basic model to save data
                    if ( $this->CI->base_model->insert('chatbot_suggestions_meta', $suggestion_body) ) {
                        $count++;
                    }

                }

                // Verify if at least one header was saved
                if ( $count > 0 ) {
                    return true;
                }

            }

        }

        return false;

    }
    
}

/* End of file suggestions.php */