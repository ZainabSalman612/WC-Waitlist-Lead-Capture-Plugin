<?php
class Waitlist_Leads {
	private $table_name;

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'waitlist_leads';
	}

	public function add_lead( $data ) {
		global $wpdb;
		$wpdb->insert(
			$this->table_name,
			array(
				'user_id'  => isset( $data['user_id'] ) ? intval($data['user_id']) : null,
				'name'     => sanitize_text_field( $data['name'] ),
				'email'    => sanitize_email( $data['email'] ),
				'phone'    => isset( $data['phone'] ) ? sanitize_text_field( $data['phone'] ) : '',
				'interest' => isset( $data['interest'] ) ? sanitize_textarea_field( $data['interest'] ) : '',
				'source'   => isset( $data['source'] ) ? sanitize_text_field( $data['source'] ) : '',
				'status'   => 'new'
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
		);
		return $wpdb->insert_id;
	}

	public function get_lead_by_email( $email ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE email = %s", $email );
		return $wpdb->get_row( $query );
	}

	public function update_lead_user_id( $lead_id, $user_id ) {
		global $wpdb;
		return $wpdb->update(
			$this->table_name,
			array( 'user_id' => intval($user_id) ),
			array( 'id' => intval($lead_id) ),
			array( '%d' ),
			array( '%d' )
		);
	}

	public function update_lead_status( $lead_id, $status ) {
		global $wpdb;
		return $wpdb->update(
			$this->table_name,
			array('status' => sanitize_text_field($status)),
			array('id' => intval($lead_id)),
			array('%s'),
			array('%d')
		);
	}
}
