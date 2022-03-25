<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Plated_Meal' ) ) {


    /**
     * TCoThreeTomatoesCatering Plated_Meal class
     *
     * Holds all Plated_Meal actions
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Plated_Meal {

        public $meals_field_name = 'plated_meal_dish_Information';
        public $entree_choices_field_name = 'entree_choices';
        public $hors_doueuvres_choices_field_name = 'hors_doueuvres_choices';
        public $dessert_choices_field_name = 'dessert_choices';

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
            add_action('woocommerce_before_add_to_cart_form', array($this, 'print_form'), 30 );

            add_action('wp_ajax_ttc_get_plated_meal_parts', array( $this, 'get_meal_parts' ) );
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

        public function retrieve_all_meals_info( $product_id )
        {
            $meals_fields = get_field($this->meals_field_name, $product_id);
            return $meals_fields;
        }

        public function retrieve_meal_info( $product_id, $index )
        {
            $meals = $this->retrieve_all_meals_info( $product_id );

            $meal = $meals[$index];
            return $meal;
        }

        public function get_meal_parts()
        {
            Acme::display_errors();
            if( !isset( $_POST['product_id'] ) ) return [ 'success' => false ];

            $product_id = $_POST['product_id'];
            $meal_index = $_POST['meal_index'];
            $return = array('product_id' => $product_id );
            $meal_info = $this->retrieve_meal_info( $product_id, $meal_index );

            $return['entrees'] = Acme::get_template('forms/catering/plated-03-1-choose-entrees', array( 'meal' => $meal_info ) );
            $return['hors_doeuvres'] = Acme::get_template('forms/catering/plated-03-2-choose-hors-doeuvres', array( 'meal' => $meal_info ) );
            $return['desserts'] = Acme::get_template('forms/catering/plated-03-3-choose-desserts', array( 'meal' => $meal_info ) );

            die( json_encode($return) );
        }

        public function get_meal_addon_modules($product_id) {
            $addons = get_field('meal_addon_modules', $product_id);

            return $addons;
        }

        public function get_meal_notes($product_id) {
            $addons = get_field('notes', $product_id);

            return $addons;
        }

        public function print_form()
        {
            $product_id = get_the_ID();
            $meals_fields = $this->retrieve_all_meals_info($product_id);
            $addon_modules = $this->get_meal_addon_modules($product_id);

            $addon_slides = array();

            if (!empty($addon_modules) )
            {
                foreach( $addon_modules as $module ) {

                    $module[ 'question_slug' ] = sanitize_title( $module['question'] );
                    $module[ 'imgsrc_for_yes' ] = Acme::get_image_link( $module['image_for_yes'] );
                    $module[ 'imgsrc_for_no' ] = Acme::get_image_link( $module['image_for_no'] );
                    $module[ 'currency' ] = get_woocommerce_currency_symbol();


                    $module[ 'secondary_question_slug' ] = sanitize_title( $module['secondary_question'] );

                    if( isset( $module['choices'] ) ) {
                        foreach( $module['choices'] as $key => $choice ) {
                            $module['choices'][$key]['imgsrc'] = Acme::get_image_link( $choice['image'] );
                        }
                    }

                    $addon_slides[] = array(
                        'id'        => 'plated-addon-' . $module['question_slug'],
                        'header'    => 'Add-on',
                        'content'   => Acme::get_template("forms/addons/{$module['acf_fc_layout']}", $module ),
                    );
                }
            }

            $meal_notes = $this->get_meal_notes($product_id);
            $notes_slides = array();

            if (!empty($meal_notes) )
            {
                foreach( $meal_notes as $note ) {

                    $note[ 'question_slug' ] = sanitize_title( $note['question'] );

                    $notes_slides[] = array(
                        'id'        => 'plated-notes-' . $module['question_slug'],
                        'header'    => 'Notes',
                        'content'   => Acme::get_template("forms/fields/notes-textarea", $note ),
                    );
                }
            }

//            Acme::diep($addons);
            $slides = array(
                array (
                    'id'        => 'plated-number-of-guests',
                    'header'    => 'How many guests?',
                    'content'   => Acme::get_template('forms/catering/plated-01-number-of-guests'),
                ),
                array (
                    'id'        => 'plated-schedule-date-times',
                    'header'    => 'When do you need it?',
                    'content'   => Acme::get_template('forms/catering/plated-02-schedule-date-times'),
                ),
                array (
                    'id'        => 'plated-choose-meal-set',
                    'header'    => 'Choose a meal:',
                    'content'   => Acme::get_template('forms/catering/plated-03-0-choose-meal-set', array( 'plated_meals' => $meals_fields ) ),
                ),
                array (
                    'id'        => 'plated-choose-entrees',
                    'header'    => 'Choose Entrees:',
                    'content'   => Acme::get_template('forms/catering/plated-03-1-choose-entrees', array( 'meal' => $meals_fields[0] ) ),
                ),
                array (
                    'id'        => 'plated-choose-hors-doeuvres',
                    'header'    => 'Choose Hors D\'oeuvres:',
                    'content'   => Acme::get_template('forms/catering/plated-03-2-choose-hors-doeuvres', array( 'meal' => $meals_fields[0] ) ),
                ),
                array (
                    'id'        => 'plated-choose-desserts',
                    'header'    => 'Choose Desserts:',
                    'content'   => Acme::get_template('forms/catering/plated-03-3-choose-desserts', array( 'meal' => $meals_fields[0] ) ),
                ),
            );

            $final_slides = array (
                array (
                    'id'        => 'plated-terms-conditions',
                    'header'    => 'Terms and Conditions',
                    'content'   => Acme::get_template('forms/catering/plated-07-terms-and-conditions-submit'),
                ),
            );

            $slides = array_merge($slides, $addon_slides, $notes_slides, $final_slides);

            echo Acme::get_template('forms/slider-form', [ 'slides' => $slides, 'data'=>['product_id' => $product_id], 'form_id' => 'plated_meal_form' ] );
//            echo Acme::get_template('forms/catering/plated-meals', array( 'plated_meals' => $fields ) );
        }
    }

    Plated_Meal::instance();
}