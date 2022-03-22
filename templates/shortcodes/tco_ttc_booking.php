<div class="ttc-booking-container">
    <input type="radio" name="booking_type" value="delivery" id="delivery_booking"/> <label for="delivery_booking"> Delivery</label>
    <input type="radio" name="booking_type" value="delivery" id="catering_booking"/> <label for="catering_booking"> Catering</label>

    <div id="delivery_details" class="booking-details">
        <input type="hidden" id="delivery_calendar" data-inline="true" name="delivery_date"
               data-without="[<?php echo join(',', \TCo_Three_Tomatoes\Bookables::get_fully_booked_dates())?>]">

        <input type="hidden" id="delivery_start_time" name="delivery_start_time"/>

        <input type="hidden" id="delivery_end_time" name="delivery_end_time"/>

        <div id="delivery_window_time_container"></div>

        <label>Include plastic utensils? </label>

        <label>
            <input type="radio" id="delivery_include_plastic_utensils" name="delivery_include_plastic_utensils" value="Yes" required/> Yes
        </label>

        <label>
            <input type="radio" id="delivery_include_plastic_utensils" name="delivery_include_plastic_utensils" value="No"  required/> No
        </label>
    </div>

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

        <div id="catering_options">
            <?php
                $catering_options = \TCo_Three_Tomatoes\Caterings::get_options();
    //            \TCo_Three_Tomatoes\Acme::diep($catering_options);
                foreach ( $catering_options as $catering_option ): ?>
            <label>
                <input type="radio" name="catering_option" value="<?php $catering_option->slug ?>"/>
                <?php echo $catering_option->name ?>
            </label>
            <?php
                endforeach;
            ?>
        </div>

        <div id="catering_venues">
            <label> Where is the venue?:
                <input type="text" list="venues" placeholder="Venue"/>
                <datalist id="venues">
                    <option>I do not have a venue selected yet</option>
                    <option>Museum of Contemporary Art</option>
                    <option>Art Students League of Denver</option>
                    <option>The Arvada Center</option>
                    <option>Center for Visual Art, Metropolitan State College of Denver</option>
                    <option>Balistreri Winery</option>
                    <option>Battery 21</option>
                    <option>Blanc</option><option value="Venue - Boettcher Mansion">Boettcher Mansion</option><option value="Venue - Butterfly Pavilion">Butterfly Pavilion</option><option value="Venue - Cable Center">Cable Center</option><option value="Venue - Castle Cliff Estate">Castle Cliff Estate</option><option value="Venue - The Center">The Center</option><option value="Venue - Chief Hosa Lodge">Chief Hosa Lodge</option><option value="Venue - Cielo at Castle Pines">Cielo at Castle Pines</option><option value="Venue - Clocktower Events">Clocktower Events</option><option value="Venue - Crooked Willow Farms">Crooked Willow Farms</option><option value="Venue - Della Terra">Della Terra</option><option value="Venue - Denver Botanic Gardens at Chatfield">Denver Botanic Gardens at Chatfield</option><option value="Venue - Denver Central Library">Denver Central Library</option><option value="Venue - Denver Film Center // Colfax">Denver Film Center // Colfax</option><option value="Venue - Dick's Sporting Goods Center">Dick's Sporting Goods Center</option><option value="Venue - Donavon Pavilion">Donavon Pavilion</option><option value="Venue - Dunafon Castle">Dunafon Castle</option><option value="Venue - Emerson Mansion">Emerson Mansion</option><option value="Venue - Evergreen Lake House">Evergreen Lake House</option><option value="Venue - The Fall Event Center">The Fall Event Center</option><option value="Venue - Fat Tail Gallery">Fat Tail Gallery</option><option value="Venue - Foothills Art Center">Foothills Art Center</option><option value="Venue - Foothills Wedding Chapel &amp; Banquet Room">Foothills Wedding Chapel &amp; Banquet Room</option><option value="Venue - Four Mile Historic Park">Four Mile Historic Park</option><option value="Venue - Grant-Humphreys Mansion">Grant-Humphreys Mansion</option><option value="Venue - Great Divide Brewing Company">Great Divide Brewing Company</option><option value="Venue - Highland Haven">Highland Haven</option><option value="Venue - Highlands Ranch Mansion">Highlands Ranch Mansion</option><option value="Venue - Holiday Event Center">Holiday Event Center</option><option value="Venue - Infinity Park Event Center">Infinity Park Event Center</option><option value="Venue - Ironworks">Ironworks</option><option value="Venue - Lannies Clocktower Cabaret">Lannies Clocktower Cabaret</option><option value="Venue - Lions Gate Center The Dove House &amp; Gatehouse">Lions Gate Center The Dove House &amp; Gatehouse</option><option value="Venue - Lone Tree Arts Center">Lone Tree Arts Center</option><option value="Venue - McNichols Civic Center Building">McNichols Civic Center Building</option><option value="Venue - Mile High Station">Mile High Station</option><option value="Venue - Montclair Civic Building - The Molkery">Montclair Civic Building - The Molkery</option><option value="Venue - Museo de las Americas">Museo de las Americas</option><option value="Venue - Paramount Theater">Paramount Theater</option><option value="Venue - Parker Arts, Culture &amp; Events (PACE) Center">Parker Arts, Culture &amp; Events (PACE) Center</option><option value="Venue - Parkside Mansion">Parkside Mansion</option><option value="Venue - PPA Event Center">PPA Event Center</option><option value="Venue - Ralston's Crossing Event Center">Ralston's Crossing Event Center</option><option value="Venue - RedLine">RedLine</option><option value="Venue - Rembrandt Yard">Rembrandt Yard</option><option value="Venue - Robischon Gallery">Robischon Gallery</option><option value="Venue - Shyft">Shyft</option><option value="Venue - Skylight">Skylight</option><option value="Venue - SkyVenture Colorado">SkyVenture Colorado</option><option value="Venue - Space Gallery">Space Gallery</option><option value="Venue - Studio 12 Art Gallery">Studio 12 Art Gallery</option><option value="Venue - Studios at Overland Crossing">Studios at Overland Crossing</option><option value="Venue - Tattered Cover Bookstore">Tattered Cover Bookstore</option><option value="Venue - Temple Emanuel">Temple Emanuel</option><option value="Venue - Temple Sinai">Temple Sinai</option><option value="Venue - Three Tomatoes Steakhouse &amp; Club at Fossil Trace">Three Tomatoes Steakhouse &amp; Club at Fossil Trace</option><option value="Venue - Translations Gallery">Translations Gallery</option><option value="Venue - Villa Parker Cultural &amp; Event Center">Villa Parker Cultural &amp; Event Center</option><option value="Venue - Walker Fine Art">Walker Fine Art</option><option value="Venue - Wash Park Studio">Wash Park Studio</option><option value="Venue - Washington Park Boat House">Washington Park Boat House</option><option value="Venue - Wings Over the Rockies">Wings Over the Rockies</option><option value="Venue - Other">Other</option>
                </datalist>
            </label>
        </div>
    </div>


</div>
