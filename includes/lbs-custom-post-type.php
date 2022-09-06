<?php
/**
 * This file is for creating CPT of Library Search Book.
 *
 * @package libraryBookSeach
 */

/**
 * Class for CPT of Library Book.
 */
class LbsCustomPostType {

	/**
	 * Function for adding CPT of Library Book.
	 */
	public function lbs_custom_post_type() {

		// Registering custom post type.
		register_post_type(
			'library-search-book', [
				'labels'    => [
					'name'          => apply_filters( 'lib_book_cpt_name', __( 'Library Book', 'library-book-search' ) ),
					'singular_name' => apply_filters( 'lib_book_cpt_singular_name', __( 'All Books', 'library-book-search' ) ),
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
