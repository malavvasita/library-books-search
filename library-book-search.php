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
 * Library book search plugin can be helpful to search the available books
 * by it's author, publisher, name and ratings
 * 
 * php version 8.0.0
 *
 * @category Library_Book_Search
 * @package  LibraryBookSeach
 * @author   Malav V. <malavvasita.mv@gmail.com>
 * @license  https://www.gnu.org/licences/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.0@
 * @link     https://github.com/malavvasita/library-book-search
 * */

if (! class_exists('LibraryBookSearch') ) {

    /**
     * Main class of plugin where all the actions begins.
     * 
     * @category Library_Book_Search
     * @package  LibraryBookSeach
     * @author   Malav V. <malavvasita.mv@gmail.com>
     * @license  https://www.gnu.org/licences/gpl-3.0.txt GNU/GPLv3
     * @version  Release: @1.0.0@
     * @link     https://github.com/malavvasita/library-book-search
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
         * 
         * @return NULL Enqueuing Admin Style CSS
         */
        public function lbsAdminEnqueueStyles()
        {
            wp_enqueue_style(
                'lbs_admin_style', 
                LBS_DIR_URL . 'assets/css/admin-style/lbs-admin-style.css'
            );
        }

        /**
         * Enqueue jquery-form to enable ajax action in searching.
         * 
         * @return NULL Enquiuing JS Form library of WP
         */
        public function enqueueJqueryForm()
        {
            wp_enqueue_script('jquery-form');
        }

        /**
         * Enqueue all styles and scripts in user side.
         * 
         * @return NULL Enqueuing Plugin Styles and Scripts
         */
        public function lbsEnqueueStylesScripts()
        {

            global $wpdb;


            $min_max_book_price_query = "SELECT 
                    MIN( `meta_value`+0 ) as min, 
                    MAX( `meta_value`+0 ) as max 
                FROM 
                    `" . $wpdb->prefix . "postmeta` 
                WHERE
                `meta_key` = 'lbs-book-price'";

            $min_max_book_price = $wpdb->get_results($min_max_book_price_query);

            $localize_data = [
            'admin_ajax_path'  => admin_url('admin-ajax.php'),
            'search_data_path' => LBS_DIR_URL . 'includes/lbs-search-data.php',
            'books_min_price'  => intval($min_max_book_price[0]->min),
            'books_max_price'  => intval($min_max_book_price[0]->max),
            ];

            wp_enqueue_style(
                'lbs-style', 
                LBS_DIR_URL . 'assets/css/lbs-style/lbs-style.css'
            );
            wp_enqueue_style(
                'lbs-jquery-style', 
                LBS_DIR_URL . 'assets/css/lbs-style/jquery-ui.min.css'
            );
            wp_enqueue_style(
                'lbs-datatables-style', 
                LBS_DIR_URL . 'assets/css/lbs-style/jquery.dataTables.min.css'
            );
            
            wp_enqueue_script(
                'jquery-ui-script', 
                LBS_DIR_URL . 'assets/js/jquery-ui.js'
            );
            wp_enqueue_script(
                'jquery-datatables-script', 
                LBS_DIR_URL . 'assets/js/jquery.dataTables.min.js'
            );
            wp_enqueue_script(
                'lbs-script', 
                LBS_DIR_URL . 'assets/js/lbs-script.js'
            );

            wp_localize_script(
                'lbs-script', 
                'localized_data', $localize_data
            );
        }

        /**
         * Handle activation of plugin.
         * 
         * @return NULL Flushing rewrite rules on activation
         */
        public function libraryBookSearchActivatePlugin()
        {
            flush_rewrite_rules();
        }

        /**
         * Handle deactivation of plugin.
         * 
         * @return NULL flushing rewrite rules for WP
         */
        public function libraryBookSearchDeactivatePlugin()
        {
            flush_rewrite_rules();
        }

        /**
         * Handle uninstallation of plugin.
         * 
         * @return NULL Call this while uninstallung the plugin
         */
        public function libraryBookSearchUninstallPlugin()
        {
            include_once LBS_DIR_PATH . 'includes/uninstall.php';
            LibrabryBooksSearchUninstall::LbsUninstallPlugin();
        }

        /**
         * Create Custom Post Type of Library Book to store books details.
         * 
         * @return NULL registering custom post type
         */
        public function libraryBookSearchCustomPostType()
        {
            include_once LBS_DIR_PATH . 'includes/lbs-custom-post-type.php';
            $lbs_cpt = new LbsCustomPostType();
            $lbs_cpt->lbsCustomPostType();
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
                array( $lbs_meta_box, 'lbsCustomMetaBox' )
            );
            add_action(
                'save_post', 
                array( $lbs_meta_box, 'lbsMetaSave' )
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
                array( $lbs_custom_shortcode, 'lbsCustomShortcode' )
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

        /**
         * Altering Title on Single page with Metadata.
         * 
         * @param $title Getting default title to alter
         * @param $id    Post ID to work with
         * 
         * @return $title   Altered title if Post Type is LBS
         */
        public function addMetadataAfterTitle($title, $id)
        {
            $books_meta_html = "";
            if (! is_admin()  
                && 'library-search-book' == get_post_type($id) 
                && is_single()
            ) {
                global $wpdb;

                $get_library_book_search_meta_data = get_post_meta($id);

                if ($get_library_book_search_meta_data['lbs-book-author'][0] ) {
                    $books_meta_html .= "<br>By " . 
                    $get_library_book_search_meta_data['lbs-book-author'][0];
                }
                
                if ($get_library_book_search_meta_data['lbs-book-publisher'][0] ) {
                    $books_meta_html .= 
                    " [" . 
                    $get_library_book_search_meta_data['lbs-book-publisher'][0] .
                    "]";
                }

                if ($get_library_book_search_meta_data['lbs-stars'][0] ) {
                    $books_meta_html .= " | Rating: " . 
                    $get_library_book_search_meta_data['lbs-stars'][0];
                }

                if ($get_library_book_search_meta_data['lbs-book-price'][0] ) {
                    $books_meta_html .= " | Price: " . 
                    $get_library_book_search_meta_data['lbs-book-price'][0];
                }

                return "<u>" . $title . "</u>" . $books_meta_html;
                
            }

            return $title;

        }

    }

    // Instantiate the object of Library Book Search.
    $library_book_search = new LibraryBookSearch();

    // Enqueuing jQuery form script of WordPress.
    add_action(
        'wp_enqueue_scripts',
        array( $library_book_search, 'enqueueJqueryForm' )
    );

    // Enqueuing admin styles.
    add_action(
        'admin_head', 
        array( $library_book_search, 'lbsAdminEnqueueStyles' )
    );

    // Enqueuing all necessary style and script in front end.
    add_action(
        'wp_head',
        array( $library_book_search, 'lbsEnqueueStylesScripts' )
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

    // Adding Metadata after single page title
    add_filter(
        'the_title', 
        array( $library_book_search, 'addMetadataAfterTitle' ), 
        10, 
        2
    );

    // Calling function of metabox.
    $library_book_search->libraryBookSearchCustomMetaBox();

    // Defining activation hook of plugin.
    register_activation_hook(
        __FILE__,
        array( $library_book_search, 'libraryBookSearchActivatePlugin' )
    );

    // Defining deactivation hook of plugin.
    register_deactivation_hook(
        __FILE__,
        array( $library_book_search, 'libraryBookSearchDeactivatePlugin' )
    );

    // Defining uninstallation hook of plugin.
    register_uninstall_hook(
        __FILE__,
        array( 'libraryBookSearchUninstallPlugin' )
    );

}
