<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
$condition_groups = get_post_meta( $post->ID, '_conditions', true );

?><div class='wpc-conditions wpc-conditions-meta-box'>
	<div class='wpc-condition-groups'>

		<p>
			<strong><?php _e( 'Match one of the conditions groups to create a new package', 'advanced-shipping-packages-for-woocommerce' ); ?></strong><?php
			echo wc_help_tip( __( 'The order will only attempt to split into packages when one of the condition groups below is matched.', 'advanced-shipping-packages-for-woocommerce' ) );
		?></p><?php

		if ( ! empty( $condition_groups ) ) :

			foreach ( $condition_groups as $condition_group => $conditions ) :
				include 'html-condition-group.php';
			endforeach;

		else :

			$condition_group = '0';
			include 'html-condition-group.php';

		endif;

	?></div>

	<div class='wpc-condition-group-template hidden' style='display: none'><?php
		$condition_group = '9999';
		$conditions      = array();
		include 'html-condition-group.php';
	?></div>
	<a class='button wpc-condition-group-add' href='javascript:void(0);'><?php _e( 'Add \'Or\' group', 'woocommerce-advanced-messages' ); ?></a>
</div>