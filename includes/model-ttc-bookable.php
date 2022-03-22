<?php

namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Bookable' ) ) {


    /**
     * TCoThreeTomatoesCatering Bookable class model
     *
     * Bookable object that holds fields and post object
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Bookable
    {

        public $post;

        public $start_date;
        public $start_time;
        public $end_date;
        public $end_time;

        public $customer_name;
        public $number_of_guests;

        public $notes;
        public $private_notes;
        public $media;
        public $private_media;

        public static $ERROR_WRONG_POST_TYPE = 882000; // 882 is a prefix for TTC, 000 is for Bookable

        public function __construct($post)
        {
            if( $post instanceof \WP_Post )
                $this->post = $post;
            else if( is_int( $post ) )
                $this->post = get_post( $post );

//            Acme::diep([$this->post]);

            if( $this->post->post_type != static::$post_type )
                return new \WP_Error( static::$ERROR_WRONG_POST_TYPE, 'Wrong post type' );

            $this->start_date = get_field('start_date', $this->post->ID );
            $this->start_time = get_field('start_time', $this->post->ID );

            $this->end_date = get_field('end_date', $this->post->ID );
            $this->end_time = get_field('end_time', $this->post->ID );

//            Acme::diep([$this->start_date, $this->start_time, $this->end_date, $this->end_time], false);

//            add_action( 'wp_insert_post', array( $this, 'new_bookable' ), 10, 3 );

        }

        public function get_start_date()
        {
            $this->start_date = $this->start_date ?: get_field('start_date', $this->post->ID );
            return $this->start_date;
        }

        public function get_start_time()
        {
            $this->start_time = $this->start_time ?: get_field('start_time', $this->post->ID );
            return $this->start_time;
        }

        public function get_end_date()
        {
            $this->end_date = $this->end_date ?: get_field('end_date', $this->post->ID );

            if ( empty( $this->end_date ) )
                return $this->start_date ?: get_field('start_date', $this->post->ID );

            return $this->end_date;
        }

        public function get_end_time()
        {
            $this->end_time = $this->end_time ?: get_field('end_time', $this->post->ID );

            if ( empty( $this->end_time ) )
                return $this->start_time ?: get_field('start_time', $this->post->ID );

            return $this->end_time;
        }

        public function get_customer_name()
        {
            $this->customer_name = $this->customer_name ?: get_field('customer_name', $this->post->ID );
            $this->customer_name = $this->customer_name ?: get_user_by('id', $this->post->post_author )->display_name;
            return $this->customer_name;
        }

        public function get_number_of_guests()
        {
            $this->number_of_guests = $this->number_of_guests ?: get_field('number_of_guests', $this->post->ID );
            return $this->number_of_guests ? $this->number_of_guests . " Guests" : "Unknown number of guests";
        }

        public function get_media()
        {
            $return = [];
            $media = get_field('media', $this->post->ID);

            if(empty($media)) return [];

//            Acme::diep($media);

            $files = [];
            $ids = [];
            foreach($media as $file) {
                $author = new \WP_User($file['author']);

                $file['name'] = $author->display_name;
                $file['avatar'] = $author->user_avatar;
                $file['time_ago'] = Acme::get_time_ago(strtotime($file['date']));
                $files[] = $file;

                $ids[] = $file['id'];
            }

            $return['files'] = $files;
            $return['ids'] = join(',', $ids);
            return $return;
        }

        public function get_notes(){
            $public_notes = get_field('public_notes', $this->post->ID);

            if(empty($public_notes)) return [];

            $notes = [];
            foreach($public_notes as $note) {
                $author = $note['author'];
                $note['name'] = $author['display_name'];
                $note['avatar'] = $author['user_avatar'];
                $note['time_ago'] = Acme::get_time_ago(strtotime($note['date_sent']));
                $notes[] = $note;
            }

            $notes = array_reverse($notes);

            return $notes;
        }

        public function get_private_notes(){
            $private_notes = get_field('private_notes', $this->post->ID);

            if(empty($private_notes)) return [];

            $notes = [];
            foreach($private_notes as $note) {
                $author = $note['author'];
                $note['name'] = $author['display_name'];
                $note['avatar'] = $author['user_avatar'];
                $note['time_ago'] = Acme::get_time_ago(strtotime($note['date_sent']));
                $notes[] = $note;
            }

            // reverse order
            $notes = array_reverse($notes);

            return $notes;
        }

        public function save_note( $message ){

            $notes = get_field( 'public_notes', $this->post->ID );
            if ( ! is_array($notes) ) $notes = [];

            $author = wp_get_current_user();
            $date_time = date_i18n("F j, Y g:i:s a", time());

//            Acme::diep([$date_time, time(), date_default_timezone_get(), wp_timezone()]);

            $note = [ [
                'author' => get_current_user_id(),
                'message' => $message,
                'date_sent' => $date_time
            ] ];

            $updated = array_merge($notes,  $note ) ;

            $updated_field = update_field( 'public_notes', $updated, $this->post->ID);

            do_action('ttc_saved_booking_note', $message, $this->post, $author );

//        Acme::diep([$offers, $offer, $updated, $updated_field]);
            return [
                'has_updated'   => $updated_field,
                'message'       => $message,
                'author_name'   => $author->display_name,
                'date_time'     => $date_time,
                'time_ago'      => Acme::get_time_ago( strtotime( $date_time ) )
            ];
        }

        public function save_private_note( $message, $type ){

            $notes = get_field( 'private_notes', $this->post->ID );
            if ( ! is_array($notes) ) $notes = [];

            $author = wp_get_current_user();
            $date_time = date_i18n("F j, Y g:i:s a", time());

            $note = [ [
                'author' => get_current_user_id(),
                'message' => $message,
                'type' => $type,
                'date_sent' => $date_time
            ] ];

            $updated = array_merge($notes,  $note ) ;


            $updated_field = update_field( 'private_notes', $updated, $this->post->ID);

            do_action('ttc_saved_booking_private_note', $message, $type, $this->post, $author );

//        Acme::diep([$offers, $offer, $updated, $updated_field]);
            return [
                'has_updated'   => $updated_field,
                'message'       => $message,
                'author_name'   => $author->display_name,
                'type'          => $type,
                'date_time'     => $date_time,
                'time_ago'      => Acme::get_time_ago( strtotime( $date_time ) )
            ];
        }

        public function save_uploads( $uploads ) {

            $media = get_field( 'media', $this->post->ID );
            if ( ! is_array($media) ) $media = [];

            $author = wp_get_current_user();
            $date_time = Acme::get_datetime_formatted( time() );

//            Acme::diep([$date_time, time(), date_default_timezone_get(), wp_timezone()]);

            $uploads_array = explode(',', $uploads);

            $updated = $uploads_array ;

            $updated_field = update_field( 'media', $updated, $this->post->ID);

            do_action('ttc_saved_media', $updated, $this->post, $author );

//        Acme::diep([$offers, $offer, $updated, $updated_field]);
            return [
                'has_updated'   => $updated_field,
                'message'       => $updated,
                'author_name'   => $author->display_name,
                'date_time'     => $date_time,
                'time_ago'      => Acme::get_time_ago( strtotime( $date_time ) )
            ];
        }
    }
}