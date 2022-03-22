<div id="catering_details" class="booking-details">
    <input type="hidden" id="catering_calendar" data-inline="true"
           data-without="[<?php echo join(',', \TCo_Three_Tomatoes\Bookables::get_fully_booked_dates())?>]">
    <!-- data-without="[2/13/2016,2/14/2016,2/17/2016]" -->

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

    <input type="hidden" name="catering_option" value="Plated"/>



</div>
