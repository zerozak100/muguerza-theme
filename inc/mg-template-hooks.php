<?php

/**
 * Product archive page
 */
// add_filter( 'woocommerce_show_page_title' );
add_action( 'woocommerce_archive_description', 'muguerza_product_filter_bar', 40 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_no_products_found', 'MG_Product_Archive::show_recommendations' );
remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );


/**
 * Shop loop items
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'muguerza_product_ver_mas' );