<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Bookings' ) ) {


	/**
	 * TCoThreeTomatoesCatering Bookings class
     * 
	 * Holds all WordPress Bookings End actions
     *
	 * @package TCoThreeTomatoesCatering
	 * @since 1.0.0
	 */
	class Bookings {

	    public $booking_id;
	    public $booking_type;
	    public $bookable;

	    public static $FEED_TYPE_NOTE  = 100;
	    public static $FEED_TYPE_LOG   = 900;

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

            add_action('wp_ajax_ttc_retrieve_booking_form', array( $this, 'retrieve_booking_form' ) );

            add_action('wp_ajax_ttc_save_booking_notes', array( $this, 'save_booking_notes' ) );

            add_action('wp_ajax_ttc_save_booking_media', array( $this, 'save_booking_media' ) );

            add_action('wp_ajax_ttc_refresh_booking_feed', array( $this, 'retrieve_feed' ) );

            add_filter( 'admin_body_class', array( $this, 'add_admin_body_classes') );

        }

        function add_admin_body_classes( $classes ) {
            global $pagenow;

            if ( isset( $_GET['page'] ) && $_GET['page'] == 'ttc-calendar' ) {
                $classes .= ' booking-form';
            }

            return $classes;
        }


        public function retrieve_booking_form() {

            $return = [];

            $this->set_bookable( $_POST['id'] );

            $logs = Booking_Logs::instance()->get_all_by_post( $_POST['id'] );

            $feed = $this->get_feed();

            $media = $this->get_bookable()->get_media();

            $return['html'] = Acme::get_template('admin/booking-form', [
                'post_id' => $this->booking_id,
                'post_type' => $this->booking_type,
                'details' => [
                    'start_date'        => $this->bookable->start_date,
                    'start_time'        => $this->bookable->start_time,
                    'end_date'          => $this->bookable->end_date,
                    'end_time'          => $this->bookable->end_time,
                    'customer_name'     => $this->bookable->customer_name,
                    'number_of_guests'  => $this->bookable->number_of_guests,
                ],
                'notes' => $this->bookable->get_notes(),
                'private_notes' => $this->bookable->get_private_notes(),
                'logs' => $logs,
                'feed' => $feed,
                'uploads' => $media
            ] );

            die( $return['html'] );
        }

        public function retrieve_feed() {

            $return = [];

            $post_id = $this->get_bookable()->post->ID;

//            Acme::diep($post_id);

            $this->set_bookable( $post_id );
            $feed = $this->get_feed();

            $return['html'] = Acme::get_template('admin/booking-feed', [
                'feed' => $feed
            ] );

            die( $return['html'] );
        }

        public function save_booking_notes ()
        {
//            Acme::diep([$_POST]);
            $return = ['success' => false];

            if( empty($_POST['message'] ) )
                die( json_encode( $return ) );

            $bookable = $this->get_bookable();

            if( $_POST['visibility'] == 'public' )
                $return['note'] = $bookable->save_note( $_POST['message'] );

            else if( $_POST['visibility'] == 'private' )
                $return['note'] = $bookable->save_private_note( $_POST['message'], $_POST['type'] );

            $return['success'] = $return['note']['has_updated'];
            $return['visibility'] = $_POST['visibility'];

            die( json_encode( $return ) );
        }

        public function save_booking_media ()
        {
//            Acme::diep([$_POST]);
            $return = ['success' => false];

            if( empty($_POST['media'] ) )
                die( json_encode( $return ) );

            $bookable = $this->get_bookable();

            if( $_POST['visibility'] == 'public' )
                $return['media'] = $bookable->save_uploads( $_POST['media'] );
//
//            else if( $_POST['visibility'] == 'private' )
//                $return['note'] = $bookable->save_private_note( $_POST['message'], $_POST['type'] );

            $return['success'] = $return['media']['has_updated'];
            $return['visibility'] = $_POST['visibility'];

            die( json_encode( $return ) );
        }

        public function set_booking_id( $post_id )
        {
            $this->booking_id = $post_id;

            $_SESSION['ttc_booking'] = array(
                'booking_id' => $post_id
            );
        }

        public function get_booking_id()
        {
            return $_SESSION['ttc_booking']['booking_id'] ?: $this->booking_id;
        }

        public function set_booking_type( $post_type )
        {
            $this->booking_type = $post_type;

            $_SESSION['ttc_booking'] = array(
                'booking_type' => $post_type
            );
        }

        public function get_booking_type()
        {
            return $_SESSION['ttc_booking']['booking_type'] ?: $this->booking_type;
        }

        public function set_bookable( $post_id ) {

            $this->set_booking_id( $post_id );

            $post = get_post( $post_id );
//            Acme::diep($_POST);
            $post_type = $post->post_type;

            $this->set_booking_type( $post_type );

            $bookable_type = 'TCo_Three_Tomatoes\\' . ucfirst($post_type);

            $bookable = new $bookable_type($post);

            $this->bookable = $bookable;

            $_SESSION['ttc_booking'] = array(
                'bookable' => $bookable
            );
        }

        /**
         * @return Bookable
         */
        public function get_bookable()
        {
            return $_SESSION['ttc_booking']['bookable'] ?: $this->bookable;
        }

        private function get_feed_key( $datetime, $feedtype, $index )
        {
            $feedtype = 'FEED_TYPE_' . strtoupper($feedtype);

            return strtotime($datetime)
                . self::$$feedtype
                . str_pad($index, 3, '0', STR_PAD_LEFT);
        }

        /**
         * The feed is a consolidation of the logs, notes, and attachments.
         */
        public function get_feed()
        {
            $feed = array();

            $logs = Booking_Logs::instance()->get_all_by_post( $this->get_bookable()->post->ID, -1 );

            $feed_count = 0;
            // Feed will have timestamp_feedtype_uniqueSuffix
            foreach( $logs as $log) {
                $feed_count++;
                $feed_key = $this->get_feed_key($log->date_created, 'log', $feed_count);
                $feed[ $feed_key ] = [
                    'user'      => $log->get_user_name(),
                    'content'   => $log->text,
                    'date'      => $log->get_date_created(),
                    'type'      => 'log'
                ];
            }

            $bookable = $this->get_bookable();

            $notes = $bookable->get_notes();

            foreach( $notes as $note) {
                $feed_count++;
                $author = $note['author'];
                $feed_key = $this->get_feed_key($note['date_sent'], 'note', $feed_count);
                $feed[ $feed_key ] = [
                    'user'      => $author['display_name'],
                    'avatar'    => $author['user_avatar'],
                    'content'   => $note['message'],
                    'time_ago'  => Acme::get_time_ago( $note['date_sent'] ),
                    'date'      => Acme::get_datetime_formatted( $note['date_sent'] ),
                    'type'      => 'note'
                ];
            }

            $private_notes = $bookable->get_private_notes();

            foreach( $private_notes as $note) {
                $feed_count++;
                $author = $note['author'];
                $feed_key = $this->get_feed_key($note['date_sent'], 'note', $feed_count);
                $feed[ $feed_key ] = [
                    'user'      => $author['display_name'],
                    'avatar'    => $author['user_avatar'],
                    'content'   => $note['message'],
                    'note_type' => $note['type'],
                    'time_ago'  => Acme::get_time_ago( $note['date_sent'] ),
                    'date'      => Acme::get_datetime_formatted( $note['date_sent'] ),
                    'type'      => 'private note'
                ];
            }

            krsort($feed);
//            Acme::diep($feed);
            return $feed;
        }
	}

	Bookings::instance();

}
?>