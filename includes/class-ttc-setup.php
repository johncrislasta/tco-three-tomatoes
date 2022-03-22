<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Setup' ) ) :

    /**
	 * Tco Three Tomatoes plugin setup class.
	 *
	 * @package TCoThreeTomatoesCatering
	 * @since 1.0.0
	 */
    class Setup {

        /**
         * Current plugin version.
         *
         * @since 1.0.0
         * @var string
         */
        public $version = '1.0.0.135';


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
         * Main plugin constructor
         *
         */
        public function __construct() {

            //Define our plugin constants
            $this->define_constants();

            //Check if required plugins are installed
            add_action( 'plugins_loaded', array( &$this, 'activation_check' ), 11 );
        }


        /**
         * Define Plugin constants
         *
         * @since 1.0.0
         */
        private function define_constants(){
            $this->define( 'TTC_PLUGIN_FILE', plugin_dir_path(__DIR__ ) . 'tco-three-tomatoes.php' );
            $this->define( 'TTC_VERSION', $this->version );
            $this->define( 'TTC_URL', plugin_dir_url(__DIR__));
            $this->define( 'TTC_DIR', plugin_dir_path(__DIR__) );
            $this->define( 'TTC_TEMPLATES', TTC_DIR . 'templates/');
            $this->define( 'TTC_ASSETS', TTC_URL . 'assets/');
            $this->define( 'TTC_TEXT_DOMAIN', 'tco_three_tomatoes');
        }

        /**
         * Define constant if not already set
         *
         * @param  string $name
         * @param  string|bool $value
         *
         * @since 1.0.0
         */
        private function define( $name, $value ) {
  		    if ( ! defined( $name ) ) {
  			   define( $name, $value );
  		    }
  	    }


        /**
         * Check if required plugins are installed
         *
         * @since 1.0.0
         */
        function activation_check(){

            //Check if WooCommerce is installed and is active
            if ( ! function_exists( 'acf' ) ) {
                add_action( 'admin_notices', array( &$this, 'acf_admin_notice' ) );
            }
        }

        /**
         * WooCommerce Admin notice
         * Error message shown to admin that WooCommerce is required
         *
         * @since 1.0.0
         */
        function acf_admin_notice(){
            ?>
	        <div class="error">
		        <p><?php _e( 'Three Tomatoes Catering requires Advance Custom Fields in order to work.', 'tco_three_tomatoes' ); ?></p>
	        </div>
            <?php
        }
    }

    //initialise our class
    $TTC_Setup = Setup::instance();
else:

    /**
	 * Show a warning that another plugin with the same name exists
	 *
	 * @package TCoThreeTomatoesCatering
	 * @since 1.0.0
	 */
    function tco_ttc_setup_error_notice(){
        $message = __("Another plugin already using the class name TCo_Three_Tomatoes\Setup exists. The Tco Three Tomatoes Catering plugin will not work as expected","tco_woo_checkout");
        echo"<div class='error'> <p>$message</p></div>";
    }

    add_action( 'admin_notices', 'tco_ttc_setup_error_notice' );

endif;
?>
