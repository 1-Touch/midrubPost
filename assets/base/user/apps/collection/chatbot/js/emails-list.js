/*
 * Chatbot Emails Addresses List javascript file
*/

jQuery(document).ready(function ($) {
    'use strict';

    /*
     * Get the website's url
     */
    var url = $('meta[name=url]').attr('content');

    /*******************************
    METHODS
    ********************************/

    /*
     * Loads Emails Addresses from database
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.1
     */
    Main.load_email_addresses = function (page) {

        // Prepare data to send
        var data = {
            action: 'load_email_addresses',
            key: $('.main .search-email-addressess .email-key').val(),
            page: page
        };

        // Set CSRF
        data[$('.main .search-email-addressess').attr('data-csrf')] = $('input[name="' + $('.main .search-email-addressess').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_email_addresses');

    };

    /*
     * Upload a CSV file
     * 
     * @param object file contains the file
     * 
     * @since   0.0.7.5
     */
    Main.saveCsvFile = function (file) {
        
        if ( file.size > ( parseInt($('.chatbot-page').attr('data-up')) * 1048576 ) ) {
            
            // Display alert
            Main.popup_fon('sube', words.file_too_large, 1500, 2000);
            
            return;
            
        }
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');

        var fileType = file.type.split('/');
        
        // Prepare the form's data
        var form = new FormData();
        
        form.append('path', '/');
        
        form.append('file', file);
        
        form.append('type', fileType[0]);
        
        form.append('enctype', 'multipart/form-data');
        
        form.append($('.upcsv').attr('data-csrf'), $('input[name="' + $('.upcsv').attr('data-csrf') + '"]').val());
        
        // Set the action
        form.append('action', 'upload_csv');

        // Upload media
        $.ajax({
            url: url + 'user/app-ajax/chatbot',
            type: 'POST',
            data: form,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (data) {

                // Verify if the success response exists
                if ( data.success ) {

                    // Display alert
                    Main.popup_fon('subi', data.message, 1500, 2000);

                    // Load Emails Addresses
                    Main.load_email_addresses(1);

                } else {

                    // Display alert
                    Main.popup_fon('sube', data.message, 1500, 2000);

                }
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                
                console.log(jqXHR);
                
            },
            complete: function (jqXHR, textStatus, errorThrown) {
                
                // Hide loading animation
                $('.page-loading').fadeOut('slow');
        
            }
            
        });

    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search for email_addresses
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */
    $(document).on('keyup', '.main .email-key', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main .cancel-emails-addresses-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main .cancel-emails-addresses-search').fadeIn('slow');

        }

        // Load Emails Addresses
        Main.load_email_addresses(1);

    });

    /*
     * Cancel the email_addresses search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */
    $(document).on('click', '.main .cancel-emails-addresses-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main .email-key').val('');

        // Hide button
        $('.main .cancel-emails-addresses-search').fadeOut('slow');

        // Load Emails Addresses
        Main.load_email_addresses(1);

    });

    /*
     * Change dropdown option
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', '.main #create-new-email .dropdown-menu a', function (e) {
        e.preventDefault();
        
        // Get Dropdown's ID
        var id = $(this).attr('data-id');
        
        // Set id
        $(this).closest('.dropdown').find('.btn-secondary').attr('data-id', id);

        // Set specifi text
        $(this).closest('.dropdown').find('.btn-secondary').html($(this).html());
        
    });

    /*
     * Displays pagination by page click
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */
    $(document).on('click', 'body .pagination li a', function (e) {
        e.preventDefault();

        // Verify which pagination it is based on data's type 
        var page = $(this).attr('data-page');

        // Display results
        switch ($(this).closest('ul').attr('data-type')) {

            case 'email-addresses':

                // Load Emails Addresses
                Main.load_email_addresses(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

        // Unselect
        $('.main #all-email_addresses-select').prop('checked', false);

    });

    /*
     * Detect all Emails Addresses selection
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', '.main #all-email-addresses-select', function (e) {

        setTimeout(function(){
            
            if ( $( '.main #all-email-addresses-select' ).is(':checked') ) {

                $( '.main .email-addresses-list input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.main .email-addresses-list input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });

    /*
     * Delete email addresses
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', 'main .delete-email-addresses', function (e) {
        
        // Get all selected email addresses
        var email_addresses = $('.main .email-addresses-list input[type="checkbox"]');
        
        // Default selected value
        var selected = [];
        
        // List all email addresses
        for ( var d = 0; d < email_addresses.length; d++ ) {

            // Verify if is checked
            if ( email_addresses[d].checked ) {
                selected.push($(email_addresses[d]).attr('data-id'));
            }
            
        }
        
        if ( selected.length < 1 ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_at_least_email, 1500, 2000);
            return;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'delete_email_addresses',
            email_addresses: Object.entries(selected)
        };

        // Set CSRF
        data[$('.main .search-email-addressess').attr('data-csrf')] = $('input[name="' + $('.main .search-email-addressess').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'delete_email');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
    
    /*
     * Export email addresses in a CSV
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', 'main .export-email-csv', function (e) {
        e.preventDefault();
        
        // Create an object with form data
        var data = {
            action: 'check_for_email_addresses'
        };

        // Set CSRF
        data[$('.main .search-email-addressess').attr('data-csrf')] = $('input[name="' + $('.main .search-email-addressess').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'check_for_email_addresses');
        
    }); 

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display the email deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.1
     */
    Main.methods.delete_email = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display success alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Load Emails Addresses
            Main.load_email_addresses(1);

            // Unselect
            $( '.main #all-email_addresses-select' ).prop('checked', false);

        } else {

            // Display error alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }

    };

    /*
     * Display the Emails Addresses
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.1
     */
    Main.methods.load_email_addresses = function (status, data) {

        // Hide pagination
        $('.main .chatbot-list .pagination').hide();

        // Unselect
        $('.main #all-email-addresses-select').prop('checked', false);

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main .chatbot-list .pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main .chatbot-list', data.total);

            // email_addresses var
            var email_addresses = '';

            // List 10 email_addresses
            for (var c = 0; c < data.email_addresses.length; c++) {

                var new_email = '';

                if ( data.email_addresses[c].new > 0 ) {

                    new_email = ' class="unread-notification"';

                }

                email_addresses += '<li' + new_email + '>'
                                    + '<div class="row">'
                                        + '<div class="col-12">'
                                            + '<a href="' + url + 'user/app/chatbot?p=history&conversation=' + data.email_addresses[c].history_id + '" class="show-group">'
                                                + '<div class="checkbox-option-select">'
                                                    + '<input id="chatbot-email-' + data.email_addresses[c].email_id + '" name="chatbot-email-' + data.email_addresses[c].email_id + '" type="checkbox" data-id="' + data.email_addresses[c].email_id + '">'
                                                    + '<label for="chatbot-email-' + data.email_addresses[c].email_id + '"></label>'
                                                + '</div>'
                                                + data.email_addresses[c].body
                                            + '</a>'
                                        + '</div>'
                                    + '</div>'
                                + '</li>';

            }

            // Display email_addresses
            $('.main .chatbot-list .email-addresses-list').html(email_addresses);

        } else {

            // No found Emails Addresses message
            var message = '<li class="found-results">'
                    + data.message
                + '</li>';

            // Display no Emails Addresses message
            $('.main .chatbot-list .email-addresses-list').html(message);

        }

    };

    /*
     * Display the email verification response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.1
     */
    Main.methods.check_for_email_addresses = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Download
            document.location.href = url + 'user/app-ajax/chatbot?action=export_email_csv';

        } else {

            // Display error alert
            Main.popup_fon('sube', data.message, 1500, 2000);            

        }

    };

    /*******************************
    FORMS
    ********************************/

    // Load Emails Addresses
    Main.load_email_addresses(1);

});