<?php //acf_form(array(
//    'post_id'       => $post_id,
//    'new_post'      => array(
//        'post_type'     => $post_type,
//        'post_status'   => 'publish'
//    ),
//    'submit_value'  => 'Create new ' . $post_type
//)); ?>
<div class="booking-form-container">
    <ul class="tab-menu">
        <li class="tab-menu-item">
            <a class="tab-menu-item-link" href="#booking_details">Details</a>
        </li>
        <li class="tab-menu-item">
            <a class="tab-menu-item-link" href="#booking_notes">Notes</a>
        </li>
        <li class="tab-menu-item">
            <a class="tab-menu-item-link" href="#booking_uploads">Uploads</a>
        </li>
        <li class="tab-menu-item">
            <a class="tab-menu-item-link" href="#booking_feed">Feed</a>
        </li>
    </ul>
    <div id="booking_details" class="tab-content">
        <?php
        echo \TCo_Three_Tomatoes\Acme::get_template('forms/booking-form-details', $details);
        ?>
    </div>
    <div id="booking_uploads" class="tab-content">
        <?php
        echo \TCo_Three_Tomatoes\Acme::get_template('admin/booking-uploads', ['uploads' => $uploads['files'], 'upload_ids' => $uploads['ids']]);
        ?>
    </div>
    <div id="booking_feed" class="tab-content">
        <?php
        echo \TCo_Three_Tomatoes\Acme::get_template('admin/booking-feed', ['feed' => $feed]);
        ?>
    </div>
    <div id="booking_note_form" class="tab-content">
        <?php
        echo \TCo_Three_Tomatoes\Acme::get_template('forms/booking-notes');
        ?>
    </div>
</div>
