<?php


namespace TCo_Three_Tomatoes;


class Shortcode
{

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
        add_action( 'wp', [$this, 'tco_ttc_create_shortcodes'] );
    }


    public static function create($name, $vars = []) {

        add_shortcode($name, function($attr, $content, $tag) use ($name, $vars) {

            $data = ['attr' => $attr, 'content' => $content, 'tag' => $tag];

            $filename = "shortcodes/{$name}";

            return Acme::get_template($filename, $data);
        });
    }

    public function tco_ttc_create_shortcodes() {
//        Acme::diep('creating shortcodes');
        Shortcode::create('tco_ttc_booking', array( 'fully_booked_dates' => join(',', \TCo_Three_Tomatoes\Bookables::get_fully_booked_dates() ) ) );
    }
}

Shortcode::instance();

