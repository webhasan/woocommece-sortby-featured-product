<?php 

function featured_products_orderby( $orderby, $query ) {
	global $wpdb;

	if ( 'featured_products' == $query->get( 'orderby' ) ) {
		$featured_product_ids = (array) wc_get_featured_product_ids();
		if ( count( $featured_product_ids ) ) {
			$string_of_ids = '(' . implode( ',', $featured_product_ids ) . ')';
			$orderby  = "( {$wpdb->posts}.ID IN {$string_of_ids} ) " . $query->get( 'order' );
		}
	}

	return $orderby;
}
add_filter( 'posts_orderby', 'featured_products_orderby', 10, 2 );


add_filter( 'woocommerce_get_catalog_ordering_args', 'woo_custom_orderby' );
function woo_custom_orderby( $args ) {
  $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'featured' == $orderby_value ) {
			$args['orderby']  = 'featured_products';		
			$args['order']  = 'DESC';	
	}
	return $args;
}


add_filter( 'woocommerce_default_catalog_orderby_options', 'woo_custom_order_option' );
add_filter( 'woocommerce_catalog_orderby', 'woo_custom_order_option' );

function woo_custom_order_option( $sortby ) {
	$sortby['featured'] = 'Sort By Feature Products';
	return $sortby;
}
