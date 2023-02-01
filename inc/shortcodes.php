<?php

function mg_home_sala_prensa_shortcode() {
    $args = array(
        'posts_per_page' => 7,
    );

    $items = array();

    $posts = get_posts( $args );
    if ( is_array( $posts ) && ! empty( $posts ) ) {
        $primary = $posts[0];
        $items = array(
            'primary' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--primary' ),
            ),
            'secondary' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--secondary' ),
            ),
            'tertiary' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--tertiary' ),
            ),
            'four' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--four', 'home-sala-prensa-item--vertical' ),
            ),
            'five' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--five', 'home-sala-prensa-item--vertical' ),
            ),
            'six' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--six', 'home-sala-prensa-item--vertical' ),
            ),
            'seven' => array(
                'post' => $primary,
                'classes' => array( 'home-sala-prensa-item--seven', 'home-sala-prensa-item--vertical' ),
            ),
        );
    }
    ob_start();
    mg_get_template( 'home/sala-prensa.php', array( 'items' => $items ) );
    // include_once MG_TEMPLATES_PATH . '/home-sala-prensa.php';
    return ob_get_clean();
}
add_shortcode( 'mg-home-sala-prensa', 'mg_home_sala_prensa_shortcode' );
