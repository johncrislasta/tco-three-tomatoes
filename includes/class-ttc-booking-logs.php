<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Booking_Logs' ) ) {


    /**
     * TCoThreeTomatoesCatering Booking_Logs class
     *
     * Holds all WordPress Booking_Logs End actions
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Booking_Logs
    {

        public $db_version = '1.1.0';

        public $wp_table;

        /**
         * The single instance of the class
         *
         * @since 1.0.0
         */
        protected static $_instance = null;


        /**
         * Get the instance
         *
         * @return Booking_Logs
         * @since 1.0.0
         */
        public static function instance() {
            if ( is_null( static::$_instance ) ) {
                static::$_instance = new static();
            }
            return static::$_instance;
        }

        public function __construct()
        {
            global $wpdb;

            $this->wp_table = $wpdb->prefix . Booking_Log::$table_name;

//            Acme::diep(['Booking Logs conscructed', TTC_PLUGIN_FILE]);
            register_activation_hook( TTC_PLUGIN_FILE, array( $this, 'create_table' ) );

            add_action('ttc_created_new_catering',          array( $this, 'log_created_catering' ),             10, 2 );
            add_action('ttc_created_new_delivery',          array( $this, 'log_created_delivery' ),             10, 2 );
            add_action('ttc_saved_booking_note',            array( $this, 'log_added_booking_note' ),           10, 3 );
            add_action('ttc_saved_booking_private_note',    array( $this, 'log_added_booking_private_note' ),   10, 4 );
            add_action('ttc_saved_media',                   array( $this, 'log_updated_booking_media' ),        10, 3 );

        }

        function create_table() {

            global $wpdb;

            $table_name     = $wpdb->prefix . Booking_Log::$table_name;
            $post_table     = $wpdb->prefix . 'posts';
            $user_table     = $wpdb->prefix . 'users';

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "
CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` TINYTEXT NULL,
  `text` TEXT NULL,
  `user_ID` BIGINT(20) UNSIGNED NOT NULL,
  `post_ID` BIGINT(20) UNSIGNED NOT NULL,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_wp_booking_logs_{$user_table}_idx` (`user_ID` ASC),
  INDEX `fk_wp_booking_logs_{$post_table}1_idx` (`post_ID` ASC),
  CONSTRAINT `fk_wp_booking_logs_{$user_table}`
    FOREIGN KEY (`user_ID`)
    REFERENCES $user_table (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_wp_booking_logs_{$post_table}1`
    FOREIGN KEY (`post_ID`)
    REFERENCES $post_table (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION) $charset_collate;";


            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $db_result = dbDelta( $sql );
//            Acme::diep($db_result);

            $installed_ver = get_option( "ttc_booking_log_db_version" );

            if ( $installed_ver < $this->db_version ) {

                $sql = "ALTER TABLE $table_name ADD `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `post_ID`;";

                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta( $sql );
            }

            update_option( 'ttc_booking_log_db_version', $this->db_version );
        }

        /**
         *  Retrieve all logs for a post
         *
         * @param $post_id
         * @param int $limit
         * @param int $offset
         * @return Booking_log[]
         */
        public function get_all_by_post($post_id, $limit = 10, $offset = -1) {
            global $wpdb;

            $limit_query = $limit > 0 ? "LIMIT $limit" : '';
            $offset_query = $offset >= 0 ? "OFFSET $offset" : '';

            $logs = $wpdb->get_results("SELECT * FROM {$this->wp_table} WHERE post_ID = $post_id $limit_query $offset_query");

//            Acme::diep($logs);

            $booking_logs = [];
            foreach($logs as $log) {
                $booking_logs[] = new Booking_Log(
                    $log->post_ID,
                    $log->user_ID,
                    $log->type,
                    $log->text,
                    $log->id,
                    $log->date_created
                );
            }

            return $booking_logs;
        }

        public function create_log($post_id, $user_id, $type, $text) {
            $booking_log = new Booking_Log($post_id, $user_id, $type, $text);

            return $booking_log->save();
        }

        public function log_created_catering($post_id, $post)
        {
            $catering = new Catering($post);

            $result = $this->create_log(
                $post_id,
                get_current_user_id(),
                'created_catering',
                "submitted a new catering order");

            return $result;
        }

        public function log_created_delivery($post_id, $post)
        {
            $delivery = new Delivery($post);

            $result = $this->create_log(
                $post_id,
                get_current_user_id(),
                'created_delivery',
                "submitted a new delivery order");

            return $result;
        }

        /**
         * @param $message string
         * @param $post \WP_Post
         * @param $user \WP_User
         * @return false|int
         */
        public function log_added_booking_note($message, $post, $user)
        {
            $result = $this->create_log(
                $post->ID,
                $user->ID,
                'added_public_booking_note',
                "added a note");

            return $result;
        }

        /**
         * @param $message string
         * @param $type string
         * @param $post \WP_Post
         * @param $user \WP_User
         * @return false|int
         */
        public function log_added_booking_private_note($message, $type, $post, $user)
        {
            $result = $this->create_log(
                $post->ID,
                $user->ID,
                'added_private_booking_note',
                "added a private note");

            return $result;
        }


        /**
         * @param $message string
         * @param $post \WP_Post
         * @param $user \WP_User
         * @return false|int
         */
        public function log_updated_booking_media($media, $post, $user)
        {
            $result = $this->create_log(
                $post->ID,
                $user->ID,
                'updated_public_booking_note',
                "updated the media list");

            return $result;
        }
    }

    Booking_Logs::instance();

}

