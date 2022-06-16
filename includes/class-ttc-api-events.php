<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\API_Events' ) ) {

    /**
     * Tco Three Tomatoes  API_Events class.
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class API_Events {

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


        /**
         * Constructor
         *
         */
        public function __construct() {

            // Template redirect to handle api requests
            add_action( 'template_redirect', array( $this, 'api_handler' ), 2 );

        }

        public function api_handler() {

            $uri = $_SERVER['REQUEST_URI'];
            if( substr($uri, 1, 14) !== 'ttc-api-events') return;

            // Get all events
            $events = $this->get_all_for_fullcalendar();

            header("HTTP/1.1 200 OK");
            header('content-type: application/json; charset=utf-8');
            die($events);

        }

        public function get_all_for_fullcalendar() {

            $start = $_GET['start'] ?? false;
            $end = $_GET['end'] ?? false;

            $event_posts = Bookables::get_upcoming([Catering::$post_type, Delivery::$post_type, Reservation::$post_type], $start, $end);

//            Acme::diep($event_posts);

            $events = array();

            foreach ( $event_posts as $event ) {
                $bookable = Bookables::get_model($event);

                $event_details = array(
                    "id" => "$event->ID",
                    "className" => "{$event->post_type}-event",
                    'title' => "#{$event->ID} {$event->post_title}, {$bookable->get_customer_name()}, {$bookable->get_number_of_guests()}",
                    'start' => date_i18n('Y-m-d', strtotime( $bookable->get_start_date() ) )
                        . "T" . date_i18n('h:i:s', strtotime( $bookable->get_start_time() ) ),
                    'end'   => date_i18n('Y-m-d', strtotime( $bookable->get_end_date() ) )
                        . "T" . date_i18n('h:i:s', strtotime( $bookable->get_end_time() ) )
                );

                if($bookable->post->post_type == 'delivery')
                    $event_details['title'] = "{$event->post_title}";

                $events[] = $event_details;

//                Acme::diep($bookable, false);
            }

            return json_encode($events);
        }

    }

    API_Events::instance();
}