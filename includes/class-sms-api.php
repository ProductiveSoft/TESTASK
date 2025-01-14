<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCSMS_API
 * Handles communication with the external SMS API for sending messages.
 */
class WCSMS_API {
    /**
     * Sends an SMS message to a specified phone number using the configured SMS API.
     *
     * @param string $phone_number The recipient's phone number.
     * @param string $message The SMS message content.
     * @return string|false The API response body on success, or false on failure.
     */
    public static function send_sms($phone_number, $message) {
        $api_key = get_option('wcsms_api_key');
        $sender_id = get_option('wcsms_sender_id');

        $response = wp_remote_post('https://api.pulseem.co.il/api/v1/SmsApi/SendSms', array(
            'body' => json_encode(array(
                'SendId' => uniqid(),
                'FromNumber' => $sender_id,
                'ToNumberList' => [$phone_number],
                'TextList' => [$message]
            )),
            'headers' => array(
                'Content-Type' => 'application/json',
                'APIKEY' => $api_key,
            ),
        ));

        $status = is_wp_error($response) ? 'failed' : 'success';
        WCSMS_Log_Status::log_sms($phone_number, $message, $status);

        if (is_wp_error($response)) {
            error_log("SMS API Error: " . $response->get_error_message());
            return false;
        }

        return wp_remote_retrieve_body($response);
    }
}
