jQuery(function($){
    

    //Report Funcionality - Start

    dateOptions = {changeMonth: true};

    start_date = $( ".datepicker_start" ).datepicker( dateOptions ).on( "change", function() {
                    end_date.datepicker( "option", "minDate", getDate( this ) );
                    range_submit(end_date.get(0), this);
                });

    end_date = $( ".datepicker_end" ).datepicker(  dateOptions ).on( "change", function() {
                    start_date.datepicker( "option", "maxDate", getDate( this ) );
                    range_submit(start_date.get(0), this);
                });

    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( "MM dd, yy", element.value );
        } catch( error ) {
            date = null;
        }

        return date;
    }

    function range_submit(el1, el2) {
        if ( el1.value !== '' && el2.value !== '' ) {
            $(el1).closest('form').trigger('submit');
        }
    }

    $(document).on('change', '[name="filter_type"]', function() { $(this).closest('form').trigger('submit'); } );

    $(document).ready ( function() { $( ".datepicker" ).datepicker(); } );
    
    //Report funcionality - End

    //Print functionality - Start
    $(document).on('click', '[name="printnote"]', function( ) {
        
          var fr = document.createElement('iframe');
          fr.style='height: 0px; width: 0px; position: absolute';
          document.body.appendChild(fr); 
          $('link, style').clone().appendTo(fr.contentDocument.body);          
          $('#booking_feed_print').clone().show().appendTo(fr.contentDocument.body);
          fr.contentWindow.print();
          fr.parentElement.removeChild(fr);

    } );
    //Print functionality - End

    const $calendarEl = $('#ttc_calendar');
    const calendar = new FullCalendar.Calendar($calendarEl[0], {
        initialView: 'dayGridMonth',
        events: '/ttc-api-events',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'listMonth,timeGridDay,timeGridWeek,dayGridMonth',
        },
        eventClick:  function(info) {
            console.log([info.event, info.event._def.publicId]);
            $('#modalTitle').html(info.event.title);
            const $modal_body = $('#ttc_modal_body');

            let data = new FormData();

            data.append('action', 'ttc_retrieve_booking_form');
            data.append('id', info.event._def.publicId);

            $modal_body.html('Loading...');
            $.ajax({
                url: tco_ttc_js.ajaxurl,
                type: 'POST',
                data: data,
                cache: false,
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function (data, textStatus, jqXHR) {
                    console.log(['ajax modal body', data, $modal_body]);
                    $modal_body.html(data);
                }
            });

            window.location.hash = '#booking_details';

            tb_show(info.event.title, '#TB_inline?&inlineId=ttc_modal')
        },

    });
    calendar.render();   

    // inline editable

});

