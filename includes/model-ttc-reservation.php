<?php

namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Reservation' ) ) {


    /**
     * TCoThreeTomatoesCatering Reservation class model
     *
     * Reservation object that holds fields and post object
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Reservation extends Bookable
    {

        public static $post_type = 'reservation';

        // ACF Fields
        // add here...

        public static $ERROR_WRONG_POST_TYPE = 882300; // 882 is a prefix for TTC, 300 is for Reservation

    }
}