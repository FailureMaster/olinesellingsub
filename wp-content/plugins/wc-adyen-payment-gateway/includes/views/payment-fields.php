<?php /** @var \PGAWC_Adyen\Core\PGAWC_Adyen_Gateway $this */ ?>
<div id="component-container"></div>
<input type="hidden" id="adyen_payment_data" name="adyen_payment_data">
<script type="application/javascript">
  jQuery(document).ready(function () {
    function handleOnChange(state, component) {

      if (state.isValid === true) {
        jQuery('#adyen_payment_data').val(JSON.stringify(state.data));
      }
    }

        try {
          const configuration = {
            locale: "<?php echo esc_js(get_locale()); ?>",
            environment: "<?php echo esc_js( $this->adyenConfig->getEnvironment() ); ?>",
            originKey: "<?php echo esc_js($this->adyenConfig->getOriginKey()); ?>",
            paymentMethodsResponse: <?php  echo json_encode($paymentMethods);?>,
            onChange: handleOnChange,
          };
          const checkout = new AdyenCheckout(configuration);
          const card = checkout.create("card").mount("#component-container");
        }catch (e) {
          console.warn(e.message);
        }
  });

</script>