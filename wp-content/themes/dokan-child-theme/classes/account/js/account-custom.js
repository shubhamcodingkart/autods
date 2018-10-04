jQuery(document).ready(function(){

	// check all orders in vendor dashboard
   	jQuery('#check_all_orders').click(function() {
		if (jQuery(this).is(':checked')) {
	        jQuery('.single_order_checkbox').attr('checked', true);
	    } else {
	        jQuery('.single_order_checkbox').attr('checked', false);
	    }
	});

   	jQuery('.single_order_checkbox').click(function() {
		//if (jQuery(this).is(':checked')) {
	        jQuery('#check_all_orders').attr('checked', false);
	    //}
	});
	
   	// show popup on shipping option select
   	jQuery('.shipping_status_selectbox').on('change', function() {

   		// select2 autocomplete
   		jQuery(document).ready(function() {
		    jQuery('#selectbox_order_status').select2();
		});

		// check order status shipped on change 
		if (this.value == 'wc-shipped') {
			jQuery('#order_status_shipping_popup_form')[0].reset();
		  	jQuery("#shipping_status_modal").modal('show');
		  	var order_id = jQuery(this).attr('data-id'); //get order id 

		  	jQuery("#order_status_shipping_popup_form").submit(function(e) {
		        e.preventDefault();

		        var tracking_number = jQuery(".tracking_number").val();
		        var shipping_career = jQuery("#selectbox_order_status").val();

		        if(tracking_number == ''){
		        	jQuery('#order_status_shipping_popup_form .error-field').text('Please select Tracking number.');
		        	return false;
		        }

		        if(shipping_career == ''){
		        	jQuery('#order_status_shipping_popup_form .error-field').text('Please select Shipping career.');
		        	return false;
		        }
		        
		        var fd = new FormData(this);
		        fd.append('action', 'codingkart_order_status_shipping_form_ajax_fn'); 
		        fd.append("order_id",order_id); 

		        var baseUrl = document.location.origin; 
	        	var ajaxurl =baseUrl+'/wp-admin/admin-ajax.php';

		        jQuery.ajax({
			        type:    "POST",
			        url:     ajaxurl,          
			        data: fd,
			        contentType: false,
			        processData: false,
			        beforeSend: function() {
	                  	jQuery(".show-loader").show();
	                },
	                success: function(result) {
	                	jQuery(".show-loader").hide();
	                	jQuery("#shipping_status_modal").modal('hide');
	                	console.log(result);
			            //window.location.href = baseUrl+"/dashboard/orders/?order_status=wc-shipped";
			        }
			    });
		    });
		}
		else{
			var order_id = jQuery(this).attr('data-id'); //get order id
			var order_status = jQuery(this).val();

			var baseUrl = document.location.origin; 
        	var ajaxurl =baseUrl+'/wp-admin/admin-ajax.php';
        	var final_string="action=codingkart_order_status_shipping_form_ajax_fn&order_id="+order_id+"&order_status="+order_status;

	        jQuery.ajax({
		        type:    "POST",
		        url:     ajaxurl,          
		        data:    final_string,
		        async : true,
		        beforeSend: function() {
                  	jQuery(".select-img-"+order_id).show();
                },
                success: function(msg) {
                	jQuery(".select-img-"+order_id).hide();
		            window.location.href = baseUrl+"/index.php/dashboard/orders/?order_status="+order_status;
		        }
		    });
		}
	})

});


// open order pdf file in new window
/* if the page has been fully loaded we add two click handlers to the button */
jQuery(document).ready(function ($) {
	/* Get the checkboxes values based on the class attached to each check box */
	$("#selected_orders").click(function (event) {
		//var actionselected = $(this).attr("id").substr(2);
		var action = 'invoice';
		if ( $.inArray(action, wpo_wcpdf_ajax.bulk_actions) !== -1 ) {
			event.preventDefault();
			var template = action;
			var checked = [];
			$('.single_order_checkbox:checked').each(
				function() {
					checked.push($(this).val());
				}
			);
			
			if (!checked.length) {
				alert('You have to select order(s) first!');
				return;
			}
			
			var order_ids=checked.join('x');

			if (wpo_wcpdf_ajax.ajaxurl.indexOf("?") != -1) {
				url = wpo_wcpdf_ajax.ajaxurl+'&action=generate_wpo_wcpdf&document_type='+template+'&order_ids='+order_ids+'&_wpnonce='+wpo_wcpdf_ajax.nonce;
			} else {
				url = wpo_wcpdf_ajax.ajaxurl+'?action=generate_wpo_wcpdf&document_type='+template+'&order_ids='+order_ids+'&_wpnonce='+wpo_wcpdf_ajax.nonce;
			}

			window.open(url,'_blank');
		}
	});



	// create order api
	$(".create_order_api").click(function (event) {
		var baseUrl = document.location.origin; 
	    var ajaxurl =baseUrl+'/wp-admin/admin-ajax.php';
		var final_string="action=codingkart_create_order_api";
		jQuery.ajax({
	        type:    "POST",
	        url:     ajaxurl,          
	        data:    final_string,
	        async : true,
	        beforeSend: function() {
	          	jQuery(".add_new_order_api_loader").show();
	        },
	        success: function(result) {
        		console.log(result);
        		jQuery(".add_new_order_api_loader").hide();
			}
	    });
	});


	// update shipping form fields and hide it
	jQuery('form#shipping-form input#shipping_type_price').val(0);
	jQuery('form#shipping-form input#additional_product').val(0);
	jQuery('form#shipping-form input#additional_qty').val(0);
	jQuery('input[name=_dokan_flat_rate]').val(0);

	jQuery('form#shipping-form .dokan-shipping-type-price').hide();
	jQuery('form#shipping-form .dokan-shipping-add-product').hide();
	jQuery('form#shipping-form .dokan-shipping-add-qty').hide();
	jQuery('form#shipping-form input[name=_dokan_flat_rate]').hide();

	$("form#shipping-form label[for=_dps_ship_policy]").text(function () {
	    return $(this).text().replace("Flat Rate", ""); 
	});

	// jQuery(".dokan-profile-completeness .dokan-alert-info").css("cursor", "pointer");
	// jQuery('.dokan-profile-completeness .dokan-alert-info').click(function() {
	// 	var baseUrl = document.location.origin;
	// 	window.location.href = baseUrl+"/index.php/dashboard/settings/store/";
	// });

	jQuery('.ck_add_new_product').click(function() {
		var baseUrl = document.location.origin; 
	    var ajaxurl =baseUrl+'/wp-admin/admin-ajax.php';
		var final_string="action=codingkart_add_new_blank_product";
		jQuery.ajax({
	        type:    "POST",
	        url:     ajaxurl,          
	        data:    final_string,
	        async : true,
	        beforeSend: function() {
	          	//jQuery(".add_new_order_api_loader").show();
	        },
	        success: function(result) {
        		console.log(result);
        		window.location.href = baseUrl+"/index.php/dashboard/products/?product_id="+result+"&action=edit";
        		//jQuery(".add_new_order_api_loader").hide();
			}
	    });
	});

	// Add bootstrap.min.js file to error page
	if (jQuery("body#error-page").length > 0) {
		var baseUrl = document.location.origin;
		jQuery("<script type='text/javascript' src="+baseUrl+"/wp-content/themes/dokan/assets/js/bootstrap.min.js'></script>").insertAfter('header#masthead');
	}

	jQuery("form.dokan-product-edit-form").submit(function(e) {
		var product_type_selectbox = jQuery('select#product_type').val();
		var product_description = tinyMCE.editors[jQuery('#post_content').attr('id')].getContent();

		// Required product price(simple product)
		if (product_type_selectbox == 'simple') {
			jQuery('.dokan-product-required-price-field').remove();
			var regular_price = jQuery('form.dokan-product-edit-form input#_regular_price').val();
			if (regular_price == '' || regular_price <= 0) {
				jQuery('<div class="dokan-product-required-price-field">Please Enter product price.</div>').insertAfter('form.dokan-product-edit-form .regular-price');
				jQuery('html, body').animate({
			        scrollTop: jQuery("form.dokan-product-edit-form .regular-price").offset().top
			    }, 1000);
				return false;
			}
		}

		// Required product Description
		if( product_description == '' ){
			jQuery('<div class="dokan-product-required-price-field">Required field.</div>').insertAfter('form.dokan-product-edit-form div#wp-post_content-editor-container');
			jQuery('html, body').animate({
		        scrollTop: jQuery("form.dokan-product-edit-form div#wp-post_content-editor-container").offset().top
		    }, 1000);
			return false;
		}

		// Required product featured image
		if( !jQuery('.dokan-feat-image-upload .instruction-inside').hasClass('dokan-hide') ){
			var img_src = jQuery('.dokan-feat-image-upload img').attr('src');
			if( img_src == '' ){
				jQuery('<div class="dokan-product-required-price-field">Please upload product image.</div>').insertAfter('form.dokan-product-edit-form .dokan-feat-image-upload');
				jQuery('html, body').animate({
			        scrollTop: jQuery("form.dokan-product-edit-form .dokan-feat-image-upload").offset().top
			    }, 1000);
				return false;
			}
		}

	});	 

	//Update product button should not disabled
	window.setInterval(function(){
		jQuery( 'input[name=dokan_update_product]' ).removeAttr( 'disabled' );
	}, 500);
		

	
	// -------------------------------------------------------

    // Add/ update user tokens from edit account
	$('.custom-wpuf-form').on('click', 'img.wpuf-clone-field', function(e) {
        var $div = $(this).closest('tr');
        var $clone = $div.clone();
        
        //clear the inputs
        $clone.find('input').val('');
        $clone.find(':checked').attr('checked', '');
        $div.after($clone);
    });

    $('.custom-wpuf-form').on('click', 'img.wpuf-remove-field', function() {
        //check if it's the only item
        var $parent = $(this).closest('tr');
        var items = $parent.siblings().andSelf().length;

        if( items > 1 ) {
            $parent.remove();
        }
    });
    
	// -------------------------------------------------------

	// stores option in top menu
	$(document).on({
		click: function(event) {
	        $("div#autods-tokens-dropdown-content").toggle();
			
		}
	}, "a#autods-tokens-dropdown-toggle");

	// stores dropdown select2
	jQuery('select[name="selectbox_autods_tokens"]').select2();

	// set single token on selectbox change
	$('select[name="selectbox_autods_tokens"]').on('change', function() {
	  	var baseUrl = document.location.origin; 
	    var ajaxurl =baseUrl+'/wp-admin/admin-ajax.php';
		var selected_token = this.value;
		var final_string="action=codingkart_update_autods_token_ajax_fn&selected_token="+selected_token;
		jQuery.ajax({
	        type:    "POST",
	        url:     ajaxurl,          
	        data:    final_string,
	        async : true,
	        beforeSend: function() {
	        },
	        success: function(result) {
        		console.log(result);
        		location.reload();
			}
	    });
	});


});





