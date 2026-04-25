<?php
class Waitlist_Maintenance {

	public function check_maintenance_mode() {
		$maintenance_mode = get_option('waitlist_maintenance_mode', false);

		if ( ! $maintenance_mode ) {
			return;
		}

		if ( is_user_logged_in() ) {
			return;
		}

		// Don't block our login/signup AJAX actions
		if ( defined( 'DOING_AJAX' ) || is_admin() || strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false ) {
			return;
		}

		// Render custom maintenance template overlay
		status_header( 503 );
		include plugin_dir_path( dirname( __FILE__ ) ) . 'templates/maintenance-page.php';
		exit;
	}
}
