jQuery(document).ready(function() {
    jQuery('.ajax_api_call').click(function() {
      var data_product_id = jQuery(this).attr('data-product_id');

      var id = jQuery(this).attr('id');
      jQuery('#'+id+' i').removeClass('fa-upload');
      jQuery('#'+id+' i').addClass('fa-circle-o-notch');
      jQuery('#'+id+' i').addClass('fa-spin');

      
      //jQuery('.item-bar #'+id).css("background-color", "#FBD3C8");

      var baseUrl = document.location.origin;
      var ajaxurl = baseUrl+'/wp-admin/admin-ajax.php';
        jQuery.ajax({
          type:    "POST",
          url:     ajaxurl,
          dataType: 'json',
          data: {'action': 'codingkart_woocommerce_get_product_json_data', data_product_id: data_product_id },
          success: function(response) {
            console.log(response);
            // icon change
            //jQuery('.item-bar #'+id).css("background-color", "#f05025");

            var result = response.result;
            var apiresponse = JSON.stringify(response.apiresponse);
            var apiresponse = apiresponse.replace(/\\/g, '');

            //show message
            if (result == 'success') {
              jQuery('#'+id+' i').removeClass('fa-circle-o-notch');
              jQuery('#'+id+' i').removeClass('fa-spin');
              jQuery('#'+id+' i').addClass('fa-check');

              jQuery.jsdvPopup({text:apiresponse});
            }else{
              jQuery('#'+id+' i').removeClass('fa-circle-o-notch');
              jQuery('#'+id+' i').removeClass('fa-spin');
              jQuery('#'+id+' i').addClass('fa-close');
              
              jQuery.jsdvPopup({text:apiresponse});
            }
          }
        });
    });

    jQuery('.product .add_to_cart_button').click(function() {
      var product_id = jQuery(this).attr('data-product_id');
        jQuery('.product #ajax_api_call_'+product_id).css("left", "75px");
    });

    // add title to single product add to cart button
    jQuery(".single_add_to_cart_button").attr("title","Add to cart");



    // Corner Notification Popup --------------------------------
    (function ($) {
        var popupHtml = '<div class="notification_popup"><span></span><div class="close"></div></div>';

        jQuery.jsdvPopup = function (options) {

            //default params
            options = $.extend({
                timeout: 4000
            }, options);
            //create and show message
            var $elem = $(popupHtml);
            //$elem.find('span').text(options.text);
            $($elem.find('span')).html(options.text);
            $elem.appendTo($('body'));

            show($elem);

            //click handler
            $elem.find('.close').on('click', function () {
                hide.call($(this).parent());
            });

            //setup timer
            setTimeout(function () {
                hide.call($elem);
            }, options.timeout);

            function show(elem) {
                elem.css({'bottom': -1 * elem.outerHeight(), "display": 'block'});
                elem.animate({'bottom': 10}, "fast");
            }

            function hide() {
                this.animate({'bottom': -1 * this.outerHeight()}, 'fast', 'swing', function () {
                    $(this).remove();
                });
            }
        };
    })(jQuery);

});



