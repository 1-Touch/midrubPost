/*
 * Themes javascript file
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
     * Install theme
     * 
     * @since   0.0.7.8
     */
    $(document).on('click', '.frontend-page .new-theme', function (e) {
        e.preventDefault();
        
        // Display alert
        Main.popup_fon('sube', 'Coming soon.', 1500, 2000);
        
    });

    /*
     * Activate theme
     * 
     * @since   0.0.7.8
     */
    $(document).on('click', '.frontend-page .activate-theme', function (e) {
        e.preventDefault();
        
        // Get theme's slug
        var theme_slug = $(this).closest('.theme-single').attr('data-slug');

        // Prepare data
        var data = {
            action: 'activate_theme',
            theme_slug: theme_slug
        };
        
        // Set the CSRF field
        data[$('.frontend-page .upload-new-theme').attr('data-csrf')] = $('.frontend-page .upload-new-theme input[name="csrf_test_name"]').val();

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/frontend', 'POST', data, 'activate_theme');
        
    }); 
    
    /*
     * Deactivate theme
     * 
     * @since   0.0.7.8
     */
    $(document).on('click', '.frontend-page .deactivate-theme', function (e) {
        e.preventDefault();
        
        // Get theme's slug
        var theme_slug = $(this).closest('.theme-single').attr('data-slug');

        // Prepare data
        var data = {
            action: 'deactivate_theme',
            theme_slug: theme_slug
        };
        
        // Set the CSRF field
        data[$('.frontend-page .upload-new-theme').attr('data-csrf')] = $('.frontend-page .upload-new-theme input[name="csrf_test_name"]').val();

        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/frontend', 'POST', data, 'deactivate_theme');
        
    });
   
    /*******************************
    RESPONSES
    ********************************/ 
   
    /*
     * Display theme activation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.8
     */
    Main.methods.activate_theme = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Refresh page after 2 seconds
            setTimeout(

                function(){

                    // Refresh page
                    document.location.href = document.location.href;

                }, 2000

            );
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*
     * Display theme deactivation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.8
     */
    Main.methods.deactivate_theme = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Refresh page after 2 seconds
            setTimeout(

                function(){

                    // Refresh page
                    document.location.href = document.location.href;

                }, 2000

            );
            
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