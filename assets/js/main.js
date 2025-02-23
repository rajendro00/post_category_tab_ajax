jQuery(function($) {
   $(".tab-item").on("click", function(){
    var target = $(this).data("target");

            // Remove 'active' class from all tabs and contents
            $(".tab-item").removeClass("active");
            $(".tab-content-item").removeClass("active").hide();

            // Add 'active' class to clicked tab and corresponding content
            $(this).addClass("active");
            $('.tab-content-item[data-category="' + target + '"]').addClass("active").fadeIn();
   });
});
