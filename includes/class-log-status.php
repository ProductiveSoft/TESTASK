<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCSMS_Log_Status {
    public static function log_sms($phone_number, $message, $status) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wcsms_logs';

        $wpdb->insert(
            $table_name,
            [
                'phone_number' => $phone_number,
                'message'      => $message,
                'status'       => $status,
                'sent_at'      => current_time('mysql'),
            ],
            [
                '%s',
                '%s',
                '%s',
                '%s',
            ]
        );

        if ($wpdb->last_error) {
            error_log('WCSMS Log Error: ' . $wpdb->last_error);
        }
    }
}
