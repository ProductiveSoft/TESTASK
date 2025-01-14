<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles logging of SMS messages sent by the WooCommerce SMS Notifications plugin.
 */
class WCSMS_Log_Status {
    /**
     * Logs the details of an SMS message into the database.
     *
     * @param string $phone_number The recipient's phone number.
     * @param string $message The SMS message content.
     * @param string $status The status of the SMS (e.g., sent, failed).
     */
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
