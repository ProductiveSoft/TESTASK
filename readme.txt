=== WooCommerce SMS Notifications ===
Contributors: Your Name or Company
Tags: WooCommerce, SMS Alerts, WooCommerce SMS, Order SMS Notifications, Bulk SMS, Order Status SMS, Cart Abandonment SMS

Send SMS notifications for WooCommerce order statuses and cart abandonment reminders using the Pulseem SMS API.

== Description ==

The WooCommerce SMS Notifications plugin allows WooCommerce store owners to send SMS notifications to customers and administrators based on order status changes and cart abandonment. The plugin integrates seamlessly with the Pulseem SMS API, ensuring reliable delivery of SMS messages.

With this plugin, you can:
* Notify customers automatically when their order status changes.
* Send cart abandonment reminders to encourage customers to complete their purchases.
* Customize SMS content for each notification type directly from the admin panel.

### Features

* Easy integration with the Pulseem SMS API.
* Fully customizable SMS templates for order statuses and cart abandonment.
* Configurable cart abandonment notification time.
* Toggle notifications on/off for specific order statuses.
* Logs of all sent SMS messages, including delivery status and content.
* Lightweight and efficient, with no unnecessary features or bloat.

### Supported Events
* Order Completed
* Order Processing
* Order Pending Payment
* Order Cancelled
* Order Refunded
* Order Failed
* Cart Abandonment Reminder

== Installation ==

### WordPress Dashboard Installation
1. Download the plugin as a `.zip` file.
2. Log in to your WordPress admin panel.
3. Navigate to *Plugins > Add New*.
4. Click *Upload Plugin* and select the `.zip` file.
5. Click *Install Now*.
6. Once installed, click *Activate*.
7. Go to *WooCommerce > SMS Notifications* in the admin menu to configure settings.

### Manual Installation via FTP
1. Download the plugin as a `.zip` file and extract it.
2. Use an FTP client to connect to your WordPress server.
3. Upload the extracted `woocommerce-sms-notifications` folder to `wp-content/plugins/`.
4. Log in to your WordPress admin panel.
5. Navigate to *Plugins > Installed Plugins*.
6. Find the "WooCommerce SMS Notifications" plugin and click *Activate*.
7. Go to *WooCommerce > SMS Notifications* in the admin menu to configure settings.

== Screenshots ==
1. Plugin settings page to configure API and message templates.
2. Activity log showing sent SMS messages with delivery status.

== Changelog ==
= 1.0.0 =
* Initial release with order status notifications and cart abandonment reminders.

= 1.1.0 =
* Added toggles for enabling/disabling notifications by order status.
* Enhanced SMS templates with placeholders like `{customer_name}` and `{order_number}`.
* Improved activity log with detailed status and timestamps.

== Upgrade Notice ==
Update to version 1.1.0 for new features, including customizable toggles and enhanced placeholders.

== Frequently Asked Questions ==

= How do I configure the plugin? =
After activation, go to *WooCommerce > SMS Notifications*. Enter your Pulseem API key, configure the sender number, and set up SMS templates for order statuses and cart abandonment.

= Can I disable notifications for specific order statuses? =
Yes, the plugin provides toggles to enable/disable notifications for each order status in the settings page.

= Does the plugin support international SMS? =
Yes, as long as your Pulseem API account is set up for international SMS delivery.

= How can I view sent SMS logs? =
Go to *WooCommerce > SMS Notifications* and view the activity log tab to see detailed records of sent messages, including status and content.
