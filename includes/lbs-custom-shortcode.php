<?php
/**
 * This file is for Shortcode functionality of Library Search Book
 *
 * @package libraryBookSeach
 */

/**
 * Class for shortcode functionality.
 */
class LbsCustomShortcode {
	/**
	 * Function for shortcode execution.
	 *
	 * @param array  $atts    Attributes for shortcode.
	 * @param string $content Content caught for shortcode.
	 */
	public function lbs_custom_shortcode( $atts, $content = null ) {
		global $wpdb;
		$options = [
			'post_type' => 'library-search-book',
		];

		?>
			<div class="lbs-search-form" id="lbs-search-form">
				<h4 class="lbs-form-title"><?php esc_html_e( 'Book Search', 'library-book-search' ); ?></h4>
				<form method="post" id="lbs-search-form" action="" >
					<div>
						<div class="lbs-fieldset">
							<label for="lbs-book-name"><?php esc_html_e( 'Book Name: ', 'library-book-search' ); ?></label><br/>
							<input type="textbox" name="lbs-book-name" class="lbs-book-name" />
						</div>
						<div class="lbs-fieldset">
							<label for="lbs-book-author"><?php esc_html_e( 'Author: ', 'library-book-search' ); ?></label>
							<select name='lbs-book-author' class="lbs-book-author">
								<option value=""><?php esc_html_e( 'Choose Any One', 'library-book-search' ); ?></option>
								<?php
								$texonomy = 'lbs-author-taxonomy';
								$terms    = get_terms( $texonomy );
								foreach ( $terms as $term ) {
									echo "<option value='" . esc_html( $term->term_id ) . "' >" . esc_html( $term->name ) . '</option>';
								}
							?>
							</select>
						</div>
					</div>
					<div>
						<div class="lbs-fieldset">
							<label for="lbs-book-publisher"><?php esc_html_e( 'Publisher: ', 'library-book-search' ); ?></label>
							<select name='lbs-book-publishers' class="lbs-book-publisher">
								<option value=""><?php esc_html_e( 'Choose Any One', 'library-book-search' ); ?></option>
								<?php
								$texonomy = 'lbs-publisher-taxonomy';
								$terms    = get_terms( $texonomy );
								foreach ( $terms as $term ) {
									echo "<option value='" . esc_html( $term->term_id ) . "' >" . esc_html( $term->name ) . '</option>';
								}
							?>
							</select>
						</div>
						<div class="lbs-fieldset">
							<label for="lbs-book-rating"><?php esc_html_e( 'Rating: ', 'library-book-search' ); ?></label>
							<select name='lbs-book-rating' class="lbs-book-rating">
								<option value=""><?php esc_html_e( 'Choose Any One', 'library-book-search' ); ?></option>
								<option value="1"><?php esc_html_e( '1', 'library-book-search' ); ?></option>
								<option value="2"><?php esc_html_e( '2', 'library-book-search' ); ?></option>
								<option value="3"><?php esc_html_e( '3', 'library-book-search' ); ?></option>
								<option value="4"><?php esc_html_e( '4', 'library-book-search' ); ?></option>
								<option value="5"><?php esc_html_e( '5', 'library-book-search' ); ?></option>
								<option value="na"><?php esc_html_e( 'N/A', 'library-book-search' ); ?></option>
							</select>
						</div>
					</div>
					<div class="lbs-price-fieldset">

							<label for="amount"><?php esc_html_e( 'Price Range: ', 'library-book-search' ); ?></label>
							<input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">

						<div id="slider-range"></div>
					</div>
					<input type="hidden" name="lbs-search-action" class="lbs-search-action" value="lbs-search">
					<div class="lbs-submit">
						<input type="submit" class="lbs-form-submit" name="lbs-book-submit" value="<?php esc_html_e( 'SEARCH', 'library-book-search' ); ?>" />
					</div>
				</form>
			</div>
			<div class="lbs-book-data" id="lbs-book-data">

				<table class="lbs-book-data-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'No', 'library-book-search' ); ?></th>
							<th><?php esc_html_e( 'Book Name', 'library-book-search' ); ?></th>
							<th><?php esc_html_e( 'Price', 'library-book-search' ); ?></th>
							<th><?php esc_html_e( 'Author', 'library-book-search' ); ?></th>
							<th><?php esc_html_e( 'Publisher', 'library-book-search' ); ?></th>
							<th><?php esc_html_e( 'Rating', 'library-book-search' ); ?></th>
						</tr>
					</thead>
					<tbody id="lbs-books-search-data">
						<?php
						$i = 1;

						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

						// Query to fetch post of Library Books.
						$posts_query = new WP_Query(
							array(
								'posts_per_page' => -1,
								'post_type'      => 'library-search-book',
								'post_status'    => 'publish',
								'paged'          => $paged,
							)
						);

						// Fetch posts related to library books only.
						$posts = $posts_query->posts;

						foreach ( $posts as $post ) {

							$id         = $post->ID;
							$book_title = $post->post_title;

							// Get authors from term taxonomy.
							$authors = get_the_terms( $id, 'lbs-author-taxonomy' );

							// Get publishers from term taxonomy.
							$publishers = get_the_terms( $id, 'lbs-publisher-taxonomy' );

							$author_list    = '';
							$publisher_list = '';

							// Prepare author list for particular book.
							if ( ! empty( $authors ) ) {
								foreach ( $authors as $author ) {
									$author_list .= $author->name . ', ';
								}
							} else {
								$author_list = __( 'N/A', 'library-book-search' );
							}

							// Prepare publisher list for particular book.
							if ( ! empty( $publishers ) ) {
								foreach ( $publishers as $publisher ) {
									$publisher_list .= $publisher->name . ', ';
								}
							} else {
								$publisher_list = __( 'N/A', 'library-book-search' );
							}

							// Fetch meta values stored with post like price and rating of book.
							$book_meta = get_post_meta( $id );
							$price     = ( $book_meta['lbs-book-price'] )[0];
							$rating    = ! empty( $book_meta['lbs-stars'] ) ? ( $book_meta['lbs-stars'] )[0] : __( 'N/A', 'library-book-search' );

								echo '<tr>' .
										'<td>' .
											esc_html( $i ) .
										'</td>' .
										'<td>' .
											"<a href='" . esc_url( get_permalink( $id ) ) . "'>" . esc_html( $book_title ) . '</a>' .
										'</td>' .
										'<td>' .
											esc_html( $price ) .
										'</td>' .
										'<td>' .
											esc_html( rtrim( $author_list, ', ' ) ) .
										'</td>' .
										'<td>' .
											esc_html( rtrim( $publisher_list, ', ' ) ) .
										'</td>' .
										'<td>' .
											esc_html( $rating ) .
										'</td>' .
									'</tr>';
									$i++;
						}
						?>
						</tbody>
				</table>
			</div>
			<div id="feedback"></div>
		<?php
	}
}
