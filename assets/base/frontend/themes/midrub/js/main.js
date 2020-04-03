/*
 * Main javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    // Get website url
    var url =  $('meta[name=url]').attr('content');
    
    /*******************************
    METHODS
    ********************************/

    /*
     * Get cookie
     * 
     * @since   0.0.7.8
     */    
    Main.getCookie =  function () {

        // Get cookie
        var cookie = document.cookie.match('(^|;) ?cookie-agree=([^;]*)(;|$)');
        
        // If cookie exists
        if ( cookie ) {

            // Hide modal
            $('.gdpr-modal').hide();
    
        } else {
    
            // Show modal
            $('.gdpr-modal').show();
    
        }
        
    };

    /*
     * Set cookie
     * 
     * @param string key contains the cookie key
     * @param string value contains the cookie value
     * 
     * @since   0.0.7.8
     */    
    Main.setCookie =  function (key, value) {

        // Get date
        var expires = new Date();

        // Set cookie life
        expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));

        // Save cookie
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        
    };    
  
    /*******************************
    ACTIONS
    ********************************/

    /*
     * Open quick answer
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.8
     */
    $(document).on('click', '.main-questions .list-group-item > a', function (e) {
        e.preventDefault();
        
        // Remove active class
        $('.main-questions .list-group-item').removeClass('active');

        // Add active class
        $(this).closest('.list-group-item').addClass('active');
        
    });

    /*
     * Open video presentation
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.8
     */
    $(document).on('click', '.main-features #video-presentation  a', function (e) {
        e.preventDefault();
        
        // Get iframe
        var iframe = $('#video-modal');

        // Get video url
        var video_url = $(this).attr('href');

        // Set video
        iframe.attr('src', video_url + '?vq=hd720');
      
        // Show modal
        $("#video-presentation-modal").modal("show");
        
    });

    /*
     * Save cookie
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.8
     */
    $(document).on('click', 'body .gdpr-modal .accept-cookies', function () {

        // Set cookie
        Main.setCookie('cookie-agree', 1);

        // Hide modal
        $('.gdpr-modal').hide();
        
    });
   
    /*******************************
    RESPONSES
    ********************************/ 

    /*
     * Display contact form response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.8
     */
    Main.methods.contact_us = function ( status, data ) {

        // Prepare notification
        var notification = '<div class="notification">'
                + data.message
            + '</div>';

        // Display notification
        $('section').after(notification);

        // Add show class
        $('main .notification').addClass('show');

        // Wait 3 seconds
        setTimeout(function () {

            // Hide notification
            $('main .notification').remove();

            if ( status === 'success' ) {

                // Reset the form
                $('main .form-contact-us')[0].reset();

            }

        }, 3000);
        
    };
    
    /*******************************
    FORMS
    ********************************/

    /*
     * Submit contact form
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.8
     */
    $(document).on('submit', '.contact-area .form-contact-us', function (e) {
        e.preventDefault();
        
        // Get full name
        var full_name = $(this).find('.full-name').val();
        
        // Get email
        var email = $(this).find('.email').val();

        // Get subject
        var subject = $(this).find('.subject').val();

        // Get message
        var message = $(this).find('.message').val();

        // Get reCaptcha code
        var recaptcha = $(this).find('.g-recaptcha-response').val();

        // Get content id
        var content_id = $(this).find('.content-id').val();
        
        // Create an object with form data
        var data = {
            action: 'contact_us',
            full_name: full_name,
            email: email,
            subject: subject,
            message: message,
            recaptcha: recaptcha,
            content_id: content_id
        };

        // Set the CSRF field
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'theme-ajax', 'POST', data, 'contact_us');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Verify if user has approved cookies
    Main.getCookie();

});