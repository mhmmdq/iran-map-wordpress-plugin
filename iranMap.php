<?php
/*
Plugin Name: نقشه ایران
Description: نمایش نقشه ایران برای برند های دکان
Author: Mohammad Qasemi
Version: 2.1.0
Author URI: https://github.com/mhmmdq
*/

defined('ABSPATH') or exit('No script kiddies please!');

define("IRMAP_PLUGIN_URL", plugin_dir_url(__FILE__));
define("IRMAP_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("IRMAP_AUTOLOADER" , IRMAP_PLUGIN_DIR . 'vendor/autoload.php');
define("IRMAP_PLUGIN_VERSION", '1.0.0');
// !file_exists(IRMAP_AUTOLOADER) and wp_die("<h1>Iran Map Autoloader not found!</h1> <p> Contact the technical department </p>");
// require_once IRMAP_AUTOLOADER;

if( !function_exists('is_plugin_active') ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


require_once IRMAP_PLUGIN_DIR . 'includes/plugin.php';
new IranMap\IranMapPlugin;