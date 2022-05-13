jQuery(function($){

    const $product_gallery = $('.woocommerce-product-gallery');

    const $plated_meal_order_details = $('<div id="plated_meal_order_details">');

    $product_gallery.append($plated_meal_order_details);

    // @TODO: Make this dynamic and based from woocommerce_currency_symbol
    let currency_symbol = tco_ttc_js.currency_symbol;

    let order_details = {};
    let order_details_dependency = {};

    function add_plated_meal_order_details( detail_id, detail_title, detail_content, detail_price = false, detail_parent = false, detail_parent_content = false )
    {

        const $plated_meal_order_detail = $('<div class="plated-meal-order-detail">');
        const $plated_meal_order_detail_title = $('<h5 class="plated-meal-order-detail-title">');
        const $plated_meal_order_detail_price = $('<span class="plated-meal-order-detail-price">').hide();
        const $plated_meal_order_detail_total_price = $('<span class="plated-meal-order-detail-total-price">');
        const $plated_meal_order_detail_item_price = $('<span class="plated-meal-order-detail-item-price">');
        const $plated_meal_order_detail_guest_count = $('<span class="plated-meal-order-detail-guest-count">');
        const $plated_meal_order_detail_content = $('<div class="plated-meal-order-detail-content">');

        $plated_meal_order_detail_title.html( detail_title );

        if( detail_price )
            $plated_meal_order_detail_price.show();
        else
            $plated_meal_order_detail_price.hide();

        $plated_meal_order_detail_total_price.html( currency_symbol + ' ' + detail_price * guest_count );
        $plated_meal_order_detail_item_price.html( currency_symbol + ' ' + detail_price);
        $plated_meal_order_detail_guest_count.html( guest_count );

        $plated_meal_order_detail_price
            .append( $plated_meal_order_detail_guest_count )
            .append( $plated_meal_order_detail_item_price )
            .append( $plated_meal_order_detail_total_price );

        $plated_meal_order_detail_title.append( $plated_meal_order_detail_price );
        $plated_meal_order_detail_content.html( detail_content );
        $plated_meal_order_detail.attr('id', detail_id )
            .append( $plated_meal_order_detail_title )
            .append( $plated_meal_order_detail_content );

        $plated_meal_order_details.append($plated_meal_order_detail);

        // update_order_details( detail_id, detail_title, detail_content, detail_price, detail_parent, detail_parent_content );

        console.log( 'added detail ' + detail_id );
    }

    function update_plated_meal_order_details( detail_id, detail_title, detail_content, detail_price = false, detail_parent = false, detail_parent_content = false )
    {
        if( detail_parent !== false && detail_parent_content !== false )
            detail_id = wpFeSanitizeTitle( detail_parent + "-" + detail_parent_content + "-" + detail_id );

        const $detail = $('#' + detail_id);

        update_running_total_object(detail_id, detail_price);

        detail_price = detail_price ? detail_price : 0;

        detail_price = Math.round(detail_price * 100) / 100;

        update_order_details( detail_id, detail_title, detail_content, detail_price, detail_parent, detail_parent_content );

        if( $detail.length ) {
            $detail.find('.plated-meal-order-detail-total-price').html(currency_symbol + ' ' + detail_price * guest_count);
            $detail.find('.plated-meal-order-detail-item-price').html(currency_symbol + ' ' + detail_price);
            $detail.find('.plated-meal-order-detail-guest-count').html( guest_count );
            let $price = $detail.find('.plated-meal-order-detail-price');

            $detail.find('.plated-meal-order-detail-title').html(detail_title).append($price);
            $detail.find('.plated-meal-order-detail-content').html(detail_content);

            if( detail_price )
                $detail.find('.plated-meal-order-detail-price').show();
            else
                $detail.find('.plated-meal-order-detail-price').hide();
        } else {
            add_plated_meal_order_details( detail_id, detail_title, detail_content, detail_price, detail_parent, detail_parent_content );
        }

        // If detail is parent, hide all grandchildren of non chosen child
        if(is_order_detail_parent( detail_id) && detail_id in order_details_dependency ) {

            let parent_answers = Object.keys( order_details_dependency[ detail_id ] );


            const index = parent_answers.indexOf( detail_content );
            if (index > -1) {
                parent_answers.splice(index, 1);
            }

            // console.log('getting parent answers', parent_answers, detail_content );

            for ( let answer of parent_answers ) {

                // console.log('answer in parent_answers', answer, parent_answers );

                for( let dependent_id in order_details_dependency[detail_id][answer] ) {
                    $('#' + dependent_id).hide().addClass('hidden');
                }
            }

            for( let dependent_id in order_details_dependency[detail_id][detail_content] ) {
                $('#' + dependent_id).show().removeClass('hidden');
            }

        }

        update_product_price();

        console.log( 'updated meal order details ', detail_id, detail_title, detail_content, detail_price, detail_parent, detail_parent_content );
    }

    function update_order_details( detail_id, detail_title, detail_content, detail_price = false, detail_parent = false, detail_parent_content = false )
    {
        order_details[detail_id] = {
            title: detail_title,
            content: detail_content,
            price: detail_price,
            is_parent: false
        };

        if (detail_parent === true)
        {
            order_details[detail_id]['is_parent'] = true;
        }
        else if (detail_parent !== false)
        {
            if( ! ( detail_parent in order_details_dependency ) )
            {
                order_details_dependency[detail_parent] = {};
            }

            if( ! ( detail_parent_content in order_details_dependency[detail_parent] ) )
            {
                order_details_dependency[detail_parent][detail_parent_content] = {};
            }

            order_details_dependency[detail_parent][detail_parent_content][detail_id] = detail_content;
        }

        console.log( 'ORDER DETAILS', order_details, order_details_dependency );
    }

    function is_order_detail_parent( detail_id ) {
        return 'is_parent' in order_details[detail_id] && order_details[detail_id]['is_parent'] === true
    }

    let $product_price = $('.product_title + p.price');
    function update_product_price() {
        console.log(['RUNNING TOTAL OBJECT', running_total_object]);
        let price = sum(running_total_object);

        price = get_sum_of_order_detail_prices() * guest_count;

        let $price_html = `<span class="woocommerce-Price-amount amount">
            <bdi>
                <span class="woocommerce-Price-currencySymbol">
                    ${currency_symbol}
                </span>
                ${price}
            </bdi>
        </span>`;

        $product_price.html($price_html );
    }

    let running_total_object = {};

    function update_running_total_object( price_id, price_per_guest ) {
        running_total_object[price_id] = price_per_guest ? price_per_guest : 0;
        // update_product_price();
    }

    // Running plated meal order calculation
    let guest_count;

    // ------------------------------- //
    // UX for Venue and Occasion
    // ------------------------------- //

    $('#occasion-select, #venue-select').change(function(){
        if( $(this).val() === 'other' ) {
            $(this).next('.other-input').show();
        } else {
            $(this).next('.other-input').hide();
        }
    });

    // ------------------------------- //
    // Update Guest Count Detail
    // ------------------------------- //

    $('#number-guest-input[type=number]').change(function () {
        update_plated_meal_order_details('plate-meal-guest-count', 'Number of Guests: ', $(this).val() );
        guest_count = $(this).val();
        update_product_price();

        $('.plated-meal-order-detail-guest-count').text(guest_count);
        $('.plated-meal-order-detail-total-price').each(function() {

            let item_price = get_num( $(this).prev().text() );
            let total_price = guest_count * item_price;

            $(this).html(currency_symbol + total_price );
        });
    });


    // ------------------------------- //
    // Update Delivery Date
    // ------------------------------- //

    const $catering_datepicker = $('#catering_datepicker');

    let catering_date = new Date();
    catering_date.setDate(catering_date.getDate() + parseInt(tco_ttc_js.settings.catering.allowed_days_in_advance));
    console.log($catering_datepicker.data('disable'));
    let catering_disabled_dates = $catering_datepicker.data('disable');

    const catering_datepicker_options = {
        dateFormat: "Y-m-d",
        inline: true,
        minDate: catering_date,
        defaultDate: catering_date,
        disable: catering_disabled_dates,
        onChange: function(selectedDates, dateStr, instance) {
            // console.log('catering flatpickr onchange listener', selectedDates, dateStr);
            // hide details for fields not affecting the pricing
            // update_plated_meal_order_details('plate-meal-event-date', 'Event Date: ', dateStr );
            store_progress('catering_datepicker', dateStr);
        },
    };
    const catering_flatpickr = flatpickr("#catering_datepicker", catering_datepicker_options); // flatpickr

    console.log(['catering_datepicker_options', catering_datepicker_options]);

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
        // update_plated_meal_order_details('plate-meal-guest-arrival-time', 'Time guests arrive: ', guest_arrival_time );
        store_progress('plate_meal_guest_arrival_time', guest_arrival_time);
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
        // update_plated_meal_order_details('plate-meal-guest-departure-time', 'Time guests depart: ', guest_departure_time );
        store_progress('plate_meal_guest_departure_time', guest_departure_time );

    }

    // ------------------------------- //
    // Update Selected Meal
    // ------------------------------- //

    const $choose_entrees       = $('#plated-choose-entrees');
    const $choose_hors_doeuvres = $('#plated-choose-hors-doeuvres');
    const $choose_desserts      = $('#plated-choose-desserts');

    let selected_meal_set = '';
    let selected_meal_set_parts = {};

    $('input[name=plated_meal]').change(function(){
        // Update the product gallery image
        // Warning, this assumes there is one or no image added for the product. If it's an entire gallery, we have to rethink how we update the rest of the images.
        const $image = $(this).next('label').find('.meal-image img');
        const img_src = $image.attr('src');
        const $plated_meal = $(this);

        add_to_meal_plates($(this).attr("id"));

        $('.wp-post-image').attr('src', img_src);

        let previously_selected_meal_set = selected_meal_set;

        selected_meal_set = $plated_meal.val();

        console.log( {'meal selected': selected_meal_set, 'previously selected meal set' : previously_selected_meal_set} );

        update_plated_meal_order_details('plate-meal-selected-meal-set', 'Selected Meal Set: ', selected_meal_set, $plated_meal.data('price'), true );

        // Load the entrees, hors doeuvres an desserts via ajax
        let data = new FormData();
        data.append('action', 'ttc_get_plated_meal_parts');

        // get product id from sliding-form
        data.append( 'product_id', $plated_meal.parents('.sliding-form').data('product_id') );

        // get meal index from meal-item
        data.append( 'meal_index', $plated_meal.parent('.meal-item').data('meal_index') );

        // Before retrieving the meal part contents, backup them up.
        if( previously_selected_meal_set !== "" )
        {
            if( ! (previously_selected_meal_set in selected_meal_set_parts ) )
                selected_meal_set_parts[previously_selected_meal_set] = {};

            selected_meal_set_parts[previously_selected_meal_set] = {
                choose_entrees: $choose_entrees.html(),
                choose_hors_doeuvres: $choose_hors_doeuvres.html(),
                choose_desserts: $choose_desserts.html()
            };
        }

        $choose_entrees.addClass('loading');
        $choose_hors_doeuvres.addClass('loading');
        $choose_desserts.addClass('loading');

        console.log('Selected meal set parts', selected_meal_set_parts);

        if( selected_meal_set in selected_meal_set_parts ) {
            $choose_entrees.html( selected_meal_set_parts[selected_meal_set]['choose_entrees'] ).removeClass('loading');
            $choose_hors_doeuvres.html( selected_meal_set_parts[selected_meal_set]['choose_hors_doeuvres'] ).removeClass('loading');
            $choose_desserts.html( selected_meal_set_parts[selected_meal_set]['choose_desserts'] ).removeClass('loading');

            // Populate values
            console.log(order_details_dependency);

            if(order_details_dependency.length) {
                let selected_meal_set_dependency = order_details_dependency['plate-meal-selected-meal-set'][selected_meal_set];
                for( let meal_part_id in selected_meal_set_dependency ) {
                    let prefix = 'plate-meal-selected-meal-set-' + wpFeSanitizeTitle(selected_meal_set) + '-plate-meal-';

                    let meal_part = meal_part_id.replace(prefix, '');

                    console.log([prefix, meal_part]);
                    if(meal_part === 'selected-dessert') {
                        console.log('[value="' + selected_meal_set_dependency[meal_part_id] + '"]', $('[value="' + selected_meal_set_dependency[meal_part_id] + '"]'))
                        $('[value="' + selected_meal_set_dependency[meal_part_id] + '"]').prop('checked', true);
                    } else if (meal_part === 'selected-hors-doeuvres') {
                        let selected_hors_doeuvres = selected_meal_set_dependency[meal_part_id].split(', ');
                        console.log(selected_hors_doeuvres);
                        for( let hors_doeuvre of selected_hors_doeuvres ) {
                            console.log('[value="' + hors_doeuvre + '"]', $('[value="' + hors_doeuvre + '"]'));
                            $('[value="' + hors_doeuvre + '"]').prop('checked', true);
                        }
                    } else {
                        console.log(meal_part, $('[name=' + meal_part + ']'));
                        $('[name=' + meal_part + ']').val(selected_meal_set_dependency[meal_part_id]);
                    }
                }
            }

        } else {
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

                    $choose_entrees
                        .removeClass('loading')
                        .find('.slide-content')
                        .html(data.entrees);
                    $choose_hors_doeuvres
                        .removeClass('loading')
                        .find('.slide-content')
                        .html(data.hors_doeuvres)
                    ;
                    $choose_desserts
                        .removeClass('loading')
                        .find('.slide-content')
                        .html(data.desserts)
                    ;

                    $choose_entrees.find('input[type=number]').attr('max', guest_count);

                    $choose_entrees.find('input[type=number]').each(function(){
                        let input_name = $(this).attr('name');
                        if( input_name in plated_meal_progress ) {
                            $(this).val( plated_meal_progress[input_name] ).change();
                        }
                    });


                    $choose_hors_doeuvres.find('input').each(function(){
                        let input_name = $(this).attr('name');

                        if( plated_meal_progress[input_name] === undefined ) return;

                        // TODO: check if plated meal progress input name is iterable
                        if( plated_meal_progress[input_name].indexOf( $(this).val() )  > -1 )
                            $(this).click();

                    });

                    $choose_desserts.find('input').each(function(){
                        let input_name = $(this).attr('name');

                        if( plated_meal_progress[input_name] === undefined ) return;

                        // TODO: check if plated meal progress input name is iterable
                        if( plated_meal_progress[input_name] === $(this).val() )
                            $(this).click();

                    })

                }
            });
        }

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
                sum += parseFloat( obj[el] );
            }
        }

        sum = Math.round(sum * 100) / 100
        return sum;
    }

    function get_sum_of_order_detail_prices() {
        let sum = 0;
        $('.plated-meal-order-detail:not(.hidden) .plated-meal-order-detail-item-price').each(function(){
            sum += get_num( $(this).text() );
        });

        return sum;
    }

    function get_num(str) {
        return parseFloat( str.replace( /^\D+/g, '') );
    }

    // Update the max attribute of each guest plate field when one gets changed
    $choose_entrees.on('change', '.entree-number-of-guest-plates', function(){
        let total_plates_entered = 0;

        // find all number of guest plates field
        let $entree_plates = $choose_entrees.find('.entree-number-of-guest-plates');

        // retrieve total number of plates entered
        $entree_plates.each(function(){
            let plate_count = $(this).val() ? parseInt( $(this).val() ) : 0;

            populate_meal_plate( $(this).attr("name"), plate_count );

            update_plated_meal_order_details('plate-meal-' + $(this).attr("name"),
                'Number of Plates for ' + $(this).data('dish_name') + ': ',
                plate_count,
                0,
                'plate-meal-selected-meal-set',
                selected_meal_set );

            let total_count = get_total_meal_plates();

            let max_count = guest_count - total_count + plate_count;

            // console.log({
            //     id: $(this).attr("name"),
            //     guestcount: guest_count,
            //     totalcount: total_count,
            //     platecount: plate_count,
            //     maxcount: max_count,
            //     meal_plates: meal_plates,
            //     current_meal_id: current_meal_id
            //     }
            // );

            // Update max attribute
            $(this).attr('max', max_count );
        })
    });


    // ------------------------------- //
    // Limit meal part checkboxes selection
    // ------------------------------- //

    // implement choice limits on plated meals (or even buffets)
    $choose_hors_doeuvres.on('change', "input[type=checkbox]", function () {

        let input_name = $(this).attr("name");
        let max_allowed = $(this).parents('.choices').attr("data-limit");
        let $checked_items = $("input[name=" + input_name + "]:checked");
        let checked_items_array = [];

        // Get amount of checked boxes with the same name
        if ($checked_items.length >= max_allowed) {

            // Disable the remaining checkboxes of the same name
            $("input[name=" + input_name + "]").not(":checked").attr("disabled", "disabled");

        } else {

            // Enable the inputs again when he unchecks one
            $("input[name=" + input_name + "]").removeAttr("disabled");

        }

        // ------------------------------- //
        // Display choices of Hors D'oeuvre
        // ------------------------------- //

        $checked_items.each(function(){
            checked_items_array.push( $(this).val() )
        });

        let price = $checked_items.length > 0 ? $(this).closest('.choices').data('price') : 0;

        update_plated_meal_order_details('plate-meal-selected-hors-doeuvres', 'Selected Hors D\'oeuvres: ', checked_items_array.join(', '), price, 'plate-meal-selected-meal-set', selected_meal_set );
    }).on('change', "input[type=radio]", function () {
        // @TODO: option to remove selection
        update_plated_meal_order_details('plate-meal-selected-hors-doeuvres', 'Selected Hors D\'oeuvres: ', $(this).val(), $(this).closest('.choices').data('price'), 'plate-meal-selected-meal-set', selected_meal_set );
    });

    // implement choice limits on plated meal parts
    $choose_desserts.on('change', "input[type=checkbox]", function () {

        var input_name = $(this).attr("name");
        var max_allowed = $(this).parents('.choices').attr("data-limit");
        let $checked_items = $("input[name=" + input_name + "]:checked");
        let checked_items_array = [];

        // Get amount of checked boxes with the same name
        if ($checked_items.length >= max_allowed) {

            // Disable the remaining checkboxes of the same name
            $("input[name=" + input_name + "]").not(":checked").attr("disabled", "disabled");

        } else {

            // Enable the inputs again when he unchecks one
            $("input[name=" + input_name + "]").removeAttr("disabled");

        }

        // ------------------------------- //
        // Display choices of Desserts
        // ------------------------------- //

        $checked_items.each(function(){
            checked_items_array.push( $(this).val() )
        });

        let price = $checked_items.length > 0 ? $(this).closest('.choices').data('price') : 0;

        update_plated_meal_order_details('plate-meal-selected-desserts', 'Selected Desserts: ', checked_items_array.join(', '), price, 'plate-meal-selected-meal-set', selected_meal_set );
    }).on('change', "input[type=radio]", function () {
        update_plated_meal_order_details('plate-meal-selected-dessert', 'Selected Dessert: ', $(this).val(), $(this).closest('.choices').data('price'), 'plate-meal-selected-meal-set', selected_meal_set );
    });


    // ------------------------------- //
    // Show Secondary Content for Addons
    // ------------------------------- //

    $('.addon > .question ~ .answer > input[type=radio]').change(function() {

        // Find Secondary Question container
        let $secondary = $(this).parent().siblings('.secondary-question');

        if( $(this).val() === $secondary.data('show_if') )
            $secondary.show();
        else
            $secondary.hide();

        // ------------------------------- //
        // Display Addon Choices
        // ------------------------------- //

        let $question = $(this).parent().siblings('.question');

        update_plated_meal_order_details('plate-meal-addon-' + $(this).attr('name'), 'Addon: ' + $question.text(), $(this).val(), $(this).data('price') );

    });



    // ------------------------------- //
    // Display Addon Secondary Choices
    // ------------------------------- //

    $('.addon > .secondary-question > .question ~ .answer > input[type=radio]').change(function() {

        // ------------------------------- //
        // Display Addon Choices
        // ------------------------------- //

        let $question = $(this).parent().siblings('.question');

        update_plated_meal_order_details('plate-meal-addon-' + $(this).attr('name'), 'Addon: ' + $question.text(), $(this).val(), $(this).data('price') );

    });

    // ------------------------------- //
    // Display Addon Time Picker
    // ------------------------------- //

    let addon_time_picker = {};

    let empty_time = { hour: '00', minute: '00', ampm: 'am' };

    function update_addon_time( id, title )
    {
        console.log('update_addon_time', addon_time_picker, id, title);

        let hour    = addon_time_picker[id]['hour'];
        let minute  = addon_time_picker[id]['minute'];
        let ampm    = addon_time_picker[id]['ampm'];

        let addon_time = `${hour}:${minute} ${ampm}`;
        update_plated_meal_order_details('plate-meal-addon-' + id, title, addon_time );
    }

    $('.addon > .secondary-question > .question ~ select').change(function() {

        // ------------------------------- //
        // Display Addon Choices
        // ------------------------------- //

        let $question = $(this).siblings('.question');

        let time_picker_id = $(this).data('id');

        if( ! ( time_picker_id in addon_time_picker ) )
            addon_time_picker[time_picker_id] = empty_time;

        console.log(addon_time_picker);

        addon_time_picker[time_picker_id][$(this).attr('name')] = $(this).val();

        update_addon_time(time_picker_id, $question.text());

    });

    //- Active state of select hors doeuvres item-//

    $(".hors_doeuvres-item").click(function(){
        console.log("hors_doeuvres clicked!");
        if($(this).hasClass("active")) {
            $(this).removeClass("active");
        } else {
            $(this).addClass("active")
        }
    });

    //- Selection of Plated Meals - Modal function -//
    const tc_modal = document.querySelector('.plated-modal-box');
    const tc_open_modal = document.querySelector('.tc-modal-open');
    const tc_close_modal = document.querySelector('.tc-modal-close');

    tc_open_modal.addEventListener('click', () => {
        tc_modal.showModal();
    })
    tc_close_modal.addEventListener('click', () => {
        tc_modal.close();
    })


    // Store progress in localStorage

    let plated_meal_progress = {};

    if( localStorage.plated_meal_order ){
        if( confirm("We see that you have an order that's in progress, we can load it for you or you can press Cancel to create a new one.") ) {
            plated_meal_progress = JSON.parse( localStorage.getItem('plated_meal_order') );
            retrieve_progress();
        }
    }

    $('#plated_meal_form').on('change', 'select, input[type=text], input[type=number], input[type=radio], input[type=checkbox], input[type=hidden], textarea', function() {

        let input_name = $(this).attr('name');

        // if checkbox, store in an array
        if( $(this).is('input[type=checkbox]') ) {

            if( plated_meal_progress[input_name] === undefined  ) {
                console.log('setting up array for plated meal progress ' + input_name );
                plated_meal_progress[input_name] = [];
            }

            if( $(this).is(':checked') ) {
                plated_meal_progress[input_name].push( $(this).val() );
            } else {
                const index = plated_meal_progress[input_name].indexOf( $(this).val() );
                if (index > -1) {
                    plated_meal_progress[input_name].splice(index, 1);
                }
            }
        }
        else
            plated_meal_progress[input_name] = $(this).val();
        console.log(['PLATED MEAL PROGRESS from changed fields', plated_meal_progress]);
    });

    function store_progress(name, value) {
        plated_meal_progress[name] = value;
        console.log(['PLATED MEAL PROGRESS from storing', plated_meal_progress]);
    }

    function retrieve_progress() {
        console.log(['PLATED MEAL PROGRESS from retrieving', plated_meal_progress]);
        for( const field in plated_meal_progress ) {
            let value = plated_meal_progress[field];

            let $field = $(`[name=${field}]`);

            if($field.is('input[type=radio]'))
                $(`[name=${field}][value="${value}"]`).click();
            else if( $field.is('input[type=checkbox]' ) ) {
                for ( const checkbox_value of value ) {
                    console.log(`[name=${field}][value="${checkbox_value}"]`);
                    $(`[name=${field}][value="${checkbox_value}"]`).click();
                }
            }

            else if(field === 'catering_date')
            {
                console.log('field is Catering Date from flatpickr', catering_flatpickr);
                let catering_date = new Date(value);

                catering_flatpickr.setDate(catering_date, true);
            }
            else
            {
                $field.val(value).change();
            }
        }
    }

    // store progress in localStorage before closing tab
    window.addEventListener('beforeunload', function (e) {
        e.preventDefault();

        localStorage.setItem('plated_meal_order', JSON.stringify(plated_meal_progress));
    });

    // Ajax call on the Add to Cart button
    $('#plated_meal_add_to_cart').click(function() {
        let data = {
            action: 'ttc_store_plated_meal_order_progress',
            product_id: $(this).val(),
            plated_meal_order_progress: plated_meal_progress,
        };

        console.log(data);

        jQuery.ajax({
            url: tco_ttc_js.ajaxurl,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                console.log(data);
            }
        });
    })
});