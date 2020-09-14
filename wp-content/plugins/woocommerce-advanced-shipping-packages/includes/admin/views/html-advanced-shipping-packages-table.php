<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Conditions table.
 *
 * Display table with all the user configured shipping packages.
 *
 * @author		Jeroen Sormani
 * @package 	Advanced Shipping Packages for WooCommerce
 * @version		1.0.0
 */

$shipping_packages = get_posts( array( 'posts_per_page' => '-1', 'post_type' => 'shipping_package', 'post_status' => array( 'draft', 'publish' ), 'orderby' => 'menu_order', 'order' => 'ASC' ) );

?><tr valign="top">
	<th scope="row" class="titledesc"><?php
		_e( 'Shipping packages', 'advanced-shipping-packages-for-woocommerce' ); ?>:<br />
	</th>
	<td class="forminp" id="advanced-shipping-packages-table">

		<p>
			<a href="javascript:void(0);" class="aspwc-quickhelp"><?php _e( 'Quick tips', 'advanced-shipping-packages-for-woocommerce' ); ?> <span class="dashicons dashicons-arrow-right-alt2"></span></a>
		</p>
		<div class="description hidden">
			<ul><em>
				<li>- <?php _e( 'Each package you setup below will attempt to split the cart when it matches the setup conditions', 'advanced-shipping-packages-for-woocommerce' ); ?></li>
				<li>- <?php _e( 'Products are only split from the original package, not from other split packages', 'advanced-shipping-packages-for-woocommerce' ); ?></li>
				<li>- <?php _e( 'All products that match the \'Product conditions\' will be split and grouped into a package', 'advanced-shipping-packages-for-woocommerce' ); ?></li>
				<li>- <?php _e( 'Each package will be shown separately in the cart/checkout with its own shipping rates/cost', 'advanced-shipping-packages-for-woocommerce' ); ?></li>
				<li>- <?php _e( 'Packages are processed in sorted order', 'advanced-shipping-packages-for-woocommerce' ); ?></li>
			</em></ul>
		</div>

		<table class='wp-list-table wpc-conditions-post-table wpc-sortable-post-table widefat'>
			<thead>
				<tr>
					<th style='width: 17px;' class="column-cb check-column"></th>
					<th style='padding-left: 10px;' class="column-primary"><?php _e( 'Title', 'advanced-shipping-packages-for-woocommerce' ); ?></th>
					<th style='padding-left: 10px;'><?php _e( 'Package name', 'advanced-shipping-packages-for-woocommerce' ); ?></th>
					<th style='width: 70px;'><?php _e( '# Groups', 'advanced-shipping-packages-for-woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody><?php

				$i = 0;
				foreach ( $shipping_packages as $shipping_package ) :
					$conditions = get_post_meta( $shipping_package->ID, '_conditions', true );
					$name       = get_post_meta( $shipping_package->ID, '_name', true );

					$alt = ( $i++ ) % 2 == 0 ? 'alternate' : '';
					?><tr class='<?php echo $alt; ?>'>

						<th class='sort check-column'>
							<input type='hidden' name='sort[]' value='<?php echo absint( $shipping_package->ID ); ?>' />
						</th>
						<td class="column-primary">
							<strong>
								<a href='<?php echo get_edit_post_link( $shipping_package->ID ); ?>' class='row-title' title='<?php _e( 'Edit shipping option', 'advanced-shipping-packages-for-woocommerce' ); ?>'><?php
									echo _draft_or_post_title( $shipping_package->ID );
								?></a><?php
								_post_states( $shipping_package );
							?></strong>
							<div class='row-actions'>
								<span class='edit'>
									<a href='<?php echo get_edit_post_link( $shipping_package->ID ); ?>' title='<?php _e( 'Edit shipping option', 'advanced-shipping-packages-for-woocommerce' ); ?>'>
										<?php _e( 'Edit', 'advanced-shipping-packages-for-woocommerce' ); ?>
									</a>
									|
								</span>
								<span class='trash'>
									<a href='<?php echo get_delete_post_link( $shipping_package->ID ); ?>' title='<?php _e( 'Delete Extra shipping option', 'advanced-shipping-packages-for-woocommerce' ); ?>'>
										<?php _e( 'Delete', 'advanced-shipping-packages-for-woocommerce' ); ?>
									</a>
								</span>
							</div>
						</td>
						<td><?php echo wp_kses_post( $name ); ?></td>
						<td><?php echo count( $conditions ); ?></td>
					</tr><?php

				endforeach;

				if ( empty( $shipping_packages ) ) :

					?><tr>
						<td colspan='4' style="display: table-cell;"><?php _e( 'There are no Advanced Shipping Packages. Yet...', 'advanced-shipping-packages-for-woocommerce' ); ?></td>
					</tr><?php

				endif;

			?></tbody>
			<tfoot>
				<tr>
					<th colspan='5' style='padding-left: 10px; display: table-cell;'>
						<a href='<?php echo admin_url( 'post-new.php?post_type=shipping_package' ); ?>' class='add button'><?php _e( 'Add shipping package', 'advanced-shipping-packages-for-woocommerce' ); ?></a>
					</th>
				</tr>
			</tfoot>
		</table>
	</td>
</tr>
