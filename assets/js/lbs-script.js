jQuery(document).ready(function($){
    $(".lbs-form-submit").click(function(){
        $.ajax({
            type: "GET",
            url: localized_data.admin_path,
            data: { 'action': 'lbs-search', 'data': 'data' },
            dataType: "text",
            success: function (resultData) {
                console.log(resultData);
            }
        });
        return false;
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