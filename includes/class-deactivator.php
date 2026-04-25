<?php
/**
 * Fired during plugin deactivation.
 */
class Waitlist_Deactivator {
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
