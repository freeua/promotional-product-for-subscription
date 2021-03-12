=== Promotional Product for Subscription ===
Contributors: freeua
Tags: woocommerce, woocommerce subscription, product
Requires at least: 5.0
Tested up to: 5.7
Requires PHP: 5.7
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Increase your sales by allowing your customers to add promotional products to their current subscription.

== Description ==

A custom addon for Woocommerce Subscriptions, which add a promotional product to the currently active user subscription and remove it after regular payment.

= Take Credit card payments easily and directly on your store =

The Vantiv plugin extends WooCommerce allowing you to take payments directly on your store via Vantiv’s API.  Plugin uses The Vantiv Express API
[Express API link](https://developer.vantiv.com/docs/DOC-1353).

== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of the WooCommerce Vantiv plugin, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “WooCommerce Vantiv Gateway” and click Search Plugins. Once you’ve found our plugin you can view details about it such as the point release, rating, and description. Most importantly, of course, you can install it by simply clicking "Install Now", then "Activate".

= Manual Upload via WordPress Admin =

If you have a copy of the plugin as a zip file, you can manually upload it and install it through the Plugins admin screen.

1. Navigate to Plugins > Add New.
2. Click the Upload Plugin button at the top of the screen.
3. Select the zip file from your local filesystem.
4. Click the Install Now button.
When installation is complete, you’ll see “Plugin installed successfully.” Click the Activate Plugin button at the bottom of the page.

= Manual Plugin Installation =

In rare cases, you may need to install a plugin by manually transferring the files onto the server. This is recommended only when absolutely necessary, for example when your server is not configured to allow automatic installations.

This procedure requires you to be familiar with the process of transferring files using an SFTP client

1.	If your plugin is in the form of a zip file, unzip the contents. You should see a single folder named after the plugin.
2.	Look in the plugin folder for a readme.txt file. Read the file to confirm that this is the correct plugin, and to look for any special instructions.
3.	Connect to your WordPress server with your SFTP client.
4.	Copy the plugin folder to the wp-content/plugins folder in your WordPress directory. This installs the plugin to your WordPress site.
5.	Navigate to your Plugins admin screen and locate the newly uploaded plugin in the list.
6.	Click the plugin’s “Activate” link.
7.	If there is one, click the plugin’s “View details” link to learn more about the plugin.


== Frequently Asked Questions ==

= Does this support recurring payments, like for subscriptions? =

No

= Does this require an SSL certificate? =

Yes! In Live Mode, an SSL certificate must be installed on your site to use Vantiv. 

= Does this support both production mode and sandbox mode for testing? =

Yes, it does - production and Test (sandbox) mode is driven by the API keys you use with a checkbox in the admin settings to toggle between both.

= Where can I get support or talk to other users? =

If you get stuck, you can ask for help in the Plugin Forum.

== Screenshots ==
1. The Vantiv payment gateway settings screen used to configure the main Vantiv gateway.
2. Pay with a Vantiv payment method on checkout page 

== Changelog ==

= 1.0.0 =
* First Release
