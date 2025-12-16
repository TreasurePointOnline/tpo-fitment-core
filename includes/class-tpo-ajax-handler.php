<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TPO_Ajax_Handler {

	public static function init() {
		$actions = array(
			'get_years',
			'get_makes',
			'get_models',
			'set_vehicle'
		);

		foreach ( $actions as $action ) {
			add_action( 'wp_ajax_tpo_' . $action, array( __CLASS__, $action ) );
			add_action( 'wp_ajax_nopriv_tpo_' . $action, array( __CLASS__, $action ) );
		}
	}

	public static function get_years() {
		check_ajax_referer( 'tpo_ymm_nonce', 'nonce' );
		global $wpdb;
		
		// Fetch distinct years, ordered descending
		$years = $wpdb->get_col( "SELECT DISTINCT year_id FROM {$wpdb->prefix}tpo_garage_vehicles ORDER BY year_id DESC" );
		
		wp_send_json_success( $years );
	}

	public static function get_makes() {
		check_ajax_referer( 'tpo_ymm_nonce', 'nonce' );
		global $wpdb;
		
		$year = isset( $_POST['year'] ) ? intval( $_POST['year'] ) : 0;
		
		if ( ! $year ) {
			wp_send_json_error( 'Missing year' );
		}

		$makes = $wpdb->get_results( $wpdb->prepare( 
			"SELECT DISTINCT make_id, make_name FROM {$wpdb->prefix}tpo_garage_vehicles WHERE year_id = %d ORDER BY make_name ASC", 
			$year 
		) );
		
		wp_send_json_success( $makes );
	}

	public static function get_models() {
		check_ajax_referer( 'tpo_ymm_nonce', 'nonce' );
		global $wpdb;
		
		$year = isset( $_POST['year'] ) ? intval( $_POST['year'] ) : 0;
		$make_id = isset( $_POST['make_id'] ) ? intval( $_POST['make_id'] ) : 0;
		
		if ( ! $year || ! $make_id ) {
			wp_send_json_error( 'Missing parameters' );
		}

		// We select base_vehicle_id here because that's what we ultimately want to save
		$models = $wpdb->get_results( $wpdb->prepare( 
			"SELECT base_vehicle_id, model_name, submodel_name, engine_id FROM {$wpdb->prefix}tpo_garage_vehicles WHERE year_id = %d AND make_id = %d ORDER BY model_name ASC", 
			$year, $make_id
		) );
		
		wp_send_json_success( $models );
	}

	public static function set_vehicle() {
		check_ajax_referer( 'tpo_ymm_nonce', 'nonce' );
		
		$vehicle_id = isset( $_POST['vehicle_id'] ) ? intval( $_POST['vehicle_id'] ) : 0;
		
		if ( ! $vehicle_id ) {
			wp_send_json_error( 'Invalid Vehicle ID' );
		}

		TPO_Session_Handler::set_vehicle( $vehicle_id );
		
		wp_send_json_success( array( 'message' => 'Vehicle set', 'vehicle_id' => $vehicle_id ) );
	}
}
