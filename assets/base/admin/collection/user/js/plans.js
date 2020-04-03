/*
 * Plans javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    // Get home page url
    var url = $('.navbar-brand').attr('href');
    
    /*******************************
    METHODS
    ********************************/

    /*
     * Load plans
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.9
     */    
    Main.user_load_plans = function (page) {

        // Verify if is the plans page
        if ( $('.user-page .user-search-for-plans').length < 1 ) {
            return;
        }

        var data = {
            action: 'load_all_plans',
            page: page,
            key: $('.user-page .user-search-for-plans').val()
        };
        
        // Set the CSRF field
        data[$('.user-page .csrf-sanitize').attr('name')] = $('.user-page .csrf-sanitize').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/user', 'POST', data, 'load_all_plans');
        
    };

    /*
     * Display plans pagination
     */
    Main.show_plans_pagination = function( id, total ) {
        
        // Empty pagination
        $( id + ' .pagination' ).empty();
        
        // Verify if page is not 1
        if ( parseInt(Main.pagination.page) > 1 ) {
            
            var bac = parseInt(Main.pagination.page) - 1;
            var pages = '<li><a href="#" data-page="' + bac + '">' + translation.mm128 + '</a></li>';
            
        } else {
            
            var pages = '<li class="pagehide"><a href="#">' + translation.mm128 + '</a></li>';
            
        }
        
        // Count pages
        var tot = parseInt(total) / 20;
        tot = Math.ceil(tot) + 1;
        
        // Calculate start page
        var from = (parseInt(Main.pagination.page) > 2) ? parseInt(Main.pagination.page) - 2 : 1;
        
        // List all pages
        for ( var p = from; p < parseInt(tot); p++ ) {
            
            // Verify if p is equal to current page
            if ( p === parseInt(Main.pagination.page) ) {
                
                // Display current page
                pages += '<li class="active"><a data-page="' + p + '">' + p + '</a></li>';
                
            } else if ( (p < parseInt(Main.pagination.page) + 3) && (p > parseInt(Main.pagination.page) - 3) ) {
                
                // Display page number
                pages += '<li><a href="#" data-page="' + p + '">' + p + '</a></li>';
                
            } else if ( (p < 6) && (Math.round(tot) > 5) && ((parseInt(Main.pagination.page) === 1) || (parseInt(Main.pagination.page) === 2)) ) {
                
                // Display page number
                pages += '<li><a href="#" data-page="' + p + '">' + p + '</a></li>';
                
            } else {
                
                break;
                
            }
            
        }
        
        // Verify if current page is 1
        if (p === 1) {
            
            // Display current page
            pages += '<li class="active"><a data-page="' + p + '">' + p + '</a></li>';
            
        }
        
        // Set the next page
        var next = parseInt( Main.pagination.page );
        next++;
        
        // Verify if next page should be displayed
        if (next < Math.round(tot)) {
            
            $( id + ' .pagination' ).html( pages + '<li><a href="#" data-page="' + next + '">' + translation.mm129 + '</a></li>' );
            
        } else {
            
            $( id + ' .pagination' ).html( pages + '<li class="pagehide"><a href="#">' + translation.mm129 + '</a></li>' );
            
        }
        
    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Search plans
     * 
     * @since   0.0.7.9
     */
    $(document).on('keyup', '.user-page .user-search-for-plans', function () {
        
        // Load all plans by key
        Main.user_load_plans(1);
        
    });

    /*
     * Display save changes button
     * 
     * @since   0.0.7.9
     */
    $(document).on('keyup', 'body .plan-input', function () {

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
    $(document).on('change', 'body .plan-option-checkbox', function (e) {
        
        // Display save button
        $('.settings-save-changes').fadeIn('slow');
        
    }); 

    /*
     * Detect all plans selection
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.9
     */ 
    $( document ).on( 'click', '.user-page #user-plans-select-all', function (e) {
        
        setTimeout(function(){
            
            if ( $( '.user-page #user-plans-select-all' ).is(':checked') ) {

                $( '.user-page .list-contents li input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.user-page .list-contents li input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    });

    /*
     * Delete plans
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.9
     */ 
    $( document ).on( 'click', '.user-page .delete-plans', function (e) {
        e.preventDefault();
    
        // Define the plans ids variable
        var plans_ids = [];
        
        // Get selected plans ids
        $('.user-page .list-contents li input[type="checkbox"]:checkbox:checked').each(function () {
            plans_ids.push($(this).attr('data-id'));
        });

        // Create an object with form data
        var data = {
            action: 'delete_plans',
            plans_ids: plans_ids
        };
        
        // Set the CSRF field
        data[$('.user-page .csrf-sanitize').attr('name')] = $('.user-page .csrf-sanitize').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/user', 'POST', data, 'delete_plans_response');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });

    /*
     * Displays pagination by page click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.9
     */    
    $( document ).on( 'click', 'body .pagination li a', function (e) {
        e.preventDefault();
        
        // Get the page number
        var page = $(this).attr('data-page');

        // Load all plans by key
        Main.user_load_plans(page);
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
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
        var inputs = $('.user-page .update-plan .plan-input').length;
        
        var all_inputs = [];
        
        // List all inputs
        for ( var i = 0; i < inputs; i++ ) {
            
            all_inputs[$('.user-page .update-plan .plan-input').eq(i).attr('id')] = $('.user-page .update-plan .plan-input').eq(i).val();
            
        }
        
        // Get all options
        var options = $('.user-page .update-plan .plan-option-checkbox').length;
        
        var all_options = [];
        
        // List all options
        for ( var o = 0; o < options; o++ ) {
            
            if ( $('.user-page .update-plan .plan-option-checkbox').eq(o).is(':checked') ) {
                all_options[$('.user-page .update-plan .plan-option-checkbox').eq(o).attr('id')] = 1;
            } else {
                all_options[$('.user-page .update-plan .plan-option-checkbox').eq(o).attr('id')] = 0;
            }
            
        }        

        // Prepare data to save
        var data = {
            action: 'update_a_plan',
            plan_id: $('.user-page .update-plan').attr('data-plan-id'),
            all_inputs: Object.entries(all_inputs),
            all_options: Object.entries(all_options)
        };
        
        data[$('.user-page .update-plan').attr('data-csrf')] = $('input[name="' + $('.user-page .update-plan').attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/user', 'POST', data, 'update_a_plan');

        // Show loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
   
    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display plans response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.9
     */
    Main.methods.load_all_plans = function ( status, data ) {

        // Uncheck all selection plans
        $( '.user-page #user-plans-select-all' ).prop('checked', false)

        // Verify if the success response exists
        if ( status === 'success' ) {

            // Generate pagination
            Main.pagination.page = data.page;
            Main.show_plans_pagination('.user-page', data.total);

            // All plans
            var all_plans = '';
            
            // List all plans
            for ( var c = 0; c < data.plans.length; c++ ) {

                // Set plan
                all_plans += '<li class="contents-single" data-id="' + data.plans[c].plan_id + '">'
                    + '<div class="row">'
                        + '<div class="col-lg-10 col-md-8 col-xs-8">'
                            + '<div class="checkbox-option-select">'
                                + '<input id="user-plans-single-' + data.plans[c].plan_id + '" name="user-plans-single-' + data.plans[c].plan_id + '" data-id="' + data.plans[c].plan_id + '" type="checkbox">'
                                + '<label for="user-plans-single-' + data.plans[c].plan_id + '"></label>'
                            + '</div>'
                            + '<a href="' + url + 'admin/user?p=plans&plan_id=' + data.plans[c].plan_id + '">'
                                + data.plans[c].plan_name
                            + '</a>'
                        + '</div>'
                        + '<div class="col-lg-2 col-md-2 col-xs-2">'
                        + '</div>'
                    + '</div>'
                + '</li>';

            }

            // Get the page
            var page = ( (data.page - 1) < 1)?1:((data.page - 1) * 20);

            // Get results to
            var to = ((parseInt(page) * 20) < data.total)?(parseInt(data.page) * 20):data.total;

            // Display plans
            $('.user-page .list-contents').html(all_plans);

            // Display start listing
            $('.user-page .pagination-from').text(page);  
            
            // Display end listing
            $('.user-page .pagination-to').text(to);  

            // Display total items
            $('.user-page .pagination-total').text(data.total);

            // Show Pagination
            $('.user-page .pagination-area').show();  
            
        } else {

            // Hide Pagination
            $('.user-page .pagination-area').hide();  
            
            // Set no data found message
            var no_data = '<li>'
                                + data.message
                            + '</li>';

            // Display plans
            $('.user-page .list-contents').html(no_data);   
            
        }

    };
   
    /*
     * Display new plan creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.9
     */
    Main.methods.create_new_plan = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            setTimeout(function(){
                document.location.href = url + 'admin/user?p=plans&plan_id=' + data.plan_id;
            }, 3000);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    }; 

    /*
     * Display plans deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.9
     */
    Main.methods.delete_plans_response = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all plans by key
            Main.user_load_plans(1);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }

    };

    /*
     * Display the plan update response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.4
     */
    Main.methods.update_a_plan = function ( status, data ) {
        
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
   
    /*
     * Create a new plan
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.9
     */
    $('body .create-plan').submit(function (e) {
        e.preventDefault();
        
        var data = {
            action: 'create_new_plan',
            plan_name: $(this).find('.plan_name').val()
        };
        
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'admin/ajax/plans', 'POST', data, 'create_new_plan');
        
    });
    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Hide loading animation
    $('.page-loading').fadeOut('slow');

    // Load all plans
    Main.user_load_plans(1);
 
});