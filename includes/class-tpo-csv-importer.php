<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TPO_CSV_Importer {

	/**
	 * Import Vehicles from CSV
	 * Expected columns: Make, Model, Year, Engine, MakeID, ModelID (ACES)
	 */
	public function import_vehicles( $file_path ) {
		if ( ! file_exists( $file_path ) ) {
			return new WP_Error( 'file_missing', 'CSV file not found.' );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'tpo_garage_vehicles';

		// Open file
		if ( ( $handle = fopen( $file_path, 'r' ) ) !== FALSE ) {
			// Skip header
			fgetcsv( $handle );

			$batch_size = 500;
			$values = [];
			$placeholders = [];

			while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== FALSE ) {
				// Map CSV columns to Schema
				// Example: 0:Make, 1:Model, 2:Year, 3:Engine, 4:ACES_Make, 5:ACES_Model
				$make = sanitize_text_field( $data[0] );
				$model = sanitize_text_field( $data[1] );
				$year = intval( $data[2] );
				$engine_id = intval( $data[3] );
				$aces_make = intval( $data[4] );
				$aces_model = intval( $data[5] );

				array_push( $values, $make, $model, $year, $engine_id, $aces_make, $aces_model );
				$placeholders[] = "('%s', '%s', %d, %d, %d, %d)";

				if ( count( $placeholders ) >= $batch_size ) {
					$this->execute_batch( $table_name, $values, $placeholders );
					$values = [];
					$placeholders = [];
				}
			}

			// Insert remaining
			if ( ! empty( $placeholders ) ) {
				$this->execute_batch( $table_name, $values, $placeholders );
			}

			fclose( $handle );
		}
	}

	private function execute_batch( $table, $values, $placeholders ) {
		global $wpdb;
		$query = "INSERT INTO {$table} (make_name, model_name, year_id, engine_id, aces_make_id, aces_model_id) VALUES " . implode( ', ', $placeholders );
		$wpdb->query( $wpdb->prepare( $query, $values ) );
	}

	/**
	 * Import Fitment from CSV
	 */
	public function import_fitment( $file_path ) {
		// Similar batched logic for wp_tpo_fitment_lookup
		// Mapping SKU -> fitment
	}
}
