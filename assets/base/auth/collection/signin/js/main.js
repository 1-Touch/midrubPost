/*
 * Main Signin javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    // Get website url
    var url =  $('meta[name=url]').attr('content');
    
    /*******************************
    METHODS
    ********************************/
   

    /*******************************
    ACTIONS
    ********************************/

   
    /*******************************
    RESPONSES
    ********************************/ 
   
    /*
     * Display sign in response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.8
     */
    Main.methods.sign_in = function ( status, data ) {

        // Verify if message exists
        if ( data.message ) {

            // Prepare notification
            var notification = '<div class="notification">'
                    + data.message
                + '</div>';

            // Display notification
            $('section').after(notification);

            // Add show class
            $('.main .notification').addClass('show');

            // Wait 3 seconds
            setTimeout(function() {

                // Hide notification
                $('.main .notification').remove();

                if ( status === 'success' ) {

                    // Redirect user
                    document.location.href = data.redirect;

                }

            }, 3000);

        }

    };
    
    /*******************************
    FORMS
    ********************************/
   
    /*
     * Sign In
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.8
     */
    $('.main .form-signin').submit(function (e) {
        e.preventDefault();

        // Hide notification
        $('.main .notification').remove();        

        // Default remember value
        var remember = 0;
        
        // Check if remember checkbox is checked
        if ( $('.form-signin .remember-me').is(':checked') ) {    
            remember = 1;
        }

        // Get email
        var email = $('.form-signin .email').val();

        // Get password
        var password = $('.form-signin .password').val();

        // Define data to send
        var data = {
            action: 'sign_in',
            email: email,
            password: password,
            remember: remember
        };

        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'auth/ajax/signin', 'POST', data, 'sign_in');

    });
    
    /*******************************
    DEPENDENCIES
    ********************************/

 
});