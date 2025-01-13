<?php
add_action('admin_menu', function() {
    add_submenu_page(
        'woocommerce',
        'SMS Notifications',
        'SMS Notifications',
        'manage_options',
        'wcsms-settings',
        'wcsms_render_settings_page'
    );
});

function wcsms_get_default_messages() {
    return [
        'pending'    => 'Dear {customer_name}, your order {order_number} is pending. We will notify you once it is confirmed.',
        'processing' => 'Dear {customer_name}, your order {order_number} is now processing. Thank you for your patience.',
        'on-hold'    => 'Dear {customer_name}, your order {order_number} is on hold. Please contact us if you have any questions.',
        'completed'  => 'Dear {customer_name}, your order {order_number} has been completed. Thank you for shopping with us!',
        'cancelled'  => 'Dear {customer_name}, your order {order_number} has been cancelled. If this is a mistake, please contact us.',
        'refunded'   => 'Dear {customer_name}, your order {order_number} has been refunded. The amount will reflect in your account shortly.',
        'failed'     => 'Dear {customer_name}, the payment for your order {order_number} has failed. Please try again or contact support.',
    ];
}

function wcsms_render_settings_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wcsms_save_settings_nonce']) && wp_verify_nonce($_POST['wcsms_save_settings_nonce'], 'wcsms_save_settings')) {
        update_option('wcsms_api_key', sanitize_text_field($_POST['api_key']));
        update_option('wcsms_sender_id', sanitize_text_field($_POST['sender_id']));
        update_option('wcsms_abandonment_time', intval($_POST['abandonment_time']));

        foreach (wc_get_order_statuses() as $status => $label) {
            $normalized_status = str_replace('wc-', '', $status);
            $message_key = "message_$normalized_status";
            $enable_key = "enable_$normalized_status";

            if (!empty($_POST[$message_key])) {
                update_option("wcsms_$message_key", sanitize_textarea_field($_POST[$message_key]));
            }
            update_option("wcsms_$enable_key", isset($_POST[$enable_key]) ? 'yes' : 'no');
        }
        echo '<div class="updated"><p>Settings saved successfully.</p></div>';
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo '<div class="error"><p>Invalid request. Please try again.</p></div>';
    }

    $default_messages = wcsms_get_default_messages();
    ?>
    <div class="wrap">
        <h1>WooCommerce SMS Notifications Settings</h1>
        <form method="post">
            <?php wp_nonce_field('wcsms_save_settings', 'wcsms_save_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th>API Key</th>
                    <td><input type="text" name="api_key" value="<?php echo esc_attr(get_option('wcsms_api_key')); ?>" style="width: 100%;"></td>
                </tr>
                <tr>
                    <th>Sender ID</th>
                    <td><input type="text" name="sender_id" value="<?php echo esc_attr(get_option('wcsms_sender_id')); ?>" style="width: 100%;"></td>
                </tr>
                <tr>
                    <th>Abandonment Time (minutes)</th>
                    <td><input type="number" name="abandonment_time" value="<?php echo esc_attr(get_option('wcsms_abandonment_time', 1440)); ?>"></td>
                </tr>
                <?php foreach (wc_get_order_statuses() as $status => $label):
                    $normalized_status = str_replace('wc-', '', $status);
                    $saved_message = get_option("wcsms_message_$normalized_status");
                    $message_to_display = $saved_message !== false ? $saved_message : $default_messages[$normalized_status];
                    ?>
                    <tr>
                        <th><?php echo esc_html($label); ?> Message</th>
                        <td>
                        <textarea name="message_<?php echo esc_attr($normalized_status); ?>" style="width: 100%; resize: none;"><?php
                            echo esc_textarea($message_to_display);
                        ?></textarea>
                            <label><input type="checkbox" name="enable_<?php echo esc_attr($normalized_status); ?>" <?php checked(get_option("wcsms_enable_$normalized_status"), 'yes'); ?>> Enable</label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit" name="save_settings" class="button-primary">Save Settings</button>
        </form>
    </div>
    <?php
}
