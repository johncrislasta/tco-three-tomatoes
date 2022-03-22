<?php

namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Caterings' ) ) {


    /**
     * TCoThreeTomatoesCatering Caterings class
     *
     * Holds all Caterings actions
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Caterings extends Bookables
    {
        public static $post_type = 'catering';

        // Taxonomies
        public static $tax_catering_option = 'catering_option';
        public static $tax_venue = 'venue';

        public static function get_options()
        {
            $terms = get_terms( array (
                'taxonomy' =>  self::$tax_catering_option,
                'hide_empty' => false,
            ) );

            return $terms;
        }
    }

    Caterings::instance();
}