<div id="catering_details" class="booking-details">
    <input type="text" id="catering_datepicker" data-inline="true" name="catering_date"
           data-disable='<?php echo json_encode($disabled_dates) ?>'>

    <div id="guest_arrival_time">
        <label>Guest Arrival Time</label>
        <select name="guest_arrival_hour">
            <option>00</option>
            <?php for( $i = 1; $i <= 12; $i++ ): ?>
                <option><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT )?></option>
            <?php endfor; ?>
        </select>
        <select name="guest_arrival_minute">
            <?php for( $i = 0; $i < 60; $i+=5 ): ?>
                <option><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT )?></option>
            <?php endfor; ?>
        </select>
        <select name="guest_arrival_ampm">
            <option>AM</option>
            <option>PM</option>
        </select>
    </div>


    <div id="guest_departure_time">
        <label>Guest Departure Time</label>
        <select name="guest_departure_hour">
            <option>00</option>
            <?php for( $i = 1; $i <= 12; $i++ ): ?>
                <option><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT )?></option>
            <?php endfor; ?>
        </select>
        <select name="guest_departure_minute">
            <?php for( $i = 0; $i < 60;  $i+=5 ): ?>
                <option><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT )?></option>
            <?php endfor; ?>
        </select>
        <select name="guest_departure_ampm">
            <option>AM</option>
            <option>PM</option>
        </select>
    </div>

    <strong>Event Scheduling</strong><br>
    If you need an event sooner than available please call us at <a href="tel:303-433-3332">303-433-3332</a>. Events are structured to be 12 hours.&nbsp;If you need us for more than 12 hours please call.

</div>
