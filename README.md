# Library Book Search

User can search from books available in library, through shortcode.
For facilitating this functionality, There is custom post available as "Library Book" when you activate the plugin. Where you can store all important information like price of book, you can give rating to book, store authors and publishers through custom taxonomies available in Library Book post.

## Getting Started

To get started with, you first need to clone this plugin in your local or remote web-site in plugins folder and activate through admin panel or WP-CLI.

### Prerequisites

Just clone the plugin in your local or remote site and activate it.
You are good to go now.


### Installing

You can download this plugin and put it in your site's plugin folder OR clone this plugin using command provided below.

```
git clone https://github.com/malavvasita/library-books-search.git
```

After downloading or cloning just activate the plugin through Admin panel or WP-CLI command as given below.

```
wp plugin activate library-books-search
```

## Deployment

You will be facilitated with Custom Post Type as "Library Book" after activating the plugin.
Start with creating entries of various books under Library Book.
After you are done with the collection of books in your site, you can now put shortcode [booksearch] in any post and you will get a search form for book and under the form you will get list of all books. Proivde the search data into form and you are ready to go.


## Author

* **Malav Vasita** - *Developer* - [See Profile](https://profiles.wordpress.org/malavvasita)

## License

This project is licensed under the GNU GPL License

## Acknowledgments

* Used jQuery library for price range control in Searching of book. [Source](https://jqueryui.com/slider/#range)
