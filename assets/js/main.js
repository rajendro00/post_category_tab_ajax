jQuery(function($){
    $(".tabs li").click(function() {
        var targetCategory = $(this).data("target");

        $(".tabs li").removeClass("active");
        $(this).addClass("active");

        $(".content").hide();
        $(".content[data-category='" + targetCategory + "']").fadeIn();
    });

    
    $(".tabs li:first").addClass("active");
    $(".content:first").show();
});