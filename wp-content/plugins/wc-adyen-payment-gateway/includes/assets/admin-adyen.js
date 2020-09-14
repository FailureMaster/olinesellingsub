jQuery(document).ready(function () {
  var $test_mode_checkbox = jQuery('#woocommerce_adyen_payment_test_mode');

  if (! $test_mode_checkbox.is(':checked')) {
    woo_adyen_hide_test_mode_inputs();
  }

  $test_mode_checkbox.change(function () {
    if (!this.checked) {
      woo_adyen_hide_test_mode_inputs();
    } else {
      jQuery('#woocommerce_adyen_payment_test_api_key').parent().parent().parent().show();
      jQuery('#woocommerce_adyen_payment_test_merchant').parent().parent().parent().show();
      jQuery('#woocommerce_adyen_payment_test_origin_key').parent().parent().parent().show();
    }
  });

  function woo_adyen_hide_test_mode_inputs(){
    jQuery('#woocommerce_adyen_payment_test_api_key').parent().parent().parent().hide();
    jQuery('#woocommerce_adyen_payment_test_merchant').parent().parent().parent().hide();
    jQuery('#woocommerce_adyen_payment_test_origin_key').parent().parent().parent().hide();
  }
});
