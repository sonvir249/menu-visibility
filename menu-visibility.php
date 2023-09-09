<?php
/*
Plugin Name: Menu Visibility
Version: 1.0
Author: sonvir249
Author URI: https://github.com/sonvir249
Description: Menu visibility plugin provides option to control the menu visibility.
License:GPL2
License URI:https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Make sure we don't expose any info if called directly.

if (!function_exists('add_action') ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

define('MV_VERSION', '5.5.6');

define('MV_REQUIRED_WP_VERSION', '5.7');

define('MV_PLUGIN', __FILE__);

define('MV_PLUGIN_BASENAME', plugin_basename(MV_PLUGIN));

define('MV_PLUGIN_DIR', untrailingslashit(dirname(MV_PLUGIN)));

require_once MV_PLUGIN_DIR . '/classes/class-mvglobalsettings.php';
require_once MV_PLUGIN_DIR . '/classes/class-mvmain.php';