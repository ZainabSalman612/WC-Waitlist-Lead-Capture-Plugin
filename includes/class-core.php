<?php
/**
 * The core plugin class.
 */
class Waitlist_Core {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->plugin_name = 'custom-waitlist-plugin';
		$this->version = WAITLIST_PLUGIN_VERSION;
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {
		require_once WAITLIST_PLUGIN_DIR . 'includes/class-loader.php';
		require_once WAITLIST_PLUGIN_DIR . 'includes/class-leads.php';
		require_once WAITLIST_PLUGIN_DIR . 'includes/class-auth.php';
		require_once WAITLIST_PLUGIN_DIR . 'includes/class-admin.php';
		require_once WAITLIST_PLUGIN_DIR . 'includes/class-frontend.php';
		require_once WAITLIST_PLUGIN_DIR . 'includes/class-maintenance.php';

		$this->loader = new Waitlist_Loader();
	}

	private function define_admin_hooks() {
		$plugin_admin = new Waitlist_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'process_actions' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
	}

	private function define_public_hooks() {
		$plugin_frontend = new Waitlist_Frontend( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $plugin_frontend, 'register_shortcodes' );

		$plugin_auth = new Waitlist_Auth();
		$this->loader->add_action( 'admin_post_nopriv_waitlist_signup', $plugin_auth, 'process_signup' );
		$this->loader->add_action( 'admin_post_waitlist_signup', $plugin_auth, 'process_signup' );
		$this->loader->add_action( 'admin_post_nopriv_waitlist_login', $plugin_auth, 'process_login' );
		$this->loader->add_action( 'admin_post_waitlist_login', $plugin_auth, 'process_login' );

		$plugin_maintenance = new Waitlist_Maintenance();
		$this->loader->add_action( 'template_redirect', $plugin_maintenance, 'check_maintenance_mode' );
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_version() {
		return $this->version;
	}
}
