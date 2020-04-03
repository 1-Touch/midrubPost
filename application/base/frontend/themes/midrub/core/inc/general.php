<?php
/**
 * General Theme Inc
 *
 * This file contains the general theme's functions
 * used in the theme
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('set_the_seo_title') ) {
    
    /**
     * The function set_the_seo_title sets the page's seo title
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    function set_the_seo_title() {

        // Get the seo title
        $meta_value = md_the_single_content_meta('quick_seo_page_title');

        if ($meta_value) {
            md_set_the_title($meta_value);
        } else {
            md_set_the_title(md_the_single_content_meta('content_title'));
        }
        
    }
    
}

// Set the seo title
set_the_seo_title();

if ( !function_exists('set_the_seo_meta_description') ) {
    
    /**
     * The function set_the_seo_meta_description sets the page's seo meta description
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    function set_the_seo_meta_description() {

        // Get the seo meta's description
        $meta_value = md_the_single_content_meta('quick_seo_meta_description');

        if ($meta_value) {
            md_set_the_meta_description($meta_value);
        }
        
    }
    
}

// Set the seo meta description
set_the_seo_meta_description();

if ( !function_exists('set_the_seo_meta_keywords') ) {
    
    /**
     * The function set_the_seo_meta_keywords sets the page's seo meta keywords
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    function set_the_seo_meta_keywords() {

        // Get the seo meta's keywords
        $meta_value = md_the_single_content_meta('quick_seo_meta_keywords');

        if ($meta_value) {
            md_set_the_meta_keywords($meta_value);
        }
        
    }
    
}

// Set the seo meta keywords
set_the_seo_meta_keywords();

if ( !function_exists('get_all_visible_plans') ) {
    
    /**
     * The function get_all_visible_plans returns visible plans if exists
     * 
     * @since 0.0.7.8
     * 
     * @return array with plans
     */
    function get_all_visible_plans() {

        // Args to pass
        $args = array(
            'visible' => 0
        );

        // Return plans
        return (new MidrubBase\Classes\Plans\Read)->get_plans($args);
        
    }
    
}

if ( !function_exists('get_home_page_stats') ) {
    
    /**
     * The function get_home_page_stats processes the home page stats
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    function get_home_page_stats() {

        $stats = the_content_meta_list('theme_top_section_stats');

        if ( $stats ) {
            
            $count = '12';

            if ( count($stats) === 2 ) {
                $count = '6';
            } else if ( count($stats) === 3 ) {
                $count = '4';
            } else if ( count($stats) === 4 ) {
                $count = '3';
            }

            $total = 4;

            if ( count($stats) < 4 ) {
                $total = count($stats);
            }

            for ( $e = 0; $e < $total; $e++ ) {

                echo '<div class="col-md-' . $count . ' col-12">'
                    . '<h3>'
                        . $stats[$e]['theme_top_section_stats_title']
                    . '</h3>'
                    . '<h5>'
                        . $stats[$e]['theme_top_section_stats_value']
                    . '</h5>'
                . '</div>';

            }

        }
        
    }
    
}