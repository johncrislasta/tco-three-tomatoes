const fileInput         = document.getElementById("file-upload-input");
const imageContainer    = document.getElementById("images");
const numOfFiles        = document.getElementById("num-of-files");
const fileUploadButton  = document.getElementById("file-upload-button");
const fileUploadForm    = document.getElementById("upload-files-form");
const uploadedFiles     = document.getElementById("uploaded-files-container");
const fileUploadsHidden = document.getElementById("file-uploads-hidden");

function preview(){
    imageContainer.innerHTML = "";
    numOfFiles.textContent = `${fileInput.files.length} Files Selected`;

    if( fileInput.files.length ) {

        for(let i of fileInput.files){
            let reader = new FileReader();
            let figure = document.createElement("figure");
            let figCap = document.createElement("figcaption");
            figCap.innerText = i.name;
            figure.appendChild(figCap);

            const fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'jfif', 'bmp'];  //acceptable file types

            reader.onload=()=>{
                let extension = i.name.split('.').pop().toLowerCase(),  //file extension from input file
                    isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types


                if (isSuccess) { //yes
                    let img = document.createElement("img");
                    console.log(reader);
                    img.setAttribute("src",reader.result);
                    figure.insertBefore(img,figCap);
                } else {
                    let img = document.createElement("div");
                    img.classList.add('file-ext-image');
                    img.insertAdjacentHTML(
                        'beforeend',
                        `<span>${extension}</span>`,
                    );
                    figure.insertBefore(img,figCap);
                }
            };

            imageContainer.appendChild(figure);
            reader.readAsDataURL(i);
        }

        fileUploadButton.disabled = false;
    } else {
        fileUploadButton.disabled = true;
    }
}

$(document).ready(function() {
    const $file_drag_area = $('.file-upload-container');

    $file_drag_area.on('dragover', function(){
        $(this).addClass('file_drag_over');
        return false;
    });
    $file_drag_area.on('dragleave', function(){
        $(this).removeClass('file_drag_over');
        return false;
    });
    $file_drag_area.on('drop', function(e){
        // e.preventDefault();
        $(this).removeClass('file_drag_over');
        var formData = new FormData();
        var files_list = e.originalEvent.dataTransfer.files;
        //console.log(files_list);
        for(var i=0; i<files_list.length; i++)
        {
            formData.append('file[]', files_list[i]);
        }
    });

    let $fileUploadForm = $(fileUploadForm);
    let $fileUploadButton = $(fileUploadButton);

    $fileUploadForm.on('change', prepareUploadImages)
        .on('submit', uploadImages);


    let files;
    function prepareUploadImages(event) {
        files = event.target.files;
    }

    function uploadImages(event){
        // (A) FILE UPLOAD
        let files_to_upload = $(this).find("#file-upload-input")[0].files,
            data = new FormData(),
            slug = $(this).data('slug');
            // $link = upload_tasks[slug]['link'],
            // $modal = upload_tasks[slug]['modal'];

        data.append("label", $('this').data('file_label'));
        data.append("action", "ttc_files_upload");
        // data.append('files_to_upload', files_to_upload);

        let file_counter = 1;
        $.each(files, function(key, value)
        {
            data.append("files_upload_" + file_counter, value);
            file_counter++;
        });

        console.log([data, files, event]);
        // data.append("KEY", "VALUE");

        // (B) AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", tco_ttc_js.ajaxurl);

        // (C) UPLOAD PROGRESS
        let percent = 0, width = 0, upload_percent = 0,
            bar = $fileUploadForm.find(".progress")[0],
            start = fileUploadButton;

        xhr.upload.onloadstart = function(evt){
            bar.style.width = "0";
            start.disabled = true;
        };

        xhr.upload.onprogress = function(evt){
            percent = evt.loaded / evt.total;
            upload_percent = Math.ceil(percent * 50);
            width = 100 - Math.ceil(percent * 50);
            bar.style.width = width + "%";
        };

        xhr.upload.onloadend = function(evt){
            bar.style.width = '5%';
        };

        // (D) ON UPLOAD COMPLETE
        xhr.onload = function(){
            console.log(this);
            console.log(this.response);
            console.log(this.status);

            start.disabled = false;
            bar.style.width = "0%";

            let responses = JSON.parse(this.response);
            let media_ids = $(fileUploadsHidden).val().split(',');
            console.log(['media_ids', media_ids]);

            for(let r in responses) {
                let response = responses[r];

                // Create figure elements inside the uploaded container
                let $figure = $('<figure>');
                let $figcaption = $('<figcaption>');
                let $img;

                if( response.response === 'ERROR' ) {
                    $img = $('<div class="file-ext-image">');
                    $img.html(`<span>ERROR</span><i>${response.error}</i>`);

                    $figcaption.text(response.message );

                } else {
                    // check if extension is not image
                    if( response['type'] === 'image' ) {
                        $img = $('<img>');
                        $img.attr('src', response.url);
                    } else {
                        $img = $('<div class="file-ext-image">');
                        $img.html(`<span>${response.type}</span>`);
                    }

                    $figcaption.text( response.filename );

                    media_ids.push( response.attachment_id );
                }

                $figure.append( $img, $figcaption );

                $(uploadedFiles).append( $figure );
            }

            $(fileUploadsHidden).val( media_ids.join()).change();

            $(fileInput).val('').change();
        };

        // (E) GO!
        xhr.send(data);

        return false;
    }
});