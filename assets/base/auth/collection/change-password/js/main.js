/*
 * Main Reset javascript file
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
     * Display change password response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.8
     */
    Main.methods.change_password = function ( status, data ) {

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
     * Change password
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.8
     */
    $('.main .form-change-password').submit(function (e) {
        e.preventDefault();

        // Get new password
        var new_password = $('.main .form-change-password .new-password').val();

        // Get repeat password
        var repeat_password = $('.main .form-change-password .repeat-password').val();

        // Get reset code
        var reset_code = $('.main .form-change-password .reset-code').val();

        // Get user_id
        var user_id = $('.main .form-change-password .user-id').val();

        // Define data to send
        var data = {
            action: 'change_password',
            new_password: new_password,
            repeat_password: repeat_password,
            reset_code: reset_code,
            user_id: user_id
        };

        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'auth/ajax/change-password', 'POST', data, 'change_password');

    });
    
    /*******************************
    DEPENDENCIES
    ********************************/

 
});