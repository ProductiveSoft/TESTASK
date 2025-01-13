<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function() {
    add_submenu_page(
        'woocommerce',
        'SMS Logs',
        'SMS Logs',
        'manage_options',
        'wcsms-logs',
        'wcsms_render_log_viewer'
    );
});

function wcsms_render_log_viewer() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wcsms_logs';

    $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY sent_at DESC");

    ?>
    <div class="wrap">
        <h1>SMS Logs</h1>
        <table class="widefat fixed">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>Phone Number</th>
                    <th>Message</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?php echo esc_html($log->sent_at); ?></td>
                            <td><?php echo esc_html($log->phone_number); ?></td>
                            <td><?php echo esc_html($log->message); ?></td>
                            <td><?php echo esc_html($log->status); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
