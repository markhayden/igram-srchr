<?php
/**
 * igram srchr
 *
 * Simple plugin for pulling in instagram queries unique to each post.
 *
 * @package   igram_srchr
 * @author    Mark Hayden <hi@markhayden.me>
 * @license   GPL-2.0+
 * @link      https://github.com/markhayden/igram-srchr
 * @copyright 2014 Mark Hayden
 *
 * @wordpress-plugin
 * Plugin Name:       igram srchr
 * Plugin URI:        https://github.com/markhayden/igram-srchr
 * Description:       Simple plugin for pulling in instagram queries unique to each post.
 * Version:           0.0.02
 * Author:            Mark Hayden
 * Author URI:        https://github.com/markhayden/
 * Text Domain:       igram_srchr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/markhayden/igram-srchr
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-igram_srchr.php' );

global $wpdb, $table_prefix;

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'igram_srchr', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'igram_srchr', 'deactivate' ) );
add_action( 'plugins_loaded', array( 'igram_srchr', 'get_instance' ) );

// Load the class files to manage custom instagram handle field on posts.
include('includes/igram-srch-formattr.php');

function igramSiteURL(){
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'].'/';
    return $protocol.$domainName;
}

function igramFormat_shortcode( $atts, $content="" ) {
	$igram_query_raw = get_post_meta( get_the_ID(), 'igram_search_query');
	$igram_site_url = igramSiteURL();

	// determine if the tweets need to be pulled in
	$curl = curl_init();
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => $igram_site_url . 'wp-content/plugins/igram-srchr/public/includes/igram-search-endpoint.php?q=' . $igram_query_raw[0],
	));
	$resp = curl_exec($curl);
	curl_close($curl);

	// initiate class object
	$igram_class = new igramSrchFormattr();

	// return formatted content
	return $igram_class->igram_srch_func( $wpdb, $table_prefix, $atts, $content );
}

add_shortcode('igram_srch', 'igramFormat_shortcode');

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-igram_srchr-admin.php' );
	add_action( 'plugins_loaded', array( 'igram_srchr_Admin', 'get_instance' ) );

}
