<?php
/**
 * Plugin Name:  Library Book Search
 * Plugin URI:   https://github.com/malavvasita/Library-Book-Search.git
 * Description:  Library book search plugin to facilitate user with searching 
                   available books in library section.
 * Version:      1.0.0
 * Author:       Malav Vasita
 * Author URI:   https://profiles.wordpress.org/malavvasita
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  'library-book-search'
 * Domain Path: /languages
 *
 * @package libraryBookSeach
 **/

if (! class_exists('LibraryBookSearch') ) {

    /**
     * Main class of plugin where all the actions begins.
     */
    class LibraryBookSearch
    {

        /**
         * Construct the basic things needed to run plugin.
         */
        public function __construct()
        {
            if (! defined('LBS_DIR_PATH') ) {
                define('LBS_DIR_PATH', plugin_dir_path(__FILE__));
            }

            if (! defined('LBS_DIR_URL') ) {
                define('LBS_DIR_URL', plugin_dir_url(__FILE__));
            }
        }

        /**
         * Enqueue all styles and scripts in admin side.
         */
        public function lbs_admin_enqueue_styles()
        {
            wp_enqueue_style('lbs_admin_style', LBS_DIR_URL . 'assets/css/admin-style/lbs-admin-style.css');
        }

        /**
         * Enqueue jquery-form to enable ajax action in searching.
         */
        public function enqueue_jquery_form()
        {
            wp_enqueue_script('jquery-form');
        }

        /**
         * Enqueue all styles and scripts in user side.
         */
        public function lbs_enqueue_styles_scripts()
        {

            global $wpdb;


            $min_max_book_price_query = "SELECT MIN( `meta_value`+0 ) as min, MAX( `meta_value`+0 ) as max FROM `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = 'lbs-book-price'";

            $min_max_book_price = $wpdb->get_results($min_max_book_price_query);

            $localize_data = [
            'admin_ajax_path'  => admin_url('admin-ajax.php'),
            'search_data_path' => LBS_DIR_URL . 'includes/lbs-search-data.php',
            'books_min_price'  => intval($min_max_book_price[0]->min),
            'books_max_price'  => intval($min_max_book_price[0]->max),
            ];

            wp_enqueue_style('lbs-style', LBS_DIR_URL . 'assets/css/lbs-style/lbs-style.css');
            wp_enqueue_style('lbs-jquery-style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
            wp_enqueue_style('lbs-datatables-style', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css');
            
            wp_enqueue_script('jquery-ui-script', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js');
            wp_enqueue_script('jquery-datatables-script', 'https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js');
            wp_enqueue_script('lbs-script', LBS_DIR_URL . 'assets/js/lbs-script.js');

            wp_localize_script('lbs-script', 'localized_data', $localize_data);
        }

        /**
         * Handle activation of plugin.
         */
        public function library_book_search_activate_plugin()
        {
            flush_rewrite_rules();
        }

        /**
         * Handle deactivation of plugin.
         */
        public function library_book_search_deactivate_plugin()
        {
            flush_rewrite_rules();
        }

        /**
         * Handle uninstallation of plugin.
         */
        public function library_book_search_uninstall_plugin()
        {
            include_once LBS_DIR_PATH . 'includes/uninstall.php';
            LibrabryBooksSearchUninstall::LbsUninstallPlugin();
        }

        /**
         * Create Custom Post Type of Library Book to store books details.
         */
        public function libraryBookSearchCustomPostType()
        {
            include_once LBS_DIR_PATH . 'includes/lbs-custom-post-type.php';
            $lbs_cpt = new LbsCustomPostType();
            $lbs_cpt->lbs_custom_post_type();
        }

        /**
         * Create custom metabox to store Book price and rating of book in CPT.
         *
         * @return NULL Adding metaboxes in WP ADMIN LBS
         */
        public function libraryBookSearchCustomMetaBox()
        {
            include_once LBS_DIR_PATH . 'includes/lbs-custom-meta-box.php';
            $lbs_meta_box = new LbsCustomMetaBox();
            add_action(
                'add_meta_boxes', 
                array( $lbs_meta_box, 'lbs_custom_meta_box' )
            );
            add_action(
                'save_post', 
                array( $lbs_meta_box, 'lbs_meta_save' )
            );
        }

        /**
         * Shortcode operation for Library Book Search.
         *
         * @return NULL Adding Shortcode
         */
        public function librarySookSearchShortcode()
        {
            include_once LBS_DIR_PATH . 'includes/lbs-custom-shortcode.php';
            $lbs_custom_shortcode = new LbsCustomShortcode();
            add_shortcode(
                'booksearch', 
                array( $lbs_custom_shortcode, 'lbs_custom_shortcode' )
            );
        }

        /**
         * Description: Use to facilitate %LIKE% when searching book with name.
         *
         * @param $where    $where needs to be modified.
         * @param $wp_query Reference of WP_Query instance to get search_term.
         *
         * @return String $where
         **/
        public function titleFilter( $where, &$wp_query )
        {
            global $wpdb;
            if ($search_term = $wp_query->get('s') ) {
                $where .= ' AND ' 
                        . $wpdb->posts 
                        . '.post_title LIKE \'%' 
                        . $wpdb->esc_like($search_term) 
                        . '%\'';
            }
            return $where;
        }

        /**
         * Ajaxified Search For Books In Shortcode.
         *
         * @return JSON $books_table
         */
        public function lbsSearch()
        {

            global $wpdb;

            $search_data_input = filter_input_array(INPUT_POST)['data'];

            $search_book_name         = $search_data_input['book_name'] ?? '';
            $search_book_author     = $search_data_input['book_author'] ?? '';
            $search_book_publisher     = $search_data_input['book_publisher'] ?? '';
            $search_book_rating     = $search_data_input['book_rating'] ?? '';
            $search_book_price_from = $search_data_input['book_price_from'] ?? '';
            $search_book_price_to     = $search_data_input['book_price_to'] ?? '';

            $args = array(
            'posts_per_page'     => -1,
            'post_type'         => 'library-search-book',
            'post_status'         => 'publish',
            's'                    => $search_book_name,
            'order_by'            => 'title',
            'order'                => 'ASC',
            );

            $price_from_meta_query = array(
            'key'            => 'lbs-book-price',
            'value'            => $search_book_price_from,
            'compare'        => '>=',
            'type'            => 'NUMERIC'
            );

            $price_to_meta_query = array(
            'key'            => 'lbs-book-price',
            'value'            => $search_book_price_to,
            'compare'        => '<=',
            'type'            => 'NUMERIC'
            );
            
            $rating_meta_query = array(
            'key'            => 'lbs-stars',
            'value'            => $search_book_rating,
            'compare'        => '=',
            );
            
            $author_meta_query = array(
            'key'            => 'lbs-book-author',
            'value'            => $search_book_author,
            'compare'        => '=',
            );
            
            $publisher_meta_query = array(
            'key'            => 'lbs-book-publisher',
            'value'            => $search_book_publisher,
            'compare'        => '=',
            );

            if (isset($search_book_author) && ! empty($search_book_author) ) {
                $args['meta_query']['relation'] = "AND";
                array_push($args['meta_query'], $author_meta_query);
            }

            if (isset($search_book_publisher) && ! empty($search_book_publisher) ) {
                $args['meta_query']['relation'] = "AND";
                array_push($args['meta_query'], $publisher_meta_query);
            }

            if (isset($search_book_rating) && ! empty($search_book_rating) ) {
                $args['meta_query']['relation'] = "AND";
                array_push($args['meta_query'], $rating_meta_query);
            }

            if (isset($search_book_price_from)  
                && ! empty($search_book_price_from) 
            ) {
                $args['meta_query']['relation'] = "AND";
                array_push($args['meta_query'], $price_from_meta_query);
            }

            if (isset($search_book_price_to) && ! empty($search_book_price_to) ) {
                $args['meta_query']['relation'] = "AND";
                array_push($args['meta_query'], $price_to_meta_query);
            }


            add_filter('posts_where', array( $this, 'titleFilter' ), 10, 2);
            $get_books = new WP_Query($args); 
            remove_filter('posts_where', array( $this, 'titleFilter' ), 10);

            // var_dump( $get_books );

            $i=1;
            $books_table = "";
            if (! $get_books ) {
                $books_table = "<tr><td>Sorry! No Books Found</td></tr>";
            }

            foreach ( $get_books->posts as $books ) {

                $book_meta_data = get_post_meta($books->ID);

                $books_table .= "<tr>
					<td>" 
                . $i .
                "</td>
					<td>
						<a href='" . get_permalink($books->ID) . "'>"
                . $books->post_title .
                "</a>
					</td>
					<td>" 
                . $book_meta_data['lbs-book-price'][0] . 
                "</td>
					<td>" 
                . $book_meta_data['lbs-book-author'][0] .
                "</td>
					<td>" 
                . $book_meta_data['lbs-book-publisher'][0] . 
                "</td>
					<td>" 
                . $book_meta_data['lbs-stars'][0] . 
                "</td>
				</tr>";
                $i++;
            }

            wp_send_json_success($books_table);
            
            die();
        }

    }

    // Instantiate the object of Library Book Search.
    $library_book_search = new LibraryBookSearch();

    // Enqueuing jQuery form script of WordPress.
    add_action(
        'wp_enqueue_scripts',
        array( $library_book_search, 'enqueue_jquery_form' )
    );

    // Enqueuing admin styles.
    add_action(
        'admin_head', 
        array( $library_book_search, 'lbs_admin_enqueue_styles' )
    );

    // Enqueuing all necessary style and script in front end.
    add_action(
        'wp_head',
        array( $library_book_search, 'lbs_enqueue_styles_scripts' )
    );

    // Created custom post type of Library Book on init of plugin.
    add_action(
        'init',
        array( $library_book_search, 'libraryBookSearchCustomPostType' )
    );

    // Created action of shortcode for Library Book on init of plugin.
    add_action(
        'init', 
        array( $library_book_search, 'librarySookSearchShortcode' )
    );

    // Enabling ajaxified search for Library Book Search.
    add_action(
        'wp_ajax_lbs-search', 
        array( $library_book_search, 'lbsSearch' )
    );
    add_action(
        'wp_ajax_no_priv_lbs-search', 
        array( $library_book_search, 'lbsSearch' )
    );

    // Calling function of metabox.
    $library_book_search->libraryBookSearchCustomMetaBox();

    // Defining activation hook of plugin.
    register_activation_hook(
        __FILE__,
        array( $library_book_search, 'library_book_search_activate_plugin' )
    );

    // Defining deactivation hook of plugin.
    register_deactivation_hook(
        __FILE__,
        array( $library_book_search, 'library_book_search_deactivate_plugin' )
    );

    // Defining uninstallation hook of plugin.
    register_uninstall_hook(
        __FILE__,
        array( 'library_book_search_uninstall_plugin' )
    );

}
