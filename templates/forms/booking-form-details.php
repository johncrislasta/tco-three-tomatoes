<div class="booking-details edit-inline">
    <h2>Details</h2>
    <div data-name="start_date" class="editable start-date">Start Date: <?php echo $start_date ?></div>
    <div data-name="start_time" class="editable start-time" class="start-time">Start Time: <?php echo $start_time ?></div>
    <div data-name="end_date" class="editable end-date">End Date: <?php echo $end_date ?></div>
    <div data-name="end_time" class="editable end-time">End Time: <?php echo $end_time ?></div>
    <div data-name="customer_name" class="editable customer-name">Customer Name: <?php echo $customer_name ?></div>
    <div data-name="number_of_guests" class="editable number-of-guests">Number of Guests: <?php echo $number_of_guests ?></div>
</div>

<div class="booking-custom-order-details">
    <?php

    $exclude = array(
        'catering_datepicker',
        'guest_arrival_hour',
        'guest_arrival_min',
        'guest_arrival_ampm',
        'guest_departure_hour',
        'guest_departure_min',
        'guest_departure_ampm',
        'product_id',
        'regular_price',
        'accept_terms_conditions',
    );

//    \TCo_Three_Tomatoes\Acme::diep($custom_order_details, false);

    foreach ( $custom_order_details as $key => $value ) {

        if( in_array( $key, $exclude) ) continue;

//        $value = $val['value'];

        $key = str_replace( ['-', '_'], ' ', $key );
        $key = ucwords($key);

        if( is_array($value) )
            $value = implode( ', ', $value );

        $value = str_replace( ['-', '_'], ' ', $value );
        $value = ucfirst($value);

        $item_data[] = array(
            'key' => $key,
            'value' => $value
        );

        echo "<p><strong>$key</strong>: $value</p>";
    }
    ?>
</div>