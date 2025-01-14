<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles cart abandonment notifications.
 */
class WCSMS_Cart_Abandonment {
    /**
     * Initializes the cart abandonment feature by adding necessary actions.
     */
    public static function init() {
        add_action('wp', [__CLASS__, 'schedule_cart_abandonment_check']);
        add_action('wcsms_check_abandoned_carts', [__CLASS__, 'process_abandoned_carts']);
    }

    /**
     * Schedules the cart abandonment check if it is not already scheduled.
     */
    public static function schedule_cart_abandonment_check() {
        if (!wp_next_scheduled('wcsms_check_abandoned_carts')) {
            wp_schedule_event(time(), 'hourly', 'wcsms_check_abandoned_carts');
        }
    }

    /**
     * Processes abandoned carts by retrieving them and sending SMS notifications.
     */
    public static function process_abandoned_carts() {
        global $wpdb;

        $abandonment_time = get_option('wcsms_abandonment_time', 1440);
        $cutoff_time = time() - ($abandonment_time * 60);
        $abandoned_carts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_sessions WHERE session_expiry < $cutoff_time");

        foreach ($abandoned_carts as $cart) {
            $session_data = maybe_unserialize($cart->session_value);
            $phone_number = $session_data['billing']['phone'];
            $message = get_option('wcsms_cart_abandonment_message');

            $response = WCSMS_API::send_sms($phone_number, $message);
            if (!$response) {
                error_log("Failed to send cart abandonment SMS to $phone_number");
            }
        }
    }
}

WCSMS_Cart_Abandonment::init();

