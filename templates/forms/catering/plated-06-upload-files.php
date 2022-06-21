<h3 class="text-center">Drag and drop or choose files below</h3><br />

<form id="upload-files-form" data-label="test_uploads">
    <div class="file-upload-container">
        <input type="file" id="file-upload-input" onchange="preview()" multiple name="file_uploads[]">
        <label for="file-input">
            <i class="fas fa-upload"></i> &nbsp; Choose files or drop here
        </label>
        <p id="num-of-files">No Files Chosen</p>
        <div id="images"></div>
    </div>

    <button disabled="disabled" class="file-upload-button" id="file-upload-button">Upload files</button>

    <div class="progress">
        <div class="bar"></div>
    </div>

    <input type="hidden" name="file_uploads" class="file-uploads" id="file-uploads-hidden"/>

    <div class="uploaded-files" id="uploaded-files-container"></div>
</form>
