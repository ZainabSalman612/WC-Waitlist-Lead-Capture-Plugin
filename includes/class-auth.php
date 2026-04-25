<?php
class Waitlist_Auth {

	public function process_signup() {
		if ( ! isset( $_POST['waitlist_signup_nonce'] ) || ! wp_verify_nonce( $_POST['waitlist_signup_nonce'], 'waitlist_signup' ) ) {
			wp_die('Invalid security token.');
		}

		$name     = sanitize_text_field( $_POST['name'] );
		$email    = sanitize_email( $_POST['email'] );
		$password = $_POST['password'];
		$phone    = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
		$interest = isset( $_POST['interest'] ) ? sanitize_textarea_field( $_POST['interest'] ) : '';

		if ( empty( $name ) || empty( $email ) || empty( $password ) ) {
			wp_redirect( add_query_arg( 'signup_error', 'empty_fields', wp_get_referer() ) );
			exit;
		}

		$user_id = email_exists( $email );
		if ( ! $user_id ) {
			// Create user
			$user_id = wp_insert_user( array(
				'user_login' => $email,
				'user_email' => $email,
				'user_pass'  => $password,
				'first_name' => $name,
				'role'       => 'customer'
			) );

			if ( is_wp_error( $user_id ) ) {
				wp_redirect( add_query_arg( 'signup_error', 'registration_failed', wp_get_referer() ) );
				exit;
			}
		}

		// Store as lead
		$leads_db = new Waitlist_Leads();
		$existing_lead = $leads_db->get_lead_by_email( $email );
		if ( ! $existing_lead ) {
			$leads_db->add_lead( array(
				'user_id'  => $user_id,
				'name'     => $name,
				'email'    => $email,
				'phone'    => $phone,
				'interest' => $interest,
				'source'   => 'frontend_form'
			) );
		} else if ( ! $existing_lead->user_id ) {
			$leads_db->update_lead_user_id( $existing_lead->id, $user_id );
		}

		// Auto login
		$user = get_user_by( 'id', $user_id );
		wp_set_current_user( $user_id, $user->user_login );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $user->user_login, $user );

		// Redirect
		$redirect_url = get_option('waitlist_redirect_page', home_url('/my-account/'));
		wp_redirect( $redirect_url );
		exit;
	}

	public function process_login() {
		if ( ! isset( $_POST['waitlist_login_nonce'] ) || ! wp_verify_nonce( $_POST['waitlist_login_nonce'], 'waitlist_login' ) ) {
			wp_die('Invalid security token.');
		}

		$creds = array(
			'user_login'    => sanitize_user( $_POST['username'] ),
			'user_password' => $_POST['password'],
			'remember'      => true
		);

		$user = wp_signon( $creds, false );

		if ( is_wp_error( $user ) ) {
			wp_redirect( add_query_arg( 'login_error', 'invalid_credentials', wp_get_referer() ) );
			exit;
		}

		$redirect_url = get_option('waitlist_redirect_page', home_url('/my-account/'));
		wp_redirect( $redirect_url );
		exit;
	}
}
