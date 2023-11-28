<?php
$_classes = array_merge( array( 'home-sala-prensa-item' ), $classes );
$_classes = implode( " ", $_classes );
$image = wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), 'full' );
$title = $post->post_title;
$excerpt = wp_trim_words( get_the_excerpt( $post->ID ), 15 );

?>
​
<div id="home-sala-prensa-item-<?php echo esc_attr( $post->ID ); ?>" class="<?php echo esc_attr( $_classes ); ?>">
    <a href="<?php echo get_permalink($post->ID); ?>">
        <div class="home-sala-prensa-item__wrapper">
            <figure class="home-sala-prensa-item__img-box">
                <?php echo $image; ?>
            </figure>
            <div class="home-sala-prensa-item__content-box">
                <h6 class="home-sala-prensa-item__title"><?php echo esc_html( $title ); ?></h6>
                <p class="home-sala-prensa-item__excerpt"><?php echo esc_html( $excerpt ); ?></p>
            </div>
        </div>
    </a>
</div>
