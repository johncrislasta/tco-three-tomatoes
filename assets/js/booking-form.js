jQuery(function($){

// save booking notes
    $('.booking-form').on('submit', '.note_create',function (e) {
        e.preventDefault();

        const $form = $(this);
        const $message = $form.find('.note_message');
        const visibility = $form.find('.note_visibility:checked').length > 0 ? 'private' : 'public';
        const $submit = $form.find('.note_submit');
        const $notes = $form.prev('.notes');




        $submit.val('Sending...').attr('disabled','disabled');

        let data = new FormData($form[0]);

        data.append('action', 'ttc_save_booking_notes');
        data.append('message', $message.val());
        data.append('visibility', visibility);
        if(visibility === 'private')
            data.append( 'type', $form.find('.note_type').val())

        $.ajax({
            url: tco_ttc_js.ajaxurl,
            type: 'POST',
            data: data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                $message.val('');
                $submit.val('Sent!').attr('disabled', null);
                console.log(data);

                let visibility = '';
                let type = '';
                if(data.visibility === 'private') {
                    visibility = 'in private';
                    type = `<span class="type">for ${data.note.type}</span>`;
                }

                $notes.prepend($(`<li>
                    <strong class="user-name">
                        ${data.note.author_name}        </strong> commented ${visibility}
                        <span class="date" title="${data.note.date_time}">
                        ${data.note.time_ago}        </span>
                        ${type}
                    <pre class="message">${data.note.message}</pre>
                </li>`));

                refresh_feed();
            }
        });
    });
});


function refresh_feed(){

    let data = new FormData();

    data.append('action', 'ttc_refresh_booking_feed');

    const $feed_container = jQuery('#booking_feed');

    $feed_container.addClass('loading');
    jQuery.ajax({
        url: tco_ttc_js.ajaxurl,
        type: 'POST',
        data: data,
        cache: false,
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data, textStatus, jqXHR) {
            console.log(['refreshing feed', data, $feed_container]);
            $feed_container.html(data)
                .removeClass('loading');

        }
    });
}