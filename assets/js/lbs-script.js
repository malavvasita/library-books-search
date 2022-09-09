jQuery(document).ready(function($){

    $('.lbs-book-data-table').DataTable({
        pagingType: 'full_numbers',
        searching : false,
    });

    var lbs_book_name        = '';
    var lbs_book_author      = '';
    var lbs_book_publisher   = '';
    var lbs_book_rating      = '';
    var lbs_book_price_from  = '';
    var lbs_book_price_to    = '';

    $( '.lbs-form-reset' ).click( function(){
        lbs_book_name        = '';
        lbs_book_author      = '';
        lbs_book_publisher   = '';
        lbs_book_rating      = '';
        lbs_book_price_from  = '';
        lbs_book_price_to    = '';

        $('.lbs-book-name').val( '' );
        $('.lbs-book-author').val( '' );
        $('.lbs-book-publisher').val( '' );
        $('.lbs-book-rating').val( '' );
        $("#slider-range").slider("values", 0);
        $("#slider-range").slider("values", 1);

        booksSearchCallback();

    } );

    $('#lbs-search-form').on('submit', function(e){
       e.preventDefault();

       lbs_book_name        = $('.lbs-book-name').val();
       lbs_book_author      = $('.lbs-book-author').val();
       lbs_book_publisher   = $('.lbs-book-publisher').val();
       lbs_book_rating      = $('.lbs-book-rating').val();
       lbs_book_price_from  = $("#slider-range").slider("values", 0);
       lbs_book_price_to    = $("#slider-range").slider("values", 1);



       booksSearchCallback();
        
    });

    function booksSearchCallback(){
        $.ajax({
            url: localized_data.admin_ajax_path,
            type:"POST",
            dataType:'JSON',
            data: {
                action: 'lbs-search',
                data: { 
                    'book_name'         : lbs_book_name, 
                    'book_author'       : lbs_book_author, 
                    'book_publisher'    : lbs_book_publisher, 
                    'book_rating'       : lbs_book_rating, 
                    'book_price_from'   : lbs_book_price_from,
                    'book_price_to'     : lbs_book_price_to,
                },
            },
            success: function(res){
                var lbs_book_search_data = Object.values(res)[1];
                $('.lbs-book-data-table').DataTable().clear().destroy();
                
                $( "#lbs-books-search-data" ).html( lbs_book_search_data );

                $('.lbs-book-data-table').DataTable({
                    pagingType: 'full_numbers',
                    searching : false,
                });

            }, 
            error: function(data){
                console.log( data );
            }
        });
    }
     
      
});

jQuery(function () {

    jQuery("#slider-range").slider({
        range: true,
        min: Number( localized_data.books_min_price ),
        max: Number( localized_data.books_max_price ),
        values: [localized_data.books_min_price, localized_data.books_max_price],
        slide: function (event, ui) {
            jQuery("#amount").val("₹ " + ui.values[0] + " - ₹ " + ui.values[1]);
        }
    });
    jQuery("#amount").val("₹ " + jQuery("#slider-range").slider("values", 0) +
        " - ₹ " + jQuery("#slider-range").slider("values", 1));
});