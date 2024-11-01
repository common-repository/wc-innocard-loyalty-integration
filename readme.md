=== Innocard Loyalty Integration for WooCommerce ===
Contributors: loyaltyag, ibrunodev
Tags: innocard,loyalty,integration,fidelity,woocommerce
Requires at least: 4.7
Tested up to: 6.3
Stable tag: 6.3
Requires PHP: 7.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

With this plugin, you can allow customers to use their Innocard Loyalty balance to pay part or all of their order on your woocommerce website.
Our plugin creates a discount in the customer's cart based on the customer's card balance while maintaining their default payment gateway.

== Description ==

# Innocard Loyalty Integration for WooCommerce

Plugin to integrate woocommerce with the Innocard Loyalty transaction service.

With this plugin, you can allow customers to use their Innocard Loyalty balance to pay part or all of their order on your woocommerce website.
Our plugin creates a discount in the customer's cart based on the customer's card balance while maintaining their default payment gateway.

To use this plugin, you need to have an Innocard loyalty account. Send an email to hello@innocardloyalty.ch for more information.

## Installation

If you are cloning from the repository, just unzip it in the plugins directory and enable it in the panel.
Afterwards, you need to access the Innocard Loyalty menu in admin and configure your Loyalty username and password and select the default virtual terminal for debit transactions.

## Support

In case of questions or problems send email to hello@innocardloyalty.ch

== Screenshots ==

1. User can type card informations
2. Balance has been showed and modify balance for discount usage
3. Discount applied

== Changelog ==

= 5.1 =
* Publishing plugin to Wordpress Store

= 5.2 =
* Fix API URL not defined after installation
* Modify allowed types of terminals

= 5.3 =
* Allow to modify all form labels
* Fix discount edit error
* Fix balance if cart total amount less than card balance. The maximum discount applied will be the cart total.

= 5.4 = 
* Fix size of "balance to be used" input

= 5.5 = 
* Fix taxes applied to discount on checkout

= 5.6 = 
* Fix some rounding issues
* Add some labels fields for translation from admin

= 5.7 = 
* Fix more specific rounding issues on tax decrement of discount

= 5.9 = 
* Fix rounding issues when trying to apply 100% discount to cart

= 6.0 =
* add_fee function was removed to prevent rounding issues. Instead we are adding cart discount after total calculation.
* Added a debug function to store important data about Innocard API integration, if enabled

= 6.1 = 
* Fixed discount field size

= 6.2 = 
* Added discount information to order emails
* Added discount information in thank you screen
* Added Innocard discount value and receipt in admin order details screen
* Discount will be removed if user check out the discount checkbox
* Discount amount will be updated if user reduce cart items

== Upgrade Notice ==
