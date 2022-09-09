<?php
/**
 * This file is for creating taxonomies for Author and Publisher of Library Search Book
 *
 * @package libraryBookSeach
 */

/**
 * Class for Taxonomies of Author and Publisher.
 */
class LbsCustomTaxonomy
{
    /**
     * Hierarchical taxonomy for author data.
     */
    public function lbs_author_taxonomy()
    {
        $labels = [
        'name'              => _x('Book Author', 'taxonomy general name', 'library-book-search'),
        'singular_name'     => _x('Book Author', 'taxonomy singular name', 'library-book-search'),
        'search_items'      => __('Search Item', 'library-book-search'),
        'all_items'         => __('All Items', 'library-book-search'),
        'parent_item'       => __('Parent Item', 'library-book-search'),
        'parent_item_colon' => __('Parent Item:', 'library-book-search'),
        'edit_item'         => __('Edit Item', 'library-book-search'),
        'update_item'       => __('Update Item', 'library-book-search'),
        'add_new_item'      => __('Add New Item', 'library-book-search'),
        'new_item_name'     => __('New Book Name', 'library-book-search'),
        'menu_name'         => __('Book Category', 'library-book-search'),
        ];
        $args   = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'library-book-search' ],
        ];

        register_taxonomy('lbs-author-taxonomy', [ 'library-search-book' ], $args);
    }

    /**
     * Non-Hierarchical taxonomy for publisher data.
     */
    public function lbs_publisher_taxonomy()
    {
        $labels = [
        'name'              => _x('Book Publisher', 'taxonomy general name', 'library-book-search'),
        'singular_name'     => _x('Book Publisher', 'taxonomy singular name', 'library-book-search'),
        'search_items'      => __('Search Item', 'library-book-search'),
        'all_items'         => __('All Items', 'library-book-search'),
        'parent_item'       => __('Parent Item', 'library-book-search'),
        'parent_item_colon' => __('Parent Item:', 'library-book-search'),
        'edit_item'         => __('Edit Item', 'library-book-search'),
        'update_item'       => __('Update Item', 'library-book-search'),
        'add_new_item'      => __('Add New Item', 'library-book-search'),
        'new_item_name'     => __('New Book Name', 'library-book-search'),
        'menu_name'         => __('Book tag', 'library-book-search'),
        ];
        $args   = [
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'book-tag' ],
        ];

        register_taxonomy('lbs-publisher-taxonomy', [ 'library-search-book' ], $args);
    }
}
