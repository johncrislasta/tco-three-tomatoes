<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Settings' ) ) {

    /**
     * TCoThreeTomatoesCatering Settings class
     *
     * Holds all Settings variables
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Settings
    {

        /**
         * The single instance of the class
         *
         * @since 1.0.0
         */
        protected static $_instance = null;

        public $delivery;
        public $catering;

        public $general;

        /**
         * Get the instance
         *
         * @since 1.0.0
         */
        public static function instance()
        {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }


        public function __construct()
        {
            //Load settings
            add_action( 'acf/init', array( $this, 'load_settings'), 10 );

        }

        public function load_settings()
        {
            $this->delivery['allowed_days_in_advance'] = get_field('allowed_number_of_days_in_advance_to_place_a_delivery_schedule', 'option');
            $this->delivery['window_times'] = get_field('delivery_window_times', 'option');
            $this->delivery['events_color'] = get_field('delivery_events_color', 'option');

            $this->catering['allowed_days_in_advance'] = get_field('allowed_number_of_days_in_advance_to_place_a_catering_schedule', 'option');
            $this->catering['events_color'] = get_field('catering_events_color', 'option');

            $this->general['limit_number_of_bookings_per_day'] = get_field('limit_number_of_bookings_per_day', 'option');
        }

    }

    function TTC_Settings() {
        return Settings::instance();
    }

    $TTC_Settings = TTC_Settings();
}
