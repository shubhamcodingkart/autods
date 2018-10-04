var jQuery, $wk
$wk = jQuery.noConflict();
(function ($wk) {
  $wk(document).ready(function () {
    $wk('#wallet-customer').select2()
    $wk('.transaction-datepicker').datepicker({
      dateFormat: 'yy-mm-dd',
      onSelect: function (datetext) {
        $wk('.transaction-datepicker').val(datetext)
      }
    })
  })
})($wk)
