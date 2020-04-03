/*
 * Main javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url =  $('meta[name=url]').attr('content');
    
    /*******************************
    METHODS
    ********************************/
   
    /*
     * Load media files by page
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.7.6
     */
    Main.loadMedias = function (page) {
        
        var category_id = 0;
        
        if ( $('.main .storage-category-selected').attr('data-id') ) {
            
            // Set category
            category_id = parseInt($('.main .storage-category-selected').attr('data-id'));

            // Display show categories button
             $('.main .show-all-categories').fadeIn('slow');
            
        }
        
        var data = {
            action: 'get_media',
            page: page,
            category_id: category_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/ajax/media', 'GET', data, 'get_media_files');
        
    };
    
    /*
     * Load media's categories
     * 
     * @since   0.0.7.6
     */
    Main.loadCategories = function () {
        
        var data = {
            action: 'get_categories'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/storage', 'GET', data, 'get_categories');
        
    };    
   
    /*******************************
    ACTIONS
    ********************************/
   
    /*
     * Detect media pagination click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .storage-page .next-button, .main .storage-page .back-button', function (e) {
        e.preventDefault();
        
        // Get page number
        var page = $(this).attr('data-page');
        
        // Load media files by page
        Main.loadMedias(page);
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Detect select all media select
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .storage-page #storage-select-all-medias', function () {

        setTimeout(function(){
            
            if ( $( '.main .storage-page #storage-select-all-medias' ).is(':checked') ) {

                $( '.main .storage-all-media-files .storage-single-media input[type="checkbox"]' ).prop('checked', true);

            } else {

                $( '.main .storage-all-media-files .storage-single-media input[type="checkbox"]' ).prop('checked', false);

            }
        
        },500);
        
    }); 
    
    /*
     * Detect stream actions click
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .storage-page .stream-media-actions a', function (e) {
        e.preventDefault();
        
        // Get action's type
        var type = parseInt($(this).attr('data-id'));
        
        // Get all selected medias
        var medias = $('.main .storage-all-media-files .storage-single-media input[type="checkbox"]');
        
        var selected = [];
        
        // List all medias
        for ( var d = 0; d < medias.length; d++ ) {
            
            if ( medias[d].checked ) {
                selected.push($(medias[d]).attr('data-id'));
            }
            
        }
        
        if ( selected.length < 1 ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_a_media, 1500, 2000);
            return;
            
        }

        if ( type === 1 ) {
            
            for ( var s = 0; s < selected.length; s++ ) {
                
                var data = {
                    action: 'delete_media',
                    media_id: selected[s],
                    returns: 1
                };

                // Make ajax call
                Main.ajax_call(url + 'user/ajax/media', 'GET', data, 'display_media_deletion_response');
                
                if ( selected.length === ( s + 1 ) ) {
                    
                    // Load all media files
                    Main.loadMedias(1);
                    
                }
                
            }
            
        } else if ( type === 2 ) {
            
            // Show popup
            $('#storage-add-to-category').modal('show');
            
        } else if ( type === 3 ) {
            
            // Get the selected category
            var category_id = parseInt($('.main .storage-category-selected').attr('data-id'));
            
            // Prepare data to send
            var data = {
                action: 'remove_from_category',
                medias: selected,
                category_id: category_id
            };
            
            // Set CSRF
            data[$('.main .storage-create-new-category').attr('data-csrf')] = $('input[name="' + $('.main .storage-create-new-category').attr('data-csrf') + '"]').val();

            // Make ajax call
            Main.ajax_call(url + 'user/app-ajax/storage', 'POST', data, 'display_remove_from_category_response');
            
            // Display loading animation
            $('.page-loading').fadeIn('slow');
            
        }
        
    });
    
    /*
     * Detect categories click in the gallery
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .stream-media-categories a', function (e) {
        e.preventDefault();
        
        // Get action's type
        var type = parseInt($(this).attr('data-id'));
        

        if ( type < 1 ) {
            
            // Show popup
            $('#storage-create-new-category').modal('show');
            
        } else {
            
            // Show category
            $('.main .storage-category-selected').closest('#file-manager').addClass('displayed-category');
            $('.main .storage-category-selected p').html($(this).html());
            $('.main .storage-category-selected').attr('data-id', type);
            
            // Load all media's files
            Main.loadMedias(1);
            
            // Display loading animation
            $('.page-loading').fadeIn('slow');
            
        }
        
    }); 

    /*
     * Detect categories click in the upload files modal
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.1
     */ 
    $( document ).on( 'click', '.main .upload-media-categories a', function (e) {
        e.preventDefault();
        
        // Get the category's id
        let category_id = parseInt($(this).attr('data-id'));
        
        // Verify if the category id is positive
        if ( category_id ) {

            // Get the category's name
            let category = $(this).html();

            // Set the category name
            $(this).closest('.dropdown').find('.btn-secondary').html(category);

            // Set the category's id
            $(this).closest('.dropdown').find('.btn-secondary').attr('data-id', category_id);
            $('.upim #category').val(category_id);

        }
        
    }); 
    
    /*
     * Delete the category
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .stream-delete-category', function (e) {
        e.preventDefault();
        
        // Get category's id
        var category_id = parseInt($('.main .storage-category-selected').attr('data-id'));
        
        var data = {
            action: 'delete_media_category',
            category_id: category_id
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/storage', 'GET', data, 'delete_media_category');
        
    });    

    /*
     * Show all medias
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */ 
    $( document ).on( 'click', '.main .show-all-categories', function (e) {
        e.preventDefault();

        // Hide category
        $('.main .storage-category-selected').closest('#file-manager').removeClass('displayed-category');
        $('.main .storage-category-selected p').html($(this).html());
        $('.main .storage-category-selected').removeAttr('data-id');

        // Hide the show categories button
        $('.main .show-all-categories').fadeOut('slow');
        
        // Load media files by page
        Main.loadMedias(1);
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
   
    /*******************************
    RESPONSES
    ********************************/
   
    /*
     * Display media deletion response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.display_media_deletion_response = function ( status, data ) { 
        
        if ( status === 'success' ) {

            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all media's files
            Main.loadMedias(1);

        } else {

            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
        // Unselect all checkbox
        $( '.main input[type="checkbox"]' ).prop('checked', false);
        
    };
    
    /*
     * Display media category remove response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.display_remove_from_category_response = function ( status, data ) { 
        
        if ( status === 'success' ) {

            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Load all media's files
            Main.loadMedias(1);
            
            $( '.main input[type="checkbox"]' ).prop('checked', false);

        } else {

            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
    };    
   
    /*
     * Display media files
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.get_media_files = function ( status, data ) {
        
        // Unselect all checkbox
        $( '.main .storage-page .storage-select-all-medias' ).prop('checked', false);

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            if ( data.page < 2 ) {

                $('.main .storage-page .back-button').addClass('btn-disabled');

            } else {

                $('.main .storage-page .back-button').removeClass('btn-disabled');
                $('.main .storage-page .back-button').attr('data-page', (parseInt(data.page) - 1));

            }

            if ( (parseInt(data.page) * 16 ) < data.total ) {

                $('.main .storage-page .next-button').removeClass('btn-disabled');
                $('.main .storage-page .next-button').attr('data-page', (parseInt(data.page) + 1));

            } else {

                $('.main .storage-page .next-button').addClass('btn-disabled');

            }
            
            var all_medias = '';
            
            for ( var m = 0; m < data.medias.length; m++ ) {
                
                var edit = '';
                
                all_medias += '<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">'
                                + '<div class="col-12 storage-single-media">'
                                    + '<div class="row">'
                                        + '<div class="col-10">'
                                            + '<h3>'
                                                + '<div class="checkbox-option-select">'
                                                    + '<input id="storage-single-media-checkbox-' + data.medias[m].media_id + '" name="storage-single-media-checkbox-' + data.medias[m].media_id + '" type="checkbox" data-id="' + data.medias[m].media_id + '">'
                                                    + '<label for="storage-single-media-checkbox-' + data.medias[m].media_id + '"></label>'
                                                + '</div>'
                                            + '</h3>'
                                        + '</div>'
                                        + '<div class="col-2 text-right">'                          
                                        + '</div>'
                                    + '</div>'
                                    + '<div class="row">'
                                        + '<div class="col-12">'
                                            + '<img src="' + data.medias[m].cover + '">'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>';
                
            }
            
            $('.main .storage-all-media-files').html(all_medias);
            
        } else {
            
            var response = '<div class="col-xl-12">'
                                + words.no_files_found
                            + '</div>';
            
            $('.main .storage-all-media-files').html(response);
            
            $('.main .storage-page .back-button').addClass('btn-disabled');
            $('.main .storage-page .next-button').addClass('btn-disabled');
            
        }

    };  
    
    /*
     * Display category's creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.storage_create_new_category = function ( status, data ) { 
        
        if ( status === 'success' ) {

            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Reset the form
            $('.main .storage-create-new-category')[0].reset();
            
            // Load all media's categories
            Main.loadCategories();

        } else {

            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
    };
    
    /*
     * Display all categories response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.get_categories = function ( status, data ) { 
        
        // Default categories list var
        var categories_list = '';

        // Select categories var
        var select_categories = '<option value="0">'
                                    + words.select_category
                                + '</option>';        

        // Verify if categories exists
        if ( status === 'success' ) {
            
            // List all categories
            for ( var e = 0; e < data.categories.length; e++ ) {
                
                // Add category to the list
                categories_list += '<a class="dropdown-item" href="#" data-id="' + data.categories[e].list_id + '">'
                                        + '<i class="far fa-arrow-alt-circle-right"></i>'
                                        + data.categories[e].name
                                    + '</a>';
                
                // Select category
                select_categories += '<option value="' + data.categories[e].list_id + '">'
                                        + data.categories[e].name
                                    + '</option>';
                
            }

        }

        // Display the categories in the upload modal list
        $('.main .upload-media-categories').html(categories_list);
        
        // Add Create Category link
        categories_list += '<div class="dropdown-divider"></div>'
                            + '<a class="dropdown-item" href="#" data-id="0">'
                                + '<i class="far fa-file-alt"></i>'
                                + words.create_category
                            + '</a>';

        
        // Display the categories in the main list
        $('.main .stream-media-categories').html(categories_list);

        // Display the select caegories text 
        $('.main .storage-select-category').html(select_categories);
        
    };
    
    /*
     * Display add media to category response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.storage_add_media_to_category = function ( status, data ) { 
        
        if ( status === 'success' ) {

            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Hide modal
            $('#storage-add-to-category').modal('hide'); 

        } else {

            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
        $( '.main input[type="checkbox"]' ).prop('checked', false);
        
    };
    
    /*
     * Display delete media category response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.delete_media_category = function ( status, data ) { 
        
        if ( status === 'success' ) {

            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Hide category
            $('.main .storage-category-selected').closest('#file-manager').removeClass('displayed-category');
            $('.main .storage-category-selected p').html($(this).html());
            $('.main .storage-category-selected').removeAttr('data-id');
            
            // Load all media's categories
            Main.loadCategories();
            
            // Load all media's files
            Main.loadMedias(1);

            // Hide the show categories button
            $('.main .show-all-categories').fadeOut('slow');

        } else {

            Main.popup_fon('sube', data.message, 1500, 2000);

        }
        
        // Unselect all checkbox
        $( '.main input[type="checkbox"]' ).prop('checked', false);
        
    };
    
    /*
     * Display image saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.download_images_from_urls = function ( status, data ) {

        // Verify if the success response exists
        if ( status === 'success' ) {
            
            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);
            
            // Reset form
            $('.main .download-images-from-url')[0].reset();
            
            // Set the user storage
            $( '.user-total-storage' ).text( data.user_storage );
            
            // Load all media's files
            Main.loadMedias(1);
            
        } else {
            
            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);
            
        }
        
    };
   
    /*******************************
    FORMS
    ********************************/
    
    /*
     * Create a new category
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('submit', '.main .storage-create-new-category', function (e) {
        e.preventDefault();
        
        // Get category's name
        var category_name = $( this ).find( '.storage-category-name' ).val();
            
        // Create an object with form data
        var data = {
            action: 'storage_create_new_category',
            category_name: category_name
        };

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/storage', 'POST', data, 'storage_create_new_category');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*
     * Add medias to a category
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('submit', '.main .storage-add-to-category', function (e) {
        e.preventDefault();
        
        // Get the selected category
        var category_id = parseInt($('.main .storage-add-to-category').find('.storage-select-category').val());

        if ( !category_id ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_a_category, 1500, 2000);
            return;
            
        }        
        
        // Get all selected medias
        var medias = $('.main .storage-all-media-files .storage-single-media input[type="checkbox"]');
        
        var selected = [];
        
        // List all medias
        for ( var d = 0; d < medias.length; d++ ) {
            
            if ( medias[d].checked ) {
                selected.push($(medias[d]).attr('data-id'));
            }
            
        }
        
        if ( selected.length < 1 ) {
            
            // Display alert
            Main.popup_fon('sube', words.please_select_a_media, 1500, 2000);
            return;
            
        }
            
        // Create an object with form data
        var data = {
            action: 'storage_add_media_to_category',
            category_id: category_id,
            medias: selected
        };

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/storage', 'POST', data, 'storage_add_media_to_category');

        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    }); 
    
    /*
     * Download images from urls
     * 
     * @param object e with global object
     * 
     * @since   0.0.7.6
     */
    $(document).on('submit', '.main .download-images-from-url', function (e) {
        e.preventDefault();
        
        // Get the image's urls
        var imported_urls = $('.main .imported-urls').val();
        
        // Create an object with form data
        var data = {
            action: 'download_images_from_urls',
            imported_urls: imported_urls
        };
        
        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/storage', 'POST', data, 'download_images_from_urls');
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
    /*******************************
    DEPENDENCIES
    ********************************/
   
    // Load all media's files
    Main.loadMedias(1);
    
    // Load all media's categories
    Main.loadCategories();
    
});