jQuery(function($){
    // alert(post_tab_ajax.ajax_url);
    $(".tabs li").click(function() {
        let targetCategory = $(this).data("target");

        $(".tabs li").removeClass("active");
        $(this).addClass("active");

        $.ajax({
            type: "POST",
            url: post_tab_ajax.ajax_url,
            data: {
               action: 'post_submit_callback_action',
               category_id: targetCategory
            },
        })
    });

    
    $(".tabs li:first").addClass("active");
    $(".content:first").show();
});