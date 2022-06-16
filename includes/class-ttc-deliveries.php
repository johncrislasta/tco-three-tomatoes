<?php

namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Deliveries' ) ) {


    /**
     * TCoThreeTomatoesCatering Deliveries class
     *
     * Holds all Deliveries actions
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Deliveries extends Bookables
    {
        public static $post_type = 'delivery';


        public function __construct() {

            // Add Synchronize Delivery Admin Page
//            add_action( 'admin_menu', array( $this, 'synchronize_admin_submenu' ) );



            add_action("rest_insert_delivery", array( $this, 'accept_rest_api_meta_fields' ), 10, 3);
        }

        // Add Synchronize submenu page under Delivery menu

        function synchronize_admin_submenu() {

            add_submenu_page(
                'edit.php?post_type=delivery',
                __( 'Synchronize', TTC_TEXT_DOMAIN ),
                __( 'Synchronize', TTC_TEXT_DOMAIN ),
                'manage_options',
                'ttc-delivery-synchronize',
                array( $this, 'synchronize_admin_page_contents' ),
                3
            );

        }


        /**
         * Display callback for the submenu page.
         */
        function synchronize_admin_page_contents() {
            ?>
            <div class="wrap">
                <h1><?php _e( 'Synchronize delivery posts', 'textdomain' ); ?></h1>
                <p><?php _e( 'Pull delivery order details', 'textdomain' ); ?></p>
                <?php
                    echo Acme::get_template('admin/delivery-synchronize');
                ?>
            </div>
            <?php
        }

        function accept_rest_api_meta_fields(\WP_Post $post, $request, $creating)
        {
            $metas = $request->get_param("meta");
            if (is_array($metas)) {
                foreach ($metas as $name => $value) {
                    //update_post_meta($post->ID, $name, $value);
                    update_field($name, $value, $post->ID);
                }
            }
        }
    }

    Deliveries::instance();

}