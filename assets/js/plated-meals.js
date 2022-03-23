jQuery(function($){

    const $product_gallery = $('.woocommerce-product-gallery');

    const $plated_meal_order_details = $('<div id="plated_meal_order_details">');

    $product_gallery.append($plated_meal_order_details);

    function add_plated_meal_order_details( detail_id, detail_title, detail_content )
    {
        const $plated_meal_order_detail = $('<div class="plated-meal-order-detail">');
        const $plated_meal_order_detail_title = $('<h5 class="plated-meal-order-detail-title">');
        const $plated_meal_order_detail_content = $('<div class="plated-meal-order-detail-content">');

        $plated_meal_order_detail_title.html( detail_title );
        $plated_meal_order_detail_content.html( detail_content );
        $plated_meal_order_detail.attr('id', detail_id )
            .append( $plated_meal_order_detail_title )
            .append( $plated_meal_order_detail_content );

        $plated_meal_order_details.append($plated_meal_order_detail);

        console.log( 'added detail ' + detail_id );
    }

    function update_plated_meal_order_details( detail_id, detail_title, detail_content )
    {
        const $detail = $('#' + detail_id);
        if( $detail.length ) {
            $detail.find('.plated-meal-order-detail-title').html(detail_title);
            $detail.find('.plated-meal-order-detail-content').html(detail_content);
        } else {
            add_plated_meal_order_details( detail_id, detail_title, detail_content );
        }

        console.log( 'updated meal order details ' );
    }

    // Running plated meal order calculation
    let guest_count;

    // ------------------------------- //
    // Update Guest Count Detail
    // ------------------------------- //

    $('#plated-number-of-guests input[type=number]').change(function () {
        update_plated_meal_order_details('plate-meal-guest-count', 'Number of Guests: ', $(this).val() );
        guest_count = $(this).val();
    });


    // ------------------------------- //
    // Update Delivery Date
    // ------------------------------- //

    document.addEventListener("catering_date_chosen", function(e) {
        update_plated_meal_order_details('plate-meal-delivery-date', 'Delivery Date: ', e.detail );
    });

    // ------------------------------- //
    // Update Guest Arrival Time
    // ------------------------------- //

    let guest_arrival_hour = '00';
    let guest_arrival_minute = '00';
    let guest_arrival_ampm = 'am';
    let guest_arrival_time = '';

    $('select[name="guest_arrival_hour"]').change(function () {
        guest_arrival_hour = $(this).val();
        update_guest_arrival_time();
    });
    $('select[name="guest_arrival_minute"]').change(function () {
        guest_arrival_minute = $(this).val();
        update_guest_arrival_time();
    });
    $('select[name="guest_arrival_ampm"]').change(function () {
        guest_arrival_ampm = $(this).val();
        update_guest_arrival_time();
    });

    function update_guest_arrival_time()
    {
        guest_arrival_time = `${guest_arrival_hour}:${guest_arrival_minute} ${guest_arrival_ampm}`;
        update_plated_meal_order_details('plate-meal-guest-arrival-time', 'Time guests arrive: ', guest_arrival_time );
    }

    // ------------------------------- //
    // Update Guest Departure Time
    // ------------------------------- //

    let guest_departure_hour = '00';
    let guest_departure_minute = '00';
    let guest_departure_ampm = 'am';
    let guest_departure_time = '';

    $('select[name="guest_departure_hour"]').change(function () {
        guest_departure_hour = $(this).val();
        update_guest_departure_time();
    });
    $('select[name="guest_departure_minute"]').change(function () {
        guest_departure_minute = $(this).val();
        update_guest_departure_time();
    });
    $('select[name="guest_departure_ampm"]').change(function () {
        guest_departure_ampm = $(this).val();
        update_guest_departure_time();
    });

    function update_guest_departure_time()
    {
        guest_departure_time = `${guest_departure_hour}:${guest_departure_minute} ${guest_departure_ampm}`;
        update_plated_meal_order_details('plate-meal-guest-departure-time', 'Time guests depart: ', guest_departure_time );
    }

    // ------------------------------- //
    // Update Selected Meal
    // ------------------------------- //

    const $choose_entrees       = $('#plated-choose-entrees');
    const $choose_hors_doeuvres = $('#plated-choose-hors-doeuvres');
    const $choose_desserts      = $('#plated-choose-desserts');

    $('input[name=plated_meal]').change(function(){
        // Update the product gallery image
        // Warning, this assumes there is one or no image added for the product. If it's an entire gallery, we have to rethink how we update the rest of the images.
        const $image = $(this).next('label').find('.meal-image img');
        const img_src = $image.attr('src');

        $('.wp-post-image').attr('src', img_src);

        console.log('meal selected ' + $(this).val());
        update_plated_meal_order_details('plate-meal-selected-meal-set', 'Selected Meal Set: ', $(this).val() );

        // Load the entrees, hors doeuvres an desserts via ajax
        let data = new FormData();
        data.append('action', 'ttc_get_plated_meal_parts');

        // get product id from sliding-form
        data.append( 'product_id', $(this).parents('.sliding-form').data('product_id') );

        // get meal index from meal-item
        data.append( 'meal_index', $(this).parent('.meal-item').data('meal_index') );


        $choose_entrees.addClass('loading');
        $choose_hors_doeuvres.addClass('loading');
        $choose_desserts.addClass('loading');
        jQuery.ajax({
            url: tco_ttc_js.ajaxurl,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function (data, textStatus, jqXHR) {
                console.log(['updating meal parts', data]);

                $choose_entrees.html(data.entrees)
                    .removeClass('loading');
                $choose_hors_doeuvres.html(data.hors_doeuvres)
                    .removeClass('loading');
                $choose_desserts.html(data.desserts)
                    .removeClass('loading');

            }
        });

    });


    // ------------------------------- //
    // Filter number of entree guest plates
    // ------------------------------- //
    /*
    Algorithm:
    Initialize an object, meal_plates, that takes in the meal id as key and an object array as value.
    The said object array will contain the name of the entree plate field as key, and the value of the field as value.
    For every time a meal is changed, an item is added if it doesn't exist already.
    The item will be populated with the object array of entree plate fields
    When an entree plate field is updated, the max attributes of all fields will be recalculated using the formula guest_count - total_count + current_value
    To get the total_count, the sum of the values of the object array is taken.
     */

    let meal_plates = {};

    let current_meal_id = '';

    function add_to_meal_plates( meal_id )
    {
        current_meal_id = meal_id;

        if( meal_id in meal_plates ) return false;

        meal_plates[meal_id] = {};
    }

    function populate_meal_plate( entree_id, count)
    {
        meal_plates[current_meal_id][entree_id] = count;
    }

    function get_total_meal_plates()
    {
        return sum( meal_plates[current_meal_id] )
    }

    function sum( obj ) {
        var sum = 0;
        for( var el in obj ) {
            if( obj.hasOwnProperty( el ) ) {
                sum += parseInt( obj[el] );
            }
        }
        return sum;
    }
    // Update the max attribute of each guest plate field when one gets changed
    $choose_entrees.on('change', '.entree-number-of-guest-plates', function(){
        let total_plates_entered = 0;

        // find all number of guest plates field
        let $entree_plates = $choose_entrees.find('.entree-number-of-guest-plates');

        // retrieve total number of plates entered
        $entree_plates.each(function(){
            let plate_count = $(this).val();
            let total_count = get_total_meal_plates();

            //let max_count = parseInt( guest_count ) - parseInt( total_count ) + parseInt( plate_count );
            let max_count = parseInt( guest_count ) - parseInt( total_count ) - parseInt( plate_count );

            console.log([parseInt( guest_count ),
                parseInt( total_count ),
                plate_count,
                max_count
                ]
            );

            console.log("themeco1");

            // Update max attribute
            $(this).attr('max', max_count );
        })
    });


});