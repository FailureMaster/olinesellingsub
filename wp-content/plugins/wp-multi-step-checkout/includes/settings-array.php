<?php
/**
 * Functions
 *
 * @package WPMultiStepCheckout
 */

defined( 'ABSPATH' ) || exit;


/**
 * The settings array for the admin page
 */
if ( ! function_exists( 'get_wmsc_settings' ) ) {
	function get_wmsc_settings( $text_domain ) {

		$account_url                      = admin_url( 'admin.php?page=wc-settings&tab=account' );
		$no_login_screenshot              = 'https://www.silkypress.com/wp-content/uploads/2018/09/multi-step-checkout-no-login.png';
		$registration_settings_screenshot = 'https://www.silkypress.com/wp-content/uploads/2019/09/registration-description.png';
		$wmsc_settings                    = array(
			/* General Settings */
			'label1'                   => array(
				'label'      => __( 'Which Steps to show', $text_domain ),
				'input_form' => 'header',
				'value'      => '',
				'section'    => 'general',
			),
			'show_shipping_step'       => array(
				'label'      => __( 'Show the <code>Shipping</code> step', $text_domain ),
				'input_form' => 'checkbox',
				'value'      => true,
				'section'    => 'general',
			),
			'show_login_step'          => array(
				'label'      => __( 'Show the <code>Login</code> step', $text_domain ),
				'input_form' => 'text',
				'value'      => sprintf( __( 'If you want to remove the login step, then make sure you have the "Enable customer registration on the Checkout page" checked and the "Display returning customer login reminder on the Checkout page" unchecked on the <a href="%1$s">WP Admin -> WooCommerce -> Settings -> Accounts</a> page. See <a href="%2$s" target="_blank">this screenshot</a>.', $text_domain ), esc_url( $account_url ), esc_url( $no_login_screenshot ) ),
				'section'    => 'general',
			),
			'unite_billing_shipping'   => array(
				'label'      => __( 'Show the <code>Billing</code> and the <code>Shipping</code> steps together', $text_domain ),
				'input_form' => 'checkbox',
				'value'      => false,
				'section'    => 'general',
			),
			'unite_order_payment'      => array(
				'label'      => __( 'Show the <code>Order</code> and the <code>Payment</code> steps together', $text_domain ),
				'input_form' => 'checkbox',
				'value'      => false,
				'section'    => 'general',
			),

			'label2'                   => array(
				'label'      => __( 'Additional Elements', $text_domain ),
				'input_form' => 'header',
				'value'      => '',
				'section'    => 'general',
			),
			'show_back_to_cart_button' => array(
				'label'      => __( 'Show the <code>Back to Cart</code> button', $text_domain ),
				'input_form' => 'checkbox',
				'value'      => true,
				'section'    => 'general',
			),
			'registration_with_login'  => array(
				'label'       => __( 'Show registration form in the Login step', $text_domain ),
				'input_form'  => 'checkbox',
				'value'       => true,
				'section'     => 'general',
				'description' => __( 'The registration form will be shown next to the login form, it will not replace it', $text_domain ),
				'pro'         => true,
			),
			'registration_desc'        => array(
				'label'      => '',
				'input_form' => 'text',
				'value'      => sprintf( __( 'Use the "Account creation" options on the <a href="%1$s">WP Admin -> WooCommerce -> Settings -> Accounts & Privacy</a> page to modify the Registration form. See <a href="%2$s" target="_blank">this screenshot</a>.', $text_domain ), esc_url( $account_url ), esc_url( $registration_settings_screenshot ) ),
				'section'    => 'general',
				'pro'        => true,
			),
			'review_thumbnails'        => array(
				'label'      => __( 'Add product thumbnails to the Order Review section', $text_domain ),
				'input_form' => 'checkbox',
				'value'      => true,
				'section'    => 'general',
				'pro'        => true,
			),

			'label3'                   => array(
				'label'      => __( 'Functionality', $text_domain ),
				'input_form' => 'header',
				'value'      => '',
				'section'    => 'general',
			),
			'validation_per_step'      => array(
				'label'       => __( 'Validate the fields during each step', $text_domain ),
				'description' => __( 'The default WooCommerce validation is done when clicking the Place Order button. With this option the validation is performed when trying to move to the next step', $text_domain ),
				'input_form'  => 'checkbox',
				'value'       => true,
				'section'     => 'general',
				'pro'         => true,
			),
			'clickable_steps'          => array(
				'label'       => __( 'Clickable Steps', $text_domain ),
				'description' => __( 'The user can click on the steps in order to get to the next one.', $text_domain ),
				'input_form'  => 'checkbox',
				'value'       => true,
				'section'     => 'general',
				'pro'         => true,
			),
			'keyboard_nav'             => array(
				'label'       => __( 'Enable the keyboard navigation', $text_domain ),
				'description' => __( 'Use the keyboard\'s left and right keys to move between the checkout steps', $text_domain ),
				'input_form'  => 'checkbox',
				'value'       => false,
				'section'     => 'general',
			),
			'url_hash'                 => array(
				'label'       => __( 'Change the URL for each step', $text_domain ),
				'description' => __( 'Each step will have a hash added to the URL. For example &quot;#login&quot; or &quot;#billing&quot;. This option, together with some &quot;History Change Trigger&quot; settings in the Google Tag Manager, allows Google Analytics to track each step as different pages.', $text_domain ),
				'input_form'  => 'checkbox',
				'value'       => false,
				'section'     => 'general',
				'pro'         => true,
			),

			/* Templates */
			'main_color'               => array(
				'label'      => __( 'Main Color', $text_domain ),
				'input_form' => 'input_color',
				'value'      => '#1e85be',
				'section'    => 'design',
			),
			'template'                 => array(
				'label'      => __( 'Template', $text_domain ),
				'input_form' => 'radio',
				'value'      => 'default',
				'values'     => array(
					'default'    => __( 'Default', $text_domain ),
					'md'         => __( 'Material Design', $text_domain ),
					'breadcrumb' => __( 'Breadcrumbs', $text_domain ),
				),
				'section'    => 'design',
				'pro'        => true,
			),
			'wpmc_buttons'             => array(
				'label'       => __( 'Use the plugin\'s buttons', $text_domain ),
				'input_form'  => 'checkbox',
				'value'       => false,
				'description' => __( 'By default the plugin tries to use the theme\'s design for the buttons. If this fails, enable this option in order to use the plugin\'s button style', $text_domain ),
				'section'     => 'design',
				'pro'         => true,
			),
			'wpmc_check_sign'          => array(
				'label'      => __( 'Show a "check" sign for visited steps', $text_domain ),
				'input_form' => 'checkbox',
				'value'      => false,
				'section'    => 'design',
				'pro'        => true,
			),
			'visited_color'            => array(
				'label'      => __( 'Visited steps color', $text_domain ),
				'input_form' => 'input_color',
				'value'      => '#1EBE3A',
				'section'    => 'design',
				'pro'        => true,
			),

			/* Step Titles */
			't_login'                  => array(
				'label'      => __( 'Login', $text_domain ),
				'input_form' => 'input_text',
				'value'      => __( 'Login', $text_domain ),
				'section'    => 'titles',
			),
			't_billing'                => array(
				'label'      => __( 'Billing', $text_domain ),
				'input_form' => 'input_text',
				'value'      => __( 'Billing', $text_domain ),
				'section'    => 'titles',
			),
			't_shipping'               => array(
				'label'      => __( 'Shipping', $text_domain ),
				'input_form' => 'input_text',
				'value'      => __( 'Shipping', $text_domain ),
				'section'    => 'titles',
			),
			't_order'                  => array(
				'label'      => __( 'Order', $text_domain ),
				'input_form' => 'input_text',
				'value'      => __( 'Order', $text_domain ),
				'section'    => 'titles',
			),
			't_payment'                => array(
				'label'      => __( 'Payment', $text_domain ),
				'input_form' => 'input_text',
				'value'      => __( 'Payment', $text_domain ),
				'section'    => 'titles',
			),
			't_back_to_cart'           => array(
				'label'      => __( 'Back to cart', $text_domain ),
				'input_form' => 'input_text',
				'value'      => _x( 'Back to cart', 'Frontend: button label', $text_domain ),
				'section'    => 'titles',
			),
			't_skip_login'             => array(
				'label'      => __( 'Skip Login', $text_domain ),
				'input_form' => 'input_text',
				'value'      => _x( 'Skip Login', 'Frontend: button label', $text_domain ),
				'section'    => 'titles',
			),
			't_previous'               => array(
				'label'      => __( 'Previous', $text_domain ),
				'input_form' => 'input_text',
				'value'      => _x( 'Previous', 'Frontend: button label', $text_domain ),
				'section'    => 'titles',
			),
			't_next'                   => array(
				'label'      => __( 'Next', $text_domain ),
				'input_form' => 'input_text',
				'value'      => _x( 'Next', 'Frontend: button label', $text_domain ),
				'section'    => 'titles',
			),
			't_error'                  => array(
				'label'       => __( 'Please fix the errors on this step before moving to the next step', $text_domain ),
				'input_form'  => 'input_text',
				'value'       => __( 'Please fix the errors on this step before moving to the next step', 'Frontend: error message', $text_domain ),
				'section'     => 'titles',
				'description' => __( 'This is an error message shown in the frontend', $text_domain ),
				'pro'         => true,
			),
			'c_sign'                   => array(
				'label'       => __( 'AND sign', $text_domain ),
				'input_form'  => 'input_text',
				'value'       => __( '&', $text_domain ),
				'section'     => 'titles',
				'description' => __( 'The sign between two unified steps. For example "Billing & Shipping"' ),
			),
			't_wpml'                   => array(
				'label'       => __( 'Use WPML to translate the text on the Steps and Buttons', $text_domain ),
				'input_form'  => 'checkbox',
				'value'       => false,
				'section'     => 'titles',
				'description' => __( 'For a multilingual website the translations from WPML will be used instead of the ones in this form', $text_domain ),
			),

		);

		return $wmsc_settings;

	}
}


/**
 * The steps array
 *
 * Note: The Login is always the first step and is not part of the get_wmsc_steps() array.
 * Use the wmsc_login_step_content() function if you want to overwrite the Login step content.
 */
if ( ! function_exists( 'get_wmsc_steps' ) ) {
	function get_wmsc_steps( $text_domain ) {

		$steps = array(
			'billing'  => array(
				'title'    => __( 'Billing', $text_domain ),
				'position' => 10,
				'class'    => 'wpmc-step-billing',
				'sections' => array( 'billing' ),
			),
			'shipping' => array(
				'title'    => __( 'Shipping', $text_domain ),
				'position' => 20,
				'class'    => 'wpmc-step-shipping',
				'sections' => array( 'shipping' ),
			),
			'review'   => array(
				'title'    => __( 'Order', $text_domain ),
				'position' => 30,
				'class'    => 'wpmc-step-review',
				'sections' => array( 'review' ),
			),
			'payment'  => array(
				'title'    => __( 'Payment', $text_domain ),
				'position' => 40,
				'class'    => 'wpmc-step-payment',
				'sections' => array( 'payment' ),
			),

		);

		return $steps;
	}
}


/**
 * The content of the Login step
 */
if ( ! function_exists( 'wmsc_step_content_login' ) ) {
	function wmsc_step_content_login( $checkout, $stop_at_login ) { ?> 
	<div class="wpmc-step-item wpmc-step-login">
			<div id="checkout_login" class="woocommerce_checkout_login wp-multi-step-checkout-step">
				<?php
				woocommerce_login_form(
					array(
						'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'wp-multi-step-checkout' ),
						'redirect' => wc_get_page_permalink( 'checkout' ),
						'hidden'   => false,
					)
				);
				?>
			</div>
				<?php
				if ( $stop_at_login ) {
					echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
				}
				?>
	</div>
	<?php }
}


/**
 * The content of the Billing step
 */
if ( ! function_exists( 'wmsc_step_content_billing' ) ) {
	function wmsc_step_content_billing() {
		do_action( 'woocommerce_checkout_before_customer_details' );
		do_action( 'woocommerce_checkout_billing' );
	}
}



/**
 * The content of the Shipping step
 */
if ( ! function_exists( 'wmsc_step_content_shipping' ) ) {
	function wmsc_step_content_shipping() {
		do_action( 'woocommerce_checkout_shipping' );
		do_action( 'woocommerce_checkout_after_customer_details' );
	}
}


/**
 * The content of the Order Payment step
 */
if ( ! function_exists( 'wmsc_step_content_payment' ) ) {
	function wmsc_step_content_payment() {
		echo '<h3 id="payment_heading">' . __( 'Payment', 'woocommerce' ) . '</h3>';
		do_action( 'wpmc-woocommerce_checkout_payment' );
		do_action( 'woocommerce_checkout_after_order_review' );
	}
}


/**
 * The content of the Order Review step
 */
if ( ! function_exists( 'wmsc_step_content_review' ) ) {
	function wmsc_step_content_review() {
		do_action( 'woocommerce_checkout_before_order_review' );
		echo '<h3 id="order_review_heading">' . __( 'Your order', 'woocommerce' ) . '</h3>';
		echo '<div id="order_review">';
		do_action( 'woocommerce_checkout_order_review' );
		do_action( 'wpmc-woocommerce_order_review' );
		echo '</div>';
	}
}



/**
 * The content of the Payment step for the Germanized for WooCommerce plugin
 */
if ( ! function_exists( 'wmsc_step_content_payment_germanized' ) ) {
	function wmsc_step_content_payment_germanized() {
		echo '<h3 id="payment_heading">' . __( 'Choose a Payment Gateway', 'woocommerce-germanized' ) . '</h3>';
		do_action( 'wpmc-woocommerce_checkout_payment' );
	}
}


/**
 * The content of the Order Review step for the Germanized for WooCommerce plugin
 */
if ( ! function_exists( 'wmsc_step_content_review_germanized' ) ) {
	function wmsc_step_content_review_germanized() {
		do_action( 'woocommerce_checkout_before_order_review' );
		echo '<h3 id="order_review_heading">' . __( 'Your order', 'woocommerce' ) . '</h3>';
		do_action( 'wpmc-woocommerce_order_review' );
		if ( function_exists( 'woocommerce_gzd_template_order_submit' ) ) {
			woocommerce_gzd_template_order_submit();
		}
	}
}



/**
 * The content of the Order Review step for the German Market plugin
 */
if ( ! function_exists( 'wmsc_step_content_review_german_market' ) ) {
	function wmsc_step_content_review_german_market() {
		do_action( 'woocommerce_checkout_before_order_review' );
		echo '<h3 id="order_review_heading">' . __( 'Your order', 'woocommerce' ) . '</h3>';
		do_action( 'wpmc-woocommerce_order_review' );
		do_action( 'woocommerce_checkout_order_review' );
	}
}



/**
 * Comparison function for sorting the steps
 */
if ( ! function_exists( 'wpmc_sort_by_position' ) ) {
	function wpmc_sort_by_position( $a, $b ) {
		return $a['position'] - $b['position'];
	}
}



?>
