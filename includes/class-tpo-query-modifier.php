<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TPO_Query_Modifier {

	public function filter_products( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		// Only modify WooCommerce product queries (shop, category, etc.)
		if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
			return;
		}

		$vehicle_id = TPO_Session_Handler::get_vehicle();

		if ( ! $vehicle_id ) {
			return;
		}

		// Add hooks for JOIN and WHERE
		add_filter( 'posts_join', array( $this, 'posts_join' ), 10, 2 );
		add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );
		add_filter( 'posts_distinct', array( $this, 'posts_distinct' ) );
	}

	public function posts_join( $join, $query ) {
		global $wpdb;

		// Join with Post Meta to get SKU
		// Join with Fitment Lookup on SKU
		
		$tpo_table = $wpdb->prefix . 'tpo_fitment_lookup';
		
		$join .= " LEFT JOIN {$wpdb->postmeta} AS pm_sku ON ({$wpdb->posts}.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku') ";
		$join .= " JOIN {$tpo_table} AS tpo_fit ON (pm_sku.meta_value = tpo_fit.product_sku) ";

		return $join;
	}

	public function posts_where( $where, $query ) {
		global $wpdb;
		
		$vehicle_id = TPO_Session_Handler::get_vehicle();
		
		// Ensure we only include products that fit the vehicle OR are universal (vehicle_id = 0)
		// We cast to int for safety
		$v_id = intval( $vehicle_id );
		
		$where .= " AND (tpo_fit.vehicle_id = $v_id OR tpo_fit.vehicle_id = 0) ";

		return $where;
	}
	
	public function posts_distinct( $distinct ) {
		return "DISTINCT";
	}
}
