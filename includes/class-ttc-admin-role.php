<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Role_Admin' ) ) {


   /**
     * TCoThreeTomatoesCatering Role_Admin class
     *
     * Holds all WordPress Role_Admin
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Role_Admin {
        /**
         * The role string
         *
         * @since 1.0.0
         */
        
        protected $role = null;        

        protected $ID = null;

        protected $capabilities = [
            'kitchenstaff' => ['label' => 'Kitchen Staff', 'caps' => [ 
                    'manage_calendar' => true, 
                    'write_message' => true, 
                    'read_message' => true,
                    'view_media' => true
                ]
            ],
            '3tstaff' => ['label' => '3T Staff', 'caps' => [
                    'manage_calendar' => true,
                    'write_message' => true,
                    'read_message' => true,
                    'view_media' => true
                ]
            ],
            '3tcustomer' => ['label' => '3T Customer', 'caps' => [ 
                    'manage_calendar' => true, 
                    'write_message' => true, 
                    'read_message' => true, 
                    'print_message' => true,
                    'view_media' => true,
                    'upload_media' => true                    
                ]
            ],
        ];


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
            
            add_action( 'init', [$this, 'init'], 99 );
            add_action( 'admin_init', [$this, 'admin_init'], 99 );
            //add_filter('parse_query', [$this, 'media_restriction'] );
            add_filter( 'ajax_query_attachments_args', [$this, 'media_restriction'] );
            add_action( 'admin_menu', array($this, 'remove_menu'), 99999999999999999 );
            add_action( 'admin_bar_menu', array($this, 'admin_bar_items'), 9999999999 );
            
        }

        public function init () {
            
            if ( get_option( '3TRolesStatus' ) !== 'added' ) {      
                foreach ($this->capabilities as $role => $info) {
                    add_role( $role , $info['label'], array_merge( $info['caps'], ['upload_files' => true, 'read' => true ] ) ) ;
                }                                
                $admin_role = get_role('administrator');
                $admin_role->add_cap('manage_calendar', true );
                update_option( '3TRolesStatus', 'added' );
            }

            if ( $this->is3T() ) { //Fix woocommerce redirect conflict
                add_filter( 'woocommerce_prevent_admin_access', '__return_false', 9999999 );
            }

        }

        public function admin_init() { //Security purpose, since list mode display all media attachments
            if ( $this->is3T() ) {
                 $_GET['mode'] = 'grid';
            }
        }

        public function media_restriction( $query ) {
            if ( $this->is3T() ) {
                $query['author'] = $this->user();                
            }
            return $query;
        }        

        public function admin_bar_items ( $wp_adminbar ) {
            if ( !$this->is('administrator') && $this->is3T() ) {          
                foreach ($wp_adminbar->get_nodes() as $node => $value) {
                    if ( !in_array($node, array ('user-actions','user-info', 'edit-profile', 'logout', 'my-account', 'menu-toggle', 'top-secondary', 'site-name', 'view-site') ) ) {
                        $wp_adminbar->remove_node($node );
                    }
                }
            }
        }

        public function remove_menu () {

            if ( !$this->is('administrator') && $this->is3T()  ) {
                            
                remove_menu_page( 'jetpack' );
                remove_menu_page( 'index.php' );                
            }

        }

        public function user() {
            
            if ( empty($this->ID) ) {
                global $current_user;
                $user_roles = $current_user->roles;
                $this->role = array_shift($user_roles);
                $this->ID = $current_user->ID;
            }

            return $this->ID;

        }
        public function is3T () { //Are they 3T roles?
            return $this->is(['kitchenstaff', '3tstaff', '3tcustomer']);
        }

        public function isMapped( $type ) { //Is it the current mapped role?
            $mapped_types = [ 'Admin' => 'administrator', 'On Site' => '3tstaff','Kitchen' => 'kitchenstaff' ];
            if ( isset( $mapped_types[$type] ) ) {
                return $this->is($mapped_types[$type]);
            }
            return false;
        }        

        public function is ( $roles ) { //Is this the current role?
            $this->user(); return in_array( $this->role, (array) $roles );
        }
               
        public function can( $capability ) {
            return is_user_logged_in() ? ( empty($this->role) ? false : ( isset($this->capabilities[$this->role]) ? ( $this->capabilities[$this->role]['caps'][$capability] == true ) : false ) ) : false;
        }

    }
   
   Role_Admin::instance();
   
}