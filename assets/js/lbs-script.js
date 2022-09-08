jQuery(document).ready(function($){

    $('.lbs-book-data-table').DataTable({
        pagingType: 'full_numbers',
        searching : false,
    });

    $('#lbs-search-form').on('submit', function(e){
       e.preventDefault();

       console.log( "Admin Ajax: " + localized_data.admin_ajax_path );
    
       var lbs_book_name        = $('.lbs-book-name').val();
       var lbs_book_author      = $('.lbs-book-author').val();
       var lbs_book_publisher   = $('.lbs-book-publisher').val();
       var lbs_book_rating      = $('.lbs-book-rating').val();
       var lbs_book_price_from  = jQuery("#slider-range").slider("values", 0);
       var lbs_book_price_to    = jQuery("#slider-range").slider("values", 1);

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
        
    });
     
      
});

jQuery(function () {
    jQuery("#slider-range").slider({
        range: true,
        min: 0,
        max: 5000,
        values: [100, 2000],
        slide: function (event, ui) {
            jQuery("#amount").val("₹ " + ui.values[0] + " - ₹ " + ui.values[1]);
        }
    });
    jQuery("#amount").val("₹ " + jQuery("#slider-range").slider("values", 0) +
        " - ₹ " + jQuery("#slider-range").slider("values", 1));
});