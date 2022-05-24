<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Plated_Meal' ) ) {


    /**
     * TCoThreeTomatoesCatering Plated_Meal class
     *
     * Holds all Plated_Meal actions
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Plated_Meal {

        public $meals_field_name = 'plated_meal_dish_Information';
        public $entree_choices_field_name = 'entree_choices';
        public $hors_doueuvres_choices_field_name = 'hors_doueuvres_choices';
        public $dessert_choices_field_name = 'dessert_choices';


        public $product_id = -1;

        /**
         * Any Singleton class.
         *
         * @var Bookables[] $instances
         */
        private static $instances = array();

        /**
         * Consctruct.
         * Private to avoid "new".
         */
        private function __construct()
        {
            add_action('woocommerce_before_add_to_cart_form', array($this, 'print_form'), 30 );

            add_action('woocommerce_single_product_summary', array($this, 'remove_add_to_cart'), 2 );

//            add_filter( 'woocommerce_add_cart_item_data', array($this, 'add_cart_item_data'), 10, 3 );

            add_action( 'woocommerce_before_calculate_totals', array($this, 'add_custom_price') );

            add_action( 'woocommerce_get_item_data', array($this, 'get_item_data'), 10, 2 );

            add_action('woocommerce_thankyou', array($this, 'create_catering_post'), 10, 1);

            $this->product_id = get_field('catering_plated_meal_product_id', 'option');

            add_action('wp_ajax_ttc_get_plated_meal_parts', array( $this, 'get_meal_parts' ) );
            add_action('wp_ajax_nopriv_ttc_get_plated_meal_parts', array( $this, 'get_meal_parts' ) );

            add_action('wp_ajax_ttc_store_plated_meal_order_progress', array( $this, 'store_plated_meal_order_progress' ) );
            add_action('wp_ajax_nopriv_ttc_store_plated_meal_order_progress', array( $this, 'store_plated_meal_order_progress' ) );

            add_action('template_redirect', array( $this, 'check_if_logged_in' ) );

        }

        /**
         * Get Instance
         *
         * @return Bookables
         */
        final public static function instance() {
            $class = get_called_class();

            if ( ! isset( $instances[ $class ] ) ) {
                self::$instances[ $class ] = new $class();
            }

            return self::$instances[ $class ];
        }

        public function retrieve_all_meals_info( $product_id )
        {
            $meals_fields = get_field($this->meals_field_name, $product_id);
            return $meals_fields;
        }

        public function retrieve_meal_info( $product_id, $index )
        {
            $meals = $this->retrieve_all_meals_info( $product_id );

            $meal = $meals[$index];
            return $meal;
        }

        public function get_meal_parts()
        {
            Acme::display_errors();
            if( !isset( $_POST['product_id'] ) ) return [ 'success' => false ];

            $product_id = $_POST['product_id'];
            $meal_index = $_POST['meal_index'];
            $return = array('product_id' => $product_id );
            $meal_info = $this->retrieve_meal_info( $product_id, $meal_index );

            $return['entrees'] = Acme::get_template('forms/catering/plated-03-1-choose-entrees', array( 'meal' => $meal_info ) );
            $return['hors_doeuvres'] = Acme::get_template('forms/catering/plated-03-2-choose-hors-doeuvres', array( 'meal' => $meal_info ) );
            $return['desserts'] = Acme::get_template('forms/catering/plated-03-3-choose-desserts', array( 'meal' => $meal_info ) );

            die( json_encode($return) );
        }

        public function get_meal_addon_modules($product_id) {
            $addons = get_field('meal_addon_modules', $product_id);

            return $addons;
        }

        public function get_meal_notes($product_id) {
            $addons = get_field('notes', $product_id);

            return $addons;
        }

        public function print_form()
        {

            if( get_the_ID() != $this->product_id ) return;

            // Remove Add To Cart
            remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

            $product_id = get_the_ID();
            $meals_fields = $this->retrieve_all_meals_info($product_id);
            $addon_modules = $this->get_meal_addon_modules($product_id);

            $addon_slides = array();

            if (!empty($addon_modules) )
            {
                foreach( $addon_modules as $module ) {

                    $module[ 'question_slug' ] = sanitize_title( $module['question'] );
                    $module[ 'imgsrc_for_yes' ] = Acme::get_image_link( $module['image_for_yes'] );
                    $module[ 'imgsrc_for_no' ] = Acme::get_image_link( $module['image_for_no'] );
                    $module[ 'currency' ] = get_woocommerce_currency_symbol();


                    $module[ 'secondary_question_slug' ] = isset( $module['secondary_question'] ) ? sanitize_title( $module['secondary_question'] ) : '';

                    if( isset( $module['choices'] ) ) {
                        foreach( $module['choices'] as $key => $choice ) {
                            $module['choices'][$key]['imgsrc'] = Acme::get_image_link( $choice['image'] );
                        }
                    }

                    $addon_slides[] = array(
                        'id'        => 'plated-addon-' . $module['question_slug'],
                        'header'    => 'Add-on',
                        'content'   => Acme::get_template("forms/addons/{$module['acf_fc_layout']}", $module ),
                    );
                }
            }

            $meal_notes = $this->get_meal_notes($product_id);
            $notes_slides = array();

            if (!empty($meal_notes) )
            {
                foreach( $meal_notes as $note ) {

                    $note[ 'question_slug' ] = sanitize_title( $note['question'] );

                    $notes_slides[] = array(
                        'id'        => 'plated-notes-' . $module['question_slug'],
                        'header'    => 'Notes',
                        'content'   => Acme::get_template("forms/fields/notes-textarea", $note ),
                    );
                }
            }

            // Get venues and occasion terms
            $args = array('post_type' => 'catering','number' => '999');
            $venues = get_terms( 'venue',
                [
                    'hide_empty' => false,
                ] + $args );
            $occasions = get_terms( 'occasion',
                [
                    'hide_empty' => false,
                ] + $args );



//            Acme::diep($addons);
            $slides = array(
                array (
                    'id'        => 'plated-basic-event-details',
                    'header'    => 'Tell us about the event',
                    'content'   => Acme::get_template('forms/catering/plated-01-basic-event-details', array( 'venues' => $venues, 'occasions' => $occasions ) ),
                ),
                array (
                    'id'        => 'plated-schedule-date-times',
                    'header'    => 'When do you need it?',
                    'content'   => Acme::get_template('forms/catering/plated-02-schedule-date-times', array( 'disabled_dates' => Bookables::get_fully_booked_dates('Y-m-d' ) ) ),
                ),
                array (
                    'id'        => 'plated-choose-meal-set',
                    'header'    => 'Choose a meal:',
                    'content'   => Acme::get_template('forms/catering/plated-03-0-choose-meal-set', array( 'plated_meals' => $meals_fields ) ),
                ),
                array (
                    'id'        => 'plated-choose-entrees',
                    'header'    => 'Choose Entrees:',
                    'content'   => Acme::get_template('forms/catering/plated-03-1-choose-entrees', array( 'meal' => false ) ),
                ),
                array (
                    'id'        => 'plated-choose-hors-doeuvres',
                    'header'    => 'Choose Hors D\'oeuvres:',
                    'content'   => Acme::get_template('forms/catering/plated-03-2-choose-hors-doeuvres', array( 'meal' => false ) ),
                ),
                array (
                    'id'        => 'plated-choose-desserts',
                    'header'    => 'Choose Desserts:',
                    'content'   => Acme::get_template('forms/catering/plated-03-3-choose-desserts', array( 'meal' => false ) ),
                ),
            );

            $final_slides = array (
                array (
                    'id'        => 'plated-terms-conditions',
                    'header'    => 'Terms and Conditions',
                    'content'   => Acme::get_template('forms/catering/plated-07-terms-and-conditions-submit'),
                ),
            );

            $slides = array_merge($slides, $addon_slides, $notes_slides, $final_slides);

            echo Acme::get_template('forms/slider-form',
                [   'slides' => $slides,
                    'data'=> [
                        'product_id' => $product_id,
                        'validation' => 'inline',
                        'validation-trigger' => '.slide-next, .slide-prev'
                    ],
                    'form_id' => 'plated_meal_form'
                ] );
//            echo Acme::get_template('forms/catering/plated-meals', array( 'plated_meals' => $fields ) );
        }

        // @TODO: Hide original add to cart button.
        //      Replace with a new add to cart button that calls an ajax function on click instead
        //      This ajax function will return a redirect of ?add_to_cart={product_id} and has the SESSIONS populated

        // @TODO: Add cart total details


        public function remove_add_to_cart() {

            // TODO: add check if product is plated meal
            add_action('woocommerce_before_add_to_cart_button', function(){ echo "<div style='display:none;'>"; }, 10);
            add_action('woocommerce_after_add_to_cart_button', function(){ echo "</div>"; }, 10);
            add_action('woocommerce_after_add_to_cart_button', function(){
                global $product;
                $product_id = $product->get_id();

                echo "<button type='button' id='plated_meal_add_to_cart' name='add-to-cart' value='{$product_id}' class='single_add_to_cart_button button alt'>Add to Cart</button>";
            }, 20);
        }
        /*		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?><!--</button>-->*/

        public function store_plated_meal_order_progress() {
//            Acme::diep($_POST);
            $_SESSION['ttc_plated_meal_progress'] = $_POST['plated_meal_order_progress'];
            $_SESSION['ttc_plated_meal_progress']['product_id'] = $_POST['product_id'];
            $_SESSION['ttc_plated_meal_progress']['regular_price'] = $_POST['calculated_price'];

            $return = array(
                'redirect' => '/cart/?add-to-cart=' . $_POST['product_id']
            );

            die( json_encode($return) );
        }

        public function add_custom_price( $cart_object ) {
//            return;
//            Acme::diep(['add_custom_price', $_SESSION, $cart_object ]);
            if( !isset( $_SESSION['ttc_plated_meal_progress']['product_id'] ) ) return;

            $custom_price = $_SESSION['ttc_plated_meal_progress']['regular_price']; // This will be your custome price
//            Acme::diep( $cart_object );

            foreach ( $cart_object->cart_contents as $key => $value ) {
                $product = $value['data'];
                if( $product->get_id() != $_SESSION['ttc_plated_meal_progress']['product_id'] ) continue;
//                Acme::diep($product->get_id());
//                $product->set_regular_price($custom_price);
//                $product->set_sale_price($custom_price);
                // for WooCommerce version 3+ use:
                $value['data']->set_price($custom_price);
            }
        }


        public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

//            Acme::diep([$cart_item_data, $product_id, $variation_id], false);

            // get product id & price
            $product = wc_get_product( $product_id );
            $price = $product->get_price();
            // extra pack checkbox
            if( ! empty( $_POST['extra_pack'] ) ) {

                $cart_item_data['new_price'] = $price + 15;
            }
            return $cart_item_data;
        }

        /**
         * Display custom item data in the cart
         */
        public function get_item_data( $item_data, $cart_item_data ) {
//            Acme::diep(['get_item_data', $item_data, $_SESSION], false);
            if( $cart_item_data['product_id'] != $_SESSION['ttc_plated_meal_progress']['product_id'] ) return;

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

            foreach ( $_SESSION['ttc_plated_meal_progress'] as $key => $value ) {

                if( in_array( $key, $exclude) ) continue;

                $key = str_replace( ['-', '_'], ' ', $key );
                $key = ucfirst($key);

                if( is_array($value) )
                    $value = implode( ', ', $value );

                $value = str_replace( ['-', '_'], ' ', $value );
                $value = ucfirst($value);

                $item_data[] = array(
                    'key' => $key,
                    'value' => $value
                );
            }


            return $item_data;
        }

        function create_catering_post( $order_id ) {
            if ( ! $order_id )
                return;

            // Allow code execution only once
            if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

                // Get an instance of the WC_Order object
                $order = wc_get_order( $order_id );

                // Get the order key
                $order_key = $order->get_order_key();

                // Get the order number
                $order_key = $order->get_order_number();

                if($order->is_paid())
                    $paid = __('yes');
                else
                    $paid = __('no');

                // Loop through order items
                foreach ( $order->get_items() as $item_id => $item ) {

                    // Get the product object
                    $product = $item->get_product();

                    // Get the product Id
                    $product_id = $product->get_id();


                    if( isset( $_SESSION['ttc_plated_meal_progress'] ) && $_SESSION['ttc_plated_meal_progress']['product_id'] == $product_id ) {

                        $catering_order = $_SESSION['ttc_plated_meal_progress'];

                        // Create new post
                        $title = $catering_order['catering_date'] . ' / ' . wp_get_current_user()->display_name . ' / ' . $catering_order['plated_meal'] . ' / ' . $catering_order['number_of_guests'] . ' Guests';

                        $status = $order->is_paid() ? 'publish' : 'draft';
                        
                        $new_post = array(
                            'post_title' => $title,
                            'post_status' => $status,
                            'post_type' => Catering::$post_type,
                        );

                        $post_id = wp_insert_post($new_post);

                        $catering = new Catering($post_id);
                        $catering->start_date = $catering_order['catering_date'];
                        $catering->start_time = $catering_order['plate_meal_guest_arrival_time'];
                        $catering->end_date = $catering_order['catering_date'];
                        $catering->end_time = $catering_order['plate_meal_guest_departure_time'];
                        $catering->number_of_guests = $catering_order['number_of_guests'];
                        $catering->order_id = $order_id;

                        $catering->save();

                    }

                }

                // Output some data
//                echo '<p>Order ID: '. $order_id . ' — Order Status: ' . $order->get_status() . ' — Order is paid: ' . $paid . '</p>';

                // Flag the action as done (to avoid repetitions on reload for example)
                $order->update_meta_data( '_thankyou_action_done', true );
                $order->save();
            }
        }

        function check_if_logged_in()
        {
            $pageid = get_option( 'woocommerce_checkout_page_id' );
            if(!is_user_logged_in() && is_page($pageid))
            {
                $url = add_query_arg(
                    'redirect_to',
                    get_permalink($pageid),
                    site_url('/my-account/') // your my account url
                );
                wp_redirect($url);
                exit;
            }
            if(is_user_logged_in())
            {
                if(is_page(get_option( 'woocommerce_myaccount_page_id' )))
                {

                    $redirect = $_GET['redirect_to'];
                    if (isset($redirect)) {
                        echo '<script>window.location.href = "'.$redirect.'";</script>';
                    }

                }
            }
        }
    }

    Plated_Meal::instance();
}

