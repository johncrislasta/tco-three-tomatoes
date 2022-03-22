<?php

namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Delivery' ) ) {


    /**
     * TCoThreeTomatoesCatering Delivery class model
     *
     * Delivery object that holds fields and post object
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Delivery extends Bookable
    {

        public static $post_type = 'delivery';

        // ACF Fields
        public $include_plastic_utensils;

        public static $ERROR_WRONG_POST_TYPE = 882200; // 882 is a prefix for TTC, 200 is for Delivery

        public function __construct($post)
        {
            parent::__construct($post);

            $this->include_plastic_utensils = get_field('include_plastic_utensils', $this->post->ID );
        }
    }
}