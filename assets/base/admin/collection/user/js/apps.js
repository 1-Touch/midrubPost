/*
 * Apps javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    // Get home page url
    var url = $('.navbar-brand').attr('href');
    
    /*******************************
    METHODS
    ********************************/

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Install app
     * 
     * @since   0.0.7.9
     */
    $(document).on('click', '.user-page .new-app', function (e) {
        e.preventDefault();
        
        // Display alert
        Main.popup_fon('sube', 'Coming soon.', 1500, 2000);
        
    });

    /*
     * Display save changes button
     * 
     * @since   0.0.7.9
     */
    $(document).on('keyup', 'body .text-input', function () {

        // Display save button
        $('.settings-save-changes').fadeIn('slow');
        
    }); 
    
    /*
     * Display save changes button
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.9
     */
    $(document).on('change', 'body .checkbox-input', function (e) {
        
        // Display save button
        $('.settings-save-changes').fadeIn('slow');
        
    }); 

    /*
     * Save settings
     * 
     * @since   0.0.7.9
     */ 
    $( document ).on( 'click', '.user-page .settings-save-changes .btn', function () {
        
        // Hide save button
        $('.user-page .settings-save-changes').fadeOut('slow');

        // Get all inputs
        var inputs = $('.user-page .text-input').length;
        
        var all_inputs = [];
        
        for ( var i = 0; i < inputs; i++ ) {
            
            all_inputs[$('.user-page .text-input').eq(i).attr('id')] = $('.user-page .text-input').eq(i).val();
            
        }

        // Get all options
        var options = $('.user-page .checkbox-input').length;
        
        var all_options = [];
        
        for ( var o = 0; o < options; o++ ) {
            
            if ( $('.user-page .checkbox-input').eq(o).is(':checked') ) {
                
                all_options[$('.user-page .checkbox-input').eq(o).attr('id')] = 1;
                
            } else {
                
                all_options[$('.user-page .checkbox-input').eq(o).attr('id')] = 0;
                
            }
            
        }

        // Prepare data to send
        var data = {
            action: 'save_social_data',
            all_inputs: Object.entries(all_inputs),
            all_options: Object.entries(all_options)
        };
        
        data[$('.save-settings').attr('data-csrf')] = $('input[name="' + $('.save-settings').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/user', 'POST', data, 'save_app_data');

        // Show loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
   
    /*******************************
    RESPONSES
    ********************************/ 
   
    /*
     * Display app saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.9
     */
    Main.methods.save_app_data = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };
    
    /*******************************
    FORMS
    ********************************/
   

    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Hide loading animation
    $('.page-loading').fadeOut('slow');
 
});