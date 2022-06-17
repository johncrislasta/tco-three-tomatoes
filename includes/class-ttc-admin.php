<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Admin' ) ) {


	/**
	 * TCoThreeTomatoesCatering Admin class
     * 
	 * Holds all WordPress Admin End actions
     *
	 * @package TCoThreeTomatoesCatering
	 * @since 1.0.0
	 */
	class Admin {

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

			//Load scripts and styles for the admin

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );

            add_action('acf/init', array( $this, 'add_themes_options_page' ) );

            // Disable Gutenberg
            add_filter('use_block_editor_for_post_type', '__return_false', 10);

            add_action( 'admin_footer', array( $this, 'prefix_add_footer_styles' ) );

            add_action( 'admin_init', [$this, 'session_init'] );

        }


		/**
         * Load Required JavaScripts and CSS
         *
         * @since 1.0.0
         */
        function enqueue_scripts_styles(){

			//Load dialogs

            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
            wp_enqueue_style( 'jquery-ui' );

            wp_enqueue_style('tco_ttc_vendor_fullcalendar_css', TTC_ASSETS . 'vendor/fullcalendar/css/main.min.css', false, TTC_VERSION);
            wp_enqueue_script('tco_ttc_vendor_fullcalendar_js', TTC_ASSETS . 'vendor/fullcalendar/js/main.min.js', array('jquery'), '', true);


			wp_enqueue_style('tco_ttc_admin_css', TTC_ASSETS . 'css/admin.css', false, TTC_VERSION);
            wp_enqueue_script('tco_ttc_admin_js', TTC_ASSETS . 'js/admin.js', array('jquery'), '', true);

            wp_enqueue_script('tco_ttc_booking_form_js', TTC_ASSETS . 'js/booking-form.js', array('jquery'), '', true);
            wp_enqueue_script('tco_ttc_tabs_js', TTC_ASSETS . 'js/tabs.js', array('jquery'), '', true);


            wp_localize_script('tco_ttc_admin_js',
                'tco_ttc_js', array(
                'tco_ttc_url'       => get_bloginfo('url'),
                'ajaxurl'           => admin_url('admin-ajax.php'),
                'uploading'         => __("Uploading", "tco_ttc_checkout"),
                'processing'        => __("Processing, please wait", "tco_ttc_checkout"),
                'error'             => __("An error occured. Please try again", "tco_ttc_checkout"),
                'loading_image'     => TTC_URL.'/assets/img/update.gif',
                'delivery_portal'   => get_field( 'delivery_portal_url', 'option' ),
            ));

            // I recommend to add additional conditions just to not to load the scripts on each page

            if ( ! did_action( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }

            wp_enqueue_script( 'tco_ttc.booking_uploads_js', TTC_ASSETS . 'js/booking-uploads.js', array( 'jquery' ) );

        }

        function prefix_add_footer_styles()
        {
            $custom_inline_style = ':root { 
                --delivery-events-color: ' . TTC_Settings()->delivery['events_color']. '; 
                --catering-events-color: ' . TTC_Settings()->catering['events_color']. ';}';

            echo "<style>$custom_inline_style</style>";

//            Acme::diep(['prefix add footer styles', $custom_inline_style, TTC_Settings()]);
        }

        function add_themes_options_page() {

            // Check function exists.
            if( function_exists('acf_add_options_page') ) {

                // Register options page.
                $option_page = acf_add_options_page(array(
                    'page_title'    => __('Three Tomatoes Catering Settings'),
                    'menu_title'    => __('Three Tomatoes'),
                    'menu_slug'     => 'tco-ttc-settings',
                    'capability'    => 'edit_posts',
                    'redirect'      => false
                ));


                // Add sub page.
                $delivery_settings = acf_add_options_sub_page(array(
                    'page_title'  => __('Delivery Settings'),
                    'menu_title'  => __('Delivery Settings'),
                    'parent_slug' => $option_page['menu_slug'],
                ));

                // Add sub page.
                $catering_settings = acf_add_options_sub_page(array(
                    'page_title'  => __('Catering Settings'),
                    'menu_title'  => __('Catering Settings'),
                    'parent_slug' => $option_page['menu_slug'],
                ));
            }
        }

        public function session_init() {
            if (!session_id()) {
                session_start();
            }
        }

        public function current_url ( $args = [] ) {
             return admin_url( sprintf('admin.php?%s', http_build_query(array_merge($_GET, $args) ) ) );
        }
	}

	Admin::instance();

}
?>