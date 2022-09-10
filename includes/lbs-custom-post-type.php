<?php
/**
 * This file is for creating CPT of Library Search Book.
 * 
 * Registering Custom Post Type to facilitate admins for bifurcating posts.
 *
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
 * Class for CPT of Library Book.
 * 
 * @category Library_Book_Search
 * @package  LibraryBookSeach
 * @author   Malav V. <malavvasita.mv@gmail.com>
 * @license  https://www.gnu.org/licences/gpl-3.0.txt GNU/GPLv3
 * @version  Release: @1.0.0@
 * @link     https://github.com/malavvasita/library-book-search
 */
class LbsCustomPostType
{

    /**
     * Function for adding CPT of Library Book.
     * 
     * @return NULL Registering Custom Post Type
     */
    public function lbsCustomPostType()
    {

        // Registering custom post type.
        register_post_type(
            'library-search-book', [
            'labels'    => [
            'name'          => apply_filters(
                'lib_book_cpt_name', 
                __('Library Book', 'library-book-search')
            ),
            'singular_name' => apply_filters(
                'lib_book_cpt_singular_name',
                __('All Books', 'library-book-search')
            ),
            ],
            'public'    => true,
            'menu_icon' => 'dashicons-book-alt',
            'supports'  => [
                    'title',
                    'editor',
                    'thumbnail',
            ],
            ]
        );
    }
}
