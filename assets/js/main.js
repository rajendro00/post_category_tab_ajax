jQuery(function($) {
    $(".tab-item").click(function () {
        var category_id = $(this).data("target");

        $(".tab-item").removeClass("active");
        $(this).addClass("active");

        // Ensure visibility before showing loading message
        $("#post-content").css("display", "block").html("<p>Loading posts...</p>");

        $.ajax({
            type: "POST",
            url: post_tab_ajax.ajax_url,
            data: {
                action: "load_category_posts",
                category_id: category_id,
            },
            success: function (response) {
                $("#post-content").html(response).fadeIn(200); // Ensure content is shown
            },
            error: function () {
                $("#post-content").html("<p>Something went wrong. Please try again.</p>").fadeIn(200);
            },
        });
    });
});
