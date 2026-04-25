<?php
/**
 * Fired during plugin activation.
 */
class Waitlist_Activator {

	public static function activate() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'waitlist_leads';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id bigint(20) NULL,
			name varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			phone varchar(50) DEFAULT '' NOT NULL,
			interest text NULL,
			status varchar(50) DEFAULT 'new' NOT NULL,
			source varchar(255) DEFAULT '' NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY email (email)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		// Set a transient to flush rewrite rules if needed
		set_transient( 'waitlist_flush_rewrite_rules', true );
	}

}
