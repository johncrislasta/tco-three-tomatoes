<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Front' ) ) {


    /**
     * TCoThreeTomatoesCatering Front class
     *
     * Holds all WordPress Front End actions
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Front {

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

            //Load scripts and styles for the frontend
            add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts_styles'));

        }


        /**
         * Load Required JavaScripts and CSS
         *
         * @since 1.0.0
         */
        function enqueue_scripts_styles(){

            wp_enqueue_style('tco_ttc_front_css', TTC_ASSETS . 'css/app.css', false, TTC_VERSION);
            wp_enqueue_script('tco_ttc_front_js', TTC_ASSETS . 'js/app.js', array('jquery'), TTC_VERSION, true);
            wp_localize_script('tco_ttc_front_js',
                'tco_ttc_js', array(
                    'tco_ttc_url'   => get_bloginfo('url'),
                    'ajaxurl'       => admin_url('admin-ajax.php'),
                    'uploading'     => __("Uploading", "tco_ttc_checkout"),
                    'processing'    => __("Processing, please wait", "tco_ttc_checkout"),
                    'error'         => __("An error occured. Please try again", "tco_ttc_checkout"),
                    'loading_image' => TTC_URL.'/assets/img/update.gif',
                    'settings'      => array( 'delivery' => TTC_Settings()->delivery, 'catering' => TTC_Settings()->catering ),
                    'currency'      => get_option( 'woocommerce_currency' ),
                    'currency_symbol' => get_woocommerce_currency_symbol()


                ));

            wp_enqueue_script('tco_ttc_slider_form', TTC_ASSETS . 'js/slider-form.js', array('jquery'), '', true);
            wp_enqueue_script('tco_ttc_plated_meals', TTC_ASSETS . 'js/plated-meals.js', array('jquery'), '', true);

            wp_enqueue_style('tco_ttc_vendor_datepicker_css', TTC_URL . '/assets/vendor/datepicker/css/datepicker.material.css', false, TTC_VERSION);
            wp_enqueue_script('tco_ttc_vendor_datepicker_js', TTC_URL . '/assets/vendor/datepicker/js/datepicker.js', array('jquery'), '', true);

        }

    }

    Front::instance();

}
?>