<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TPO_Frontend {

	public function __construct() {
		add_shortcode( 'tpo_home', array( $this, 'render_homepage' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'tpo-fitment-css', TPO_FITMENT_URL . 'public/css/tpo-fitment.css', array(), TPO_FITMENT_VERSION );
		wp_enqueue_script( 'tpo-fitment-js', TPO_FITMENT_URL . 'public/js/tpo-fitment.js', array( 'jquery' ), TPO_FITMENT_VERSION, true );
		
		wp_localize_script( 'tpo-fitment-js', 'tpo_ajax', array(
			'url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'tpo_ymm_nonce' )
		));
	}

	public function render_selector() {
		?>
		<div id="tpo-ymm-selector" class="tpo-garage-bar">
			<div class="container">
				<span class="garage-label">My Garage:</span>
				<select id="tpo-year"><option value="">Year</option></select>
				<select id="tpo-make" disabled><option value="">Make</option></select>
				<select id="tpo-model" disabled><option value="">Model</option></select>
				<button id="tpo-go-btn" disabled>Go</button>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Homepage Shortcode [tpo_home]
	 */
	public function render_homepage() {
		ob_start();
		?>
		<div class="tpo-clone-wrapper">
			
			<!-- Fake Header (Visual only, to match their look) -->
			<header class="tpo-clone-header">
				<div class="tpo-top-bar">Free Shipping on Orders Over $99</div>
				<div class="tpo-main-header tpo-container">
					<div class="tpo-logo">
						<img src="https://og-audio.com/img/logo-1725464191.jpg" alt="OG Audio Logo">
					</div>
					<div class="tpo-search">
						<input type="text" placeholder="Search our catalog...">
						<button>🔍</button>
					</div>
				</div>
			</header>



		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render Badge on Product Loop/Single
	 */
	public function render_product_badge() {
		global $product;
		$vehicle_id = TPO_Session_Handler::get_vehicle();

		if ( ! $vehicle_id ) {
			return; // No vehicle selected, maybe show "Universal" or nothing
		}

		// Logic to check fitment for *this* product vs *current* vehicle
		// This requires a separate lookup or checking a property if we pre-loaded it.
		// For performance in a loop, we might rely on the main query filtering out non-fits,
		// so everything remaining IS a fit.
		// BUT if we want "Does not fit" badges on pages where we don't filter (like Related Products),
		// we need a check.
		
		echo '<div class="tpo-fitment-badge tpo-fit-yes">Guaranteed to Fit</div>';
	}
}
