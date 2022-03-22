jQuery(function($){

    // on upload button click
    $('body').on( 'click', '.upload-link', function(e){

        e.preventDefault();

        const $uploads_container = $('#uploads-container');
        const $upload_ids = $('#upload-ids');

        let button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                    type : 'image'
                },
                button: {
                    text: 'Attach' // button label text
                },
                multiple: true
            });
            custom_uploader.on('select', function() { // it also has "open" and "close" events
                $uploads_container.html('');

                var attachment_ids = [];
                var attachments = custom_uploader.state().get('selection').toJSON();
                console.log(attachments);

                for(let ai in attachments) {

                    let $upload_item = $('<div class="upload-item">');
                    let $file_thumbnail = $('<div class="file-thumbnail">');
                    let $file_name = $('<div class="file-name">');
                    let $upload_link = $('<a href="#" class="upload-link">');
                    let $upload_link_img = $('<img>');
                    let $upload_link_remove = $('<a href="#" class="upload-remove">Remove file</a>');
                    let $upload_link_hidden = $('<input type="hidden" name="uploaded-image">');

                    $upload_link_img.attr('src', attachments[ai].sizes.thumbnail.url);
                    $upload_link_hidden.val(attachments[ai].id);

                    $upload_link.append($upload_link_img);

                    $file_thumbnail.append($upload_link);
                    $file_thumbnail.append($upload_link_remove);
                    $file_thumbnail.append($upload_link_hidden);

                    $file_name.html(attachments[ai].title);

                    $upload_item.addClass(attachments[ai].type);
                    $upload_item.append($file_thumbnail);
                    $upload_item.append($file_name);

                    $uploads_container.append($upload_item);

                    attachment_ids.push(attachments[ai].id);
                }

                $upload_ids.val(attachment_ids.join(','));


                let data = new FormData();
                let visibility = 'public';

                data.append('action', 'ttc_save_booking_media');
                data.append('media', $upload_ids.val());
                data.append('visibility', 'public');
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

                        console.log(data);

                        let visibility = '';
                        let type = '';
                        // if(data.visibility === 'private') {
                        //     visibility = 'in private';
                        //     type = `<span class="type">for ${data.note.type}</span>`;
                        // }

                        refresh_feed();
                    }
                });

            });
            custom_uploader.on('open',function() {
                var selection = custom_uploader.state().get('selection');
                var ids_value = $upload_ids.val();

                if(ids_value.length > 0) {
                    var ids = ids_value.split(',');

                    ids.forEach(function(id) {
                        attachment = wp.media.attachment(id);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });
                }
            }).open();

    });

    // on remove button click
    $('body').on('click', '.upload-remove', function(e){

        e.preventDefault();

        var button = $(this);
        button.next().val(''); // emptying the hidden field
        button.hide().prev().html('Upload image');
    });

});