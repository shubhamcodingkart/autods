var jQuery, $wk, walletajax
$wk = jQuery.noConflict();
(function ($wk) {
  $wk(document).on('click', '#wallet-checkout-payment', function () {
    var check
    if (this.checked) {
      check = 1
    } else {
      check = 0
    }
    $wk.ajax({
      url: walletajax.ajaxurl,
      type: 'POST',
      data: {
        action: 'ajax_wallet_check',
        'check': check,
        'nonce': walletajax.nonce
      },
      success: function (response) {
        $wk(document.body).trigger('update_checkout')
      }
    })
    $wk(document.body).trigger('update_checkout')
  })

  $wk(document).ready(function () {
    $wk('#wallet-customer-search').select2()
  })
})($wk)
