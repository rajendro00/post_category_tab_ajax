jQuery(function($) {
   $(".tab-item").on("click", function(e){
    var target = $(this).data("target");
    $('.post-preloader').show();
    $.post(post_tab_ajax.ajax_url, {      //POST request
         _nonce: post_tab_ajax.nonce, //nonce
        action: "post_tab",         //action
        cat: target               //data
        }, function(data) {    
            $('.post-preloader').hide();
            $('.tab-content-item').html(data);
        }
    );

   });
   $(window).on("load", function(){ 
    let catId = $(".tab-items li:first-child").data("target");
    $.post(post_tab_ajax.ajax_url, {      //POST request
        _nonce: post_tab_ajax.nonce, //nonce
       action: "post_tab",         //action
       cat: catId               //data
       }, function(data) {    
                  //callback
            $('.post-preloader').hide();
           $('.tab-content-item').html(data);
       }
   );
   });
});
