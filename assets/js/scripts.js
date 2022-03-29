(function($) {
	"use strict";
	$(document).ready(function(){
		$(document).on('submit', '#search-filter-form', function(e) {
            e.preventDefault();
            var $this = $(this);
            var formData = new FormData(this);
            formData.append('action', 'search_filter_form');
            $.ajax({
                type: 'post',
                url: my_ajax_object.ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(data) {
                    $("#search").val("search.......");
                },
                complete: function(data) {
                 $("#search").val("search");
                },
                success: function(data) {
                    console.log(data);
                    $('.author_single_card_main_section').html(data);
                },
                error: function(data) {
                    
                },

            });
        });
        
	});

})(jQuery);