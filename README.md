# WC Waitlist Lead Capture Plugin

A production-ready WordPress plugin for WooCommerce that combines waitlist lead capture with native user account creation.

## Features
- **Combined Signup Form**: Seamlessly capture leads while generating native WordPress/WooCommerce customer accounts.
- **Lead Storage**: Stores user data securely in a custom table (`wp_waitlist_leads`). 
- **Admin Dashboard**: Easy-to-use dashboard to search, filter, update statuses, and export leads to CSV.
- **Maintenance Mode**: Restrict your site to non-logged-in users while still capturing waitlist interest.
- **Native Shortcodes**: Deploy your custom responsive forms anywhere via `[waitlist_signup]` and `[waitlist_login]`.

## Installation
1. Move the `Custom-waitlist-plugin-` directory into your WordPress `wp-content/plugins/` directory.
2. Go to your WordPress Dashboard > **Plugins**.
3. Locate **WC Waitlist Lead Capture** and click **Activate**.
4. The plugin will automatically configure the required database tables.

## Usage
- **Settings & Dashboard**: Go to **Waitlist Leads** in the left-hand WordPress admin panel.
- **Signup Form**: Place the shortcode `[waitlist_signup]` on the desired page.
- **Login Form**: Place the shortcode `[waitlist_login]` on the desired page.
- **Maintenance Mode**: Go to **Waitlist Leads** -> **Settings** and check "Enable Maintenance Mode". Unauthenticated visitors will see a "Coming Soon" screen rendering your waitlist form, while authenticated users will experience the site normally.

## Security Practices Used
- `dbDelta` implementation for reliable initial schema staging.
- Full `wpdb->prepare` integration for explicit defense against SQL injection.
- Robust WP nonces generated for all authentication and admin manipulation endpoints.
