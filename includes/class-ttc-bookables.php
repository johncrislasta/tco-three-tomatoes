<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Bookables' ) ) {


	/**
	 * TCoThreeTomatoesCatering Bookables class
     * 
	 * Holds all Bookables actions
     *
	 * @package TCoThreeTomatoesCatering
	 * @since 1.0.0
	 */
	class Bookables {

        public static $post_type = '';

        /**
         * Any Singleton class.
         *
         * @var Bookables[] $instances
         */
        private static $instances = array();

        /**
         * Consctruct.
         * Private to avoid "new".
         */
        private function __construct()
        {
            add_action( 'wp_insert_post', array( $this, 'new_bookable' ), 10, 3 );
        }

        /**
         * Get Instance
         *
         * @return Bookables
         */
        final public static function instance() {
            $class = get_called_class();

            if ( ! isset( $instances[ $class ] ) ) {
                self::$instances[ $class ] = new $class();
            }

            return self::$instances[ $class ];
        }


        /**
         *   Do something when a new book is created
         */
        public function new_bookable($post_id, $post, $update) {
            if ($post->post_type == static::$post_type && $post->post_status == 'publish' && empty(get_post_meta( $post_id, 'check_if_run_once' ))) {
//                Acme::diep(['new_bookable', 'created_new_' . static::$post_type, $post->post_type, $post->post_status, $post_id]);
                # New Post

                # Do something here...
                do_action('ttc_created_new_' . static::$post_type, $post_id, $post);

                # And update the meta so it won't run again
                update_post_meta( $post_id, 'check_if_run_once', true );
            }
        }

        public static function get_fully_booked_dates($format = "m/d/Y") {
//            die('get_fully_booked_dates format: ' . $format);

            $bookings_limit = TTC_Settings()->general['limit_number_of_bookings_per_day'];


            if( empty( $bookings_limit ) || $bookings_limit <= 1 )
                return self::get_upcoming_dates([Catering::$post_type, Delivery::$post_type, Reservation::$post_type], $format);

            // Either loop through the dates OR do a DB Query
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
                        AND
                            post_type IN (%s, %s, %s)   
                        GROUP BY
                            meta_value
                        HAVING 
                            COUNT(post_id) > %d",
                    'start_date',
                    'publish',
                    Catering::$post_type,
                    Delivery::$post_type,
                    Reservation::$post_type,
                    $bookings_limit - 1
                )
            );

            $dates = [];
            foreach ( $result as $r ) {
                $dates[] = date_i18n($format, strtotime($r->meta_value));
            }

            // Get blocked off dates from reservation
            $result = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT 
                            DISTINCT pm.meta_value 
                        FROM {$wpdb->postmeta} pm 
                        WHERE 
                            pm.post_id IN 
                                ( 
                                SELECT pm.post_id 
                                FROM {$wpdb->postmeta} pm
                                LEFT JOIN {$wpdb->posts} p 
                                    ON p.ID = pm.post_id
                                WHERE pm.meta_key = '%s'
                                    AND pm.meta_value = '%s'
                                    AND p.post_status = '%s'
                                    AND p.post_type = '%s'
                            ) 
                        AND 
                            pm.meta_key = '%s'",
                    'block_off_date_for_all',
                    1,
                    'publish',
                    Reservation::$post_type,
                    'start_date'
                )
            );

            $blocked_dates = [];
            foreach ( $result as $r ) {
                $blocked_dates[] = date_i18n($format, strtotime($r->meta_value));
            }

            $dates = $array = array_unique(array_merge($dates, $blocked_dates));

            return $dates;
        }

        public static function get_upcoming_dates($post_types = [], $format = "m/d/Y")
        {
            $upcoming = static::get_upcoming($post_types);
            $dates = array();

            foreach( $upcoming as $post ) {
                $bookable = static::get_model($post);

//                Acme::diep([$bookable, $post],false);

                $dates[] = $format ? date_i18n( $format, strtotime( $bookable->start_date ) ) : $bookable->start_date;
            }

//            Acme::diep([$upcoming, $dates]);
            return $dates;
        }

        /**
         * @param $post
         * @return Bookable
         */
        public static function get_model($post) {

            $model = 'TCo_Three_Tomatoes\\'.ucfirst($post->post_type);
            $bookable = new $model($post);

            return $bookable;
        }

        public static function get_upcoming($post_types = [], $start_date_time = false, $end_date_time = false)
        {
            $current_date = date_i18n('Ymd', strtotime("now"));
            $next_year_date = date_i18n('Ymd', strtotime("+1 year"));

            $dates_interval = [];
            $start_interval = array (
                    'key'     => 'start_date',
                    'value'   => $current_date,
                    'compare' => '>',
                );
            $end_interval = array (
                    'key'     => 'start_date',
                    'value'   => $next_year_date,
                    'compare' => '<=',
                );

            if( $start_date_time )
                $start_interval['value'] = date_i18n('Ymd', strtotime($start_date_time) );

            if( $end_date_time )
                $end_interval['value'] = date_i18n('Ymd', strtotime($end_date_time) );

            $dates_interval[] = $start_interval;
            $dates_interval[] = $end_interval;

            $args = array (
                'post_type' => empty($post_types) ? static::$post_type : $post_types,
                'meta_query'=> array(
                    'relation'      => 'AND',
                    array (
                        'key'       => 'start_date',
                        'compare'   => 'EXISTS',
                        'type'      => 'DATE'
                    ),
                ) + $dates_interval,
                'meta_key'    => 'start_date',
                'orderby'     => 'meta_value',
                'order'       => 'ASC',
                'post_status' => 'publish',
                'posts_per_page' => -1
            );

            // the upcoming events query
            $upcoming_query = new \WP_Query( $args );

            return !empty($upcoming_query->posts) ? $upcoming_query->posts : false;
        }


        /**
         * Avoid clone instance
         */
        private function __clone() {
        }

        /**
         * Avoid serialize instance
         */
        private function __sleep() {
        }

        /**
         * Avoid unserialize instance
         */
        private function __wakeup() {
        }

	}

    Bookables::instance();
}
?>