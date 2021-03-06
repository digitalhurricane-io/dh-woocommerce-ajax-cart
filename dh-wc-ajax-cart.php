<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/digitalhurricane-io/dh-woocommerce-ajax-cart
 * @since             1.0.0
 * @package           Dh_Wc_Ajax_Cart
 *
 * @wordpress-plugin
 * Plugin Name:       DH Woocommerce Ajax Cart
 * Plugin URI:        https://github.com/digitalhurricane-io/dh-woocommerce-ajax-cart
 * Description:       Adds ajax functionality for woocommerce cart
 * Version:           1.0.3
 * Author:            Digital Hurricane
 * Author URI:        https://github.com/digitalhurricane-io/dh-woocommerce-ajax-cart
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dh-wc-ajax-cart
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DH_WC_AJAX_CARTVERSION', '1.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dh-wc-ajax-cart-activator.php
 */
function activate_dh_wc_ajax_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dh-wc-ajax-cart-activator.php';
	Dh_Wc_Ajax_Cart_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dh-wc-ajax-cart-deactivator.php
 */
function deactivate_dh_wc_ajax_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dh-wc-ajax-cart-deactivator.php';
	Dh_Wc_Ajax_Cart_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dh_wc_ajax_cart' );
register_deactivation_hook( __FILE__, 'deactivate_dh_wc_ajax_cart' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dh-wc-ajax-cart.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dh_wc_ajax_cart() {
	$plugin = new Dh_Wc_Ajax_Cart();
	$plugin->run();
}

add_action('woocommerce_loaded', 'run_dh_wc_ajax_cart');


// UPDATE CHECK
// check gitlab tags vs version at top of this file
require_once 'plugin-update-checker/plugin-update-checker.php';
$doLoginUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/digitalhurricane-io/dh-woocommerce-ajax-cart',
	__FILE__,
	'dh-woocommerce-ajax-cart'
);
