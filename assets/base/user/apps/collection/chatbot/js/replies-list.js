/*
 * Chatbot Replies List javascript file
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
     * Loads replies from database
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.0
     */
    Main.load_replies = function (page) {

        // Prepare data to send
        var data = {
            action: 'load_replies',
            key: $('.main .search-replies .replies-key').val(),
            page: page
        };

        // Set CSRF
        data[$('.main .search-replies').attr('data-csrf')] = $('input[name="' + $('.main .search-replies').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_replies');

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

                    // Load replies
                    Main.load_replies(1);

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
     * Submit upload csv form
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $( document ).on( 'change', '#csvfile', function (e) {
        $('.upcsv').submit();
    }); 

    /*
     * Search for replies
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .replies-key', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main .cancel-replies-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main .cancel-replies-search').fadeIn('slow');

        }

        // Load replies
        Main.load_replies(1);

    });

    /*
     * Search for suggestions
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main #create-new-reply .chatbot-search-for-suggestions', function (e) {
        e.preventDefault();

        // Create an object with form data
        var data = {
            action: 'suggestions_groups',
            key: $(this).val()
        };

        // Set CSRF
        data[$('.main .chatbot-create-reply').attr('data-csrf')] = $('input[name="' + $('.main .chatbot-create-reply').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_suggestions_groups');

    });

    /*
     * Cancel the replies search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .cancel-replies-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main .replies-key').val('');

        // Hide button
        $('.main .cancel-replies-search').fadeOut('slow');

        // Load replies
        Main.load_replies(1);

    });

    /*
     * Get the suggestions groups
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main #create-new-reply .chatbot-select-suggestions-group', function (e) {
        e.preventDefault();

        // Create an object with form data
        var data = {
            action: 'suggestions_groups'
        };

        // Set CSRF
        data[$('.main .chatbot-create-reply').attr('data-csrf')] = $('input[name="' + $('.main .chatbot-create-reply').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'load_suggestions_groups');

    });

    /*
     * Change dropdown option
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */ 
    $( document ).on( 'click', '.main #create-new-reply .dropdown-menu a', function (e) {
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
     * @since   0.0.8.0
     */
    $(document).on('click', 'body .pagination li a', function (e) {
        e.preventDefault();

        // Verify which pagination it is based on data's type 
        var page = $(this).attr('data-page');

        // Display results
        switch ($(this).closest('ul').attr('data-type')) {

            case 'replies':

                // Load replies
                Main.load_replies(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

        // Unselect
        $('.main #all-replies-select').prop('checked', false);

    });

    /*
     * Detect all Replies selection
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */ 
    $( document ).on( 'click', '.main #all-replies-select', function (e) {

        setTimeout(function(){
            
            if ( $( '.main #all-replies-select' ).is(':checked') ) {

                $( '.main .replies-list input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.main .replies-list input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });

    /*
     * Delete replies
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */ 
    $( document ).on( 'click', 'main .delete-replies', function (e) {
        
        // Get all selected replies
        var replies = $('.main .replies-list input[type="checkbox"]');
        
        // Default selected value
        var selected = [];
        
        // List all replies
        for ( var d = 0; d < replies.length; d++ ) {

            // Verify if is checked
            if ( replies[d].checked ) {
                selected.push($(replies[d]).attr('data-id'));
            }
            
        }
        
        if ( selected.length < 1 ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_at_least_reply, 1500, 2000);
            return;
            
        }
        
        // Create an object with form data
        var data = {
            action: 'delete_replies',
            replies: Object.entries(selected)
        };

        // Set CSRF
        data[$('.main .chatbot-create-reply').attr('data-csrf')] = $('input[name="' + $('.main .chatbot-create-reply').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'delete_reply');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });

    /*
     * Import replies from CSV
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */ 
    $( document ).on( 'click', 'main .select-csv-file', function (e) {
        e.preventDefault();
        
        $('#csvfile').click();
        
    }); 
    
    /*
     * Export replies in a CSV
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */ 
    $( document ).on( 'click', 'main .download-csv-file', function (e) {
        e.preventDefault();
        
        // Create an object with form data
        var data = {
            action: 'check_for_replies'
        };

        // Set CSRF
        data[$('.main .chatbot-create-reply').attr('data-csrf')] = $('input[name="' + $('.main .chatbot-create-reply').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'check_for_replies');
        
    }); 

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display the suggestions groups
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.load_suggestions_groups = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Groups var
            var groups = '';
    
            // List 10 groups
            for (var c = 0; c < data.groups.length; c++) {
    
                groups += '<li class="list-group-item">'
                    + '<a href="#" data-id="' + data.groups[c].group_id + '">'
                        + data.groups[c].group_name
                    + '</a>'
                + '</li>';
    
            } 

            // Display groups
            $('.main #create-new-reply .chatbot-suggestions-list').html(groups);

        } else {

            // No found groups message
            var message = '<li class="no-results">'
                    + data.message
                + '</li>';

            // Display no groups message
            $('.main #create-new-reply .chatbot-suggestions-list').html(message);

        }

    };

    /*
     * Display the reply saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.save_reply = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display success alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Load replies
            Main.load_replies(1);

            // Reset the form
            $( '.main #create-new-reply .chatbot-create-reply' )[0].reset();

            // Unselect
            $( '.main #all-replies-select' ).prop('checked', false);

        } else {

            // Display error alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }

    };

    /*
     * Display the reply deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.delete_reply = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display success alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Load replies
            Main.load_replies(1);

            // Unselect
            $( '.main #all-replies-select' ).prop('checked', false);

        } else {

            // Display error alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }

    };

    /*
     * Display the replies
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.load_replies = function (status, data) {

        // Hide pagination
        $('.main .chatbot-list .pagination').hide();

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main .chatbot-list .pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main .chatbot-list', data.total);

            // Replies var
            var replies = '';

            // List 10 replies
            for (var c = 0; c < data.replies.length; c++) {

                replies += '<li>'
                            + '<div class="row">'
                                + '<div class="col-12">'
                                    + '<a href="' + url + 'user/app/chatbot?p=replies&reply=' + data.replies[c].reply_id + '" class="show-group">'
                                        + '<div class="checkbox-option-select">'
                                            + '<input id="chatbot-reply-' + data.replies[c].reply_id + '" name="chatbot-reply-' + data.replies[c].reply_id + '" type="checkbox" data-id="' + data.replies[c].reply_id + '">'
                                            + '<label for="chatbot-reply-' + data.replies[c].reply_id + '"></label>'
                                        + '</div>'
                                        + data.replies[c].body
                                    + '</a>'
                                + '</div>'
                            + '</div>'
                        + '</li>';

            }

            // Display replies
            $('.main .chatbot-list .replies-list').html(replies);

        } else {

            // No found replies message
            var message = '<li class="found-results">'
                    + data.message
                + '</li>';

            // Display no replies message
            $('.main .chatbot-list .replies-list').html(message);

        }

    };

    /*
     * Display the reply verification response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.check_for_replies = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Download
            document.location.href = url + 'user/app-ajax/chatbot?action=export_csv';

        } else {

            // Display error alert
            Main.popup_fon('sube', data.message, 1500, 2000);            

        }

    };

    /*******************************
    FORMS
    ********************************/

    /*
     * Save Reply
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('submit', '.main #create-new-reply .chatbot-create-reply', function (e) {
        e.preventDefault();

        // Get keywords
        var keywords = $(this).find('.reply-keywords').val();

        // Create an object with form data
        var data = {
            action: 'save_reply',
            keywords: keywords
        };

        if ( $(this).find('button[data-target="#menu-text-reply"]').hasClass('collapsed') ) {

            if ( !$(this).find('.chatbot-select-suggestions-group').attr('data-id') ) {

                // Display error alert
                Main.popup_fon('sube', words.please_select_suggestion_group, 1500, 2000);
                return;

            }

            // Set group's ID
            data['group'] = $(this).find('.chatbot-select-suggestions-group').attr('data-id');

            // Set the response's type
            data['response_type'] = 2;

        } else {

            if ( $('.reply-text-message').val().trim().length < 4 ) {

                // Display error alert
                Main.popup_fon('sube', words.please_enter_text_reply, 1500, 2000);
                return;

            }

            // Set message
            data['message'] = $(this).find('.reply-text-message').val();

            // Set the response's type
            data['response_type'] = 1;

        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'save_reply');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Upload CSV
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('submit', '.main .upcsv', function (e) {
        e.preventDefault();

        // Get the CSV file
        var files = $('#csvfile')[0].files;
        
        // Verify if a CSV file exists
        if ( typeof files[0] !== 'undefined' ) {
            
            // Upload CSV
            Main.saveCsvFile(files[0]);
            
        }

    });

    // Load replies
    Main.load_replies(1);

});