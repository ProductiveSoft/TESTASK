<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WCSMS_Order_Status_SMS {
    public static function init() {
        add_action('woocommerce_order_status_changed', [__CLASS__, 'send_order_status_sms'], 10, 3);
    }

    public static function send_order_status_sms($order_id, $old_status, $new_status) {
        error_log("Order status changed: Order ID = $order_id, Old Status = $old_status, New Status = $new_status");

        $normalized_status = str_replace('wc-', '', $new_status);
        $enabled = get_option("wcsms_enable_$normalized_status");

        error_log("Checking SMS enabled option for status: $normalized_status. Value: $enabled");

        if ($enabled !== 'yes') {
            error_log("SMS not enabled for status: $normalized_status");
            return;
        }

        $order = wc_get_order($order_id);
        $phone_number = $order->get_billing_phone();

        if (!$phone_number) {
            error_log("No phone number found for Order ID $order_id");
            return;
        }

        $default_messages = self::get_default_messages_from_settings();
        $custom_message = get_option("wcsms_message_$normalized_status");
        $message_template = $custom_message ?: ($default_messages[$normalized_status] ?? '');

        if (!$message_template) {
            error_log("No message template found for status: $normalized_status");
            return;
        }

        $message = str_replace(
            ['{customer_name}', '{order_number}', '{status}'],
            [$order->get_billing_first_name(), $order_id, $new_status],
            $message_template
        );

        $response = WCSMS_API::send_sms($phone_number, $message);

        if (!$response) {
            error_log("Failed to send SMS for Order $order_id to $phone_number");
        } else {
            error_log("SMS successfully sent for Order $order_id to $phone_number");
        }
    }

    private static function get_default_messages_from_settings() {
        $default_messages = [];
        foreach (wc_get_order_statuses() as $status => $label) {
            $status_key = str_replace('wc-', '', $status);
            $default_messages[$status_key] = get_option("wcsms_message_$status_key", '');
        }
        return $default_messages;
    }
}

WCSMS_Order_Status_SMS::init();
