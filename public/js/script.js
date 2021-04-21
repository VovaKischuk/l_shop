$( document ).ready(function() {
    var $slider = $("#slider-range");
    //Get min and max values
    var priceMin = $slider.attr("data-price-min"),
        priceMax = $slider.attr("data-price-max");

    //Set min and max values where relevant
    $("#price-filter-min, #price-filter-max").map(function(){
        $(this).attr({
            "min": priceMin,
            "max": priceMax
        });
    });
    // $("#price-filter-min").attr({
    //     "placeholder": "min " + priceMin,
    //     "value": priceMin
    // });
    // $("#price-filter-max").attr({
    //     "placeholder": "max " + priceMax,
    //     "value": priceMax
    // });

    $slider.slider({
        range: true,
        min: Math.max(priceMin, 0),
        max: priceMax,
        values: [priceMin, priceMax],
        slide: function(event, ui) {
            $("#price-filter-min").val(ui.values[0]);
            $("#price-filter-max").val(ui.values[1]);
        }
    });

    $("#price-filter-min, #price-filter-max").map(function(){
        updateSlider();
        $(this).on("input", function() {
            updateSlider();
        });
    });

    function updateSlider(){
        $slider.slider("values", [$("#price-filter-min").val(), $("#price-filter-max").val()]);
    }

});