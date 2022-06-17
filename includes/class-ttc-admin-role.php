<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Role_Admin' ) ) {


   /**
     * TCoThreeTomatoesCatering Role_Admin class
     *
     * Holds all WordPress Role_Admin
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Role_Admin {
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
            
           
        }
        
    }
   
   Role_Admin::instance();
   
}