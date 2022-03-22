<h2>Uploads</h2>
<div id="uploads-container">
    <?php
    // Print all notes
    foreach($uploads as $file):
        \TCo_Three_Tomatoes\Acme::display_errors();
//        \TCo_Three_Tomatoes\Acme::diep($file);
        ?>
        <div class="upload-item <?php echo $file['type'] ?>">

            <div class="file-thumbnail">
                <?php
                    echo '<a href="#" class="upload-link"><img src="' . $file['sizes']['thumbnail'] . '" /></a>
                          <a href="#" class="upload-remove">Remove file</a>
                          <input type="hidden" name="uploaded-image" value="' . $file['id'] . '">';
                ?>
            </div>

            <div class="file-name">
                <?php echo $file['title'] ?>
            </div>

        </div>
    <?php endforeach; ?>
</div>

<?php

    $upload_ids = $upload_ids ?: '';
echo '<a href="#" class="upload-link">Upload files</a>
	      <input type="hidden" name="upload-ids" id="upload-ids" value="'.$upload_ids.'">';

?>