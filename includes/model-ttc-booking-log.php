<?php

namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Booking_Log' ) ) {


    /**
     * TCoThreeTomatoesCatering Booking_Log class model
     *
     * Booking_Log object that holds fields and post object
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Booking_Log
    {

        public static $table_name = 'booking_logs';

        // Column names here
        public $id;
        public $post_id;
        public $post;
        public $user_id;

        /**
         * @var \WP_User
         */
        public $user;
        public $date_created;
        public $type;
        public $text;

        /**
         * Booking_Log constructor.
         * @param $id
         * @param $post_id
         * @param $post
         * @param $user_id
         * @param $user
         * @param $date_created
         * @param $type
         * @param $text
         */
        public function __construct($post_id, $user_id, $type, $text, $id = -1, $date_created = false)
        {
            $this->id           = $id;
            $this->post_id      = $post_id;
            $this->post         = $post_id ? get_post( $post_id ) : null;
            $this->user_id      = $user_id;
            $this->user         = new \WP_User( $user_id );
            $this->date_created = $date_created;
            $this->type         = $type;
            $this->text         = $text;
        }

        public function save()
        {
            global $wpdb;

            $table = $wpdb->prefix . self::$table_name;

            $data = array (
                'post_ID'       => $this->post_id,
                'user_ID'       => $this->user_id,
                'type'          => $this->type,
                'text'          => $this->text,
                'date_created'  => date_i18n('Y-m-d H:i:s', time())
            );

            $format = array(
                '%d',   // post_ID
                '%d',    // user_ID
                '%s',    // type
                '%s',    // text
                '%s',    // text
            );

            // If id is specified, update
            if( $this->id > 0 )
            {
                $result = $wpdb->update(
                    $table,
                    $data,
                    array( 'id' => $this->id ),
                    $format,
                    array( '%d' )
                );
            } else {
                $result = $wpdb->insert(
                    $table,
                    $data,
                    $format
                );
            }

//            Acme::diep(['save booking log', $result, $this, $wpdb->last_query]);
            return $result;
        }

        public function get_date_created($format = 'F j, Y g:i a')
        {
            if( !$format )
                return $this->date_created;

            return Acme::get_datetime_formatted($this->date_created, $format);
        }

        public function get_user_name()
        {
            return $this->user->display_name;
        }

        public function get_user_avatar()
        {
            return $this->user->user_avatar;
        }

    }
}