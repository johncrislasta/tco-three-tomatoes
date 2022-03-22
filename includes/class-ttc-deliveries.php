<?php

namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Deliveries' ) ) {


    /**
     * TCoThreeTomatoesCatering Deliveries class
     *
     * Holds all Deliveries actions
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Deliveries extends Bookables
    {
        public static $post_type = 'delivery';

    }

    Deliveries::instance();

}