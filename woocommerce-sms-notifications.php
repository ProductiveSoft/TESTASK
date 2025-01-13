<?php
/*
Plugin Name: WooCommerce SMS Notifications
Description: Sends SMS notifications to customers based on order status changes and cart abandonment.
Version: 1.0.0
Author: Phil Wamba
Author URI: https://github.com/philwamba
*/

defined('ABSPATH') || exit;

define('WCSMS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WCSMS_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once WCSMS_PLUGIN_DIR . 'admin/settings-page.php';
require_once WCSMS_PLUGIN_DIR . 'admin/log-viewer.php';
require_once WCSMS_PLUGIN_DIR . 'includes/class-sms-api.php';
require_once WCSMS_PLUGIN_DIR . 'includes/class-log-status.php';
require_once WCSMS_PLUGIN_DIR . 'includes/class-cart-abandonment.php';
require_once WCSMS_PLUGIN_DIR . 'includes/class-order-status.php';

register_activation_hook(__FILE__, 'wcsms_create_logs_table');
function wcsms_create_logs_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wcsms_logs';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        phone_number VARCHAR(15) NOT NULL,
        message TEXT NOT NULL,
        status VARCHAR(50) NOT NULL,
        sent_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
        error_log("WooCommerce SMS Notifications: Table '$table_name' created successfully.");
    } else {
        error_log("WooCommerce SMS Notifications: Failed to create table '$table_name'.");
    }
}
