<?php
/**
 * Classification Themes Inc
 *
 * This file contains the classifications themes functions
 * used in the themes
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.9
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH RETURNS DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('the_contents_list_by_the_classification') ) {
    
    /**
     * The function the_contents_list_by_the_classification gets the contents's list in the classification page
     * 
     * @since 0.0.7.9
     * 
     * @return object with contents list or boolean false
     */
    function the_contents_list_by_the_classification() {
        
        // Gets contents_list's component variable
        return md_the_component_variable('contents_list');
        
    }
    
}

if ( !function_exists('the_classification_meta') ) {
    
    /**
     * The function the_classification_meta gets the classification's meta
     * 
     * @param $meta contains the classification's meta
     * 
     * @since 0.0.7.9
     * 
     * @return string with classification's meta or boolean false
     */
    function the_classification_meta($meta) {
        
        if ( md_the_component_variable('single_classification') ) {

            if ( isset(md_the_component_variable('single_classification')[$meta]) ) {
                return md_the_component_variable('single_classification')[$meta];
            }

        }

        return false;
        
    }
    
}

if ( !function_exists('the_classification_url') ) {
    
    /**
     * The function the_classification_url gets the classification's url
     * 
     * @since 0.0.7.9
     * 
     * @return string with url or boolean false
     */
    function the_classification_url() {
        
        if ( md_the_component_variable('single_classification') ) {

            if ( isset(md_the_component_variable('single_classification')['item_slug']) ) {
                return site_url(md_the_component_variable('classification_slug') . '/' . md_the_component_variable('single_classification')['item_slug']);
            }

        }

        return false;
        
    }
    
}

if ( !function_exists('the_classification_breadcrumb') ) {
    
    /**
     * The function the_classification_breadcrumb generates the classification's breadcrumb
     * 
     * @since 0.0.7.9
     * 
     * @return array with items
     */
    function the_classification_breadcrumb() {

        $list = array();
        
        // Verify if classification has parent
        if ( md_the_component_variable('classification_item_parent') ) {

            // Set classification's id
            $classification_id = md_the_component_variable('classification_item_id');

            if ( $classification_id ) {

                for ( $e = 0; $e < 100; $e++ ) {

                    // Get classification's item
                    $item = the_db_request(
                        'classifications',
                        'classifications.classification_id, classifications.parent, classifications_meta.meta_slug, classifications_meta.meta_value as name',
                        array(
                            'classifications.classification_id' => $classification_id,
                            'classifications_meta.meta_name' => 'name'
                        ),
                        array(),
                        array(),
                        array(
                            array(
                                'table' => 'classifications_meta',
                                'condition' => 'classifications.classification_id=classifications_meta.classification_id',
                                'join_from' => 'LEFT'
                            )

                        )

                    );

                    if ( $item ) {

                        if ( $classification_id === md_the_component_variable('classification_item_id') ) {
                                
                            $list[$e] = array(
                                'name' => $item[0]['name']
                            );

                        } else {

                            $list[$e] = array(
                                'name' => $item[0]['name'],
                                'url' => site_url(md_the_component_variable('classification_slug') . '/' . $item[0]['meta_slug'])
                            );

                        }
                        
                        if ( $item[0]['parent'] > 0 ) {

                            $classification_id = $item[0]['parent'];

                        } else {

                            break;

                        }

                    }

                }

                if ( $list ) {
                    array_multisort ($list, SORT_DESC, $list);
                }
                
            }

        } else {

            $list[] = array(
                'name' => md_the_component_variable('classification_item_name')
            );

        }

        return $list;
        
    }
    
}

/*
|--------------------------------------------------------------------------
| DEFAULTS FUNCTIONS WHICH DISPLAYS DATA
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| DEFAULT FUNCTIONS TO SAVE DATA
|--------------------------------------------------------------------------
*/

if ( !function_exists('set_single_classification') ) {
    
    /**
     * The function set_single_classification sets the single classification
     * 
     * @param array $classification contains the classification's data
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    function set_single_classification($content) {

        // Set the classification's ID
        md_set_component_variable('single_classification', $content);
        
    }
    
}