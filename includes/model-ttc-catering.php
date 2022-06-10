<?php

namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Catering' ) ) {


    /**
     * TCoThreeTomatoesCatering Catering class model
     *
     * Catering object that holds fields and post object
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Catering extends Bookable
    {
        // ACF Fields
        // add here...

        public static $post_type = 'catering';

        public static $ERROR_WRONG_POST_TYPE = 882100; // 882 is a prefix for TTC, 100 is for Catering

    }
}