jQuery(document).ready(function($){

	// 
   	jQuery('.import-products-box-magento').click(function() {
		jQuery('#import_products_magento_modal').modal('show');

		$('.import_products_magento_credentials_form')[0].reset();
		$('.import_products_magento_credentials_form .req-field').remove();

	});

   	jQuery('.import_products_magento_credentials_form').submit(function(e) {
   		e.preventDefault();

   		jQuery('.req-field').remove();

   		var isValid = true;
        $('.import_products_magento_credentials_form input').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                jQuery('<p class="req-field">Required field.</p>').insertAfter(this);
            }
        });
        if (isValid == false){
        	e.preventDefault();
        }
        else{
        	var fd = new FormData(this);
	    	fd.append('action', 'codingkart_import_products_magento_credentials_form_ajax'); 
	    	var baseUrl = document.location.origin;
	      	var ajaxurl = baseUrl+'/wp-admin/admin-ajax.php';

		    jQuery.ajax({
		        type:    "POST",
		        url:     ajaxurl,          
		        data: fd,
		        contentType: false,
		        processData: false,
		        beforeSend: function() {
		        },
		        success: function(result) {
		        	jQuery('#import_products_magento_modal').modal('hide');
		        	jQuery('#successful_integration_popup').modal('show');
		        	setTimeout(function(){ window.location.href = baseUrl+"/index.php/dashboard/import-products"; }, 2000);
		        }
		    });
        	//alert('Thank you for submitting');
        }

      	
    });

   	// Active product tab in Product integration tab & CSV import page
	if( (window.location.href.indexOf("/dashboard/tools/csv-import/") > -1) || (window.location.href.indexOf("/dashboard/import-products/") > -1) || (window.location.href.indexOf("/dashboard/update-products/") > -1) || (window.location.href.indexOf("/dashboard/update_products/csv-import/") > -1) ) {
       jQuery('ul.dokan-dashboard-menu li.products').addClass('active');
    }
    
});