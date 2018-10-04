jQuery(document).ready(function(){

	jQuery('#cart_items_api_call').click(function() {

   		jQuery('#cart_items_api_call i').removeClass('fa-upload');
      jQuery('#cart_items_api_call i').addClass('fa-circle-o-notch');
      jQuery('#cart_items_api_call i').addClass('fa-spin');

      jQuery('#cart_items_api_call span').remove();

   		var baseUrl = document.location.origin; 
    	var ajaxurl =baseUrl+'/wp-admin/admin-ajax.php';

    	var final_string="action=codingkart_get_cart_items_detail_api_fn";
        jQuery.ajax({
	        type:    "GET",
	        url:     ajaxurl,          
	        data: final_string,
	        contentType: false,
	        processData: false,
	        beforeSend: function() {
            //jQuery(".show-loader").show();
          },
          success: function(response) {
          	console.log(response);
            var obj = JSON.parse(response);
            var check_if_error = response.success; //to view you pop up
            var product_upload_count = obj.product_upload_count; //to view you pop up

            if (check_if_error == 0) {
              jQuery('a#cart_items_api_call i').removeClass('fa-circle-o-notch');
              jQuery('a#cart_items_api_call i').removeClass('fa-spin');
              jQuery('a#cart_items_api_call i').addClass('fa-close');

              jQuery.jsdvPopup( {text:'Invalid API URL!'} );
            }

            if (product_upload_count > 0) {
              jQuery('a#cart_items_api_call i').removeClass('fa-circle-o-notch');
              jQuery('a#cart_items_api_call i').removeClass('fa-spin');
              jQuery('a#cart_items_api_call i').addClass('fa-check');

              jQuery('<span>('+product_upload_count+')</span>').insertAfter('a#cart_items_api_call i');

              jQuery.jsdvPopup( {text:obj.response} );
            }else{
              jQuery('a#cart_items_api_call i').removeClass('fa-circle-o-notch');
              jQuery('a#cart_items_api_call i').removeClass('fa-spin');
              jQuery('a#cart_items_api_call i').addClass('fa-close');

              jQuery.jsdvPopup( {text:'0 Product Uploaded!'} );
            }
          }
	    });
	});

});