/*
 * Pixabay javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';
    
    /*
     * Get the website's url
     */
    var url =  $('meta[name=url]').attr('content');
    
    /*******************************
    ACTIONS
    ********************************/
   
    /*
     * Publish a comment on online post
     */
    $(document).on('click', '.pixabay-page .pixabay-download-photo', function (e) {
        e.preventDefault();
        
        var data = {
            link: $(this).attr('data-link'),
            bytes: $(this).attr('data-size'),
            cover: $(this).attr('data-cover'),
            name: $(this).attr('data-name')
        };
        
        // Send photo's data
        window.opener.pixabay_save_photo(data);
        
        // Close window
        window.close();
        
    });   
   
    /*
     * Publish a comment on online post
     */
    $(document).on('submit', '.search-pixabay-photos', function (e) {
        e.preventDefault();
        
        // Get key
        var key = $(this).find('.search-input').val();
        
        var API_KEY = $('.main .pixabay-page').attr('data-pixabay');

        var URL = '//pixabay.com/api/?key=' + API_KEY + '&q=' + encodeURIComponent(key);

        $.getJSON(URL, function (data) {
            
            if (parseInt(data.totalHits) > 0) {

                var images = '';
                
                $.each(data.hits, function (i, hit) {
                    
                    var filename = hit.previewURL.substring(hit.previewURL.lastIndexOf('/')+1);
                    
                    images += '<div class="col-xl-4 col-md-4 col-sm-4 col-xs-6">'
                                 + '<div class="col-xl-12 clean">'
                                    + '<img src="' + hit.webformatURL + '" class="img-responsive">'
                                    + '<a href="#" data-link="' + hit.largeImageURL + '" data-size="' + hit.imageSize + '" data-cover="' + hit.webformatURL + '" data-name="' + filename + '" class="pixabay-download-photo"><i class="icon-cloud-download"></i></a>'
                                + '</div>'
                            + '</div>';
                    
                });
                
                $('.pixabay-page .row').html(images);
                
                
            } else {
                
                $('.pixabay-page .row').html('<div class="col-xl-12">'
                                                + '<p class="no-results-found">No results found.</p>'
                                            + '</div>');
                
            }
            $('.page-loading').fadeOut();
        });
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');
        
    });
    
});