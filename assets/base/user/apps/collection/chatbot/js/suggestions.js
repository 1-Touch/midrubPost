/*
 * Chatbot Suggestions javascript file
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
     * Gets the suggestions data from database by group's ID and user's ID
     * 
     * @since   0.0.8.0
     */
    Main.load_suggestions = function () {

        // Prepare data to send
        var data = {
            action: 'load_suggestions',
            group_id: $('.main .chatbot-page-suggestions').attr('data-group')
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'display_suggestions');

    };

    /*
     * Prepare the suggestion's header
     * 
     * @param array header contains the suggestion's header
     * @param string type contains the sugestion's type
     * 
     * @since   0.0.8.0
     * 
     * @return string with template name
     */
    Main.generate_header_content = function (header, type) {

        // Return template's name based on type
        switch (type) {

            case 'generic-template':

                return words.generic_template;

            case 'media-template':

                return words.media_template;

            case 'button-template':

                return words.media_template;
        }

    };

    /*
     * Prepare the suggestion's body
     * 
     * @param array header contains the suggestion's header
     * @param array body contains the suggestion's body
     * @param string type contains the sugestion's type
     * 
     * @since   0.0.8.0
     * 
     * @return string with template name
     */
    Main.generate_body_content = function (header, body, type) {

        // Generate content based on type
        switch (type) {

            case 'generic-template':

                // Default image value
                var image = '';

                // Verify if Image exists
                if (typeof header[0].header.cover_id !== 'undefined') {

                    image = '<div class="row">'
                        + '<div class="col-12 generic-template-styles">'
                            + '<img src="' + header[0].header.cover_src + '">'
                        + '</div>'
                    + '</div>';

                }

                // Default title value
                var title = '';

                // Verify if title exists
                if (typeof header[0].header.title !== 'undefined') {

                    title = '<div class="row">'
                        + '<div class="col-12 generic-template-styles">'
                            + '<h3>'
                                + header[0].header.title
                            + '</h3>'
                        + '</div>'
                    + '</div>';

                }

                // Default subtitle value
                var subtitle = '';

                // Verify if subtitle exists
                if (typeof header[0].header.subtitle !== 'undefined') {

                    subtitle = '<div class="row">'
                        + '<div class="col-12 generic-template-styles">'
                            + '<h5>'
                                + header[0].header.subtitle
                            + '</h5>'
                        + '</div>'
                    + '</div>';

                }

                // Default url value
                var url = '';

                // Verify if url exists
                if (typeof header[0].header.url !== 'undefined') {

                    var url = header[0].header.url;
                    var hostname = (new URL(url)).hostname;

                    url = '<div class="row">'
                        + '<div class="col-12 generic-template-styles">'
                            + '<a href="' + header[0].header.url + '">'
                                + hostname
                            + '</a>'
                        + '</div>'
                    + '</div>';

                }
                
                var option = '';

                if ( body[0].type === 'suggestions-group' ) {

                    option = '<p class="suggestion-suggestions-option">'
                                + '<i class="lni-comment-reply"></i>'
                                + body[0].title
                            + '</p>';

                } else {

                    option = '<p class="generic-template-content">'
                                + '<a href="' + body[0].link + '" target="_blank">'
                                    + body[0].title
                                + '</a>'
                            + '</p>';

                }

                return image
                    + title
                    + subtitle
                    + url
                    + '<div class="row">'
                        + '<div class="col-12">'
                            + option
                        + '</div>'
                    + '</div>';

            case 'media-template':

                // Default image value
                var image = '';
    
                // Verify if Image exists
                if (typeof header[0].header.cover_id !== 'undefined') {
    
                    image = '<div class="row">'
                            + '<div class="col-12 media-template-styles">'
                                + '<img src="' + header[0].header.cover_src + '">'
                            + '</div>'
                        + '</div>';
    
                }
                    
                var option = '<p class="media-template-content">'
                                + '<a href="' + body[0].link + '" target="_blank">'
                                    + body[0].title
                                + '</a>'
                            + '</p>';
    
                return image
                    + '<div class="row">'
                        + '<div class="col-12">'
                            + option
                        + '</div>'
                    + '</div>';

            case 'button-template':

                // Default title value
                var title = '';

                // Verify if title exists
                if (typeof header[0].header.title !== 'undefined') {

                    title = '<div class="row">'
                        + '<div class="col-12 generic-template-styles">'
                            + '<h3>'
                                + header[0].header.title
                            + '</h3>'
                        + '</div>'
                    + '</div>';

                }
                    
                var option = '';

                if (body[0].type === 'suggestions-group') {

                    option = '<p class="suggestion-suggestions-option">'
                                + '<i class="lni-comment-reply"></i>'
                                + body[0].title
                            + '</p>';

                } else {

                    option = '<p class="button-template-content">'
                                + '<a href="' + body[0].link + '" target="_blank">'
                                    + body[0].title
                                + '</a>'
                            + '</p>';

                }
    
                return  title
                    + '<div class="row">'
                        + '<div class="col-12">'
                            + option
                        + '</div>'
                    + '</div>';                    

        }

    };

    /*
     * Extract the main suggestions level from an array
     * 
     * @param array suggestions contains a main level suggestion with it's childrens
     * @param integer position helps to add correct arrows
     * @param integer total contains the number of main level suggestions
     * 
     * @since   0.0.8.0
     */
    Main.get_main_suggestion = function (suggestions, position, total) {

        // Columns variable
        var columns = 0;

        // Before_toast variable
        var before_toast = '';

        // After_toast variable
        var after_toast = '';

        // Verify if total isn's 4
        switch (total) {

            case 1:

                // Set new columns
                columns = 12;

                // Set before toast lines
                before_toast = '<div class="line-main-left-bottom line-main-left-bottom-first"></div>';

                // Verify if suggestions exists
                if (typeof suggestions.suggestions !== 'undefined') {

                    after_toast = '<div class="line-second-left-bottom"></div>'
                        + '<div class="line-second-left-top-bottom"></div>';

                }

                break;

            case 2:

                // Set new columns
                columns = 6;

                // Verify which position is
                if (position < 1) {

                    // Set before toast lines
                    before_toast = '<div class="line-main-left-bottom line-main-left-bottom-first"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-left-bottom"></div>'
                            + '<div class="line-second-left-top-bottom"></div>';

                    }

                } else {

                    // Set before toast lines
                    before_toast = '<div class="line-main-right-left-bottom"></div>'
                        + '<div class="line-main-right-bottom line-main-right-bottom-last"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-right-bottom"></div>'
                            + '<div class="line-second-right-top-bottom line-main-right-bottom-first"></div>';

                    }

                }

                break;

            case 3:

                // Set new columns
                columns = 4;

                // Verify which position is
                if (position < 1) {

                    // Set before toast lines
                    before_toast = '<div class="line-main-first-4-left"></div>'
                        + '<div class="line-main-first-4-top"></div>'
                        + '<div class="line-main-left-bottom line-main-left-bottom-first"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-left-bottom"></div>'
                            + '<div class="line-second-left-top-bottom"></div>';

                    }

                } else if (position < 2) {

                    // Set before toast lines
                    before_toast = '<div class="line-main-top-bottom"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-left-bottom"></div>'
                            + '<div class="line-second-left-top-bottom"></div>';

                    }

                } else {

                    // Set before toast lines
                    before_toast = '<div class="line-main-right-left-bottom"></div>'
                        + '<div class="line-main-right-bottom"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-right-bottom"></div>'
                            + '<div class="line-second-right-top-bottom"></div>';

                    }

                }

                break;

            case 4:

                // Set new columns
                columns = 3;

                // Verify which position is
                if (position < 1) {

                    // Set before toast lines
                    before_toast = '<div class="line-main-first-3-left"></div>'
                        + '<div class="line-main-first-3-top"></div>'
                        + '<div class="line-main-left-bottom line-main-left-bottom-first"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-left-bottom"></div>'
                            + '<div class="line-second-left-top-bottom"></div>';

                    }

                } else if (position < 2) {

                    // Set before toast lines
                    before_toast = '<div class="line-main-left-bottom"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-left-bottom"></div>'
                            + '<div class="line-second-left-top-bottom"></div>';

                    }

                } else if (position < 3) {

                    // Set before toast lines
                    before_toast = '<div class="line-main-right-left-bottom"></div>'
                        + '<div class="line-main-right-bottom"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-right-bottom"></div>'
                            + '<div class="line-second-right-top-bottom"></div>';

                    }

                } else {

                    // Set before toast lines
                    before_toast = '<div class="line-main-fourth-3-right"></div>'
                        + '<div class="line-main-fourth-3-top"></div>'
                        + '<div class="line-main-right-bottom line-main-right-bottom-last"></div>';

                    // Verify if suggestions exists
                    if (typeof suggestions.suggestions !== 'undefined') {

                        after_toast = '<div class="line-second-right-bottom"></div>'
                            + '<div class="line-second-right-top-bottom line-main-right-bottom-first"></div>';

                    }

                }

                break;

        }

        // Get the column's slug
        var column_slug = Main.column_slug(position);

        // Suggestions button
        var suggestion_button = '';

        // Verify if suggestion's type is a group
        if ( suggestions.body[0].type === 'suggestions-group' ) {

            suggestion_button = ' show-suggestion-button-add';

        }

        // Start to create html
        var html = '<div class="col-' + columns + ' ' + column_slug + '-' + columns + '">'
                        + '<div class="row">'
                            + '<div class="col-12">'
                                + '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">'
                                    + before_toast
                                    + '<div class="toast" role="main-' + column_slug + '" aria-live="assertive" aria-atomic="true">'
                                        + '<div class="toast-header">'
                                            + '<small>'
                                                + Main.generate_header_content(suggestions.header, suggestions.type)
                                            + '</small>'
                                            + '<button type="button" class="ml-2 mb-1 close delete-suggestion" data-dismiss="toast" aria-label="Delete">'
                                                + '<span aria-hidden="true">&times;</span>'
                                            + '</button>'
                                        + '</div>'
                                        + '<div class="toast-body p-0">'
                                            + Main.generate_body_content(suggestions.header, suggestions.body, suggestions.type)
                                        + '</div>'
                                        + '<div class="toast-footer' + suggestion_button + '">'
                                            + '<button type="button" class="btn btn-primary theme-color-black add-new-suggestion">'
                                                + '<i class="lni-add-file"></i>'
                                            + '</button>'
                                        + '</div>'
                                    + '</div>'
                                    + after_toast
                                + '</div>'
                            + '</div>'
                        + '</div>'
                    + '</div>';

        // Display main's level suggestion
        $('.main .suggestions-body-area > .row').eq(1).append(html);

    };

    /*
     * Extract the second suggestions level from an array
     * 
     * @param array suggestions contains a main level suggestion with it's childrens
     * @param integer position helps to add correct arrows
     * @param integer total contains the number of main level suggestions
     * 
     * @since   0.0.8.0
     */
    Main.get_second_suggestions = function (suggestions, position, total) {

        // Space
        var space = '';

        // If the parent is 1 or 2
        if ((position === 0 && total > 1) || (position === 1 && total > 2)) {
            space = 'offset-3';
        } else if (total < 2) {
            space = 'offset-2';
        }

        // Verify if suggestions exists
        if (typeof suggestions.suggestions !== 'undefined') {

            // Default value for the html variable
            var html = '';

            // List all suggestions
            for (var l = 0; l < suggestions.suggestions.length; l++) {

                // Third variable's value
                var third = '';

                // Create the third suggestions level if exists
                if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                    // List all third suggestions level for the current second suggestions level
                    for (var t = 0; t < suggestions.suggestions[l].suggestions.length; t++) {

                        // Fourth variable's value
                        var fourth = '';

                        // Before_toast_third variable
                        var before_toast_third = '';

                        // After_toast_third variable
                        var after_toast_third = '';

                        // Draw lines
                        switch (total) {

                            case 1:

                                // Verify if is not the last suggestion
                                if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                    after_toast_third = '<div class="line-third-left-middle"></div>';

                                }

                                if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                    after_toast_third = '<div class="line-third-last-left"></div>';

                                }

                                if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                    after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                }

                                break;

                            case 2:

                                // Verify which position is
                                if (position < 1) {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-left-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-left"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                } else {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-right-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-right"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                }

                                break;



                            case 3:

                                // Verify which position is
                                if (position < 1) {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-left-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-left"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                } else if (position < 2) {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-left-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-left"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                } else {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-right-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-right"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                }

                                break;

                            case 3:

                                // Verify which position is
                                if (position < 1) {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-left-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-left"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                } else if (position < 2) {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-left-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-left"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                } else {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-right-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-right"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                }

                                break;

                            case 4:

                                // Verify which position is
                                if (position < 1) {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-left-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-left"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                } else if (position < 2) {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-left-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-left"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                } else if (position < 3) {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-right-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-right"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                } else {

                                    // Verify if is not the last suggestion
                                    if (t !== (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-right-middle"></div>';

                                    }

                                    if (t === (suggestions.suggestions[l].suggestions.length - 1)) {

                                        after_toast_third = '<div class="line-third-last-right"></div>';

                                    }

                                    if (typeof suggestions.suggestions[l].suggestions[t].suggestions !== 'undefined') {

                                        after_toast_third += '<div class="line-fourth-top-bottom"></div>';

                                    }

                                }

                                break;

                        }

                        // Get fourth suggestions level if exists
                        var fourth_suggestions = Main.the_fourth_suggestions(suggestions.suggestions[l].suggestions[t]);

                        // Verify if the fourth suggestions level exists for the current third suggestions level
                        if (fourth_suggestions) {
                            fourth = fourth_suggestions;
                        }

                        // Get the column's slug
                        var column_slug = Main.column_slug(position);

                        // Suggestions button
                        var suggestion_button = '';

                        // Verify if suggestion's type is a group
                        if (suggestions.body[0].type === 'suggestions-group') {

                            suggestion_button = ' show-suggestion-button-add';

                        }

                        // Add html to the third variable's value
                        third += '<div class="row">'
                                    + '<div class="col-9 ' + space + '">'
                                        + '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">'
                                            + before_toast_third
                                            + '<div class="toast" role="third-level" aria-live="assertive" aria-atomic="true">'
                                                + '<div class="toast-header">'
                                                    + '<small>'
                                                        + Main.generate_header_content(suggestions.suggestions[l].suggestions[t].header, suggestions.suggestions[l].suggestions[t].type)
                                                    + '</small>'
                                                    + '<button type="button" class="ml-2 mb-1 close delete-suggestion" data-dismiss="toast" aria-label="Delete">'
                                                        + '<span aria-hidden="true">&times;</span>'
                                                    + '</button>'
                                                + '</div>'
                                                + '<div class="toast-body p-0">'
                                                    + Main.generate_body_content(suggestions.suggestions[l].suggestions[t].header, suggestions.suggestions[l].suggestions[t].body, suggestions.suggestions[l].suggestions[t].type)
                                                + '</div>'
                                                + '<div class="toast-footer' + suggestion_button + '">'
                                                    + '<button type="button" class="btn btn-primary theme-color-black add-new-suggestion">'
                                                        + '<i class="lni-add-file"></i>'
                                                    + '</button>'
                                                + '</div>'
                                            + '</div>'
                                            + after_toast_third
                                        + '</div>'
                                        + fourth
                                    + '</div>'
                                + '</div>';

                    }

                }

                // Before_toast variable
                var before_toast = '';

                // After_toast variable
                var after_toast = '';

                // Columns variable
                var columns = 3;

                // Verify if total isn's 4
                switch (total) {

                    case 1:

                        // Set new columns
                        columns = 12;

                        // Verify if is the first suggestion
                        if (l !== (suggestions.suggestions.length - 1)) {

                            // Add middle line
                            after_toast = '<div class="line-second-left-middle"></div>';

                        }

                        // Verify if is the last suggestion
                        if (l === (suggestions.suggestions.length - 1)) {

                            // Add last line
                            after_toast = '<div class="line-second-last-left"></div>';

                        }

                        // Create the third suggestions level if exists
                        if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                            // Add third level lines
                            after_toast += '<div class="line-third-left-top-left"></div>'
                                + '<div class="line-third-left-corner"></div>'
                                + '<div class="line-third-left-top-bottom"></div>';

                        }

                        break;

                    case 2:

                        // Set new columns
                        columns = 6;

                        // Verify which position is
                        if (position < 1) {

                            after_toast = '<div class="line-second-left-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-left"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-left-top-left"></div>'
                                    + '<div class="line-third-left-corner"></div>'
                                    + '<div class="line-third-left-top-bottom"></div>';

                            }

                        } else {

                            after_toast = '<div class="line-second-right-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-right"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-right-top-right"></div>'
                                    + '<div class="line-third-right-corner"></div>'
                                    + '<div class="line-third-right-top-bottom"></div>';

                            }

                        }

                        break;

                    case 3:

                        // Set new columns
                        columns = 4;

                        // Verify which position is
                        if (position < 1) {

                            after_toast = '<div class="line-second-left-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-left"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-left-top-left"></div>'
                                    + '<div class="line-third-left-corner"></div>'
                                    + '<div class="line-third-left-top-bottom"></div>';

                            }

                        } else if (position < 2) {

                            after_toast = '<div class="line-second-left-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-left"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-left-top-left"></div>'
                                    + '<div class="line-third-left-corner"></div>'
                                    + '<div class="line-third-left-top-bottom"></div>';

                            }

                        } else {

                            after_toast = '<div class="line-second-right-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-right"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-right-top-right"></div>'
                                    + '<div class="line-third-right-corner"></div>'
                                    + '<div class="line-third-right-top-bottom"></div>';

                            }

                        }

                        break;

                    case 4:

                        // Set new columns
                        columns = 3;

                        // Verify which position is
                        if (position < 1) {

                            after_toast = '<div class="line-second-left-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-left"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-left-top-left"></div>'
                                    + '<div class="line-third-left-corner"></div>'
                                    + '<div class="line-third-left-top-bottom"></div>';

                            }

                        } else if (position < 2) {

                            after_toast = '<div class="line-second-left-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-left"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-left-top-left"></div>'
                                    + '<div class="line-third-left-corner"></div>'
                                    + '<div class="line-third-left-top-bottom"></div>';

                            }

                        } else if (position < 3) {

                            after_toast = '<div class="line-second-right-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-right"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-right-top-right"></div>'
                                    + '<div class="line-third-right-corner"></div>'
                                    + '<div class="line-third-right-top-bottom"></div>';

                            }

                        } else {

                            after_toast = '<div class="line-second-right-middle"></div>';

                            // Verify if is the last suggestion
                            if (l === (suggestions.suggestions.length - 1)) {

                                // Add last line
                                after_toast = '<div class="line-second-last-right"></div>';

                            }

                            // Verify if suggestions exists
                            if (typeof suggestions.suggestions[l].suggestions !== 'undefined') {

                                after_toast += '<div class="line-third-right-top-right"></div>'
                                    + '<div class="line-third-right-corner"></div>'
                                    + '<div class="line-third-right-top-bottom"></div>';

                            }

                        }

                        break;

                }

                // Get the column's slug
                var column_slug = Main.column_slug(position);

                // Suggestions button
                var suggestion_button = '';

                // Verify if suggestion's type is a group
                if (suggestions.suggestions[l].body[0].type === 'suggestions-group') {

                    suggestion_button = ' show-suggestion-button-add';

                }

                // Create the second suggestions level
                html += '<div class="row">'
                            + '<div class="col-12">'
                                + '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">'
                                    + before_toast
                                    + '<div class="toast" role="second-level" aria-live="assertive" aria-atomic="true">'
                                        + '<div class="toast-header">'
                                            + '<small>'
                                                + Main.generate_header_content(suggestions.suggestions[l].header, suggestions.suggestions[l].type)
                                            + '</small>'
                                            + '<button type="button" class="ml-2 mb-1 close delete-suggestion" data-dismiss="toast" aria-label="Delete">'
                                                + '<span aria-hidden="true">&times;</span>'
                                            + '</button>'
                                        + '</div>'
                                        + '<div class="toast-body p-0">'
                                            + Main.generate_body_content(suggestions.suggestions[l].header, suggestions.suggestions[l].body, suggestions.suggestions[l].type)
                                        + '</div>'
                                        + '<div class="toast-footer' + suggestion_button + '">'
                                            + '<button type="button" class="btn btn-primary theme-color-black add-new-suggestion">'
                                                + '<i class="lni-add-file"></i>'
                                            + '</button>'
                                        + '</div>'
                                    + '</div>'
                                    + after_toast
                                + '</div>'
                                + third
                            + '</div>'
                        + '</div>';

            }

            // Get the column's slug
            var column_slug = Main.column_slug(position);

            // Display suggestions
            $('.main .suggestions-body-area .' + column_slug + '-' + columns).append(html);

            // Verify if the second suggestions level exists
            if ($('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-left-bottom').length > 0) {

                // Calculate height
                var second_line = ($('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-last-left').offset().top - $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-left-bottom').offset().top);

                // Verify the length of the document
                var top = ($('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-left-bottom').offset().top - $('.main .suggestions-body-area .' + column_slug + '-' + columns).offset().top + 10);

                // Change the the second line height 
                $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-left-top-bottom').css({
                    'height': second_line + 'px',
                    'margin-top': top + 'px',
                    'top': 0
                });

                // Get all third lines
                var third_lines = $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-third-left-top-bottom');

                // Verify if third lines exists
                if (third_lines.length > 0) {

                    // List all lines
                    for (var t = 0; t < third_lines.length; t++) {

                        // Get height
                        var top_third = ($(third_lines[t]).closest('.justify-content-center').find('.toast').outerHeight() + 90);

                        // Add height
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-top-left').css({ 'top': top_third + 'px' });
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-corner').css({ 'top': (top_third + 17) + 'px' });
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-top-bottom').css({ 'top': (top_third + 30) + 'px' });

                        // Calculate the distance
                        var distance = ($(third_lines[t]).closest('.col-12').find('.line-third-last-left').offset().top - $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-corner').offset().top);

                        // Set the height
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-top-bottom').css({
                            'height': distance + 'px'
                        });

                        // Get all fourth's lines
                        var fourth_lines = $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-fourth-top-bottom');

                        // Verify if the fourth's lines exists
                        for (var f = 0; f < fourth_lines.length; f++) {

                            // Get the parent height
                            var parent_height = $(fourth_lines[f]).closest('.justify-content-center').find('.toast').outerHeight();

                            // Get total height
                            var total_height = ($(fourth_lines[f]).closest('.' + space).outerHeight() - (parent_height + 90));

                            // Set the top
                            $(fourth_lines[f]).css({
                                'top': (parent_height + 90) + 'px',
                                'height': total_height + 'px'
                            });

                        }

                        // Exact last third left arrow position
                        var left_position = ($(third_lines[t]).offset().left - $(third_lines[t]).closest('.col-12').find('.line-third-last-left').offset().left);

                        // For 1 and 4 columns don't change
                        if ((total > 1) && (total < 4)) {

                            // Add correct position
                            $(third_lines[t]).closest('.col-12').find('.line-third-last-left').css({
                                'margin-left': left_position + 'px'
                            });

                        }

                    }

                }

            } else if ($('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-main-first-4-left').length > 0) {

                // Get all third lines
                var third_lines = $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-third-left-top-bottom');

                // Verify if third lines exists
                if (third_lines.length > 0) {

                    // List all lines
                    for (var t = 0; t < third_lines.length; t++) {

                        // Get height
                        var top_third = ($(third_lines[t]).closest('.justify-content-center').find('.toast').outerHeight() + 90);

                        // Add height
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-top-left').css({ 'top': top_third + 'px' });
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-corner').css({ 'top': (top_third + 17) + 'px' });
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-top-bottom').css({ 'top': (top_third + 30) + 'px' });

                        // Calculate the distance
                        var distance = ($(third_lines[t]).closest('.col-12').find('.line-third-last-left').offset().top - $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-corner').offset().top);

                        // Set the height
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-left-top-bottom').css({
                            'height': distance + 'px'
                        });

                        // Get all fourth's lines
                        var fourth_lines = $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-fourth-top-bottom');

                        // Verify if the fourth's lines exists
                        for (var f = 0; f < fourth_lines.length; f++) {

                            // Get the parent height
                            var parent_height = $(fourth_lines[f]).closest('.justify-content-center').find('.toast').outerHeight();

                            // Get total height
                            var total_height = ($(fourth_lines[f]).closest('.' + space).outerHeight() - (parent_height + 90));

                            // Set the top
                            $(fourth_lines[f]).css({
                                'top': (parent_height + 90) + 'px',
                                'height': total_height + 'px'
                            });

                        }

                        // Exact last third left arrow position
                        var left_position = ($(third_lines[t]).offset().left - $(third_lines[t]).closest('.col-12').find('.line-third-last-left').offset().left);

                        // Add correct position
                        $(third_lines[t]).closest('.col-12').find('.line-third-last-left').css({
                            'margin-left': left_position + 'px'
                        });
                    }

                }


            } else if ($('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-right-top-bottom').length > 0) {

                // Calculate height
                var second_line = ($('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-last-right').offset().top - $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-right-bottom').offset().top);

                // Verify the length of the document
                var top = ($('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-right-bottom').offset().top - $('.main .suggestions-body-area .' + column_slug + '-' + columns).offset().top + 10);

                // Change the the second line height 
                $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-second-right-top-bottom').css({
                    'height': second_line + 'px',
                    'margin-top': top + 'px',
                    'top': 0
                });

                // Get all third lines
                var third_lines = $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-third-right-top-bottom');

                // Verify if third lines exists
                if (third_lines.length > 0) {

                    // List all lines
                    for (var t = 0; t < third_lines.length; t++) {

                        // Get height
                        var top_third = ($(third_lines[t]).closest('.justify-content-center').find('.toast').outerHeight() + 90);

                        // Add height
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-right-top-right').css({ 'top': top_third + 'px' });
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-right-corner').css({ 'top': (top_third + 17) + 'px' });
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-right-top-bottom').css({ 'top': (top_third + 30) + 'px' });

                        // Calculate the distance
                        var distance = ($(third_lines[t]).closest('.col-12').find('.line-third-last-right').offset().top - $(third_lines[t]).closest('.justify-content-center').find('.line-third-right-corner').offset().top);

                        // Set the height
                        $(third_lines[t]).closest('.justify-content-center').find('.line-third-right-top-bottom').css({
                            'height': distance + 'px'
                        });

                        // Get all fourth's lines
                        var fourth_lines = $('.main .suggestions-body-area .' + column_slug + '-' + columns + ' .line-fourth-top-bottom');

                        // Verify if the fourth's lines exists
                        for (var f = 0; f < fourth_lines.length; f++) {

                            // Get the parent height
                            var parent_height = $(fourth_lines[f]).closest('.justify-content-center').find('.toast').outerHeight();

                            // Get total height
                            var total_height = ($(fourth_lines[f]).closest('.col-9').outerHeight() - (parent_height + 90));

                            // Set the top
                            $(fourth_lines[f]).css({
                                'top': (parent_height + 90) + 'px',
                                'height': total_height + 'px'
                            });

                        }

                        // Last third right line default width
                        var third_last_line = 17;

                        // If the width is less than
                        if ($('.main .new-suggestions-top').width() <= 1353) {
                            third_last_line = 7;
                        }

                        // Exact last third right arrow position
                        var right_position = ($(third_lines[t]).closest('.justify-content-center').find('.line-third-right-top-bottom').offset().left - $(third_lines[t]).closest('.col-12').find('.line-third-last-right').offset().left - third_last_line);

                        // For 4 columns don't change
                        if (total < 4) {

                            if ( navigator.userAgent.search('Firefox') ) {

                                var left = $( '.main .line-third-right-top-bottom' ).first();
                                let position = left.position();

                                right_position = (position.left - parseInt($(third_lines[t]).closest('.col-12').find('.line-third-last-right').width()));

                            }

                            // Add correct position
                            $(third_lines[t]).closest('.col-12').find('.line-third-last-right').css({
                                'left': (right_position + 2) + 'px'
                            });

                        }

                    }

                }

            }

        }

    };

    /*
     * Extract the fourth suggestions level from an array
     * 
     * @param array suggestions contains a main level suggestion with it's childrens
     * 
     * @since   0.0.8.0
     * 
     * @return string with html
     */
    Main.the_fourth_suggestions = function (suggestions) {

        // Define default html variable's value
        var html = '';

        // Verify if suggestions exists
        if (typeof suggestions.suggestions !== 'undefined') {

            // List all fourth suggestions level
            for (var a = 0; a < suggestions.suggestions.length; a++) {

                html += '<div class="row">'
                            + '<div class="col-12">'
                                + '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">'
                                    + '<div class="toast" role="fourth-level" aria-live="assertive" aria-atomic="true">'
                                        + '<div class="toast-header">'
                                            + '<small>'
                                                + Main.generate_header_content(suggestions.suggestions[a].header, suggestions.suggestions[a].type)
                                            + '</small>'
                                            + '<button type="button" class="ml-2 mb-1 close delete-suggestion" data-dismiss="toast" aria-label="Delete">'
                                                + '<span aria-hidden="true">&times;</span>'
                                            + '</button>'
                                        + '</div>'
                                        + '<div class="toast-body p-0">'
                                            + Main.generate_body_content(suggestions.suggestions[a].header, suggestions.suggestions[a].body, suggestions.suggestions[a].type)
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>'
                        + '</div>';

            }

        }

        return html;

    }

    /*
     * Calculate the column slug
     * 
     * @param integer position helps to add correct arrows
     * 
     * @since   0.0.8.0
     * 
     * @return string with html
     */
    Main.column_slug = function (position) {

        // Default column's slug'
        var column = 'first';

        // Verify if position is not 0
        switch (position) {

            case 1:
                column = 'second';
                break;

            case 2:
                column = 'third';
                break;

            case 3:
                column = 'fourth';
                break;

        }

        return column;

    }

    /*
     * Get the categories list by page
     * 
     * @param integer page contains the page number
     * 
     * @since   0.0.8.0
     */
    Main.get_categories_by_page = function (page) {

        var data = {
            action: 'get_categories_by_page',
            page: page
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'get_categories_by_page');

    }

    /*
     * Get all categories
     * 
     * @since   0.0.8.0
     */
    Main.get_all_categories = function () {

        // Prepare data to request
        var data = {
            action: 'get_all_categories',
            key: $('.main .search-categories .search-category').val()
        };

        // Set CSRF
        data[$('.main .search-categories').attr('data-csrf')] = $('input[name="' + $('.main .search-categories').attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'get_all_categories');

    }

    /*
     * Get Media Preview
     * 
     * @param object file contains the file's data
     * @param object object contains file's formated data
     * 
     * @since   0.0.8.0
     */
    Main.getMediaPreview = function (file, object) {

        // Call FileReader
        var fileReader = new FileReader();

        // Verify the file type
        if (file.type.match('image')) {

            // Create an thumbnail
            fileReader.onload = function () {
                var img = document.createElement('img');
                img.src = fileReader.result;

                var image = new Image();

                image.onload = function () {
                    var canvas = document.createElement('canvas');
                    canvas.width = 250;
                    canvas.height = 250;

                    canvas.getContext('2d').drawImage(this, 0, 0, 250, 250);

                    object.cover = canvas.toDataURL('image/png');
                };

                image.src = img.src;

            };

            fileReader.readAsDataURL(file);

        } else {

            fileReader.onload = function () {

                var blob = new Blob([fileReader.result], { type: file.type });
                var url = URL.createObjectURL(blob);
                var video = document.createElement('video');

                var timeupdate = function () {

                    if (snapImage()) {

                        video.removeEventListener('timeupdate', timeupdate);
                        video.pause();

                    }

                };

                video.addEventListener('loadeddata', function () {

                    if (snapImage()) {

                        video.removeEventListener('timeupdate', timeupdate);

                    }

                });

                var snapImage = function () {

                    var canvas = document.createElement('canvas');
                    canvas.width = 250;
                    canvas.height = 250;
                    canvas.getContext('2d').drawImage(video, 0, 0, 250, 250);
                    var image = canvas.toDataURL();
                    var success = image.length > 10;

                    if (success) {

                        var img = document.createElement('img');
                        img.src = image;
                        URL.revokeObjectURL(url);
                        object.cover = img.src;

                    }

                    return success;

                };

                video.addEventListener('timeupdate', timeupdate);
                video.preload = 'metadata';
                video.src = url;
                video.muted = true;
                video.playsInline = true;
                video.play();

            };

            fileReader.readAsArrayBuffer(file);

        }

    };

    /*
     * Save files
     * 
     * @param object file contains the file's data 
     * 
     * @since   0.0.8.0
     */
    Main.saveFile = function (file) {

        // Get file type
        var fileType = file.type.split('/');

        // Verify if the uploaded file is an image
        if ( fileType[0] !== 'image' ) {

            // Display error alert
            Main.popup_fon('sube', words.templates_supports_only_images, 1500, 2000);
            return;
            
        }

        // Verify if files method exists
        if (typeof Main.files !== '') {
            Main.files = [];
        }

        // Add file in the queue
        Main.files[file.lastModified + '-' + file.size] = {
            key: file.lastModified + '-' + file.size,
            name: file.name,
            type: file.type,
            size: file.size,
            lastModified: file.lastModified
        };

        // Create form's data
        var form = new FormData();
        form.append('path', '/');
        form.append('file', file);
        form.append('type', fileType[0]);
        form.append('enctype', 'multipart/form-data');
        form.append($('.upim').attr('data-csrf'), $('input[name="' + $('.upim').attr('data-csrf') + '"]').val());

        // Generate preview
        Main.getMediaPreview(file, Main.files[file.lastModified + '-' + file.size]);

        // Display loading animation
        $('.page-loading').fadeIn('slow');

        // Default count value
        var s = 0;

        // Start to count the time
        var timer = setInterval(function () {

            // Get the cover
            var cover = Main.files[file.lastModified + '-' + file.size].cover;

            // Verify if the cover exists
            if (typeof cover !== 'undefined') {

                // Try to upload files
                Main.uploadFile(form, Main.files[file.lastModified + '-' + file.size]);

                // Clear interval
                clearInterval(timer);

            }

            if (s > 15) {

                clearInterval(timer);

            } else {

                s++;

            }

        }, 1000);

    };

    /*
     * Upload media files
     * 
     * @param object form contains the formdata
     * @param object path contains the file's information
     * 
     * @since   0.0.8.0
     */
    Main.uploadFile = function (form, path) {

        // Set the media's cover
        form.append('cover', path.cover);

        // Set the action
        form.append('action', 'upload_media_in_storage');

        // Upload media
        $.ajax({
            url: url + 'user/ajax/media',
            type: 'POST',
            data: form,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (data) {

                if (data.success) {

                    // Display success alert
                    Main.popup_fon('subi', data.message, 1500, 2000);

                    // Get file type
                    var fileType = path.type.split('/');

                    // Verify the file's type
                    if (fileType[0] === 'image') {

                        // Set media's id
                        $('.main #suggestions-manager #template-preview .waiting-for-upload').attr('data-media', data.media_id);

                        // Display the cover
                        $('.main #suggestions-manager #template-preview .waiting-for-upload').html('<img src="' + data.media_cover + '">');

                    }

                } else {

                    // Display error alert
                    Main.popup_fon('sube', data.message, 1500, 2000);

                }

            },
            error: function (jqXHR, textStatus, errorThrown) {

                console.log(jqXHR);

            },
            complete: function () {

                // Hide loading animation
                $('.page-loading').fadeOut();

                // Remove waiting for upload class
                $('.main #suggestions-manager #template-preview').removeClass('waiting-for-upload');

            }

        });

    };

    /*
     * Order suggestions in a diagram
     * 
     * @param array suggestions contains the suggestions list
     * 
     * @since   0.0.8.0
     */
    Main.order_suggestions = function (suggestions) {

        // Empty the diagram area
        $('.main .suggestions-body-area > .row').eq(1).empty();

        // Add header identifier
        switch (suggestions.length) {

            case 1:
                $('.main .suggestions-body-area').removeClass('cols-3');
                $('.main .suggestions-body-area').removeClass('cols-4');
                $('.main .suggestions-body-area').removeClass('cols-6');
                $('.main .suggestions-body-area').addClass('cols-12');
                break;

            case 2:
                $('.main .suggestions-body-area').removeClass('cols-3');
                $('.main .suggestions-body-area').removeClass('cols-4');
                $('.main .suggestions-body-area').removeClass('cols-12');
                $('.main .suggestions-body-area').addClass('cols-6');
                break;

            case 3:
                $('.main .suggestions-body-area').removeClass('cols-3');
                $('.main .suggestions-body-area').removeClass('cols-6');
                $('.main .suggestions-body-area').removeClass('cols-12');
                $('.main .suggestions-body-area').addClass('cols-4');
                break;
                
            case 4:
                $('.main .suggestions-body-area').removeClass('cols-4');
                $('.main .suggestions-body-area').removeClass('cols-6');
                $('.main .suggestions-body-area').removeClass('cols-12');
                $('.main .suggestions-body-area').addClass('cols-3');
                break;

        }

        // List all main suggestions
        for (var s = 0; s < suggestions.length; s++) {

            // Generates the main suggestions level and displays them
            Main.get_main_suggestion(suggestions[s], s, suggestions.length);

            // Generates the second suggestions level and displays them
            Main.get_second_suggestions(suggestions[s], s, suggestions.length);

        }

        // Verify if total is greater than 1
        if ( suggestions.length > 1 ) {

            switch ( suggestions.length ) {

                case 2:

                    // If the width is less than
                    if ($('.main .new-suggestions-top').width() < 1219) {

                        // Set the position of the second suggestions list
                        $('.main .second-6').css({
                            'top': '100px'
                        });

                        // Get total height
                        var first_height = $('.main .suggestions-body-area').outerHeight();

                        // Set the position of the second suggestions list
                        $('.main .suggestions-body-area').css({
                            'height': (first_height + 200) + 'px'
                        });

                        // Calculate distance for right left bottom arrow
                        var bottom_left = ($('.main .line-main-right-bottom-last').offset().left - $('.main .line-main-right-left-bottom').offset().left);

                        // Set width
                        $('.main .line-main-right-bottom-last').css({
                            'width': (bottom_left + 3) + 'px',
                            'margin-left': '-' + bottom_left + 'px',
                        });

                    }

                    // Calculate the main line height
                    var height = ($('.main .suggestions-body-area .second-6 .line-main-right-left-bottom').offset().top - $('.main .suggestions-body-area .first-6 .line-main-left-bottom').offset().top);

                    // Set height
                    $('.main .line-main-right-left-bottom').css({
                        'height': height + 'px',
                        'top': '-' + (height - 3) + 'px'
                    });

                    // Calculate distance for right left top arrow
                    var top_left = ($('.main .line-main-bottom').offset().left - $('.main .line-main-left-bottom-first').offset().left - 20);


                    // Set width
                    $('.main .line-main-left').css({
                        'width': (top_left + 3) + 'px',
                        'margin-left': '-' + top_left + 'px',
                    });

                    break;

                case 3:

                    // Get first column height
                    var first_4 = $('.main .first-4').outerHeight();

                    // Get second column height
                    var second_4 = $('.main .second-4').outerHeight();

                    // Get third column height
                    var third_4 = $('.main .third-4').outerHeight();

                    // If the width is less than
                    if ( ( $('.main .new-suggestions-top').outerWidth() > 1002 ) && ( $('.main .new-suggestions-top').outerWidth() < 1219 ) ) {

                        // Calculate the height for the first column
                        var first_column = (second_4 - third_4) + second_4;

                        // Move the first column
                        $('.main .first-4').css({
                            'margin-top': (first_column + 100) + 'px'
                        });

                        // Move the main top bottom line to the first column
                        $('.main .line-main-top-bottom').css({
                            'margin-top': '2px'
                        });

                        // Move the main top left bottom line to the first column
                        $('.main .line-main-left-bottom-first').css({
                            'margin-top': '-' + (first_column + 100) + 'px'
                        });

                        // Calculate positive height for first 4 left line
                        var first_4_left_line = ($('.main .suggestions-body-area .first-4 .line-main-first-4-left').offset().top - $('.main .suggestions-body-area .first-4 .line-main-left-bottom-first').offset().top);

                        // Calculate negative height for first 4 left line
                        var first_4_left_line_top = ($('.main .suggestions-body-area .first-4 .line-main-left-bottom-first').offset().top - $('.main .suggestions-body-area .first-4 .line-main-first-4-left').offset().top + 55);

                        // Change the the second line height 
                        $('.main .suggestions-body-area .first-4 .line-main-first-4-left').css({
                            'height': first_4_left_line + 'px',
                            'margin-top': first_4_left_line_top + 'px',
                            'top': 0
                        });

                        // Calculate width between line-main-first-4-top and line-main-first-4-left
                        var first_4_left_line_width = ($('.main .suggestions-body-area .first-4 .line-main-first-4-top').offset().left - $('.main .suggestions-body-area .first-4 .line-main-first-4-left').offset().left);

                        // Set width
                        $('.main .line-main-first-4-top').css({
                            'width': first_4_left_line_width + 'px',
                            'margin-left': '-' + (first_4_left_line_width - 3) + 'px',
                        });

                    } else if ( $('.main .new-suggestions-top').outerWidth() > 1219) {
 
                        // Calculate the height for the first column
                        var first_column = (second_4 - third_4) + second_4;

                        // Move the first column
                        $('.main .first-4').css({
                            'margin-top': 0
                        });

                        // Move the main top bottom line to the first column
                        $('.main .line-main-top-bottom').css({
                            'margin-top': 0
                        });

                        // Move the main top left bottom line to the first column
                        $('.main .line-main-left-bottom-first').css({
                            'margin-top': 0
                        });

                    } else if ($('.main .new-suggestions-top').outerWidth() < 1219) {

                        // Set second column position
                        $('.main .second-4').css({
                            'margin-top': (first_4 + 100) + 'px'
                        });

                        // Set third column position
                        $('.main .third-4').css({
                            'margin-top': (first_4 + second_4 + 200) + 'px'
                        });

                    }

                    // Set the position of the second suggestions list
                    $('.main .suggestions-body-area').css({
                        'height': ($(document).outerHeight() + 400) + 'px'
                    });

                    // Calculate distance for right left top arrow
                    var top_left = ($('.main .line-main-bottom').offset().left - $('.main .line-main-left-bottom-first').offset().left - 20);

                    // Set width
                    $('.main .line-main-left').css({
                        'width': (top_left + 3) + 'px',
                        'margin-left': '-' + top_left + 'px',
                    });

                    break;

                case 4:

                    // Get first column height
                    var first_3 = $('.main .first-3').outerHeight();

                    // Get second column height
                    var second_3 = $('.main .second-3').outerHeight();

                    // Get third column height
                    var third_3 = $('.main .third-3').outerHeight();

                    // Get fourth column height
                    var fourth_3 = $('.main .fourth-3').outerHeight();

                    // If the width is less than
                    if (($('.main .new-suggestions-top').width() < 1219) && $('.main .new-suggestions-top').width() > 1002) {

                        // Calculate the height for the first column
                        var first_column = (second_3 - third_3) + second_3;

                        // Move the first column
                        $('.main .first-3').css({
                            'margin-top': (first_column + 100) + 'px'
                        });

                        // Move the fourth column
                        $('.main .fourth-3').css({
                            'margin-top': (first_column + 100) + 'px'
                        });

                        // Move the first main left bottom line
                        $('.main .line-main-left-bottom-first').css({
                            'margin-top': '-' + (first_column + 100) + 'px'
                        });

                        // Move the last main right bottom line
                        $('.main .line-main-right-bottom-last').css({
                            'margin-top': '-' + (first_column + 100) + 'px'
                        });

                        // Calculate positive height for first 3 left line
                        var first_3_left_line = ($('.main .suggestions-body-area .first-3 .line-main-first-3-left').offset().top - $('.main .suggestions-body-area .first-3 .line-main-left-bottom-first').offset().top);

                        // Calculate negative height for first 3 left line
                        var first_3_left_line_top = ($('.main .suggestions-body-area .first-3 .line-main-left-bottom-first').offset().top - $('.main .suggestions-body-area .first-3 .line-main-first-3-left').offset().top + 20);

                        // Change the the main first line height 
                        $('.main .suggestions-body-area .first-3 .line-main-first-3-left').css({
                            'height': first_3_left_line + 'px',
                            'margin-top': first_3_left_line_top + 'px',
                            'top': 0
                        });

                        // Change the the main fourth line height 
                        $('.main .suggestions-body-area .fourth-3 .line-main-fourth-3-right').css({
                            'height': first_3_left_line + 'px',
                            'margin-top': first_3_left_line_top + 'px',
                            'top': 0
                        });

                        // Calculate width between line-main-first-3-top and line-main-first-3-left
                        var first_3_left_line_width = ($('.main .suggestions-body-area .first-3 .line-main-first-3-top').offset().left - $('.main .suggestions-body-area .first-3 .line-main-first-3-left').offset().left);

                        // Set width
                        $('.main .line-main-first-3-top').css({
                            'width': ($('.main .line-main-first-3-top').width() + first_3_left_line_width) + 'px',
                            'margin-left': '-' + ($('.main .line-main-first-3-top').width() + first_3_left_line_width - 17) + 'px',
                        });

                        // Calculate width between line-main-fourth-3-top and line-main-fourth-3-right
                        var first_3_right_line_width = ($('.main .suggestions-body-area .fourth-3 .line-main-fourth-3-right').offset().left - $('.main .suggestions-body-area .fourth-3 .line-main-fourth-3-top').offset().left);

                        // Set width
                        $('.main .line-main-fourth-3-top').css({
                            'width': first_3_right_line_width + 'px'
                        });

                        // Set the body height
                        $('.main .suggestions-body-area').css({
                            'height': (first_3 + second_3 + 400) + 'px'
                        });

                    } else if ($('.main .new-suggestions-top').width() < 1003) {

                        // Move the second column
                        $('.main .second-3').css({
                            'margin-top': (first_3 + 100) + 'px'
                        });

                        // Move the third column
                        $('.main .third-3').css({
                            'margin-top': (first_3 + second_3 + 100) + 'px'
                        });

                        // Move the fourth column
                        $('.main .fourth-3').css({
                            'margin-top': (first_3 + second_3 + third_3 + 100) + 'px'
                        });

                    }

                    break;

            }

        }

    };

    /*
     * Select the template in the Templates Manager
     * 
     * @param array suggestions contains the suggestions list
     * 
     * @since   0.0.8.0
     */
    Main.select_templates_manager_template = function (suggestions) {

        // Remove active tabs
        $('#suggestions-manager .col-md-4 .nav-pills > a').attr('aria-selected', 'false');
        $('#suggestions-manager .col-md-4 .nav-pills > a').removeClass('active');
        $('#suggestions-manager .col-md-8 #template-preview .tab-pane').removeClass('show active');

        // Display template by type
        switch (suggestions[0].type) {

            case 'generic-template':

                // Select tab by type
                $('#suggestions-manager .col-md-4 .nav-pills > a[href="#v-pills-generic-template"]').attr('aria-selected', 'true');
                $('#suggestions-manager .col-md-4 .nav-pills > a[href="#v-pills-generic-template"]').addClass('active');
                $('#suggestions-manager .col-md-8 #template-preview #v-pills-generic-template').addClass('show active');

                break;

            case 'media-template':

                // Select tab by type
                $('#suggestions-manager .col-md-4 .nav-pills > a[href="#v-pills-media-template"]').attr('aria-selected', 'true');
                $('#suggestions-manager .col-md-4 .nav-pills > a[href="#v-pills-media-template"]').addClass('active');
                $('#suggestions-manager .col-md-8 #template-preview #v-pills-media-template').addClass('show active');

                break;

            case 'button-template':

                // Select tab by type
                $('#suggestions-manager .col-md-4 .nav-pills > a[href="#v-pills-button-template"]').attr('aria-selected', 'true');
                $('#suggestions-manager .col-md-4 .nav-pills > a[href="#v-pills-button-template"]').addClass('active');
                $('#suggestions-manager .col-md-8 #template-preview #v-pills-button-template').addClass('show active');

                break;

        }

    };

    /*
     * Set the template's header in the Templates Manager
     * 
     * @param array suggestions contains the suggestions list
     * 
     * @since   0.0.8.0
     */
    Main.select_templates_manager_template_header = function (suggestions) {

        // Display template by type
        switch (suggestions[0].type) {

            case 'generic-template':

                // Verify if Image exists
                if (typeof suggestions[0].header[0].header.cover_id !== 'undefined') {

                    // Set the image
                    $('#suggestions-manager #template-preview > .show .drag-and-drop-files').attr('data-media', suggestions[0].header[0].header.cover_id);
                    $('#suggestions-manager #template-preview > .show .drag-and-drop-files').html('<img src="' + suggestions[0].header[0].header.cover_src + '">');

                }

                // Verify if input fields exists
                if (suggestions[0].header.length > 0) {

                    // List all header's inputs
                    Object.keys(suggestions[0].header[0].header).forEach(function (key) {

                        // Set form inputs
                        $('#suggestions-manager #template-preview > .show #generic-template-header input[name="' + key + '"]').val(suggestions[0].header[0].header[key]);

                    });

                }

                break;

            case 'media-template':

                // Verify if Image exists
                if (typeof suggestions[0].header[0].header.cover_id !== 'undefined') {

                    // Set the image
                    $('#suggestions-manager #template-preview > .show .drag-and-drop-files').attr('data-media', suggestions[0].header[0].header.cover_id);
                    $('#suggestions-manager #template-preview > .show .drag-and-drop-files').html('<img src="' + suggestions[0].header[0].header.cover_src + '">');

                }

                // Verify if input fields exists
                if (suggestions[0].header.length > 0) {

                    // List all header's inputs
                    Object.keys(suggestions[0].header[0].header).forEach(function (key) {

                        // Set form inputs
                        $('#suggestions-manager #template-preview > .show #media-template-header input[name="' + key + '"]').val(suggestions[0].header[0].header[key]);

                    });

                }

                break;

            case 'button-template':

                // Verify if input fields exists
                if (suggestions[0].header.length > 0) {

                    // List all header's inputs
                    Object.keys(suggestions[0].header[0].header).forEach(function (key) {

                        // Set form inputs
                        $('#suggestions-manager #template-preview > .show #button-template-header input[name="' + key + '"]').val(suggestions[0].header[0].header[key]);

                    });

                }

                break;                

        }

    };

    /*
     * Set the template's body in the Templates Manager
     * 
     * @param array suggestions contains the suggestions list
     * 
     * @since   0.0.8.0
     */
    Main.select_templates_manager_template_body = function (suggestions) {

        // List all suggestions
        for (var s = 0; s < suggestions.length; s++) {

            // Show collapses
            $('.main #template-preview #' + suggestions[s].type + '-button-' + (s + 1)).addClass('show');
            $('.main #template-preview a[href="#' + suggestions[s].type + '-button-' + (s + 1) + '"]').attr('aria-expanded', 'true');

            // Verify if body exists
            if (suggestions[s].body.length > 0) {

                // Open the collapse
                switch (suggestions[s].body[0].type) {

                    case 'link':

                        // Hide the link collapse
                        $('#suggestions-manager #template-preview > .show #' + suggestions[s].type + '-button-' + (s + 1) + '-menu-suggestions').removeClass('show');
                        $('.main #template-preview a[href="#' + suggestions[s].type + '-button-' + (s + 1) + '-menu-suggestions"]').attr('aria-expanded', 'false');                    

                        // Show the groups suggestions collapse
                        $('#suggestions-manager #template-preview > .show #' + suggestions[s].type + '-button-' + (s + 1) + '-menu-link').addClass('show');
                        $('.main #template-preview a[href="#' + suggestions[s].type + '-button-' + (s + 1) + '-menu-link"]').attr('aria-expanded', 'true');

                        // List all body fields
                        Object.keys(suggestions[s].body[0]).forEach(function (key) {

                            // Set form inputs
                            $('#suggestions-manager #template-preview > .show #' + suggestions[s].type + '-button-' + (s + 1) + '-menu-link input[name="' + key + '"]').val(suggestions[s].body[0][key]);

                        });

                        break;

                    case 'suggestions-group':

                        // Hide the link collapse
                        $('#suggestions-manager #template-preview > .show #' + suggestions[s].type + '-button-' + (s + 1) + '-menu-link').removeClass('show');
                        $('.main #template-preview a[href="#' + suggestions[s].type + '-button-' + (s + 1) + '-menu-link"]').attr('aria-expanded', 'false');                    

                        // Show the groups suggestions collapse
                        $('#suggestions-manager #template-preview > .show #' + suggestions[s].type + '-button-' + (s + 1) + '-menu-suggestions').addClass('show');
                        $('.main #template-preview a[href="#' + suggestions[s].type + '-button-' + (s + 1) + '-menu-suggestions"]').attr('aria-expanded', 'true');

                        // List all body fields
                        Object.keys(suggestions[s].body[0]).forEach(function (key) {

                            // Set form inputs
                            $('#suggestions-manager #template-preview > .show #' + suggestions[s].type + '-button-' + (s + 1) + '-menu-suggestions input[name="' + key + '"]').val(suggestions[s].body[0][key]);

                        });

                        break;

                }

            }

        }

    };

    /*
     * Turn array to object
     * 
     * @param array suggestions contains the suggestions list
     * 
     * @since   0.0.8.0
     * 
     * @return object with suggestions
     */
    Main.to_object = function (suggestions) {

        // Create new object
        var newObj = new Object();

        // Verify if suggestions is an object
        if (typeof suggestions == "object") {

            // List all arrays
            for (var i in suggestions) {

                // Turn array to object
                var thisArray = Main.to_object(suggestions[i]);

                // Add object to newObj
                newObj[i] = thisArray;

            }

        } else {

            // Add object to newObj
            newObj = suggestions;

        }

        return newObj;

    };

    /*
     * Turn object to array
     * 
     * @param array suggestions contains the suggestions list
     * 
     * @since   0.0.8.0
     * 
     * @return array with suggestions
     */
    Main.to_array = function (suggestions) {

        // Create new array
        var newArr = [];

        // Verify if suggestions is an array
        if (typeof suggestions === "object") {

            // List all arrays
            for (var i in suggestions) {

                // Turn object to array
                var thisArr = Main.to_array(suggestions[i]);

                // Add array to newArr
                newArr[i] = thisArr;

            }

        } else {

            // Add array to newArr
            newArr = suggestions;

        }

        return newArr;

    };

    /*******************************
    ACTIONS
    ********************************/

    /*
     * Detect drag and drop
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('drag dragstart dragend dragover dragenter dragleave drop', '.drag-and-drop-files', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Add active class
        $(this).addClass('drag-active waiting-for-upload');

        // Verify if files were dropped
        if (e.handleObj.origType === 'dragleave' || e.handleObj.origType === 'drop') {

            // Remove active class
            $(this).removeClass('drag-active');

            // Verify if files exists
            if (typeof e.originalEvent.dataTransfer.files[0] !== 'undefined') {

                // Append file to the queue
                $('#file').prop('files', e.originalEvent.dataTransfer.files);

                // Submit form
                $('#upim').submit();

            }

        }

    });

    /*
     * Detect categories manager modal open
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $('.main #categories-manager').on('shown.bs.modal', function (e) {

        // Get Categories List By Page
        Main.get_categories_by_page(1);

    });

    /*
     * Detect when categories are shown
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $('.main #categories').on('shown.bs.collapse', function () {

        // Get ALL Categories
        Main.get_all_categories();

    });

    /*
     * Search for categories
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('keyup', '.main .search-category', function (e) {
        e.preventDefault();

        if ($(this).val() === '') {

            // Hide button
            $('.main .cancel-categories-search').fadeOut('slow');

        } else {

            // Display the cancel button
            $('.main .cancel-categories-search').fadeIn('slow');

        }

        // Get ALL Categories
        Main.get_all_categories();

    });

    /*
     * Submit form
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('change', '#file', function (e) {

        // Submit form
        $('#upim').submit();

    });

    /*
     * Select a file
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.drag-and-drop-files', function (e) {
        e.preventDefault();

        // Add active class
        $(this).addClass('waiting-for-upload');

        // Select file
        $('#file').click();

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

            case 'categories':

                // Get Categories List By Page
                Main.get_categories_by_page(page);

                break;

        }

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Delete a category
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .all-categories .delete-category', function (e) {
        e.preventDefault();

        // Get the category's id
        var category_id = $(this).attr('data-id');

        // Create an object with form data
        var data = {
            action: 'delete_category',
            category_id: category_id
        };

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'GET', data, 'delete_category');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Select a category
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .all-categories-list .select-category', function (e) {
        e.preventDefault();

        // Get the category's id
        var category_id = $(this).attr('data-id');

        // Verify if the category already exists
        if ( $('.main .suggestions-categories .panel-heading .select-category[data-id="' + category_id + '"]').length > 0 ) {

            // Remove the selected category from the displayed list
            $('.main .suggestions-categories .panel-heading .btn[data-id="' + category_id + '"]').remove();

            // Remove selected class
            $(this).removeClass('selected-category');


        } else {

            // Add selected category to the displayed list
            $($(this).prop('outerHTML')).insertBefore('.main .select-categories');

            // Add selected class
            $(this).addClass('selected-category');

        }

    });

    /*
     * Cancel the category search
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .cancel-categories-search', function (e) {
        e.preventDefault();

        // Empty the input
        $('.main .search-category').val('');

        // Hide button
        $('.main .cancel-categories-search').fadeOut('slow');

        // Get ALL Categories
        Main.get_all_categories();

    });

    /*
     * Add new suggestion
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .add-new-suggestion', function (e) {
        e.preventDefault();

        // Remove active-manage-suggestions class
        $('.main .btn').removeClass('active-manage-suggestions');

        // Add active-manage-suggestions class
        $(this).addClass('active-manage-suggestions');

        // Reset template preview
        switch ($('.main #template-preview > .active').attr('data-type')) {

            case 'generic-template':

                // Reset media
                $('.main #template-preview > .active .drag-and-drop-files').removeAttr('data-media');
                $('.main #template-preview > .active .drag-and-drop-files img').remove();
                $('.main #template-preview > .active .drag-and-drop-files').text(words.drag_image_click_to_upload);

                // Empty all inputs
                $('.main #template-preview > .active .form-control').val('');

                // Hide all collapses
                $('.main #template-preview > .active .collapse').collapse('hide');

                // Show required collapses
                $('.main #template-preview #generic-template-header').addClass('show');
                $('.main #template-preview a[href="#generic-template-header"]').attr('aria-expanded', 'true');
                $('.main #template-preview #generic-template-button-1-menu-link').addClass('show');
                $('.main #template-preview a[href="#generic-template-button-1-menu-link"]').attr('aria-expanded', 'true');
                $('.main #template-preview #generic-template-button-2-menu-link').addClass('show');
                $('.main #template-preview a[href="#generic-template-button-2-menu-link"]').attr('aria-expanded', 'true');
                $('.main #template-preview #generic-template-button-3-menu-link').addClass('show');
                $('.main #template-preview a[href="#generic-template-button-3-menu-link"]').attr('aria-expanded', 'true');

                break;

            case 'media-template':

                // Reset media
                $('.main #template-preview > .active .drag-and-drop-files').removeAttr('data-media');
                $('.main #template-preview > .active .drag-and-drop-files img').remove();
                $('.main #template-preview > .active .drag-and-drop-files').text(words.drag_image_click_to_upload);

                // Empty all inputs
                $('.main #template-preview > .active .form-control').val('');

                // Hide all collapses
                $('.main #template-preview > .active .collapse').collapse('hide');

                // Show required collapses
                $('.main #template-preview #media-template-header').addClass('show');
                $('.main #template-preview a[href="#media-template-header"]').attr('aria-expanded', 'true');
                $('.main #template-preview #media-template-button-1-menu-link').addClass('show');
                $('.main #template-preview a[href="#media-template-button-1-menu-link"]').attr('aria-expanded', 'true');

                break;

            case 'button-template':

                // Empty all inputs
                $('.main #template-preview > .active .form-control').val('');

                // Hide all collapses
                $('.main #template-preview > .active .collapse').collapse('hide');

                // Show required collapses
                $('.main #template-preview #button-template-header').addClass('show');
                $('.main #template-preview a[href="#button-template-header"]').attr('aria-expanded', 'true');
                $('.main #template-preview #button-template-button-1-menu-link').addClass('show');
                $('.main #template-preview a[href="#button-template-button-1-menu-link"]').attr('aria-expanded', 'true');
                $('.main #template-preview #button-template-button-2-menu-link').addClass('show');
                $('.main #template-preview a[href="#button-template-button-2-menu-link"]').attr('aria-expanded', 'true');
                $('.main #template-preview #button-template-button-3-menu-link').addClass('show');
                $('.main #template-preview a[href="#button-template-button-3-menu-link"]').attr('aria-expanded', 'true');

                break;

        }

        // Display template settings based on position
        switch ( $(this).closest('.toast').attr('role') ) {

            case 'main-list':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Select template
                    Main.select_templates_manager_template(Main.suggestions_list);

                    // Set header
                    Main.select_templates_manager_template_header(Main.suggestions_list);

                    // Set body
                    Main.select_templates_manager_template_body(Main.suggestions_list);

                }

                break;

            case 'main-first':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Verify if main has suggestions
                    if (typeof Main.suggestions_list[0].suggestions !== 'undefined') {

                        // Select template
                        Main.select_templates_manager_template(Main.suggestions_list[0].suggestions);

                        // Set header
                        Main.select_templates_manager_template_header(Main.suggestions_list[0].suggestions);

                        // Set body
                        Main.select_templates_manager_template_body(Main.suggestions_list[0].suggestions);

                    }

                }

                break;

            case 'main-second':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Verify if main has suggestions
                    if (typeof Main.suggestions_list[1].suggestions !== 'undefined') {

                        // Select template
                        Main.select_templates_manager_template(Main.suggestions_list[1].suggestions);

                        // Set header
                        Main.select_templates_manager_template_header(Main.suggestions_list[1].suggestions);

                        // Set body
                        Main.select_templates_manager_template_body(Main.suggestions_list[1].suggestions);

                    }

                }

                break;

            case 'main-third':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Verify if main has suggestions
                    if (typeof Main.suggestions_list[2].suggestions !== 'undefined') {

                        // Select template
                        Main.select_templates_manager_template(Main.suggestions_list[2].suggestions);

                        // Set header
                        Main.select_templates_manager_template_header(Main.suggestions_list[2].suggestions);

                        // Set body
                        Main.select_templates_manager_template_body(Main.suggestions_list[2].suggestions);

                    }

                }

                break;

            case 'main-fourth':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Verify if main has suggestions
                    if (typeof Main.suggestions_list[3].suggestions !== 'undefined') {

                        // Select template
                        Main.select_templates_manager_template(Main.suggestions_list[3].suggestions);

                        // Set header
                        Main.select_templates_manager_template_header(Main.suggestions_list[3].suggestions);

                        // Set body
                        Main.select_templates_manager_template_body(Main.suggestions_list[3].suggestions);

                    }

                }

                break;

            case 'second-level':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {
                    
                    // Default column value
                    var column = 0;

                    // Default parent value
                    var parent = 'col-12'; 

                    if ( $('.main .suggestions-body-area').hasClass('cols-3') ) {

                        parent = 'col-3';

                        if ( $('.main .active-manage-suggestions').closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-3' ) {
                            column = 1;
                        } else if ( $('.main .active-manage-suggestions').closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-3' ) {
                            column = 2;
                        } else if ( $('.main .active-manage-suggestions').closest('.' + parent).attr('class').replace(parent + ' ', '') === 'fourth-3' ) {
                            column = 3;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-4') ) {

                        parent = 'col-4';

                        if ( $('.main .active-manage-suggestions').closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-4' ) {
                            column = 1;
                        } else if ( $('.main .active-manage-suggestions').closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-4' ) {
                            column = 2;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-6') ) {

                        parent = 'col-6';

                        if ( $('.main .active-manage-suggestions').closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-6' ) {
                            column = 1;
                        }

                    }

                    // Get Row
                    var row = $('.main .active-manage-suggestions').closest('.suggestions-body-area').find('.' + $('.main .active-manage-suggestions').closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').index($('.main .active-manage-suggestions').closest('.row'));


                    // Verify if row exists
                    if ( typeof Main.suggestions_list[column].suggestions[(row - 1)].suggestions !== 'undefined' ) {

                        // Select template
                        Main.select_templates_manager_template(Main.suggestions_list[column].suggestions[(row - 1)].suggestions);

                        // Set header
                        Main.select_templates_manager_template_header(Main.suggestions_list[column].suggestions[(row - 1)].suggestions);

                        // Set body
                        Main.select_templates_manager_template_body(Main.suggestions_list[column].suggestions[(row - 1)].suggestions);
                        
                    }

                }

                break;

            case 'third-level':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {
                    
                    // Default column value
                    var column = 0;

                    // Default parent value
                    var parent = 'col-12'; 

                    if ( $('.main .suggestions-body-area').hasClass('cols-3') ) {

                        parent = 'col-3';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-3' ) {
                            column = 1;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-3' ) {
                            column = 2;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'fourth-3' ) {
                            column = 3;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-4') ) {

                        parent = 'col-4';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-4' ) {
                            column = 1;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-4' ) {
                            column = 2;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-6') ) {

                        parent = 'col-6';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-6' ) {
                            column = 1;
                        }

                    }

                    // Set level
                    var level = $('.main .' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').index($(this).closest('.col-12').parent());

                    // Third level
                    var t_level = $('.main .' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').eq(level).find('.col-12 > .row').index($(this).closest('.col-9').parent());

                    // Verify if row exists
                    if ( typeof Main.suggestions_list[column].suggestions[(level - 1)].suggestions[t_level].suggestions !== 'undefined' ) {

                        // Select template
                        Main.select_templates_manager_template(Main.suggestions_list[column].suggestions[(level - 1)].suggestions[t_level].suggestions);

                        // Set header
                        Main.select_templates_manager_template_header(Main.suggestions_list[column].suggestions[(level - 1)].suggestions[t_level].suggestions);

                        // Set body
                        Main.select_templates_manager_template_body(Main.suggestions_list[column].suggestions[(level - 1)].suggestions[t_level].suggestions);
                        
                    }

                }
    
                break;

        }

        // Show modal
        $('.main #suggestions-manager').modal('show');

    });

    /*
     * Delete suggestion
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('click', '.main .delete-suggestion', function (e) {
        e.preventDefault();

        // New array
        var new_array = [];        

        // Display template settings based on position
        switch ( $(this).closest('.toast').attr('role') ) {

            case 'main-first':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // List all suggestions
                    for (var s = 0; s < Main.suggestions_list.length; s++) {

                        if (s < 1) {
                            continue;
                        }

                        // Add suggestions
                        new_array.push(Main.suggestions_list[s]);

                    }

                    // Set new data
                    Main.suggestions_list = new_array;

                }

                break;

            case 'main-second':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Verify if main has suggestions
                    if (typeof Main.suggestions_list[1] !== 'undefined') {

                        // List all suggestions
                        for (var s = 0; s < Main.suggestions_list.length; s++) {

                            if (s === 1) {
                                continue;
                            }

                            // Add suggestions
                            new_array.push(Main.suggestions_list[s]);

                        }

                        // Set new data
                        Main.suggestions_list = new_array;

                    }

                }

                break;

            case 'main-third':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Verify if main has suggestions
                    if (typeof Main.suggestions_list[2] !== 'undefined') {

                        // List all suggestions
                        for (var s = 0; s < Main.suggestions_list.length; s++) {

                            if (s === 2) {
                                continue;
                            }

                            // Add suggestions
                            new_array.push(Main.suggestions_list[s]);

                        }

                        // Set new data
                        Main.suggestions_list = new_array;

                    }

                }

                break;

            case 'main-fourth':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Verify if main has suggestions
                    if (typeof Main.suggestions_list[3] !== 'undefined') {

                        // List all suggestions
                        for (var s = 0; s < Main.suggestions_list.length; s++) {

                            if (s === 3) {
                                continue;
                            }

                            // Add suggestions
                            new_array.push(Main.suggestions_list[s]);

                        }

                        // Set new data
                        Main.suggestions_list = new_array;

                    }

                }

                break;

            case 'second-level':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {
                    
                    // Default column value
                    var column = 0;

                    // Default parent value
                    var parent = 'col-12'; 

                    if ( $('.main .suggestions-body-area').hasClass('cols-3') ) {

                        parent = 'col-3';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-3' ) {
                            column = 1;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-3' ) {
                            column = 2;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'fourth-3' ) {
                            column = 3;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-4') ) {

                        parent = 'col-4';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-4' ) {
                            column = 1;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-4' ) {
                            column = 2;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-6') ) {

                        parent = 'col-6';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-6' ) {
                            column = 1;
                        }

                    }

                    // Get Row
                    var row = $(this).closest('.suggestions-body-area').find('.' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').index($(this).closest('.row'));

                    // List all suggestions
                    for ( var s = 0; s < Main.suggestions_list.length; s++ ) {

                        if ( s === column ) {

                            // Verify if the suggestion has childrens
                            if ( typeof Main.suggestions_list[s].suggestions !== 'undefined' ) {

                                // Suggestion array
                                var suggestion = [];    

                                // List suggestion's childrens
                                for ( var c = 0; c < Main.suggestions_list[s].suggestions.length; c++ ) {

                                    if ( row === (c + 1) ) {
                                        continue;
                                    }

                                    // Add childrens to parent
                                    suggestion.push(Main.suggestions_list[s].suggestions[c]);

                                }

                                // Create parent array
                                var parent_array = [];

                                // Set header
                                parent_array['header'] = Main.suggestions_list[s]['header'];

                                // Set body
                                parent_array['body'] = Main.suggestions_list[s]['body'];

                                // Set type
                                parent_array['type'] = Main.suggestions_list[s]['type'];

                                if ( suggestion.length > 0 ) {

                                    // Set suggestions
                                    parent_array['suggestions'] = suggestion;

                                }

                                // Add suggestions
                                new_array.push(parent_array);

                            }

                        } else {

                            // Add suggestions
                            new_array.push(Main.suggestions_list[s]);

                        }

                    }

                    // Set new data
                    Main.suggestions_list = new_array;                

                }

                break;

            case 'third-level':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {
                    
                    // Default column value
                    var column = 0;

                    // Default parent value
                    var parent = 'col-12'; 

                    if ( $('.main .suggestions-body-area').hasClass('cols-3') ) {

                        parent = 'col-3';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-3' ) {
                            column = 1;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-3' ) {
                            column = 2;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'fourth-3' ) {
                            column = 3;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-4') ) {

                        parent = 'col-4';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-4' ) {
                            column = 1;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-4' ) {
                            column = 2;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-6') ) {

                        parent = 'col-6';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-6' ) {
                            column = 1;
                        }

                    }

                    // Get Row
                    var row = $(this).closest('.suggestions-body-area').find('.' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').index($(this).closest('.col-12').parent());

                    // List all suggestions
                    for ( var s = 0; s < Main.suggestions_list.length; s++ ) {

                        if ( s === column ) {

                            // Verify if the suggestion has childrens
                            if ( typeof Main.suggestions_list[s].suggestions !== 'undefined' ) {

                                // Suggestions array
                                var suggestions = [];    

                                // List suggestion's in second level
                                for ( var c = 0; c < Main.suggestions_list[s].suggestions.length; c++ ) {

                                    // Second level array
                                    var second_level = [];

                                    // Set header
                                    second_level['header'] = Main.suggestions_list[s].suggestions[c]['header'];

                                    // Set body
                                    second_level['body'] = Main.suggestions_list[s].suggestions[c]['body'];

                                    // Set type
                                    second_level['type'] = Main.suggestions_list[s].suggestions[c]['type'];                                    

                                    if ( row === (c + 1) ) {

                                        if ($(this).closest('.col-9').length > 0) {

                                            // Set level
                                            var level = $('.main .' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').index($(this).closest('.col-12').parent());

                                            // Third level
                                            var t_level = $('.main .' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').eq(level).find('.col-12 > .row').index($(this).closest('.col-9').parent());

                                            // Verify if third level exists
                                            if ( typeof Main.suggestions_list[s].suggestions[c].suggestions !== 'undefined' ) {

                                                // Third level array
                                                var third_level = [];
                                                
                                                // List suggestions in the third level
                                                for ( var t = 0; t < Main.suggestions_list[s].suggestions[c].suggestions.length; t++ ) {

                                                    if ( t === t_level ) {
                                                        continue;
                                                    }

                                                    // Set suggestions from the third level to second level
                                                    third_level.push(Main.suggestions_list[s].suggestions[c].suggestions[t]);

                                                }

                                                // Verify if third level exists
                                                if ( third_level.length > 0 ) {

                                                    // Set third level
                                                    second_level['suggestions'] = third_level;

                                                }
                                                
                                            }

                                            // Add second level to main
                                            suggestions.push(second_level);

                                        }

                                        continue;

                                    }

                                    // Add second level to main
                                    suggestions.push(second_level);

                                }

                                // Create parent array
                                var parent_array = [];

                                // Set header
                                parent_array['header'] = Main.suggestions_list[s]['header'];

                                // Set body
                                parent_array['body'] = Main.suggestions_list[s]['body'];

                                // Set type
                                parent_array['type'] = Main.suggestions_list[s]['type'];

                                if ( suggestions.length > 0 ) {

                                    // Set suggestions
                                    parent_array['suggestions'] = suggestions;

                                }

                                // Add suggestions
                                new_array.push(parent_array);

                            }

                        } else {

                            // Add suggestions
                            new_array.push(Main.suggestions_list[s]);

                        }

                    }

                    // Set new data
                    Main.suggestions_list = new_array;                

                }

                break;

            case 'fourth-level':

                // Verify if the group has suggestions
                if (typeof Main.suggestions_list !== 'undefined') {
                    
                    // Default column value
                    var column = 0;

                    // Default parent value
                    var parent = 'col-12'; 

                    if ( $('.main .suggestions-body-area').hasClass('cols-3') ) {

                        parent = 'col-3';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-3' ) {
                            column = 1;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-3' ) {
                            column = 2;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'fourth-3' ) {
                            column = 3;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-4') ) {

                        parent = 'col-4';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-4' ) {
                            column = 1;
                        } else if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'third-4' ) {
                            column = 2;
                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-6') ) {

                        parent = 'col-6';

                        if ( $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') === 'second-6' ) {
                            column = 1;
                        }

                    }

                    // Get parent row
                    var parent_row = $(this).closest('.suggestions-body-area').find('.' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').index($(this).closest('.col-9 ').closest('.col-12').parent());

                    // Get row
                    var row = $(this).closest('.suggestions-body-area').find('.' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').eq(parent_row).find('.col-9 > .row').index($(this).closest('.row'));


                    // List all suggestions
                    for ( var s = 0; s < Main.suggestions_list.length; s++ ) {

                        if ( s === column ) {

                            // Verify if the suggestion has childrens
                            if ( typeof Main.suggestions_list[s].suggestions !== 'undefined' ) {

                                // Suggestions array
                                var suggestions = [];    

                                // List suggestion's in second level
                                for ( var c = 0; c < Main.suggestions_list[s].suggestions.length; c++ ) {

                                    // Second level array
                                    var second_level = [];

                                    // Set header
                                    second_level['header'] = Main.suggestions_list[s].suggestions[c]['header'];

                                    // Set body
                                    second_level['body'] = Main.suggestions_list[s].suggestions[c]['body'];

                                    // Set type
                                    second_level['type'] = Main.suggestions_list[s].suggestions[c]['type'];                                    

                                    if ( parent_row === (c + 1) ) {

                                        if ($(this).closest('.col-9').length > 0) {

                                            // Set level
                                            var level = $('.main .' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').index($(this).closest('.col-12').parent());

                                            // Third level
                                            var t_level = $('.main .' + $(this).closest('.' + parent).attr('class').replace(parent + ' ', '') + ' > .row').eq(level).find('.col-12 > .row').index($(this).closest('.col-9').parent());

                                            // Verify if third level exists
                                            if ( typeof Main.suggestions_list[s].suggestions[c].suggestions !== 'undefined' ) {

                                                // Third level array
                                                var third_level = [];
                                                
                                                // List suggestions in the third level
                                                for ( var t = 0; t < Main.suggestions_list[s].suggestions[c].suggestions.length; t++ ) {

                                                    if ( typeof Main.suggestions_list[s].suggestions[c].suggestions[t].suggestions !== 'undefined' ) {

                                                        // Fourth level array
                                                        var fourth_level = [];

                                                        // Set header
                                                        fourth_level['header'] = Main.suggestions_list[s].suggestions[c].suggestions[t]['header'];

                                                        // Set body
                                                        fourth_level['body'] = Main.suggestions_list[s].suggestions[c].suggestions[t]['body'];

                                                        // Set type
                                                        fourth_level['type'] = Main.suggestions_list[s].suggestions[c].suggestions[t]['type'];

                                                        // Fourth level suggestions array
                                                        var fourth_suggestions_array = [];

                                                        for (var su = 0; su < Main.suggestions_list[s].suggestions[c].suggestions[t].suggestions.length; su++) {

                                                            if (su === row) {
                                                                continue;
                                                            }

                                                            // Set suggestions from the fourth level to fourth level
                                                            fourth_suggestions_array.push(Main.suggestions_list[s].suggestions[c].suggestions[t].suggestions[su]);

                                                        }

                                                        // Verify if fourth level exists
                                                        if (fourth_suggestions_array.length > 0) {

                                                            // Set fourth level
                                                            fourth_level['suggestions'] = fourth_suggestions_array;

                                                        }

                                                        // Set suggestions from the third level to second level
                                                        third_level.push(fourth_level);

                                                    } else {

                                                        // Set suggestions from the third level to second level
                                                        third_level.push(Main.suggestions_list[s].suggestions[c].suggestions[t]);

                                                    }

                                                }

                                                // Verify if third level exists
                                                if ( third_level.length > 0 ) {

                                                    // Set third level
                                                    second_level['suggestions'] = third_level;

                                                }
                                                
                                            }

                                            // Add second level to main
                                            suggestions.push(second_level);

                                        }

                                        continue;

                                    }

                                    // Add second level to main
                                    suggestions.push(second_level);

                                }

                                // Create parent array
                                var parent_array = [];

                                // Set header
                                parent_array['header'] = Main.suggestions_list[s]['header'];

                                // Set body
                                parent_array['body'] = Main.suggestions_list[s]['body'];

                                // Set type
                                parent_array['type'] = Main.suggestions_list[s]['type'];

                                if ( suggestions.length > 0 ) {

                                    // Set suggestions
                                    parent_array['suggestions'] = suggestions;

                                }

                                // Add suggestions
                                new_array.push(parent_array);

                            }

                        } else {

                            // Add suggestions
                            new_array.push(Main.suggestions_list[s]);

                        }

                    }


                    // Set new data
                    Main.suggestions_list = new_array;                

                }

                break;

        }

        // Verify if the suggestions exists
        if ( new_array.length > 0 ) {

            // Order suggestions
            Main.order_suggestions(Main.suggestions_list);

        } else {

            // Empty main's level suggestions
            $('.main .suggestions-body-area > .row').eq(1).empty();

            // Delete the suggestions list
            delete Main.suggestions_list;

            $('.main .suggestions-body-area').removeClass('cols-3');
            $('.main .suggestions-body-area').removeClass('cols-4');
            $('.main .suggestions-body-area').removeClass('cols-6');
            $('.main .suggestions-body-area').addClass('cols-12');

            // Start to create html
            var html = '<div class="col-12 first-12">'
                + '<div class="row">'
                    + '<div class="col-12">'
                        + '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">'
                            + '<div class="line-main-left-bottom line-main-left-bottom-first"></div>'
                                + '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">'
                                    + '<div class="toast-body text-center">'
                                        + words.no_suggestions_found
                                    + '</div>'
                                + '</div>'
                            + '</div>'
                        + '</div>'
                    + '</div>'
                + '</div>';

            // Display main's level suggestion
            $('.main .suggestions-body-area > .row').eq(1).append(html);

        }

    });

    /*******************************
    RESPONSES
    ********************************/

    /*
     * Display suggestions in a diagram
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.display_suggestions = function (status, data) {

        // Verify if is edge
        if ( navigator.userAgent.search('Edg') ) {

            $('.main .suggestions-body-area').show();

        }

        // Verify if the success response exists
        if (status === 'success') {

            // Add object in the queue
            Main.suggestions_list = Main.to_array(data.suggestions);

            // Order suggestions
            Main.order_suggestions(Main.suggestions_list);

        } else {

            $('.main .suggestions-body-area').removeClass('cols-3');
            $('.main .suggestions-body-area').removeClass('cols-4');
            $('.main .suggestions-body-area').removeClass('cols-6');
            $('.main .suggestions-body-area').addClass('cols-12');

            // Start to create html
            var html = '<div class="col-12 first-12">'
                + '<div class="row">'
                    + '<div class="col-12">'
                        + '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">'
                            + '<div class="line-main-left-bottom line-main-left-bottom-first"></div>'
                                + '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">'
                                    + '<div class="toast-body text-center">'
                                        + words.no_suggestions_found
                                    + '</div>'
                                + '</div>'
                            + '</div>'
                        + '</div>'
                    + '</div>'
                + '</div>';

            // Display main's level suggestion
            $('.main .suggestions-body-area > .row').eq(1).append(html);

        }

    };

    /*
     * Display the category creation response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.create_category = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Get Categories List By Page
            Main.get_categories_by_page(1);

            // Get ALL Categories
            Main.get_all_categories();

            // Reset the form
            $('.main .chatbot-create-category')[0].reset();

        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }

    };

    /*
     * Display the category delete response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.delete_category = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Get Categories List By Page
            Main.get_categories_by_page(1);

            // Get ALL Categories
            Main.get_all_categories();

        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }

    };

    /*
     * Display the categories
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.get_categories_by_page = function (status, data) {

        // Hide pagination
        $('.main #categories-manager .fieldset-pagination').hide();

        // Verify if the success response exists
        if (status === 'success') {

            // Show pagination
            $('.main #categories-manager .fieldset-pagination').fadeIn('slow');

            // Display the pagination
            Main.pagination.page = data.page;
            Main.show_pagination('.main #categories-manager', data.total);

            // Categories var
            var categories = '';

            // List 10 categories
            for (var c = 0; c < data.categories.length; c++) {

                categories += '<li>'
                                + '<div class="row">'
                                    + '<div class="col-10">'
                                        + '<i class="far fa-bookmark"></i>'
                                        + data.categories[c].name
                                    + '</div>'
                                    + '<div class="col-2 text-right">'
                                        + '<button type="button" class="delete-category" data-id="' + data.categories[c].category_id + '">'
                                            + '<i class="icon-trash"></i>'
                                        + '</button>'
                                    + '</div>'
                                + '</div>'
                            + '</li>';

            }

            // Display categories
            $('.main #categories-manager .all-categories').html(categories);

        } else {

            // No categories
            var message = '<li>'
                            + '<div class="row">'
                                + '<div class="col-10">'
                                    + data.message
                                + '</div>'
                            + '</div>'
                        + '</li>';

            // Display no categories message
            $('.main #categories-manager .all-categories').html(message);

        }

    };

    /*
     * Display the categories
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.get_all_categories = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Categories var
            var categories = '';

            // List 10 categories
            for (var c = 0; c < data.categories.length; c++) {

                // Selected
                var selected = '';

                // Verify if the category is selected
                if ( $('.main .suggestions-categories .panel-heading .select-category[data-id="' + data.categories[c].category_id + '"]').length > 0 ) {
                    selected = ' selected-category';
                }
                
                // Add category to list
                categories += '<button class="btn btn-primary select-category' + selected + '" type="button" data-id="' + data.categories[c].category_id + '">'
                                + '<i class="far fa-bookmark"></i>'
                                + data.categories[c].name
                            + '</button>';

            }

            // Display categories
            $('.main .all-categories-list').html(categories);

        } else {

            // No categories
            var message = '<div class="row">'
                            + '<div class="col-10">'
                                + data.message
                            + '</div>'
                        + '</div>';

            // Display no categories message
            $('.main .all-categories-list').html(message);

        }

    };

    /*
     * Display suggestions saving response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.8.0
     */
    Main.methods.save_suggestions = function (status, data) {

        // Verify if the success response exists
        if (status === 'success') {

            // Display alert
            Main.popup_fon('subi', data.message, 1500, 2000);

            // Redirect
            setTimeout(function(){

                // If group's id exists
                if ( !$('.main .chatbot-page-suggestions').attr('data-group') ) {

                    // Redirect to the group's page
                    document.location.href = url + 'user/app/chatbot?p=suggestions&group=' + data.group_id;

                }

            }, 2000);

        } else {

            // Display alert
            Main.popup_fon('sube', data.message, 1500, 2000);

        }

    };

    /*******************************
    FORMS
    ********************************/

    /*
     * Save Suggestions Group
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('submit', '.main .save-suggestions-form', function (e) {
        e.preventDefault();

        // Get the group's name
        var group_name = $(this).find('.group-name').val();

        // Get categories
        var selected_categories = $('.main .suggestions-categories .panel-heading .select-category');

        // Categories
        var categories = [];    

        // List all categories
        if ( selected_categories.length > 0 ) {

            // List all categories
            for ( var d = 0; d < selected_categories.length; d++ ) {

                // Set category
                categories.push($(selected_categories[d]).attr('data-id'));

            }

            // Turn categories to object
            categories = Main.to_object(categories);

        }

        // Verify if suggestions list exists
        if (typeof Main.suggestions_list !== 'undefined') {

            // Get suggestions list
            var suggestions = Main.to_object(Main.suggestions_list);

        } else {

            // Default value
            var suggestions = [];

        }

        // Create an object with form data
        var data = {
            action: 'save_suggestions',
            group_name: group_name,
            suggestions: suggestions,
            categories: categories
        };

        // If group's id exists
        if ( $('.main .chatbot-page-suggestions').attr('data-group') ) {
            data['group_id'] = $('.main .chatbot-page-suggestions').attr('data-group');
        }

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'save_suggestions');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Save Category
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('submit', '.main .chatbot-create-category', function (e) {
        e.preventDefault();

        // Get the category's name
        var category_name = $(this).find('.category-name').val();

        // Create an object with form data
        var data = {
            action: 'create_category',
            category_name: category_name
        };

        // Set CSRF
        data[$(this).attr('data-csrf')] = $('input[name="' + $(this).attr('data-csrf') + '"]').val();

        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/chatbot', 'POST', data, 'create_category');

        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

    /*
     * Save Template
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $(document).on('submit', '.main .chatbot-save-template', function (e) {
        e.preventDefault();

        // Verify if the template has filled the required fields
        if ( $('.main #template-preview > .active').attr('data-type') === 'generic-template' ) {

            // Verify if the template's title exists
            if ( $('.main #template-preview > .active').find('.template-title').val() === '' ) {

                // Display error alert
                Main.popup_fon('sube', words.template_title_required, 1500, 2000);

                return;

            }

            // Verify if the template's subtitle exists
            if ( $('.main #template-preview > .active').find('.template-subtitle').val() === '' ) {

                // Display error alert
                Main.popup_fon('sube', words.template_subtitle_required, 1500, 2000);

                return;

            }

            // Verify if the template's url exists
            if ( $('.main #template-preview > .active').find('.template-main-url').val().match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g) === null ) {

                // Display error alert
                Main.popup_fon('sube', words.template_url_required, 1500, 2000);

                return;

            }
          
            // Verify if the template's first collapse is open
            if ( ( $('.main #template-preview > .active a[href="#generic-template-button-1"]').attr('aria-expanded') === 'false' ) || ( $('.main #template-preview > .active #generic-template-button-1 .collapse.show').length < 1 ) ) {

                // Display error alert
                Main.popup_fon('sube', words.template_requires_at_least_one_button, 1500, 2000);

                return;

            }
            
            // Verify if the template's first button is configured
            if ( $('.main #template-preview > .active #generic-template-button-1 .collapse.show').attr('id') === 'generic-template-button-1-menu-link' ) {

                // Verify if the button title and url is not empty
                if ( ( $('.main #template-preview > .active #generic-template-button-1 .collapse.show').find('.template-button-title').val() === '' ) || ( $('.main #template-preview > .active #generic-template-button-1 .collapse.show').find('.template-button-link').val() === '' ) ) {

                    // Display error alert
                    Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                    return;

                }                

            }

            // Verify if the template's first button is configured
            if ( $('.main #template-preview > .active #generic-template-button-1 .collapse.show').attr('id') === 'generic-template-button-1-menu-suggestions' ) {

                // Verify if the button suggestions has title
                if ( $('.main #template-preview > .active #generic-template-button-1 .collapse.show').find('.template-button-title').val() === '' ) {

                    // Display error alert
                    Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                    return;

                }                

            }

            // Verify if the template's second collapse is open
            if ( $('.main #template-preview > .active a[href="#generic-template-button-2"]').attr('aria-expanded') === 'true' ) {

                // Verify if the template's second button is configured
                if ($('.main #template-preview > .active #generic-template-button-2 .collapse.show').attr('id') === 'generic-template-button-2-menu-link') {

                    // Verify if the button title and url is not empty
                    if (($('.main #template-preview > .active #generic-template-button-2 .collapse.show').find('.template-button-title').val() === '') || ($('.main #template-preview > .active #generic-template-button-2 .collapse.show').find('.template-button-link').val() === '')) {

                        // Display error alert
                        Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                        return;

                    }

                }

                // Verify if the template's second button is configured
                if ($('.main #template-preview > .active #generic-template-button-2 .collapse.show').attr('id') === 'generic-template-button-2-menu-suggestions') {

                    // Verify if the button suggestions has title
                    if ($('.main #template-preview > .active #generic-template-button-2 .collapse.show').find('.template-button-title').val() === '') {

                        // Display error alert
                        Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                        return;

                    }

                }

            }

            // Verify if the template's third collapse is open
            if ( $('.main #template-preview > .active a[href="#generic-template-button-3"]').attr('aria-expanded') === 'true' ) {

                // Verify if the template's second collapse is open
                if ($('.main #template-preview > .active a[href="#generic-template-button-2"]').attr('aria-expanded') === 'false') {

                    // Display error alert
                    Main.popup_fon('sube', words.please_consigure_buttons_correct_order, 1500, 2000);

                    return;                    

                }

                // Verify if the template's third button is configured
                if ($('.main #template-preview > .active #generic-template-button-3 .collapse.show').attr('id') === 'generic-template-button-3-menu-link') {

                    // Verify if the button title and url is not empty
                    if (($('.main #template-preview > .active #generic-template-button-3 .collapse.show').find('.template-button-title').val() === '') || ($('.main #template-preview > .active #generic-template-button-3 .collapse.show').find('.template-button-link').val() === '')) {

                        // Display error alert
                        Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                        return;

                    }

                }

                // Verify if the template's first button is configured
                if ($('.main #template-preview > .active #generic-template-button-3 .collapse.show').attr('id') === 'generic-template-button-3-menu-suggestions') {

                    // Verify if the button suggestions has title
                    if ($('.main #template-preview > .active #generic-template-button-3 .collapse.show').find('.template-button-title').val() === '') {

                        // Display error alert
                        Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                        return;

                    }

                }

            }

        } else if ( $('.main #template-preview > .active').attr('data-type') === 'media-template' ) {

            // Verify if an image was uploaded
            if ( !$('.main #template-preview > .active #media-template .drag-and-drop-files').attr('data-media') ) {

                // Display error alert
                Main.popup_fon('sube', words.please_upload_an_image, 1500, 2000);

                return;                

            }
          
            // Verify if the template's first collapse is open
            if ( ( $('.main #template-preview > .active a[href="#media-template-button-1"]').attr('aria-expanded') === 'false' ) || ( $('.main #template-preview > .active #media-template-button-1 .collapse.show').length < 1 ) ) {

                // Display error alert
                Main.popup_fon('sube', words.template_requires_at_least_one_button, 1500, 2000);

                return;

            }
            
            // Verify if the template's first button is configured
            if ( $('.main #template-preview > .active #media-template-button-1 .collapse.show').attr('id') === 'media-template-button-1-menu-link' ) {

                // Verify if the button title and url is not empty
                if ( ( $('.main #template-preview > .active #media-template-button-1 .collapse.show').find('.template-button-title').val() === '' ) || ( $('.main #template-preview > .active #media-template-button-1 .collapse.show').find('.template-button-link').val() === '' ) ) {

                    // Display error alert
                    Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                    return;

                }                

            }

            // Verify if the template's first button is configured
            if ( $('.main #template-preview > .active #media-template-button-1 .collapse.show').attr('id') === 'media-template-button-1-menu-suggestions' ) {

                // Verify if the button suggestions has title
                if ( $('.main #template-preview > .active #media-template-button-1 .collapse.show').find('.template-button-title').val() === '' ) {

                    // Display error alert
                    Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                    return;

                }

            }

        } else if ( $('.main #template-preview > .active').attr('data-type') === 'button-template' ) {

            // Verify if the template's title exists
            if ( $('.main #template-preview > .active').find('.template-title').val() === '' ) {

                // Display error alert
                Main.popup_fon('sube', words.template_title_required, 1500, 2000);

                return;

            }
          
            // Verify if the template's first collapse is open
            if ( ( $('.main #template-preview > .active a[href="#button-template-button-1"]').attr('aria-expanded') === 'false' ) || ( $('.main #template-preview > .active #button-template-button-1 .collapse.show').length < 1 ) ) {

                // Display error alert
                Main.popup_fon('sube', words.template_requires_at_least_one_button, 1500, 2000);

                return;

            }
            
            // Verify if the template's first button is configured
            if ( $('.main #template-preview > .active #button-template-button-1 .collapse.show').attr('id') === 'button-template-button-1-menu-link' ) {

                // Verify if the button title and url is not empty
                if ( ( $('.main #template-preview > .active #button-template-button-1 .collapse.show').find('.template-button-title').val() === '' ) || ( $('.main #template-preview > .active #button-template-button-1 .collapse.show').find('.template-button-link').val() === '' ) ) {

                    // Display error alert
                    Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                    return;

                }                

            }

            // Verify if the template's first button is configured
            if ( $('.main #template-preview > .active #button-template-button-1 .collapse.show').attr('id') === 'button-template-button-1-menu-suggestions' ) {

                // Verify if the button suggestions has title
                if ( $('.main #template-preview > .active #button-template-button-1 .collapse.show').find('.template-button-title').val() === '' ) {

                    // Display error alert
                    Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                    return;

                }                

            }

            // Verify if the template's second collapse is open
            if ( $('.main #template-preview > .active a[href="#button-template-button-2"]').attr('aria-expanded') === 'true' ) {

                // Verify if the template's second button is configured
                if ($('.main #template-preview > .active #button-template-button-2 .collapse.show').attr('id') === 'button-template-button-2-menu-link') {

                    // Verify if the button title and url is not empty
                    if (($('.main #template-preview > .active #button-template-button-2 .collapse.show').find('.template-button-title').val() === '') || ($('.main #template-preview > .active #button-template-button-2 .collapse.show').find('.template-button-link').val() === '')) {

                        // Display error alert
                        Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                        return;

                    }

                }

                // Verify if the template's second button is configured
                if ($('.main #template-preview > .active #button-template-button-2 .collapse.show').attr('id') === 'button-template-button-2-menu-suggestions') {

                    // Verify if the button suggestions has title
                    if ($('.main #template-preview > .active #button-template-button-2 .collapse.show').find('.template-button-title').val() === '') {

                        // Display error alert
                        Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                        return;

                    }

                }

            }

            // Verify if the template's third collapse is open
            if ( $('.main #template-preview > .active a[href="#button-template-button-3"]').attr('aria-expanded') === 'true' ) {

                // Verify if the template's second collapse is open
                if ($('.main #template-preview > .active a[href="#button-template-button-2"]').attr('aria-expanded') === 'false') {

                    // Display error alert
                    Main.popup_fon('sube', words.please_consigure_buttons_correct_order, 1500, 2000);

                    return;                    

                }

                // Verify if the template's third button is configured
                if ($('.main #template-preview > .active #button-template-button-3 .collapse.show').attr('id') === 'button-template-button-3-menu-link') {

                    // Verify if the button title and url is not empty
                    if (($('.main #template-preview > .active #button-template-button-3 .collapse.show').find('.template-button-title').val() === '') || ($('.main #template-preview > .active #button-template-button-3 .collapse.show').find('.template-button-link').val() === '')) {

                        // Display error alert
                        Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                        return;

                    }

                }

                // Verify if the template's first button is configured
                if ($('.main #template-preview > .active #button-template-button-3 .collapse.show').attr('id') === 'button-template-button-3-menu-suggestions') {

                    // Verify if the button suggestions has title
                    if ($('.main #template-preview > .active #button-template-button-3 .collapse.show').find('.template-button-title').val() === '') {

                        // Display error alert
                        Main.popup_fon('sube', words.please_enter_correct_data_for_your_button, 1500, 2000);

                        return;

                    }

                }

            }

        }

        // Get all panels
        var panels = $('.main #template-preview > .active > .panel-group > .panel');

        // Verify if at least one panel was found
        if (panels.length > 0) {

            // Default suggestions array
            var suggestions = [];

            // Default header array
            var header = [];

            // List all panels
            for (var t = 0; t < panels.length; t++) {

                // Get target
                var target = $(panels[t]).attr('data-target');

                // Add suggestions to the list
                switch (target) {

                    case 'header':

                        // Create a new array
                        header['header'] = [];

                        // Get forms inputs
                        var inputs = $(panels[t]).find('.form-control');

                        // Verify if inputs exists
                        if (inputs.length > 0) {

                            // List all inputs
                            for (var i = 0; i < inputs.length; i++) {
                                header['header'][$(inputs[i]).attr('name')] = $(inputs[i]).val();
                            }

                        }

                        // Get medias
                        var medias = $(panels[t]).find('.drag-and-drop-files');

                        // Verify if media exists
                        if (medias.length > 0) {

                            // List all medias
                            for (var m = 0; m < medias.length; m++) {

                                // Verify if a media exists
                                if ($(medias[m]).attr('data-media')) {
                                    header['header']['cover_id'] = $(medias[m]).attr('data-media');
                                    header['header']['cover_src'] = $(medias[m]).find('img').attr('src');
                                }

                            }

                        }

                        break;

                    case 'option-1':
                    case 'option-2':
                    case 'option-3':
                    case 'option-4':

                        // Decrease t
                        var m = t - 1;

                        // Verify if option is used
                        if (!$(panels[t]).find('.panel-collapse').hasClass('show')) {
                            continue;
                        }

                        // If suggestions[t] is object
                        if (typeof suggestions[m] !== 'object') {
                            suggestions[m] = [];
                        }

                        // Get forms inputs
                        var inputs = $(panels[t]).find('.collapse.show .form-control');

                        // Verify if inputs exists
                        if (inputs.length > 0) {

                            // All_inputs
                            var all_inputs = [];

                            // List all inputs
                            for (var i = 0; i < inputs.length; i++) {

                                if ($(inputs[i]).val()) {
                                    all_inputs[$(inputs[i]).attr('name')] = $(inputs[i]).val();
                                }

                            }

                            // Verify if inputs exists
                            if (!all_inputs) {
                                continue;
                            } else {
                                all_inputs['type'] = $(panels[t]).find('.card .collapse.show').attr('data-type');
                            }

                            // If suggestions[t]['header'] is object
                            if (typeof suggestions[m]['header'] !== 'object') {
                                suggestions[m]['header'] = [];
                            }

                            // If suggestions[t]['suggestions'] is object
                            if (typeof suggestions[m]['body'] !== 'object') {
                                suggestions[m]['body'] = [];
                            }

                            // If suggestions[m]['type'] is object
                            if (typeof suggestions[m]['type'] !== 'object') {
                                suggestions[m]['type'] = [];
                            }

                            // Set header
                            suggestions[m]['header'].push(header);

                            // Set body
                            suggestions[m]['body'].push(all_inputs);

                            // Set type
                            suggestions[m]['type'] = $(panels[t]).closest('.template-model').attr('data-type');

                        }

                        break;

                }

            }

            // Verify if suggestions exists
            if (suggestions) {

                // Verify if Main.suggestions_list already exists
                if (typeof Main.suggestions_list !== 'undefined') {

                    // Default column position
                    var column = 0;

                    // Default level position
                    var level = 0;

                    // Default third level position
                    var t_level = -1;

                    // Try to detect the position of the column
                    if ( $('.main .suggestions-body-area').hasClass('cols-6') ) {

                        if ( $('.main .active-manage-suggestions').closest('.second-6').length > 0 ) {

                            // Set column
                            column = 1;

                            if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                                // Set level
                                level = $('.suggestions-body-area .second-6 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());                                

                                t_level = $('.suggestions-body-area .second-6 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                            } else {

                                // Set level
                                level = $('.main .second-6 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                            }

                        } else {

                            if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                                // Set level
                                level = $('.suggestions-body-area .first-6 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());

                                t_level = $('.suggestions-body-area .first-6 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                            } else {

                                // Set level
                                level = $('.main .first-6 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                            }

                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-4') ) {


                        if ( $('.main .active-manage-suggestions').closest('.first-4').length > 0 ) {

                            // Set column
                            column = 0;

                            if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                                // Set level
                                level = $('.suggestions-body-area .first-4 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());                                

                                t_level = $('.suggestions-body-area .first-4 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                            } else {

                                // Set level
                                level = $('.main .first-4 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                            }

                        } else if ( $('.main .active-manage-suggestions').closest('.second-4').length > 0 ) {

                            // Set column
                            column = 1;

                            if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                                // Set level
                                level = $('.suggestions-body-area .second-4 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());                                

                                t_level = $('.suggestions-body-area .second-4 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                            } else {

                                // Set level
                                level = $('.main .second-4 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                            }

                        } else if ( $('.main .active-manage-suggestions').closest('.third-4').length > 0 )  {

                            // Set column
                            column = 2;

                            if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                                // Set level
                                level = $('.suggestions-body-area .third-4 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());

                                t_level = $('.suggestions-body-area .third-4 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                            } else {

                                // Set level
                                level = $('.main .third-4 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                            }

                        }

                    } else if ( $('.main .suggestions-body-area').hasClass('cols-3') ) {

                        if ( $('.main .active-manage-suggestions').closest('.second-3').length > 0 ) {

                            // Set column
                            column = 1;

                            if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                                // Set level
                                level = $('.suggestions-body-area .second-3 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());                                

                                t_level = $('.suggestions-body-area .second-3 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                            } else {

                                // Set level
                                level = $('.main .second-3 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                            }

                        } else if ( $('.main .active-manage-suggestions').closest('.third-3').length > 0 ) {

                            // Set column
                            column = 2;

                            if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                                // Set level
                                level = $('.suggestions-body-area .third-3 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());

                                t_level = $('.suggestions-body-area .third-3 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                            } else {

                                // Set level
                                level = $('.main .third-3 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                            }

                        } else if ( $('.main .active-manage-suggestions').closest('.fourth-3').length > 0 ) {

                            // Set column
                            column = 3;

                            if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                                // Set level
                                level = $('.suggestions-body-area .fourth-3 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());

                                t_level = $('.suggestions-body-area .fourth-3 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                            } else {

                                // Set level
                                level = $('.main .fourth-3 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                            }

                        }

                    } else {

                        if ( $('.main .active-manage-suggestions').closest('.col-9').length > 0 ) {

                            // Set level
                            level = $('.suggestions-body-area .first-12 > .row').index($('.main .active-manage-suggestions').closest('.col-12').parent());

                            t_level = $('.suggestions-body-area .first-12 > .row').eq(level).find('.col-12 > .row').index($('.main .active-manage-suggestions').closest('.col-9').parent());


                        } else {

                            // Set level
                            level = $('.main .first-12 > .row').index($('.main .active-manage-suggestions').closest('.row'));

                        }

                    }

                    // Verify if is the first level of suggestions
                    if ($('.main .active-manage-suggestions').closest('.toast').attr('role') === 'main-list') {

                        // Get the suggestion's list
                        var current_list = Main.suggestions_list;

                        // New suggestion list
                        var new_suggestions = [];

                        // List all suggestions
                        for (var l = 0; l < suggestions.length; l++) {

                            // New array
                            var new_array = [];

                            // Set header
                            new_array['header'] = suggestions[l]['header'];

                            // Set body
                            new_array['body'] = suggestions[l]['body'];

                            // Set type
                            new_array['type'] = suggestions[l]['type'];

                            if (typeof current_list[l] !== 'undefined') {

                                // Verify if suggestions exists
                                if (typeof current_list[l]['suggestions'] !== 'undefined') {

                                    // Set suggestions
                                    new_array['suggestions'] = current_list[l]['suggestions'];

                                }

                            }

                            // Add suggestions
                            new_suggestions.push(new_array);

                        }

                        // Set new data
                        Main.suggestions_list = new_suggestions;

                    } else {

                        // Get the suggestion's list
                        var current_list = Main.suggestions_list;

                        // New suggestion list
                        var new_suggestions = [];

                        // List all suggestions
                        for (var l = 0; l < current_list.length; l++) {

                            // New array
                            var new_array = [];

                            // Set header
                            new_array['header'] = current_list[l]['header'];

                            // Set body
                            new_array['body'] = current_list[l]['body'];

                            // Set type
                            new_array['type'] = current_list[l]['type'];

                            // Verify if suggestions exists
                            if (typeof current_list[l]['suggestions'] !== 'undefined') {

                                if ( (l === column) && (level === 0) ) {

                                    // Set suggestions
                                    new_array['suggestions'] = suggestions;

                                } else if ( (l === column) && (level > 0) ) {

                                    // Set suggestions
                                    new_array['suggestions'] = current_list[l]['suggestions'];
                                    
                                    // List suggestions
                                    for ( var s = 0; s < current_list[l]['suggestions'].length; s++ ) {

                                        if ( ( (s + 1) === level ) && ( t_level > -1 ) ) {

                                            if ( typeof new_array['suggestions'][s]['suggestions'][t_level]['suggestions'] !== 'undefined' ) {
                                                new_array['suggestions'][s]['suggestions'][t_level]['suggestions'] = suggestions;
                                            } else {
                                                new_array['suggestions'][s]['suggestions'][t_level]['suggestions'] = suggestions;
                                            }

                                        } else if ( (s + 1) === level ) {

                                            new_array['suggestions'][s]['suggestions'] = suggestions;

                                        }

                                    }

                                } else if ( ( l === column ) && ( level < 0 ) ) {

                                    // Set suggestions
                                    new_array['suggestions'] = current_list[l]['suggestions'];

                                } else {

                                    // Set suggestions
                                    new_array['suggestions'] = current_list[l]['suggestions'];

                                }

                            } else {

                                if ( l === column ) {

                                    // Set suggestions
                                    new_array['suggestions'] = suggestions;

                                }

                            }

                            // Add suggestions
                            new_suggestions.push(new_array);

                        }

                        // Set new data
                        Main.suggestions_list = new_suggestions;

                    }

                } else {

                    // Add object in the queue
                    Main.suggestions_list = suggestions;

                }

                // Order suggestions
                Main.order_suggestions(Main.suggestions_list);

            } else {

                $('.main .suggestions-body-area').removeClass('cols-3');
                $('.main .suggestions-body-area').removeClass('cols-4');
                $('.main .suggestions-body-area').removeClass('cols-6');
                $('.main .suggestions-body-area').addClass('cols-12');

                // Start to create html
                var html = '<div class="col-12 first-12">'
                                + '<div class="row">'
                                    + '<div class="col-12">'
                                        + '<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">'
                                            + '<div class="line-main-left-bottom line-main-left-bottom-first"></div>'
                                            + '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">'
                                                + '<div class="toast-body text-center">'
                                                    + words.no_suggestions_found
                                                + '</div>'
                                            + '</div>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>';

                // Display main's level suggestion
                $('.main .suggestions-body-area > .row').eq(1).append(html);

            }

        }

        // Hide modal
        $('.main #suggestions-manager').modal('hide');

    });

    /*
     * Upload Media
     * 
     * @param object e with global object
     * 
     * @since   0.0.8.0
     */
    $('.main #upim').submit(function (e) {
        e.preventDefault();

        // Get files
        var files = $('#file')[0].files;

        // Verify if files exists
        if (typeof files[0] !== 'undefined') {

            // List all files
            for (var f = 0; f < files.length; f++) {

                // Save file
                Main.saveFile(files[f]);

            }

        }

    });

    // Verify if is edge
    if ( navigator.userAgent.search('Edg') ) {

        $('.main .suggestions-body-area').hide();

        setTimeout(function() {
            
            // Load suggestions
            Main.load_suggestions();

        }, 1000);

    } else {

        // Load suggestions
        Main.load_suggestions();

    }

    // If user changes the document size
    $( window ).resize(function () {

        // Load suggestions
        Main.load_suggestions();
        
        // Display loading animation
        $('.page-loading').fadeIn('slow');

    });

});