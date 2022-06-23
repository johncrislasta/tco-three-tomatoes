const $ = jQuery;

var catering_calendar;

jQuery(function($){

    console.log(tco_ttc_js);

    var delivery_calendar = new Datepicker('#delivery_calendar', {
        // allowed days in advance from delivery settings
        min: (function(){
            var date = new Date();
            date.setDate(date.getDate() + parseInt(tco_ttc_js.settings.delivery.allowed_days_in_advance));
            return date;
        })(),
        openOn: (function(){
            var date = new Date();
            date.setDate(date.getDate() + parseInt(tco_ttc_js.settings.delivery.allowed_days_in_advance));
            return date;
        })(),
    });

    var catering_calendar = new Datepicker('#catering_calendar', {
        // allowed days in advance from delivery settings
        min: (function(){
            var date = new Date();
            date.setDate(date.getDate() + parseInt(tco_ttc_js.settings.catering.allowed_days_in_advance));
            return date;
        })(),
        openOn: (function(){
            var date = new Date();
            date.setDate(date.getDate() + parseInt(tco_ttc_js.settings.catering.allowed_days_in_advance));
            return date;
        })(),
        onChange: (function(date) {
            // Create the event
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            // date = date.toLocaleDateString("en-US", options);

            if ( date ) {
                console.log('datepicker onChange', date);
                var event = new CustomEvent("catering_date_chosen", { "detail": date });
            } else {
                var event = new CustomEvent("catering_date_chosen", { "detail": new Date() });
            }


            // Dispatch/Trigger/Fire the event
            document.dispatchEvent(event);
        })
    });

    // catering_calendar.setDate(Date('May 15, 2022'));
    console.log('Catering calendar: ', catering_calendar);

    for(let wt in tco_ttc_js.settings.delivery.window_times) {
        let window_time = tco_ttc_js.settings.delivery.window_times[wt];
        // Create label and radio
        let $dwt_label = $('<label class="window-time-label">')
            .text(window_time.label + "( " + window_time.start_time + " to " + window_time.end_time + " )")
            .attr('for', 'window_time_' + window_time.label )
            .data('start_time', window_time.start_time )
            .data('end_time', window_time.end_time );
        let $dwt_radio = $('<input type="radio">' )
            .attr('name', 'window_time' )
            .attr('id', 'window_time_' + window_time.label )
            .val( window_time.label )

        $dwt_label.prepend($dwt_radio);
        $('#delivery_window_time_container').append($dwt_label);
    }

});