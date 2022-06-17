<?php
namespace TCo_Three_Tomatoes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! class_exists( '\WP_List_Table' ) ) {    
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );           
}

if ( ! class_exists( 'TCo_Three_Tomatoes\Report_Table' ) ) {

    /**
     * TCoThreeTomatoesCatering Report_Items class
     *
     * Holds all WordPress Report_Items
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Report_Items {

        public $total_count = 0;

        public $items = [];

        public function __construct ($per_page, $current_page) {
            

            $post_types = empty($_GET['filter_type']) ? ['catering', 'delivery'] : $_GET['filter_type'];

            $args = [
                'post_type' => $post_types,
                'posts_per_page' => $per_page,
                'paged' => $current_page,
            ];            
            
            $meta_query = [];

            if ( !empty($_GET['start_date']) && !empty($_GET['end_date']) ) {
                $meta_query[] = [
                    'relation' => 'AND',
                    ['key' => 'start_date', 'value' => date('Ymd', strtotime( $_GET['start_date'] ) ), 'compare' => '>=', 'type' => 'DATE'],
                    ['key' => 'start_date', 'value' => date('Ymd', strtotime( $_GET['end_date'] ) ), 'compare' => '<=', 'type' => 'DATE']
                ];
            }

            if ( !empty($_GET['search_type']) ) {
                if ( !empty($_GET['search']) ) {
                    switch ($_GET['search_type']) {
                        case 'venue':                                
                        case 'meal':
                            $meta_query[] = ['key' => 'custom_order_details', 'value' => $_GET['search'], 'compare' => 'LIKE']  ;                          
                        break;                        
                        case 'name':
                            $meta_query[] = ['key' => 'customer_name', 'value' => $_GET['search'], 'compare' => 'LIKE'];
                        break;                                                
                        case 'event':
                            $meta_query[] = ['key' => 'event_name', 'value' => $_GET['search'], 'compare' => 'LIKE'];                            
                        break;                        
                    }
                }
            }       

            if ( count( $meta_query ) > 0 )        {
                 if ( count($meta_query) > 1 ) {
                     $meta_query['relation'] = 'AND';
                 }
                 $args['meta_query'] =  $meta_query;
            }

            if ( isset($_GET['order']) ) {
                $args['order'] = strtoupper( $_GET['order'] );
            }

            switch ($_GET['orderby']) {
                case 'post_title':
                    $args['orderby'] = 'title';                    
                break;
                case 'event':
                    $args['orderby'] = 'meta_value';
                    $args['meta_key'] = 'event_name';                    
                break;
                case 'guests':                
                    $args['orderby'] = 'meta_value';
                    $args['meta_key'] = 'number_of_guests';                    
                    $args['meta_type'] = 'NUMERIC'; 
                break;
                case 'contact':
                    $args['orderby'] = 'meta_value';
                    $args['meta_key'] = 'customer_name';                    
                break;
                case 'date_time':
                    $args['orderby'] = 'meta_value';
                    $args['meta_key'] = 'start_date';                    
                    $args['meta_type'] = 'DATE'; 
                break;
            }


            $query = new \WP_Query ( $args );
            $this->total_count = $query->found_posts;          

            if ( $query->post_count > 0 ) {
                foreach ($query->posts as $post) {
                    $data = (array) $post;
                    if ( function_exists('get_field') ) {
                        $date_start = get_field('start_date', $post->ID);
                        $date_end = get_field('end_date', $post->ID);
                        $time_start = get_field('start_time', $post->ID);
                        $time_end = get_field('end_time', $post->ID);
                        
                        $data['date'] = sprintf('%s%s', $date_start, $date_start == $date_end || empty($date_end) ? '' : ' - '.$date_end );
                        $data['time'] = sprintf('%s%s', $time_start,  $time_start == $time_end || empty($time_end) ? '' : ' - '.$time_end );

                        $data['guests'] = get_field('number_of_guests', $post->ID);
                        $data['contact'] = get_field('customer_name', $post->ID);
                        $data['order'] = get_field('order_id', $post->ID);

                        $data['meal'] = '';
                        $meal = json_decode(get_field('custom_order_details', $post->ID));
                        
                        if ( is_a($meal, '\stdClass') ) {
                            $data['meal'] = $meal->plated_meal;
                        }                        

                        $data['venue'] = '';

                        if ( $post->post_type == 'catering') {
                            $terms = get_the_terms($post, 'venue');        
                            if ( $terms ) {
                                $data['venue'] = join(', ', wp_list_pluck($terms, 'name'));
                            }
                        } 

                        $data['event'] = get_field('event_name', $post->ID);

                        $type =  get_post_type_object( $post->post_type );
                        $data['type'] = $type->labels->singular_name;

                    }                                        
                    $this->items[] = $data;
                }
            }


        }


    }

    /**
     * TCoThreeTomatoesCatering Report_Table class
     *
     * Holds all WordPress Report_Table
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Report_Table extends \WP_List_Table {
        
        public static $defaults = [
            'filter_type' => [ 'catering' => 'Catering', 'delivery' => 'Delivery' ],
            'search_type' => [ 'name' => 'Customer Name', 'venue' => 'Venue', 'meal' => 'Meal', 'event' => 'Event Name' ]
        ];              

        public function get_columns(){
          $columns = array(
            'post_title' => __( 'Title',  TTC_TEXT_DOMAIN) ,  
            'date_time' =>  __( 'Date/Time',  TTC_TEXT_DOMAIN),
            'guests' => __('Guests',  TTC_TEXT_DOMAIN), 
            'contact' => __('Customer Name',  TTC_TEXT_DOMAIN), 
            'meal' => __('Meal',  TTC_TEXT_DOMAIN),             
            'order' => __('Order ID',  TTC_TEXT_DOMAIN),
            'event' => __('Event',  TTC_TEXT_DOMAIN),
          );

          if ( empty($_GET['filter_type']) ) {
            $columns['type'] = __('Type',  TTC_TEXT_DOMAIN);
          }

          if ( $_GET['filter_type'] == 'catering' ) {
            $columns['venue'] = __('Venue',  TTC_TEXT_DOMAIN);
          }

          return $columns;
        }

        public function get_sortable_columns() {        
            return [
                'post_title' => ['post_title', true ],                
                'event' => ['event', true ],
                'guests' => ['guests', true ],
                'contact' => ['contact', true ],
                'date_time' => ['date_time', 'asc' ],
            ];
        }

        public function prepare_items() {            

            $columns = $this->get_columns();
            $hidden = get_hidden_columns( $this->screen );
            $sortable = $this->get_sortable_columns();   
            $primary  = $this->get_primary_column_name();

            $this->_column_headers = array( $columns, $hidden, $sortable, $primary );            

            $per_page     = 10;
            $current_page = $this->get_pagenum();

            $result = new Report_Items( $per_page, $current_page );            

            $this->set_pagination_args( [
                'total_items' => $result->total_count, 
                'per_page'    => $per_page
            ] );

            $this->items = $result->items;

        }

        function column_default( $item, $column_name ) {
          $value = '';
          switch( $column_name ) {                 
            case 'post_title':                           
            case 'guests': 
            case 'contact': 
            case 'meal': 
            case 'venue': 
            case 'order': 
            case 'type':
            case 'event': 
              $value = $item[ $column_name ];
            break;            
            case 'date_time': 
              $value = implode('<br>', [$item['date'], $item['time']]);
            break;
          }
          return $value;
        }

        public function __construct() {
            
            parent::__construct( [
                'singular' => __( 'Report',  TTC_TEXT_DOMAIN ),
                'plural'   => __( 'Reports',  TTC_TEXT_DOMAIN ),
                'ajax'     => false 
            ] );            
                                   
        }

        public function extra_tablenav( $which ) {
            switch ( $which ) {
                case 'top':
                    echo Acme::get_template('admin/report/form-header');
                break;                
                default:
                    echo Acme::get_template('admin/report/form-footer');
                break;
            }                        
        }

        
        
        public function display_tablenav( $which ) {

            ob_start();
            $this->extra_tablenav( $which );
            $extra = ob_get_clean();

            ob_start();
            $this->pagination( $which );
            $pagination = ob_get_clean();

            echo Acme::get_template('admin/report/form-nav', ['extra' => $extra, 'pagination' => $pagination, 'which' => esc_attr( $which ) ]);

        }
        
    }


    /**
     * TCoThreeTomatoesCatering Report_Admin class
     *
     * Holds all WordPress Report_Admin
     *
     * @package TCoThreeTomatoesCatering
     * @since 1.0.0
     */
    class Report_Admin {
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
            
             add_action( 'admin_menu', array( $this, 'admin_menu' ), 9999999999999999 );  
             add_action('init', [$this, 'maybe_export'], 99 );                        
        }

        public function admin_menu () {
           
            $hook = add_menu_page(
                __( 'Report', TTC_TEXT_DOMAIN ),
                __( 'Report', TTC_TEXT_DOMAIN ),
                'manage_options',
                'ttc-report',
                array( $this, 'render' ),
                'dashicons-chart-bar',
                3
            );
                        

        }

        public function maybe_export() {
            if ( isset($_GET['export']) && current_user_can( 'administrator' )  ) {
                $result = new Report_Items(-1, 1);
                
                $headers = ['Title','Date','Time','Guests','Customer','Meal','Order ID','Event','Venue','Type'];

                $filename = 'Report-'.date('d-m-Y-H_i_s');

                switch ($_GET['export']) {
                    case 'CSV':
                        
                        $data = [$headers];

                        $f = fopen('php://output', 'w');                                     

                        if ( count($result->items) > 0 ) {
                            
                            foreach ($result->items as $item ) {
                                
                                $data[] = [
                                    $this->csv_value( $item['post_title'] ),                                    
                                    $this->csv_value( $item['date'] ),                                    
                                    $this->csv_value( $item['time'] ),                                    
                                    $this->csv_value( $item['guests'] ),                                    
                                    $this->csv_value( $item['contact'] ),                                    
                                    $this->csv_value( $item['meal'] ),                                    
                                    $this->csv_value( $item['order'] ),                                    
                                    $this->csv_value( $item['event'] ),
                                    $this->csv_value( $item['venue'] ),
                                    $this->csv_value( $item['type'] ),
                                ];
                            }

                            foreach ($data as $line) {          
                                fputs($f, implode(',', $line)."\n");
                            }

                        }

                        header('Content-Type: application/csv');
                        header('Content-Disposition: attachment; filename="'.$filename.'.csv";');
                        flush();

                    break;
                    case 'PDF':
                        
                        require_once( TTC_DIR . 'includes/vendors/dompdf/autoload.inc.php' ); 

                        $dompdf = new \Dompdf\Dompdf();

                        $dompdf->loadHtml( Acme::get_template('admin/report/pdf', ['title' => $filename, 'headers' => $headers, 'items' => $result->items] ) );                                 
                        $dompdf->setPaper('A4', 'landscape');
                                    
                        $dompdf->render();      
                        
                        $dompdf->stream( $filename.'.pdf');                        

                    break;                    
                }                
                die();
            }
        }

        protected function csv_value ( $value ) {
            return '"'.trim(str_replace( '"', '""', $value ) ).'"';
        }

        public static function render () {
             echo Acme::get_template('admin/report/form');
        }

    }
   
   Report_Admin::instance();
   
}