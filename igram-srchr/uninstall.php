<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   igram_srchr
 * @author    Mark Hayden <hi@markhayden.me>
 * @license   GPL-2.0+
 * @link      https://github.com/markhayden/igram-srchr
 * @copyright 2014 Mark Hayden
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {

	global $wpdb;

	$table_name = $wpdb->prefix . "igram_srchr";
	$options_table_name = $wpdb->prefix . "options";

	$sql = "DROP TABLE `".$table_name."`";
	dbDelta( $sql );

	exit;
}