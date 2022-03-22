<?php

namespace TCo_Three_Tomatoes;
use TCo_Three_Tomatoes\Bookable;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Reservations' ) ) {


    /**
     * TCoThreeTomatoesCatering Reservations class
     *
     * Holds all Reservations actions
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Reservations extends Bookables
    {

    }

    Reservations::instance();

}