<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Calendar_Admin' ) ) {


    /**
     * TCoThreeTomatoesCatering Calendar_Admin class
     *
     * Holds all WordPress Calendar_Admin
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Calendar_Admin
    {
        /**
         * The single instance of the class
         *
         * @since 1.0.0
         */
        protected static $_instance = null;


        /**
         * Get the instance
         *
         * @since 1.0.0
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }


        public function __construct() {

            // Add Calendar Admin Page

            add_action( 'admin_menu', array( $this, 'calendar_admin_menu' ) );
        }



        function calendar_admin_menu() {

            add_menu_page(
                __( 'Calendar', TTC_TEXT_DOMAIN ),
                __( 'Calendar', TTC_TEXT_DOMAIN ),
                'manage_options',
                'ttc-calendar',
                array( $this, 'calendar_admin_page_contents' ),
                'dashicons-schedule',
                3
            );

        }

        function calendar_admin_page_contents() {

            echo Acme::get_template('admin/calendar');
            echo Acme::get_template('admin/modal');
        }

    }

    Calendar_Admin::instance();
}





