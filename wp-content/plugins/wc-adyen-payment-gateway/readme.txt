=== Payment Gateway for Adyen and WooCommerce ===
Contributors: waseem_senjer,wprubyplugins
Donate link: https://wpruby.com
Tags: woocommerce, woocommerce extension, adyen, payment gateway
Requires at least: 4.0
Tested up to: 5.4
Stable tag: 1.1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adyen Integration for WooCommerce.

== Description ==
Start accepting card payments on your WooCommerce by using Adyen integration.

= Key Benefits =
* Accepting debit and credit card payments.
* Automatically updating Order statuses using Adyen webhooks notifications.
* Automatically refunds payments on Adyen when you refund them in WooCommerce.
* Capture payments immediately.

= Setup instructions =
-   [Setup the Adyen payment gateway](https://wpruby.com/knowledgebase/setup-the-adyen-payment-gateway/)
-   [How to configure Adyen Webhook notifications?](https://wpruby.com/knowledgebase/how-to-configure-adyen-webhook-notifications/)

######Disclaimer: This plugin is created and maintained by WPRuby.com using the publicly available API of Adyen and WooCommerce. All product names, trademarks and registered trademarks are property of their respective owners.


== Installation ==
= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Adyen Payment Gateway for WooCommerce'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `wc-adyen-payment-gateway.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `wc-adyen-payment-gateway.zip`
2. Extract the `wc-adyen-payment-gateway` directory to your computer
3. Upload the `wc-adyen-payment-gateway` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard



== Frequently Asked Questions ==

= How to get Adyen API key? =

To generate an API Key:
1.  Log in to your  [Customer Area](https://ca-test.adyen.com/).
2.  Navigate to  **Account**  >  **Users**, and click the user  **ws@Company.[YourCompanyAccount]**.
3.  Under  **Authentication**, click  **Generate New API Key**.

	    Don't forget to copy and securely store the API Key in your system. If you lose this API Key you won't be able to restore it later.

4.  Click  **Save**  at the bottom of the page.


= How to get Adyen Origin key? =

In order to get an origin key, please follow [this guide from Adyen](https://docs.adyen.com/user-management/how-to-get-an-origin-key).

== Screenshots ==

1. The plugin provides test mode where you can test the Adyen payment gateway before going live.
2. At the moment, the plugin support Adyen Card payments.

== Changelog ==
= 1.1.0 =
* Added: Live API prefix option.

= 1.0.2 =
* Fixed: API Key was not decoded properly.
* Added: Integrator information to API request.

= 1.0.1 =
* Fix autoload for linux operating systems.

= 1.0 =
* Initial release.

== Upgrade Notice ==
* Initial release