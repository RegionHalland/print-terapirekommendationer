<?php
/**
 * Plugin Name: Print Terapirekommendationer
 * Plugin URI: https://github.com/regionhalland/print-terapirekommendationer
 * Description: Allows editors to print chapters of Terapirekommendationer
 * Version: 1.0.0
 * Author: David Öhlin
 * Author URI: https://github.com/regionhalland
 * License: MIT License
 */

define('PRINT_TR_PLUGIN_NAME', 'Tr Print');
define('PRINT_TR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PRINT_TR_PLUGIN_URL', plugins_url('', __FILE__));
define('PRINT_TR_PLUGIN_CACHE_DIR', trailingslashit(wp_upload_dir()['basedir']) . 'cache');

if (file_exists(PRINT_TR_PLUGIN_PATH . 'vendor/autoload.php')) {
	require_once PRINT_TR_PLUGIN_PATH . 'vendor/autoload.php';
}

if (file_exists(dirname(ABSPATH) . '/vendor/autoload.php')) {
	require_once dirname(ABSPATH) . '/vendor/autoload.php';
}

require_once PRINT_TR_PLUGIN_PATH . 'src/php/Vendor/Psr4ClassLoader.php';

$loader = new TrPrint\Vendor\Psr4ClassLoader();
$loader->addPrefix('TrPrint', PRINT_TR_PLUGIN_PATH);
$loader->addPrefix('TrPrint', PRINT_TR_PLUGIN_PATH . 'src/php/');
$loader->register();

// Initialize plugin
add_action('plugins_loaded', function () {
	new TrPrint\App();
}, 20);
