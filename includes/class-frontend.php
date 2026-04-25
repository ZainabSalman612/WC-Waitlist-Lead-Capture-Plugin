<?php
class Waitlist_Frontend {
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/frontend.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/frontend.js', array( 'jquery' ), $this->version, false );
	}

	public function register_shortcodes() {
		add_shortcode( 'waitlist_signup', array( $this, 'render_signup_form' ) );
		add_shortcode( 'waitlist_login', array( $this, 'render_login_form' ) );
	}

	public function render_signup_form() {
		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'templates/signup-form.php';
		return ob_get_clean();
	}

	public function render_login_form() {
		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'templates/login-form.php';
		return ob_get_clean();
	}
}
