=== WooCommerce DYMO Print ===
Contributors: pepbc
Plugin URI: https://wordpress.org/plugins/woocommerce-dymo-print/
Author URI: https://pepbc.nl/
Tags: woocommerce, dymo
Requires at least: 5.6
Requires PHP: 7.0
Tested up to: 5.7
Stable tag: 3.0.2
WC requires at least: 3.8.0
WC tested up to: 5.1
License: GPLv2

This plugin adds the possibility to print shipping addresses on your DYMO label printer.
== Description ==
***Quickly print your DYMO labels from within your WooCommerce order overview.***

The _WooCommerce Dymo Print plugin_ adds a quick connection with your DYMO labelwriter within your WooCommerce shop. Now you are able to print shipping address labels in a second!

For more information, check out [our website](https://wpfortune.com/shop/plugins/woocommerce-dymo-print/).

This plugin is compatible with WordPress 5.5.x and WooCommerce 4.6.x

### Free version

This free version may be used as is. *It will only print on DYMO address labels (size 89x36mm - #99012).* 

If you want more options, other labels and support you can buy WooCommerce DYMO Print Pro for only &euro;29,00.

The plugin adds a new side panel on the order page to allow shop managers to print out DYMO labels on the fly. This is useful for a lot of shops and makes live a lot easier ;-) .

### Support for DYMO Connect (DCD)
> We currently recommend using _DYMO Connect_ on Windows systems. [download DYMO Connect (DCD) version 1.2 for Windows](https://www.dymo.com/en-GB/online-support/dymo-user-guides). 

> You might need to empty your browser history and use CTRL-F5 (Windows) to clear your browser cache.

### Support for DYMO Label Software (DLS)

> To use WooCommerce DYMO Print on a Mac you need to install _DYMO Label Software version 8.7.4_. [download DLS 8.7.4 for Mac](https://www.dymo.com/en-GB/online-support/dymo-user-guides). 

> You might need to empty your browser history and use CMD + R (or CMD + SHIFT + R) (Mac) to clear your browser cache.

### Features

* Print shipping labels on your DYMO LabelWriter in seconds.
* Print shipping labels from order detail page
* Print shipping labels from order overview page
* Add company name on your labels (optional)
* Add extra info with order number on your labels (optional)
* Localisation: English & Dutch

**WooCommerce DYMO Print is fully compatible with WooCommerce 4.6.x and WordPress 5.5.x.**

== Installation ==

1. Install WooCommerce DYMO Print either via the Wordpress.org plugin directory or by uploading the files to the '/wp-content/plugins/' directory.
2. Activate the plugin  through the 'Plugins' menu in WordPress.

** Mac users: Update DYMO Label Software to version 8.7.4 or above**

To use WooCommerce DYMO Print you also need to _update DYMO Label Software_ to version 8.7.4. or above

* [download DLS 8.7.4 for Mac here](https://www.dymo.com/en-GB/online-support/dymo-user-guides). 

** Windows users: Update DYMO Connect to version 1.2 or above**

To use WooCommerce DYMO Print you also need to _update DYMO Connect_ to version 1.2. or above

* [download DCD 1.2 for Windows here](https://www.dymo.com/en-GB/online-support/dymo-user-guides). 

== Upgrade Notice ==
= 3.0.0 = 
Added support for DYMO Connect (DCD). We advise to use DYMO Connect on Windows.

== Usage ==

Go to WooCommerce > DYMO print, configure and start printing. Simple!

== Screenshots ==

1. WooCommerce DYMO Print settings
2. WooCommerce DYMO Print adds a button to the order overview page
3. WooCommerce DYMO Print adds a button to the order detail page

== Changelog ==
***WooCommerce DYMO Print***
= 2021.01.18 - version 3.0.2 =
* Fix: Visual styling progress bar on overview page

= 2020.10.22 - version 3.0.1 =
* Change: Updated DYMO Connect Framework library to latest version
* Fix: loading icon not showing in settings

= 2020.02.26 - version 3.0.0 =
* Added: Support for DYMO Connect
* Added: Label preview on settings page
* Fix: Address formatting when using DYMO Connect
* Change: Several code optimization
* Removed: Some deprecated admin notices
* Removed: woocommerce-dymo-print.js (old DLS Framework)

= 2020.02.19 - version 2.0.3 =
* Fix: Several small changes for WC 3.9
* Added: French translations thanks to GRAFISYMA.

= 2018.11.28 - version 2.0.2 =
* Fix: Added second printer option if first printer is not connected

= 2018.10.13 - version 2.0.1 =
* Fix: Several small bugfixes which leads to errors on some hosting configurations

= 2018.10.11 - version 2.0.0 =
* Complete rewrite of the plugin
* Added: Better debug logs
* Added: Better error notifications
* Added: Inline printing with ajax (no pop-ups anymore!)
* Added: Output order number inside extra information field
* Fix: Support for DYMO Label Software version 8.7.2. and higher

= 2018.02.01 - version 1.3.1 =
* Fix: Added support for new WooCommerce 3.3 order overview
* Fix: Minor text changes.

= 2016.07.18 - version 1.3.0 =
* Fix: bug with wrong closed tag which disabled printing

= 2016.07.01 - version 1.2.9 =
* Fix: Incombatibility in some browsers during printing

= 2016.01.11 - version 1.2.8 =
* Fix: Several bugfixes in new DYMO Javascript Framework

= 2015.12.16 - version 1.2.7 =
* Tweak: New DYMO Javascript Framework
* Tweak: Dutch translations for latest version
* Added: Label print window closes automatically after 10 seconds
* Fix: Several layout issues on settings page

= 2015.11.30 - version 1.2.6 =
* Several bug fixes

= 2015.08.04 - version 1.2.4 =
* Several bug fixes

= 2015.03.05 - version 1.2.3 =
* Fix problem with special characters in some states

= 2015.01.14 - version 1.2.3 =
* Fix small translation bug 

= 2014.05.22 - version 1.2.2 =
* Fix error for WooCommerce 2.1.9 printing address on one line

= 2014.04.20 - version 1.2.1 =
* Fix for https requests

= 2014.01.12 - version 1.2 =
* Ready for WooCommerce 2.1 (Tested on Beta 3)
* Cleaned up several unused functions

= 2013.12.12 - version 1.1.7 =
* We've checked WooCommerce DYMO Print on Wordpress 3.8 and have done some minor layout modifications
* Removed: unused styles and scripts

= 2013.11.27 - version 1.1.6 =
* Tweak: Updated Dymo Javascript framework to version 1.2.5 which should fix some issues in IE11 and Windows 8.1
* Fixed: Bug when NextGen Gallery is used

= 2013.08.20 - version 1.1.5 =
* Fixed: Several small bugfixes

= 2013.07.31 - version 1.1.4 =
* Removed: check if shipping is active. Now it's possible to print billing labels instead.
* Added: support for billing address if order has no shipping address.
* Fixed: better support for label layout
* Fixed: label layout compatible with PRO version

= 2013.07.27 - version 1.1.3 =
* Added: uninstall.php to remove all presets.
* Fixed: several small bugfixes
* Removed: Update check for PRO.

= 2013.06.19 - version 1.1.2 =
* Fixed: small bug which causes errors on some PHP installations.

= 2013.06.15 - version 1.1.1 =
* Added support for quotes in addresses (thanks to Evert-Jan for reporting this)
* Added better error handling when label file contains wrong objects.
* Minor bugfixes

= 2013.05.07 - version 1.1.0 =
* Minor bugfixes

= 2013.05.03 - version 1.0.9 =
* Tweak: Check if shipping is active

= 2013.04.09 - version 1.0.8 =
* Fix: Domain check

= 2013.03.28 - version 1.0.7 =
* Fix: Update check

= 2013.03.26 - version 1.0.6 =
* Small bugfixes
* Fix: Update check

= 2013.03.19 - version 1.0.5 =
* Added DYMO Framework check on settingspage
* Stable release

= 2012.12.19 - version 1.0 =
* Stable release

= 2012.12.19 - version 0.5 =
* Minor bug-fixes

= 2012.12.18 - version 0.1 =
* First release

== Frequently Asked Questions ==

= Support for DYMO Connect on Windows = 
Please make sure you've updated DYMO Connect (DCD) to version 1.2. [Download DCD 1.2 for Windows here](https://www.dymo.com/en-GB/online-support/dymo-user-guides). 
All older versions will not work anymore!

= Support for DYMO Label Software on Mac = 
Please make sure you've updated DYMO Label Software (DLS) to version 8.7.4. [Download DLS 8.7.4 for Windows here](https://www.dymo.com/en-GB/online-support/dymo-user-guides). 
All older versions will not work anymore!

= Message "No DYMO Printers installed" but I am sure it is! =
If your DYMO printer is not printing you probably get a messages "No Dymo Printers installed". 
Please follow instructions on [our website] (https://wpfortune.com/documentation/plugins/woocommerce-dymo-print/#debugdymoframework).

= Is it possible to print a barcode or a QRcode on my labels? =
With our PRO version it is possible to design your own labels. Just select a barcode object and make sure it has the required OBJECT-name.

= What is the difference between the Free and Pro versions of this plugin? = 
You may use the free version as it is. When you buy WooCommerce DYMO Print PRO you get a lot more options: print unlimited labels for unlimited purposes, customize your own labels, bulk print labels, choose your label size, print your company logo on your labels, use a DYMO LabelWriter 450 Twin Turbo.
For a full list of features, please check out [our website](https://wpfortune.com/shop/plugins/woocommerce-dymo-print/).

= Where can I find more information about this plugin? =
You can find more information on [our website](https://wpfortune.com/shop/plugins/woocommerce-dymo-print/).

= Which DYMO LabelWriters can I use? = 
You can use a LabelWriter 400 or LabelWriter 450 (Turbo). If you want to use a DYMO LabelWriter 450 Twin Turbo and use both rolls, you could buy our PRO version.

= Which labels are supported? = 
The free version only supports basic DYMO address labels *(89x36mm - #99012)*. With the PRO version you could choose your own markup and labelsize. 

= Why is there a PRO version? = 
We want to give everyone the opportunity to use and try our plugins, but if you want to get more options and access to our support section you can buy our PRO version. WooCommerce DYMO Print Pro costs only **&euro;29,00**.