<?php

/**
 * Bulk Noindex by URL
 *
 *
 * @link              https://iamrizwan.me/
 * @since             1.0.0
 * @package           Bulk_Noindex_By_Url
 *
 * @wordpress-plugin
 * Plugin Name:       Bulk Noindex by URL
 * Plugin URI:        https://iamrizwan.me/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Rizwan
 * Author URI:        https://iamrizwan.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bulk-noindex-by-url
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'BULK_NIBU_VERSION', '1.0.0' );


/**
 * Plugin Directory Path
 */
define( 'BULK_NIBU_PATH', plugin_dir_path( __FILE__ ) );



/**
 * Includes
 */
require_once BULK_NIBU_PATH . 'includes/admin.php';



/**
 * Add noindex if the current page is listed in settings
 */
add_action('wp_head', function(){

	$time = microtime(TRUE);

	$current_uri = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$noindex_uris = array_map('trim', 
						explode( 
							PHP_EOL, 
							get_option( 'bulk_nibu_options' )['bulk_nibu_field_urls_list']
						)
					);
	


	// If found in settings, noindex this page!
	if( in_array( $current_uri, $noindex_uris ) ){

		echo "\n\n<!-- START: Bulk Noindex By URL -->\n\n";

		echo '<meta name="robots" content="noindex">';

		echo "\n\n<!-- Time Took: ", microtime(TRUE) - $time . "-->\n";
		echo "<!-- END: Bulk Noindex By URL -->\n\n\n";

	}
	
});