<?php
require_once plugin_dir_path( __FILE__ ) . 'class-waitlist-list-table.php';

class Waitlist_Admin {
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/admin.css', array(), $this->version, 'all' );
	}
    
    public function register_settings() {
        register_setting('waitlist_options_group', 'waitlist_maintenance_mode');
        register_setting('waitlist_options_group', 'waitlist_redirect_page');
    }

	public function add_plugin_admin_menu() {
		add_menu_page(
			'Waitlist Leads', 
			'Waitlist Leads', 
			'manage_options', 
			$this->plugin_name, 
			array( $this, 'display_plugin_admin_page' ), 
			'dashicons-groups', 
			25
		);

        add_submenu_page(
            $this->plugin_name,
            'Settings',
            'Settings',
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'display_settings_page')
        );
	}

	public function display_plugin_admin_page() {
		$leads_table = new Waitlist_List_Table();
		$leads_table->prepare_items();
		?>
		<div class="wrap">
			<h2>Waitlist Leads</h2>
			<form method="post">
				<?php
				$leads_table->search_box( 'search', 'search_id' );
				$leads_table->display();
				?>
			</form>
            <hr>
            <h3>Export</h3>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="waitlist_export_csv">
                <?php wp_nonce_field('waitlist_export', 'waitlist_export_nonce'); ?>
                <button type="submit" class="button button-primary">Export All Leads to CSV</button>
            </form>
		</div>
		<?php
	}

    public function display_settings_page() {
        ?>
        <div class="wrap">
            <h2>Waitlist Settings</h2>
            <form method="post" action="options.php">
                <?php settings_fields('waitlist_options_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Enable Maintenance Mode</th>
                        <td>
                            <input type="checkbox" name="waitlist_maintenance_mode" value="1" <?php checked('1', get_option('waitlist_maintenance_mode')); ?> />
                            <p class="description">If checked, non-logged-in users will be redirected to the waitlist sign-up.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Post Log-in/Signup Redirect URL</th>
                        <td>
                            <input type="text" name="waitlist_redirect_page" value="<?php echo esc_attr(get_option('waitlist_redirect_page', home_url('/my-account/'))); ?>" class="regular-text" />
                            <p class="description">Where users should be directed after signing up or logging in. Default is /my-account/</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

	public function process_actions() {
        if ( isset( $_POST['action'] ) && $_POST['action'] === 'waitlist_export_csv' ) {
            if ( ! isset( $_POST['waitlist_export_nonce'] ) || ! wp_verify_nonce( $_POST['waitlist_export_nonce'], 'waitlist_export' ) ) {
                wp_die('Invalid security token');
            }
            if ( ! current_user_can('manage_options') ) wp_die('Unauthorized');

            global $wpdb;
            $table_name = $wpdb->prefix . 'waitlist_leads';
            $leads = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY id DESC", ARRAY_A );

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="waitlist-leads.csv"');

            $output = fopen('php://output', 'w');
            fputcsv($output, array('ID', 'User ID', 'Name', 'Email', 'Phone', 'Interest', 'Status', 'Source', 'Created At'));

            foreach ( $leads as $lead ) {
                fputcsv($output, $lead);
            }
            fclose($output);
            exit;
        }

        if ( ( isset( $_POST['action'] ) && $_POST['action'] === 'mark_contacted' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] === 'mark_contacted' ) ) {
            if ( ! current_user_can('manage_options') ) return;
            $lead_ids = isset($_POST['lead']) ? array_map('intval', (array) $_POST['lead']) : array();
            if ( ! empty( $lead_ids ) ) {
                $leads_db = new Waitlist_Leads();
                foreach ( $lead_ids as $id ) {
                    $leads_db->update_lead_status( $id, 'contacted' );
                }
            }
            wp_redirect( admin_url('admin.php?page=' . $this->plugin_name) );
            exit;
        }

        if ( ( isset( $_POST['action'] ) && $_POST['action'] === 'mark_converted' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] === 'mark_converted' ) ) {
            if ( ! current_user_can('manage_options') ) return;
            $lead_ids = isset($_POST['lead']) ? array_map('intval', (array) $_POST['lead']) : array();
            if ( ! empty( $lead_ids ) ) {
                $leads_db = new Waitlist_Leads();
                foreach ( $lead_ids as $id ) {
                    $leads_db->update_lead_status( $id, 'converted' );
                }
            }
            wp_redirect( admin_url('admin.php?page=' . $this->plugin_name) );
            exit;
        }
	}
}
