<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Deletes the SMS logs table from the database.
 */
global $wpdb;
$table_name = $wpdb->prefix . 'wcsms_logs';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

/**
 * Removes plugin-specific settings stored in the WordPress options table.
 */
delete_option('wcsms_api_key');
delete_option('wcsms_sender_id');
delete_option('wcsms_abandonment_time');

/**
 * Cleans up message templates and settings related to each WooCommerce order status.
 */
foreach (wc_get_order_statuses() as $status => $label) {
    $normalized_status = str_replace('wc-', '', $new_status);
    delete_option("wcsms_message_$normalized_status");
    delete_option("wcsms_enable_$normalized_status");
}
