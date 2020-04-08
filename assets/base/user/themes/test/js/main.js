/*
 * Main javascript file
*/
jQuery(document).ready( function ($) {
    'use strict';
    
    /*******************************
    METHODS
    ********************************/

    /*
     * Display alert
     */
    Main.popup_fon = function( cl, msg, ft, lt ) {

        // Add message
        $('<div class="md-message ' + cl + '"><i class="icon-bell"></i> ' + msg + '</div>').insertAfter('section');

        // Display alert
        setTimeout(function () {

            $( document ).find( '.md-message' ).animate({opacity: '0'}, 500);

        }, ft);

        // Hide alert
        setTimeout(function () {

            $( document ).find( '.md-message' ).remove();

        }, lt);

    };

    /*
     * Display pagination
     */
    Main.show_pagination = function( id, total ) {
        
        // Empty pagination
        $( id + ' .pagination' ).empty();
        
        // Verify if page is not 1
        if ( parseInt(Main.pagination.page) > 1 ) {
            
            var bac = parseInt(Main.pagination.page) - 1;
            var pages = '<li class="page-item"><a href="#" class="page-link" data-page="' + bac + '">' + Main.translation.theme_prev + '</a></li>';
            
        } else {
            
            var pages = '<li class="pagehide page-item"><a href="#" class="page-link">' + Main.translation.theme_prev + '</a></li>';
            
        }
        
        // Count pages
        var tot = parseInt(total) / 10;
        tot = Math.ceil(tot) + 1;
        
        // Calculate start page
        var from = (parseInt(Main.pagination.page) > 2) ? parseInt(Main.pagination.page) - 2 : 1;
        
        // List all pages
        for ( var p = from; p < parseInt(tot); p++ ) {
            
            // Verify if p is equal to current page
            if ( p === parseInt(Main.pagination.page) ) {
                
                // Display current page
                pages += '<li class="active page-item"><a data-page="' + p + '" class="page-link">' + p + '</a></li>';
                
            } else if ( (p < parseInt(Main.pagination.page) + 3) && (p > parseInt(Main.pagination.page) - 3) ) {
                
                // Display page number
                pages += '<li class="page-item"><a href="#" class="page-link" data-page="' + p + '">' + p + '</a></li>';
                
            } else if ( (p < 6) && (Math.round(tot) > 5) && ((parseInt(Main.pagination.page) === 1) || (parseInt(Main.pagination.page) === 2)) ) {
                
                // Display page number
                pages += '<li class="page-item"><a href="#" class="page-link" data-page="' + p + '">' + p + '</a></li>';
                
            } else {
                
                break;
                
            }
            
        }
        
        // Verify if current page is 1
        if (p === 1) {
            
            // Display current page
            pages += '<li class="active page-item"><a data-page="' + p + '" class="page-link">' + p + '</a></li>';
            
        }
        
        // Set the next page
        var next = parseInt( Main.pagination.page );
        next++;
        
        // Verify if next page should be displayed
        if (next < Math.round(tot)) {
            
            $( id + ' .pagination' ).html( pages + '<li class="page-item"><a href="#" class="page-link" data-page="' + next + '">' + Main.translation.theme_next + '</a></li>' );
            
        } else {
            
            $( id + ' .pagination' ).html( pages + '<li class="pagehide page-item"><a href="#" class="page-link">' + Main.translation.theme_next + '</a></li>' );
            
        }
        
    };

    /*
     * Display calendar months
     */
    function show_year( month, year ) {
        
        // Set months
        var months = [
            '',
            Main.translation.theme_january,
            Main.translation.theme_february,
            Main.translation.theme_march,
            Main.translation.theme_april,
            Main.translation.theme_may,
            Main.translation.theme_june,
            Main.translation.theme_july,
            Main.translation.theme_august,
            Main.translation.theme_september,
            Main.translation.theme_october,
            Main.translation.theme_november,
            Main.translation.theme_december
        ];

        // Add months
        $( '.year-month' ).text( months[month] + ' ' + year );
        
    }

    /*
     * Display calendar
     */
    Main.show_calendar = function ( month, day, year, format ) {

        // Display months
        show_year( month, year );
        
        // Set current date
        var current = new Date();
        
        var d = new Date(year, month, 0);
        
        var e = new Date(d.getFullYear(), d.getMonth(), 1);
        
        var fday = e.getDay();
        
        var show = 1;

        fday++;

        format = 1;

        $( '.midrub-calendar' ).addClass( 'usa' );

        var n = '<tr>'
                    + '<td style="width: 14.28%;">'
                        + Main.translation.theme_s
                    + '</td>'
                    + '<td style="width: 14.28%;">'
                        + Main.translation.theme_m
                    + '</td>'
                    + '<td style="width: 14.28%;">'
                        + Main.translation.theme_t
                    + '</td>'
                    + '<td style="width: 14.28%;">'
                        + Main.translation.theme_w
                    + '</td>'
                    + '<td style="width: 14.28%;">'
                        + Main.translation.theme_tu
                    + '</td>'
                    + '<td style="width: 14.28%;">'
                        + Main.translation.theme_f
                    + '</td>'
                    + '<td style="width: 14.28%;">'
                        + Main.translation.theme_su
                    + '</td>'
                + '</tr>'
                + '<tr>';

        for ( var s = 1; s < d.getDate() + fday; s++ ) {

            if ( format ) {

                var tu = s - 1;

            } else {

                var tu = s;

            }

            if ( tu % 7 === 0 ) {
                n += '</tr><tr>';

            }
            if ( fday <= s ) {

                var add_date = '';

                if ( year + '-' + month + '-' + show === Main.selected_date ) {

                    add_date = 'add-date';

                }

                if ( ( show === day ) && ( month === current.getMonth() + 1 ) && ( year === current.getFullYear() ) ) {


                    n += '<td><a href="#" class="current-day ' + add_date + '" data-date="' + year + '-' + month + '-' + show + '">' + show + '</a></td>';

                } else {

                    if ( ( ( show < day ) && ( month === current.getMonth() + 1 ) && ( year == current.getFullYear() ) ) || ( ( ( month < current.getMonth() + 1 ) && ( year <= current.getFullYear() ) ) || ( year < current.getFullYear() ) ) ) {

                        n += '<td><a href="#" class="past-days">' + show + '</a></td>';

                    } else {

                        n += '<td><a href="#" data-date="' + year + '-' + month + '-' + show + '" class="' + add_date + '">' + show + '</a></td>';

                    }

                }

                show++;

            } else {

                n += '<td></td>';

            }

        }

        n += '</tr>';
        
        $( '.calendar-dates' ).html( n );
        
    };

    if ( $( '.open-midrub-planner' ).length > 0 ) {
        
        // Set current date
        Main.ctime = new Date();

        // Set current months
        Main.month = Main.ctime.getMonth() + 1;

        // Set current day
        Main.day = Main.ctime.getDate();

        // Set current year
        Main.year = Main.ctime.getFullYear();

        // Set current year
        Main.cyear = Main.year;

        // Set date/hour format
        Main.format = 0;

        // Set selected_date
        Main.selected_date = '';

        // Set selected time
        Main.selected_time = '08:00';
        
    }

    /*
     * Calculate time between two dates
     */
    Main.calculate_time = function( from, to ) {
        'use strict';

        // Set calculation time
        var calculate = to - from;

        // Set after variable
        var after = '<i class="far fa-calendar-check"></i> ';

        // Set before variable 
        var before = ' ' + Main.translation.theme_ago;

        // Define calc variable
        var calc;

        // Verify if time is older than now
        if ( calculate < 0 ) {

            // Set absolute value of a calculated time
            calculate = Math.abs(calculate);

            // Set icon
            after = '<i class="far fa-calendar-plus"></i> ';

            // Empty before
            before = '';

        }

        // Calculate time
        if ( calculate < 60 ) {

            return after + Main.translation.theme_just_now;

        } else if ( calculate < 3600 ) {

            calc = calculate / 60;
            calc = Math.round(calc);
            return after + calc + ' ' + Main.translation.theme_minutes + before;

        } else if ( calculate < 86400 ) {

            calc = calculate / 3600;
            calc = Math.round(calc);
            return after + calc + ' ' + Main.translation.theme_hours + before;

        } else if ( calculate >= 86400 ) {

            calc = calculate / 86400;
            calc = Math.round(calc);
            return after + calc + ' '+ Main.translation.theme_days + before;

        }

    };
  
    /*******************************
    ACTIONS
    ********************************/

    /*
     * Detect any click
     */
    $( 'body' ).click(function(e) {
        
        if ( $('.midrub-planner').length > 0 ) {

            var midrub_planner = $( '.midrub-planner' );

            if ( !midrub_planner.is(e.target) && midrub_planner.has(e.target).length === 0 ) {

                // Hide calendar
                $('.midrub-planner').fadeOut('fast');

            }
        
        }

    });

    /*
     * Detect schedule click
     */
    $(document).on('click', '.open-midrub-planner', function(e) {
        e.preventDefault();

        // Display calendar
        Main.show_calendar( Main.month, Main.day, Main.year, Main.format );
        
        // Set calendar position
        $('.midrub-planner').css({
            'top': ( $(this).offset().top - 330 ) + 'px',
            'left': ( $(this).offset().left - 275 ) + 'px',
        });
        
        // Display calendar
        $('.midrub-planner').fadeIn('fast');
        
    });
    
    /*
     * Select a date
     */  
    $(document).on('click', '.midrub-calendar td a', function (e) {
        e.preventDefault();
        
        // Remove class add-date
        $('.midrub-calendar tr td a').removeClass('add-date');
        
        // Add class add-date
        $(this).addClass('add-date');
        
        // Set new selected date
        Main.selected_date = $(this).attr('data-date');
        
        // Set current date
        var current_date = Main.selected_date;
        
        // Split date
        var split_date = current_date.split( '-' );
        
        // Set correct format
        var format_date = split_date[0] + '-' + ( 10 > split_date[1] ? '0' + split_date[1]: split_date[1] ) + '-' + ( 10 > split_date[2] ? '0' + split_date[2] : split_date[2]  );
        
        // Set date and time
        $( '.datetime' ).val( format_date + ' ' + Main.selected_time );

    });
    
    /*
     * Select time
     */  
    $(document).on('change', '.midrub-calendar-time-hour,.midrub-calendar-time-minutes,.midrub-calendar-time-period', function (e) {
        e.preventDefault();
        
        // Get hour
        var hour = $(this).closest('.row').find('.midrub-calendar-time-hour').val();

        // Get minutes
        var minutes = $(this).closest('.row').find('.midrub-calendar-time-minutes').val();

        // Verify if time period exists
        if ( $('.midrub-calendar-time-period').length > 0 ) {
            
            // Get period
            var period = $(this).closest('.row').find('.midrub-calendar-time-period').val();
            
            if ( period === 'AM' ) {
            
                // Adjust format
                var format_time = hour + ':' + minutes;
            
            } else {
                
                if ( hour > 11 ) {

                    // Adjust format
                    var format_time = '00:' + minutes; 

                } else {

                    // Adjust format
                    var format_time = (12 + parseInt(hour)) + ':' + minutes; 

                }         
                
            }

            // Set selected time
            Main.selected_time = format_time;
            
        } else {
            
            // Adjust format
            var format_time = hour + ':' + minutes;

            // Set selected time
            Main.selected_time = format_time;
            
        }
        
        // Verify if date was selected
        if ( Main.selected_date ) {
            
            // Set current date
            var current_date = Main.selected_date;

            // Split date
            var split_date = current_date.split( '-' );

            // Set correct format
            var format_date = split_date[0] + '-' + ( 10 > split_date[1] ? '0' + split_date[1]: split_date[1] ) + '-' + ( 10 > split_date[2] ? '0' + split_date[2] : split_date[2]  );

            // Set date and time
            $( '.datetime' ).val( format_date + ' ' + Main.selected_time );
            
        }
        
    });

    /*
     * Go back button
     */    
    $('.midrub-calendar .go-back').click(function (e) {
        e.preventDefault();

        Main.month--;

        if ( Main.month < 1 ) {
            
            Main.year--;
            Main.month = 12;
            
        }
        
        // Display calendar
        Main.show_calendar( Main.month, Main.day, Main.year, Main.format);
        
    });
    
    /*
     * Go next button
     */
    $('.midrub-calendar .go-next').click(function (e) {
        e.preventDefault();

        Main.month++;

        if ( Main.month > 12 ) {
            
            Main.year++;
            
            Main.month = 1;
            
        }
        
        // Display calendar
        Main.show_calendar( Main.month, Main.day, Main.year, Main.format);
        
    });
   
    /*******************************
    RESPONSES
    ********************************/ 
    
    /*******************************
    FORMS
    ********************************/
    
    /*******************************
    DEPENDENCIES
    ********************************/

    // Hide the loading page animation
    setTimeout(function(){
        $('.page-loading').fadeOut('slow');
    }, 600);

});