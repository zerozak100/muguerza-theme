<?php

abstract class MG_Product_Archive {

    public static $no_products_in_unit_found = false;


    protected $is_recommending;

    /**
     * @var int
     */
    protected $unidad_id;
    /**
     * @var string
     */
    protected $s;

    /**
     * @var MG_User
     */
    protected $user;

    /**
     * @var MG_Unidad
     */
    protected $unidad;

    public static function init() {
        include_once __DIR__ . '/class-mg-product-archive-especialidades.php';
        include_once __DIR__ . '/class-mg-product-archive-servicios.php';
        include_once __DIR__ . '/class-mg-product-archive-shop.php';

        if ( self::is_page( 'especialidades' ) ) {
            new MG_Product_Archive_Especialidades();
        }

        if ( self::is_page( 'servicios' ) ) {
            new MG_Product_Archive_Servicios();
        }

        if ( self::is_page( 'tienda' ) ) {
            new MG_Product_Archive_Shop();
        }
    }

    // https://stackoverflow.com/questions/5598480/php-parse-current-url
    public static function is_page( $page ) {
        $parsed_url = parse_url( 'https://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] );
        return str_contains( $parsed_url[ 'path' ], "/$page" );
    }

    /**
     * La primera vez que muestra resultados cae aquí pero al cambiar de página (en la query existe is_recommending) ya no se usa este metodo, se usa filter_products 
     */
    public static function show_recommendations() {
        add_filter( 'woocommerce_pagination_args', 'MG_Product_Archive::recommendations_pagination' );

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 12,
        );
        set_query_var( 'is_recommending', '1' );
        $products = new WP_Query( $args );
        set_query_var( 'is_recommending', false );

        wc_set_loop_prop( 'is_search', $products->is_search() );
        wc_set_loop_prop( 'total', $products->found_posts );
        wc_set_loop_prop( 'is_filtered', is_filtered() );
        wc_set_loop_prop( 'total_pages', $products->max_num_pages );
        wc_set_loop_prop( 'per_page', $products->get( 'posts_per_page' ) );
        wc_set_loop_prop( 'current_page', max( 1, $products->get( 'paged', 1 ) ) );

        if ( $products->found_posts === 0 ) {
            wc_no_products_found();
        } else {
            self::no_products_in_unit_found();
        }

        do_action( 'woocommerce_before_shop_loop' );
        woocommerce_product_loop_start();

        while ( $products->have_posts() ) :
            $products->the_post();
			do_action( 'woocommerce_shop_loop' );
            wc_get_template_part( 'content', 'product' );
        endwhile;
        
        woocommerce_product_loop_end();
        do_action( 'woocommerce_after_shop_loop' );
    }

    /**
     * Ajustar paginación para que funcione con las recomendaciones
     */
    public static function recommendations_pagination( $args ) {
        // wp_safe_redirect( add_query_arg( array_merge( $wp->query_vars, array( 'is_recommending' => 'true' ) ), home_url( $wp->request ) ) );
        // die();
        $args[ 'base' ] .= '&is_recommending=1';
        return $args;
    }

    public static function no_products_in_unit_found() {
        self::$no_products_in_unit_found = true;
        echo "<p class='woocommerce-info woocommerce-no-products-found'>No pudimos encontrar lo que buscabas en la unidad actual pero encontramos esto en otras unidades</p>";
    }

    public static function get_filter( $name ) {
        $allowed = array(
            'is_recommending',
            'service_type_id',
            's',
        );
        
        if ( in_array( $name, $allowed ) ) {
            return sanitize_text_field( get_query_var( $name ) );
            // return ! empty( $_GET[ $name ] ) ? sanitize_text_field( $_GET[ $name ] ) : '';
        }

        return '';
    }

    //

    public function __construct() {
        add_filter( 'init', array( $this, 'load' ) );
        add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
        add_filter( 'query_vars', array( $this, 'query_vars' ) );
        add_action( 'woocommerce_before_shop_loop', array( $this, 'show_no_products_found_in_unit' ) );
        add_filter( 'woocommerce_page_title', array( $this, 'page_title' ) );
        // add_action( 'template_redirect', array( $this, '' ) );
        // add_filter( 'body_class', array( $this, 'body_class' ) );
    }

    public function load() {
        $this->user   = MG_User::current();
        $this->unidad = $this->user->get_unidad();
    }

    /**
     * @param WP_Query $query
     */
    public function pre_get_posts( $query ) {
        if ( ! is_admin() && is_post_type_archive( 'product' ) && $query->is_main_query() || $this->is_recommending || '1' === get_query_var( 'is_recommending' ) ) {

            if ( ! $this->unidad->get_id() ) {
                // mensaje de error seleccione una unidad
                wp_safe_redirect( home_url( '/' ) );
                exit();
            }

            $this->setup_data();
            $this->filter_products( $query );
        }
    }

    public function page_title( $title ) {
        if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
            if ( self::is_page( 'servicios' ) ) {
                return 'Servicios';
            }
            
            if ( self::is_page( 'especialidades' ) ) {
                return 'Especialidades';
            }

            return 'Especialidades y Servicios';
        }
        return $title;
    }

    public function query_vars( $vars ) {
        $vars[] = 'is_recommending';
        $vars[] = 'service_type_id';
        return $vars;
    }

    public function show_no_products_found_in_unit() {
        $is_recommending = $this->get_filter( 'is_recommending' );
        if ( $is_recommending ) {
            self::no_products_in_unit_found();
        }
    }

    private function setup_data() {
        $this->s               = $this->get_filter( 's' );
        $this->is_recommending = $this->get_filter( 'is_recommending' );
    }

    /**
     * @param WP_Query $query
     */
    abstract public function filter_products( $query );

    // public function body_class( $classes ) {
    //     if ( is_page( 'servicios' ) || is_page( 'especialidades' ) ) {
    //         $classes = array_merge( $classes, array( 'woocommerce', 'archive' ) );
    //     }
    //     return $classes;
    // }
}

MG_Product_Archive::init();


// optimizar https://wordpress.stackexchange.com/questions/268589/how-to-override-a-query-and-display-specific-page-by-id
function alter_the_query( $request ) {
    $query = new WP_Query();
    $query->parse_query( $request );

    if ( $query->is_page( 'servicios' ) || $query->is_page( 'especialidades' ) ){
        unset( $request['pagename'] );
        $request['page_id'] = get_page_by_path( 'tienda' )->ID;
    }

    return $request;
}
add_filter( 'request', 'alter_the_query' );
