<?php
/*
Plugin Name: WooCommerce DYMO Print
Plugin URI: https://wpfortune.com/shop/plugins/woocommerce-dymo-print/
Description: This plugin provides shipping labels for your DYMO label printer from the backend. - Free version
Version: 3.0.2
Author: PEP
Author URI: https://wpfortune.com/
WC requires at least: 3.7.0
WC tested up to: 4.9.0
*/
/*  Copyright 2012  WP Fortune  (email : info@wpfortune.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once(plugin_dir_path( __FILE__ ) . 'lib/class-wc-dymo.php');

/*
 * General plugin settings
 */
$settings_plugin_name      =    'WooCommerce DYMO Print';
$settings_plugin_version   =    '3.0.2';
$settings_plugin_id        =    'woocommerce-dymo'; // Needed to work with the WPFortune updater
$settings_plugin_slug      =    'wc_dymo';
$settings_plugin_file      =    plugin_basename( __FILE__ );
$settings_plugin_dir       =    plugin_dir_path( __FILE__ );
$settings_upgrade_url      =    'https://www.wpfortune.com';
$settings_renew_url        =    'https://www.wpfortune.com/my-account/';
$settings_docs_url         =    'https://wpfortune.com/documentation/plugins/woocommerce-dymo-print/';
$settings_support_url      =    'https://wordpress.org/support/plugin/woocommerce-dymo-print';

new WPF_DYMO($settings_plugin_name, $settings_plugin_version, $settings_plugin_id, $settings_plugin_slug, $settings_plugin_dir, $settings_plugin_file, $settings_upgrade_url, $settings_renew_url, $settings_docs_url, $settings_support_url);

?>