<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TPO_Session_Handler {

	public static function init() {
		if ( ! session_id() && ! headers_sent() ) {
			// In a real WP environment, we rely on WC session or WP hooks.
			// Starting raw PHP session is generally discouraged in WP but used here for simplicity if WC is missing.
		}
	}

	/**
	 * Set the user's current vehicle.
	 *
	 * @param int $vehicle_id The ID from wp_tpo_garage_vehicles.
	 */
	public static function set_vehicle( $vehicle_id ) {
		if ( function_exists( 'WC' ) && WC()->session ) {
			WC()->session->set( 'tpo_vehicle_id', $vehicle_id );
		} else {
			// Fallback or Cookie method
			setcookie( 'tpo_vehicle_id', $vehicle_id, time() + 86400, COOKIEPATH, COOKIE_DOMAIN );
			$_COOKIE['tpo_vehicle_id'] = $vehicle_id;
		}
		
		// Trigger an action for analytics or other listeners
		do_action( 'tpo_vehicle_set', $vehicle_id );
	}

	/**
	 * Get the current vehicle ID.
	 *
	 * @return int|null Vehicle ID or null if not set.
	 */
	public static function get_vehicle() {
		if ( function_exists( 'WC' ) && WC()->session ) {
			return WC()->session->get( 'tpo_vehicle_id' );
		}
		
		if ( isset( $_COOKIE['tpo_vehicle_id'] ) ) {
			return intval( $_COOKIE['tpo_vehicle_id'] );
		}

		return null;
	}

	/**
	 * Clear the current vehicle selection.
	 */
	public static function clear_vehicle() {
		if ( function_exists( 'WC' ) && WC()->session ) {
			WC()->session->set( 'tpo_vehicle_id', null );
		}
		
		setcookie( 'tpo_vehicle_id', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
		unset( $_COOKIE['tpo_vehicle_id'] );
	}
}
