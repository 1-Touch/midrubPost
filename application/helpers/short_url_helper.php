<?php
/**
 * Short URL helper
 *
 * This file contains the method to short the url
 * i've added it here to call the helper only where is required to short a url
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('short_url')) {
    
    /**
     * The function short_url shorts a url
     * 
     * @return void
     */
    function short_url($url) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Load Urls Model
        $CI->load->model('urls');
        
        // Load Gshorter library
        $CI->load->library('gshorter');
        
        // Load URL Helper
        $CI->load->helper('url');
            
        // This function will return a short url if Gshorter is configured corectly
        return $CI->gshorter->short($url);
        
    }
    
}