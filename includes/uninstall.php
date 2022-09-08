<?php
/**
 * This is uninstallation file for Library Book Search Plugin.
 *
 * @package libraryBookSeach
 */

/**
 * Class for uninstallation of plugin.
 */
class LibrabryBooksSearchUninstall {

	/**
	 * Handle uninstallation of plugin.
	 */
	public static function lbs_uninstall_plugin() {

		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			die;
		}

		global $wpdb;

		$wpdb->query( "DELETE from wp_posts WHERE post_type = 'library-search-book' " ); // db call ok; no-cache ok.
		$wpdb->query( 'DELETE from wp_postmeta WHERE post_id NOT IN( SELECT id FROM wp_posts ) ' ); // db call ok; no-cache ok.
		$wpdb->query( 'DELETE from wp_term_relationships WHERE object_id NOT IN( SELECT id FROM wp_posts ) ' ); // db call ok; no-cache ok.
	}

}
