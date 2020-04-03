/*
 * Chatbot Phone Numbers List javascript file
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
     * Loads phone numbers from database
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.1
     */
    Main.load_phone_numbers = function (page) {

        // Prepare data to send
        var data = {
            action: 'load_phone_numbers',
            key: $('.main .search-phone-numbers .phone-key').val(),
            page: page
        };

        // Set CSRF
        data[$('.main .search-phone-numbers').attr('data-csrf')] = $('input[name="' + $('.main .search-phone-numbers').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_phone_numbers');

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

                    // Load phone numbers
                    Main.load_phone_numbers(1);

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
     * Search for phone_numbers
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */
    $(document).on('keyup', '.main .phone-key', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main .cancel-phone-numbers-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main .cancel-phone-numbers-search').fadeIn('slow');

        }

        // Load phone numbers
        Main.load_phone_numbers(1);

    });

    /*
     * Cancel the phone_numbers search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */
    $(document).on('click', '.main .cancel-phone-numbers-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main .phone-key').val('');

        // Hide button
        $('.main .cancel-phone-numbers-search').fadeOut('slow');

        // Load phone numbers
        Main.load_phone_numbers(1);

    });

    /*
     * Change dropdown option
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', '.main #create-new-phone .dropdown-menu a', function (e) {
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

            case 'phone-numbers':

                // Load phone numbers
                Main.load_phone_numbers(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

        // Unselect
        $('.main #all-phone_numbers-select').prop('checked', false);

    });

    /*
     * Detect all phone numbers selection
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', '.main #all-phone-numbers-select', function (e) {

        setTimeout(function(){
            
            if ( $( '.main #all-phone-numbers-select' ).is(':checked') ) {

                $( '.main .phone-numbers-list input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.main .phone-numbers-list input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });

    /*
     * Delete phone_numbers
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', 'main .delete-phone-numbers', function (e) {
        
        // Get all selected phone_numbers
        var phone_numbers = $('.main .phone-numbers-list input[type="checkbox"]');
        
        // Default selected value
        var selected = [];
        
        // List all phone_numbers
        for ( var d = 0; d < phone_numbers.length; d++ ) {

            // Verify if is checked
            if ( phone_numbers[d].checked ) {
                selected.push($(phone_numbers[d]).attr('data-id'));
            }
            
        }
        
        if ( selected.length < 1 ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_at_least_phone, 1500, 2000);
            return;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'delete_phone_numbers',
            phone_numbers: Object.entries(selected)
        };

        // Set CSRF
        data[$('.main .search-phone-numbers').attr('data-csrf')] = $('input[name="' + $('.main .search-phone-numbers').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'delete_phone');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
    
    /*
     * Export phone numbers in a CSV
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', 'main .export-phone-csv', function (e) {
        e.preventDefault();
        
        // Create an object with form data
        var data = {
            action: 'check_for_phone_numbers'
        };

        // Set CSRF
        data[$('.main .search-phone-numbers').attr('data-csrf')] = $('input[name="' + $('.main .search-phone-numbers').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'check_for_phone_numbers');
        
    }); 

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display the phone deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.1
     */
    Main.methods.delete_phone = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display success alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Load phone numbers
            Main.load_phone_numbers(1);

            // Unselect
            $( '.main #all-phone_numbers-select' ).prop('checked', false);

        } else {

            // Display error alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }

    };

    /*
     * Display the phone numbers
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.1
     */
    Main.methods.load_phone_numbers = function (status, data) {

        // Hide pagination
        $('.main .chatbot-list .pagination').hide();

        // Unselect
        $('.main #all-phone-numbers-select').prop('checked', false);

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main .chatbot-list .pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main .chatbot-list', data.total);

            // phone_numbers var
            var phone_numbers = '';

            // List 10 phone_numbers
            for (var c = 0; c < data.phone_numbers.length; c++) {

                var new_phone = '';

                if ( data.phone_numbers[c].new > 0 ) {

                    new_phone = ' class="unread-notification"';

                }

                phone_numbers += '<li' + new_phone + '>'
                                    + '<div class="row">'
                                        + '<div class="col-12">'
                                            + '<a href="' + url + 'user/app/chatbot?p=history&conversation=' + data.phone_numbers[c].history_id + '" class="show-group">'
                                                + '<div class="checkbox-option-select">'
                                                    + '<input id="chatbot-phone-' + data.phone_numbers[c].phone_id + '" name="chatbot-phone-' + data.phone_numbers[c].phone_id + '" type="checkbox" data-id="' + data.phone_numbers[c].phone_id + '">'
                                                    + '<label for="chatbot-phone-' + data.phone_numbers[c].phone_id + '"></label>'
                                                + '</div>'
                                                + data.phone_numbers[c].body
                                            + '</a>'
                                        + '</div>'
                                    + '</div>'
                                + '</li>';

            }

            // Display phone_numbers
            $('.main .chatbot-list .phone-numbers-list').html(phone_numbers);

        } else {

            // No found phone numbers message
            var message = '<li class="found-results">'
                    + data.message
                + '</li>';

            // Display no phone numbers message
            $('.main .chatbot-list .phone-numbers-list').html(message);

        }

    };

    /*
     * Display the phone verification response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.1
     */
    Main.methods.check_for_phone_numbers = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Download
            document.location.href = url + 'user/app-ajax/chatbot?action=export_phone_csv';

        } else {

            // Display error alert
            Main.popup_fon('sube', data.message, 1500, 2000);            

        }

    };

    /*******************************
    FORMS
    ********************************/

    // Load phone numbers
    Main.load_phone_numbers(1);

});