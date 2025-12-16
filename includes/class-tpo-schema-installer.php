<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TPO_Schema_Installer {

	public static function install() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		// Table 1: Garage Vehicles (The Master Vehicle List)
		$table_name_vehicles = $wpdb->prefix . 'tpo_garage_vehicles';
		$sql_vehicles = "CREATE TABLE $table_name_vehicles (
			base_vehicle_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			make_id int(11) NOT NULL,
			model_id int(11) NOT NULL,
			year_id int(4) NOT NULL,
			engine_id int(11) DEFAULT 0,
			aces_make_id int(11) DEFAULT NULL,
			aces_model_id int(11) DEFAULT NULL,
			make_name varchar(100) NOT NULL,
			model_name varchar(100) NOT NULL,
			submodel_name varchar(100) DEFAULT '',
			PRIMARY KEY  (base_vehicle_id),
			KEY make_model_year (make_id, model_id, year_id),
			KEY aces_lookup (aces_make_id, aces_model_id)
		) $charset_collate;";

		// Table 2: Fitment Lookup (The Link between Products and Vehicles)
		$table_name_fitment = $wpdb->prefix . 'tpo_fitment_lookup';
		$sql_fitment = "CREATE TABLE $table_name_fitment (
			fitment_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			product_sku varchar(100) NOT NULL,
			vehicle_id bigint(20) unsigned NOT NULL,
			quantity int(4) DEFAULT 1,
			fitment_note text,
			PRIMARY KEY  (fitment_id),
			KEY product_sku (product_sku),
			KEY vehicle_lookup (vehicle_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		dbDelta( $sql_vehicles );
		dbDelta( $sql_fitment );
	}
}
