<?php
/**
 * Menu Visibilty
 *
 * @package           Menu Visibilty
 * @author            sonvir249
 * @copyright         2023 sonvir249
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Menu Visbility
 * Plugin URI:        https://github.com/sonvir249/menu-visibility
 * Description:       Control menu visibilty.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            sonvir249
 * Author URI:        https://github.com/sonvir249
 * Text Domain:       plugin-slug
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://github.com/sonvir249/menu-visibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once dirname( __FILE__ ) . '/classes/GlobalSettings.php';
require_once dirname( __FILE__ ) . '/classes/MenuVisibility.php';
