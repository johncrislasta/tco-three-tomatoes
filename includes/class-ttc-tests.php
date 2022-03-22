<?php
namespace TCo_Three_Tomatoes;

use TCo_Three_Tomatoes\Reservation, TCo_Three_Tomatoes\Delivery, TCo_Three_Tomatoes\Catering;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Tests' ) ) {


	/**
	 * TCoThreeTomatoesCatering Tests class
     * 
	 * Test functionalities
     *
	 * @package TCoThreeTomatoesCatering
	 * @since 1.0.0
	 */
	class Tests {

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
            // Test via template_redirect
            add_action('wp', array( $this, 'do_tests' ), 1 );
//            add_action('created_new_catering', array( $this, 'create_catering' ), 10, 2 );
		}

		public function do_tests() {
            if( ! isset($_GET['tco_ttc_tests']) ) return;
//            $this->get_all_future_dates();
//            $this->get_all_fully_booked_dates();
            $this->get_bookable_media();
        }

        public function get_bookable_media(){
            $bookable = new Catering(89);
            Acme::diep($bookable->get_media());
        }

        public function get_all_fully_booked_dates() {
            global $wpdb;
            $result = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT
                                meta_value,
                                COUNT(post_id)
                            FROM
                                $wpdb->postmeta
                            LEFT JOIN $wpdb->posts
                                ON post_id =  ID
                            WHERE
                                meta_key = %s
                            AND
                                post_status = %s    
                            GROUP BY
                                meta_value
                            HAVING 
                                COUNT(post_id) > %d",
                    'start_date',
                    'publish',
                    1
                )
            );

            Acme::diep([$result, Bookables::get_fully_booked_dates()]);
        }

        public function create_catering($post_id, $post)
        {
            Acme::diep(['creating catering action hook', $post_id, $post]);
        }

        public function get_all_future_dates(){

            $dates = Bookables::instance()->get_upcoming_dates([Reservation::$post_type, Catering::$post_type, Delivery::$post_type]);
            Acme::diep([$dates]);
//            wp_insert_post();
        }

	}

	Tests::instance();

}
?>