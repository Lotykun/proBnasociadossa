<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           bn_contenttype_project
 *
 * @wordpress-plugin
 * Plugin Name:       BN Content Type PROJECT
 * Plugin URI:        http://example.com/bn-contenttype-project-uri/
 * Description:       Plugin que genera el CPT PROJECT y todos los campos asociados a el.
 * Version:           1.0.0
 * Author:            jlotito
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bn-contenttype-project
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
define( 'bn_contenttype_project_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bn-contenttype-project-activator.php
 */
function activate_bn_contenttype_project() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bn-contenttype-project-activator.php';
	bn_contenttype_project_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bn-contenttype-project-deactivator.php
 */
function deactivate_bn_contenttype_project() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bn-contenttype-project-deactivator.php';
	bn_contenttype_project_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bn_contenttype_project' );
register_deactivation_hook( __FILE__, 'deactivate_bn_contenttype_project' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bn-contenttype-project.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bn_contenttype_project() {

	$plugin = new bn_contenttype_project();
	$plugin->run();

}
run_bn_contenttype_project();
