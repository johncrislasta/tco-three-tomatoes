<?php


namespace TCo_Three_Tomatoes;


class Acme
{
    /**
     * Renders the contents of the given template to a string and returns it.
     * Ends with a forward slash.
     *
     * @param string $template_name The name of the template to render (without .php)
     * @param array  $attributes    The PHP variables for the template
     *
     * @return string               The contents of the template.
     */
    public static function get_template( $template_name, $attributes = null ) {
        if ( ! $attributes ) {
            $attributes = array();
        }

        ob_start();

        do_action( 'tcottc_template_before_' . $template_name );


        foreach ($attributes as $key => $value)
            $$key = $value;
        require( TTC_TEMPLATES . $template_name . '.php');

        do_action( 'tcottc_template_after_' . $template_name );

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public static function redirect_to_404() {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 ); exit();
    }

    /**
    * Inserts a new key/value after the key in the array.
    *
    * @param $key
    *   The key to insert after.
    * @param $array
    *   An array to insert in to.
    * @param $new_key
    *   The key to insert.
    * @param $new_value
    *   An value to insert.
    *
    * @return
    *   The new array if the key exists, FALSE otherwise.
    *
    * @see array_insert_before()
    */
    public static function array_insert_after($key, array &$array, $new_key, $new_value) {
        if (array_key_exists($key, $array)) {
            $new = array();
            foreach ($array as $k => $value) {
                $new[$k] = $value;
                if ($k === $key) {
                    $new[$new_key] = $new_value;
                }
            }
            return $new;
        }
        return FALSE;
    }

    public static function display_errors()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public static function diep( $arr, $to_die = true ) {
        $display = is_array( $arr ) || is_object( $arr ) ?
            print_r($arr, true) :
            $arr;
        if( $to_die )
            die("<pre>" . $display . "</pre>");
        else
            echo "<pre>" . $display . "</pre>";
    }

    public static function url_origin( $s, $use_forwarded_host = false )
    {
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    public static function full_url( $s = [], $use_forwarded_host = false )
    {
        if (!empty($s)) $s = $_SERVER;

        return self::url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
    }

    public static function get_datetime_formatted($datetime = false, $format = 'F j, Y g:i a')
    {
        if( is_string($datetime) )
            $datetime = strtotime($datetime);

        $datetime = $datetime ?: time();

        return date_i18n($format, $datetime);
    }

    public static function get_time_ago( $time )
    {
        $time_difference = time() - strtotime( $time );

        if( $time_difference < 1 ) { return 'less than 1 second ago'; }
        $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        );

        foreach( $condition as $secs => $str )
        {
            $d = $time_difference / $secs;

            if( $d >= 1 )
            {
                $t = round( $d );
                return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
            }
        }
    }

}