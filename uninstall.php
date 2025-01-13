<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'wcsms_logs';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

delete_option('wcsms_api_key');
delete_option('wcsms_sender_id');
delete_option('wcsms_abandonment_time');

foreach (wc_get_order_statuses() as $status => $label) {
    $normalized_status = str_replace('wc-', '', $new_status);
    delete_option("wcsms_message_$normalized_status");
    delete_option("wcsms_enable_$normalized_status");
}
