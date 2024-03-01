<div class="home-sala-prensa">
    <?php

    if ( is_array( $items ) && ! empty( $items ) ) {
        foreach ( $items as $item ) {
            mg_get_template( 'home/sala-prensa-item.php' , array(
                'post' => $item[ 'post' ],
                'classes' => $item[ 'classes' ],
            ));
        }
    }
    ?>
</div>