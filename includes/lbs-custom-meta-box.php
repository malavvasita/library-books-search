<?php
/**
 * This file is for creating metabox in CPT of Library Search Book.
 *
 * @package libraryBookSeach
 */

/**
 * Class for having meta box in CPT.
 */
class LbsCustomMetaBox {

	/**
	 * Function for adding meta box for Library Seach Book CPT.
	 */
	public function lbs_custom_meta_box() {
		// Adding metabox.
		add_meta_box(
			'lbs_meta_box', __( 'Additional book information', 'library-book-search' ), array( $this, 'lbs_book_meta' ), 'library-search-book', 'normal', 'high'
		);

	}

	/**
	 * Metabox callback function
	 *
	 * @param array $post this will applied for individual post.
	 */
	public function lbs_book_meta( $post ) {
		// Verifying nonce.
		wp_nonce_field( basename( __FILE__ ), 'lbs_book_nonce' );
		$lbs_stored_meta = get_post_meta( $post->ID );

		?>

		<div>
			<div class="meta-row">
				<div class="meta-th">
					<label for="lbs-book-price" class="lbs-book-row-title"><?php echo esc_html__( 'Book Price', 'lbs-book' ); ?></label>
				</div>
				<div class="meta-td">
					<input type="number" required="true" style="width: 100%;" name="lbs-book-price" id="lbs-book-price" value="<?php echo ! empty( $lbs_stored_meta['lbs-book-price'] ) ? esc_attr( $lbs_stored_meta['lbs-book-price'][0] ) : ''; ?>">
				</div>
				<br/>
				<div class="meta-th">
					<label for="lbs-book-author" class="lbs-book-row-title"><?php echo esc_html__( 'Book Author', 'lbs-book' ); ?></label>
				</div>
				<div class="meta-td">
					<input type="text" required="true" style="width: 100%;" name="lbs-book-author" id="lbs-book-author" value="<?php echo ! empty( $lbs_stored_meta['lbs-book-author'] ) ? esc_attr( $lbs_stored_meta['lbs-book-author'][0] ) : ''; ?>">
				</div>
				<br/>
				<div class="meta-th">
					<label for="lbs-book-publisher" class="lbs-book-row-title"><?php echo esc_html__( 'Book Publisher', 'lbs-book' ); ?></label>
				</div>
				<div class="meta-td">
					<input type="text" required="true" style="width: 100%;" name="lbs-book-publisher" id="lbs-book-publisher" value="<?php echo ! empty( $lbs_stored_meta['lbs-book-publisher'] ) ? esc_attr( $lbs_stored_meta['lbs-book-publisher'][0] ) : ''; ?>">
				</div>
				<br/>
				<div class="meta-th">
					<label for="lbs-book-rating" class="lbs-book-row-title"><?php echo esc_html__( 'Book Rating', 'lbs-book' ); ?></label>
				</div>
				<div class="meta-td rating" >
					<label>
						<input type="radio" name="lbs-stars" value="1" />
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '1' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
					</label>
					<label>
						<input type="radio" name="lbs-stars" value="2" />
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '2' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '2' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
					</label>
					<label>
						<input type="radio" name="lbs-stars" value="3" />
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '3' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '3' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '3' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
					</label>
					<label>
						<input type="radio" name="lbs-stars" value="4" />
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '4' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '4' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '4' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '4' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
					</label>
					<label>
						<input type="radio" name="lbs-stars" value="5" />
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '5' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '5' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '5' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '5' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
						<span class="icon" <?php echo ! empty( $lbs_stored_meta['lbs-stars'] ) && ( '5' === $lbs_stored_meta['lbs-stars'][0] ) ? "style='color: #09f;'" : ''; ?> >★</span>
					</label>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * This lbs_meta_save function will save meta data to database.
	 *
	 * @param int $post_id this will be id of singular post.
	 * @return array
	 */
	public function lbs_meta_save( $post_id ) {
		$lbs_book_nonce = filter_input( INPUT_POST, 'lbs_book_nonce' );
		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( ! empty( $lbs_book_nonce ) && wp_verify_nonce( $lbs_book_nonce, basename( __FILE__ ) ) ) ? 'true' : 'false';

		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		if ( ! empty( filter_input( INPUT_POST, 'lbs-stars' ) ) ) {
			update_post_meta( $post_id, 'lbs-stars', sanitize_text_field( filter_input( INPUT_POST, 'lbs-stars' ) ) );
		}

		if ( ! empty( filter_input( INPUT_POST, 'lbs-book-price' ) ) && is_numeric( filter_input( INPUT_POST, 'lbs-book-price' ) ) ) {
			update_post_meta( $post_id, 'lbs-book-price', absint( filter_input( INPUT_POST, 'lbs-book-price' ) ) );
		}

		if ( ! empty( filter_input( INPUT_POST, 'lbs-book-author' ) ) ) {
			update_post_meta( $post_id, 'lbs-book-author', filter_input( INPUT_POST, 'lbs-book-author' ) );
		}

		if ( ! empty( filter_input( INPUT_POST, 'lbs-book-publisher' ) ) ) {
			update_post_meta( $post_id, 'lbs-book-publisher', filter_input( INPUT_POST, 'lbs-book-publisher' ) );
		}
	}

}
