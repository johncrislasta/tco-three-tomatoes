jQuery(function($){
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