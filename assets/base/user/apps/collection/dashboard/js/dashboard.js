/*
 * Dashboard javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url =  $('meta[name=url]').attr('content');

    // Hide the loading page animation
    setTimeout(function(){
        $('.page-loading').fadeOut('slow');
    }, 600);
    
});