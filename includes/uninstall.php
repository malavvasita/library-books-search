<?php
/**
 * This is uninstallation file for Library Book Search Plugin.
 * php version 8.0.0
 *
 * @category Library_Book_Search
 * @package  LibraryBookSeach
 * @author   Malav V. <malavvasita.mv@gmail.com>
 * @license  https://www.gnu.org/licences/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.0@
 * @link     https://github.com/malavvasita/library-book-search
 */

/**
 * Class for uninstallation of plugin.
 *
 * @category Library_Book_Search
 * @package  LibraryBookSeach
 * @author   Malav V. <malavvasita.mv@gmail.com>
 * @license  https://www.gnu.org/licences/gpl-3.0.txt GNU/GPLv3
 * @version  Release: @1.0.0@
 * @link     https://github.com/malavvasita/library-book-search
 */
class LibrabryBooksSearchUninstall
{

    /**
     * Handle uninstallation of plugin.
     *
     * @return NULL
     */
    public static function lbsUninstallPlugin()
    {

        if (! defined('WP_UNINSTALL_PLUGIN') ) {
            die;
        }

        global $wpdb;

        $wpdb->query(
            "DELETE 
                from 
            wp_posts 
                WHERE 
            post_type = 'library-search-book' "
        ); // db call ok; no-cache ok.
        
        $wpdb->query(
            'DELETE 
                from 
            wp_postmeta 
                WHERE 
            post_id 
                NOT IN( 
                    SELECT id FROM wp_posts 
                )'
        ); // db call ok; no-cache ok.
        $wpdb->query(
            'DELETE 
                from 
            wp_term_relationships 
                WHERE 
            object_id NOT IN( 
                SELECT id FROM wp_posts 
            )'
        ); // db call ok; no-cache ok.
    }

}
