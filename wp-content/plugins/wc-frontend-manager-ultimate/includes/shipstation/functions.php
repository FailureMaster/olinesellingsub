<?php

/**
 * Get Order data for a vendor
 *
 * @param int   $vendor_id
 * @param array $args
 *
 * @return array
 */
function wcfmu_shipstation_get_orders( $vendor_id, $args = array() ) {
    global $wpdb;

    $current_time = current_time( 'mysql' );

    $defaults = array(
        'count' => false,
        'start_date' => date( 'Y-m-d 00:00:00', strtotime( $current_time ) ),
        'end_date' => $current_time,
        'status' => null,
        'page' => 1,
        'fields' => array( 'wcfm_orders.*', 'p.post_date_gmt' ),
        'limit' => WCFMu_SHIPSTATION_EXPORT_LIMIT * ( $args['page'] - 1 ),
        'offset' => WCFMu_SHIPSTATION_EXPORT_LIMIT,
    );

    $args = wp_parse_args( $args, $defaults );

    $cache_group = 'wcfmu_vendor_data_' . $vendor_id;
    $cache_key   = 'wcfmu-vendor-orders-' . md5( serialize( $args ) ) . '-' . $vendor_id;
    $orders      = wp_cache_get( $cache_key, $cache_group );

    if ( ! $orders ) {
        $select = implode( ', ', $args['fields'] );

        $where = $wpdb->prepare(
            'wcfm_orders.vendor_id = %d AND p.post_status != %s', $vendor_id, 'trash'
        );

        if ( is_array( $args['status'] ) ) {
            $where .= sprintf( " AND order_status IN ('%s')", implode( "', '", $args['status'] ) );
        } else if ( $args['status'] ) {
            $where .= $wpdb->prepare( ' AND order_status = %s', $args['status'] );
        }

        $where .= $wpdb->prepare( ' AND p.post_date_gmt >= %s AND p.post_date_gmt <= %s', $args['start_date'], $args['end_date'] );

        $select = ! $args['count'] ? "SELECT $select" : "SELECT COUNT(p.ID) as count";
        $from = " FROM {$wpdb->prefix}wcfm_marketplace_orders AS wcfm_orders";
        $join = " LEFT JOIN $wpdb->posts p ON wcfm_orders.order_id = p.ID";
        $where = " WHERE $where";

        if ( ! $args['count'] ) {
            $group_by = ' GROUP BY wcfm_orders.order_id';
            $order_by = ' ORDER BY p.post_date_gmt ASC';
            $limit = $wpdb->prepare( ' LIMIT %d, %d', $args['limit'], $args['offset'] );
        } else {
            $group_by = '';
            $order_by = '';
            $limit = '';
        }

        $sql = $select . $from . $join . $where . $group_by . $order_by . $limit;

        $orders = $wpdb->get_results( $sql );

        wp_cache_set( $cache_key, $orders, $cache_group, HOUR_IN_SECONDS * 2 );
        wcfmu_cache_update_group( $cache_key, $cache_group );
    }

    return $orders;
}

/**
 * Keep record of keys by group name
 *
 * @param string $key
 *
 * @param string $group
 *
 * @return void
 */
function wcfmu_cache_update_group( $key, $group ) {
    $keys = get_option( $group, array() );

    if ( in_array( $key, $keys ) ) {
        return;
    }

    $keys[] = $key;
    update_option( $group, $keys );
}

/**
 * get all commission ids from a order by vendor
 *
 * @param int $vendor_id
 *
 * @param int $order_id
 *
 * @return array
 */
function get_order_commission_ids_by_vendor( $vendor_id, $order_id ) {
    global $wpdb;

    $commission_ids = array();

    $sql = $wpdb->prepare( "SELECT `ID` FROM {$wpdb->prefix}wcfm_marketplace_orders WHERE 1=1 AND `vendor_id` = %d AND `order_id` = %d", $vendor_id, $order_id );

    $result = $wpdb->get_results( $sql, ARRAY_A );

    if( ! empty( $result ) ) {
        $commission_ids = wp_list_pluck( $result, 'ID' );
    }

    return $commission_ids;
}

/**
 * Vendor Order - Main Order Status Update
 *
 * @param int $vendor_id
 *
 * @param int $order_id
 *
 * @param string $order_status
 *
 * @return string json_format
 */
function shipstation_update_order_status( $vendor_id, $order_id, $order_status ) {
    global $WCFM;

    if ( wc_is_order_status( $order_status ) && $order_id ) {
        $order = wc_get_order( $order_id );
        $order->update_status( str_replace('wc-', '', $order_status), '', true );

        // Add Order Note for Log
        $vendor_id = apply_filters( 'wcfm_current_vendor_id', $vendor_id );
        $shop_name =  get_user_by( 'ID', $vendor_id )->display_name;
        if( wcfm_is_vendor( $vendor_id ) ) {
            $shop_name =  wcfm_get_vendor_store( absint( $vendor_id ) );
        }
        $wcfm_messages = sprintf( __( 'Order status updated to <b>%s</b> by <b>%s</b>', 'wc-frontend-manager-ultimate' ), wc_get_order_status_name( str_replace('wc-', '', $order_status) ), $shop_name );
        $is_customer_note = apply_filters( 'wcfm_is_allow_order_update_note_for_customer', '1' );

        if( wcfm_is_vendor( $vendor_id ) ) add_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );
        $comment_id = $order->add_order_note( $wcfm_messages, $is_customer_note);
        if( wcfm_is_vendor( $vendor_id ) ) { add_comment_meta( $comment_id, '_vendor_id', $vendor_id ); }
        if( wcfm_is_vendor( $vendor_id ) ) remove_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );

        $wcfm_messages = sprintf( __( '<b>%s</b> order status updated to <b>%s</b> by <b>%s</b>', 'wc-frontend-manager-ultimate' ), '#<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_order_url($order_id) . '">' . $order->get_order_number() . '</a>', wc_get_order_status_name( str_replace('wc-', '', $order_status) ), $shop_name );
        $WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'status-update' );

        do_action( 'woocommerce_order_edit_status', $order_id, str_replace('wc-', '', $order_status) );
        do_action( 'wcfm_order_status_updated', $order_id, str_replace('wc-', '', $order_status) );

        return '{"status": true, "message": "' . __( 'Order status updated.', 'wc-frontend-manager-ultimate' ) . '"}';
    }

    return '{"status": false, "message": "' . __( 'Failed to update Order status.', 'wc-frontend-manager-ultimate' ) . '"}';
}

/**
 * Vendor Order - Commission Status Update
 *
 * @param int $vendor_id
 *
 * @param int $order_id
 *
 * @param string $order_status
 *
 * @return string json_format
 */
function shipstation_vendor_order_status_update( $vendor_id, $order_id, $order_status ) {
    global $WCFM, $WCFMmp, $wpdb;

    $order_status = 'wc-' === substr( $order_status, 0, 3 ) ? $order_status : 'wc-'.$order_status;

    if( !wcfm_is_vendor( $vendor_id ) ) return;

    if( !$order_id ) {
        return '{"status": false, "message": "' . __( 'No Order ID found.', 'wc-frontend-manager-ultimate' ) . '"}';
    }

    if( $order_status == 'wc-refunded' ) {
        return '{"status": false, "message": "' . __( 'This status not allowed, please go through Refund Request.', 'wc-frontend-manager-ultimate' ) . '"}';
    }

    if( $order_status == 'wc-shipped' ) {
        return '{"status": false, "message": "' . __( 'This status not allowed, please go through Shipment Tracking.', 'wc-frontend-manager-ultimate' ) . '"}';
    }

    $wcfmmp_marketplace_options   = wcfm_get_option( 'wcfm_marketplace_options', array() );
    $order_sync  = isset( $wcfmmp_marketplace_options['order_sync'] ) ? $wcfmmp_marketplace_options['order_sync'] : 'no';
    if( $order_sync == 'yes' ) {
        return shipstation_update_order_status( $vendor_id, $order_id, $order_status );
    }

    if( $vendor_id ) {
        $order = wc_get_order( $order_id );
        $status = str_replace('wc-', '', $order_status);
        $wpdb->update("{$wpdb->prefix}wcfm_marketplace_orders", array('commission_status' => $status), array('order_id' => $order_id, 'vendor_id' => $vendor_id), array('%s'), array('%d', '%d') );

        // Withdrawal Threshold check by Order Completed date
        if( apply_filters( 'wcfm_is_allow_withdrwal_check_by_order_complete_date', false ) && ( $status == 'completed' ) ) {
            $wpdb->update( "{$wpdb->prefix}wcfm_marketplace_orders", array( 'created' => date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ) ), array( 'order_id' => $order_id, 'vendor_id' => $vendor_id ), array('%s'), array('%d', '%d') );
        }

        do_action( 'wcfmmp_vendor_order_status_updated', $order_id, $order_status, $vendor_id );

        // Add Order Note for Log
        if( apply_filters( 'wcfmmp_is_allow_sold_by_linked', true ) ) {
            $shop_name = wcfm_get_vendor_store( absint($vendor_id) );
        } else {
            $shop_name = wcfm_get_vendor_store_name( absint($vendor_id) );
        }

        // Fetch Product ID
        $is_all_complete = true;
        if( apply_filters( 'wcfm_is_allow_itemwise_notification', true ) ) {
            $sql = 'SELECT product_id  FROM ' . $wpdb->prefix . 'wcfm_marketplace_orders AS commission';
            $sql .= ' WHERE 1=1';
            $sql .= " AND `order_id` = " . $order_id;
            $sql .= " AND `vendor_id` = " . $vendor_id;
            $commissions = $wpdb->get_results( $sql );
            $product_id = 0;
            if( !empty( $commissions ) ) {
                foreach( $commissions as $commission ) {
                    $product_id = $commission->product_id;

                    $wcfm_messages = sprintf( __( 'Order item <b>%s</b> status updated to <b>%s</b> by <b>%s</b>', 'wc-frontend-manager-ultimate' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_permalink($product_id) . '">' . get_the_title( $product_id ) . '</a>', $WCFMmp->wcfmmp_vendor->wcfmmp_vendor_order_status_name( $order_status ), $shop_name );

                    add_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );
                    $is_customer_note = apply_filters( 'wcfm_is_allow_order_update_note_for_customer', '1' );
                    $comment_id = $order->add_order_note( apply_filters( 'wcfm_order_item_status_update_message', $wcfm_messages, $order_id, $vendor_id, $product_id ), $is_customer_note );
                    add_comment_meta( $comment_id, '_vendor_id', $vendor_id );
                    remove_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );

                    $wcfm_messages = apply_filters( 'wcfm_order_item_status_update_admin_message', sprintf( __( '<b>%s</b> order item <b>%s</b> status updated to <b>%s</b> by <b>%s</b>', 'wc-frontend-manager-ultimate' ), '#<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_order_url($order_id) . '">' . wcfm_get_order_number( $order_id ) . '</a>', '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_permalink($product_id) . '">' . get_the_title( $product_id ) . '</a>', $WCFMmp->wcfmmp_vendor->wcfmmp_vendor_order_status_name( $order_status ), $shop_name ), $order_id, $vendor_id, $product_id );
                    $WCFM->wcfm_notification->wcfm_send_direct_message( $vendor_id, 0, 0, 1, $wcfm_messages, 'status-update' );
                }
            }
        } else {
            $wcfm_messages = sprintf( __( 'Order status updated to <b>%s</b> by <b>%s</b>', 'wc-frontend-manager-ultimate' ), $WCFMmp->wcfmmp_vendor->wcfmmp_vendor_order_status_name( $order_status ), $shop_name );

            add_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );
            $is_customer_note = apply_filters( 'wcfm_is_allow_order_update_note_for_customer', '1' );
            $comment_id = $order->add_order_note( apply_filters( 'wcfm_order_item_status_update_message', $wcfm_messages, $order_id, $vendor_id, 0 ), $is_customer_note);
            add_comment_meta( $comment_id, '_vendor_id', $vendor_id );
            remove_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );

            $wcfm_messages = apply_filters( 'wcfm_order_item_status_update_admin_message', sprintf( __( '<b>%s</b> order status updated to <b>%s</b> by <b>%s</b>', 'wc-frontend-manager-ultimate' ), '#<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_order_url($order_id) . '">' . $order->get_order_number() . '</a>', $WCFMmp->wcfmmp_vendor->wcfmmp_vendor_order_status_name( $order_status ), $shop_name ), $order_id, $vendor_id, 0 );
            $WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'status-update' );
        }

        // Update Main Order status on all Commission Order Status Update
        if( in_array( $status, apply_filters( 'wcfm_change_main_order_on_child_order_statuses', array( 'completed', 'processing' ) ) ) && apply_filters( 'wcfm_is_allow_mark_complete_main_order_on_all_child_order_complete', true ) ) {
            if ( wc_is_order_status( 'wc-'.$status ) && $order_id ) {

                // Check is all vendor orders completed or not
                $is_all_complete = true;
                $sql = 'SELECT commission_status  FROM ' . $wpdb->prefix . 'wcfm_marketplace_orders AS commission';
                $sql .= ' WHERE 1=1';
                $sql .= " AND `order_id` = " . $order_id;
                $commissions = $wpdb->get_results( $sql );
                if( !empty( $commissions ) ) {
                    foreach( $commissions as $commission ) {
                        if( $commission->commission_status != $status ) {
                            $is_all_complete = false;
                        }
                    }
                }

                if( $is_all_complete ) {
                    $order->update_status( $status, '', true );

                    // Add Order Note for Log
                    $wcfm_messages = sprintf( __( '<b>%s</b> order status updated to <b>%s</b>', 'wc-frontend-manager-ultimate' ), '#' . $order->get_order_number(), wc_get_order_status_name( $status ) );
                    $is_customer_note = apply_filters( 'wcfm_is_allow_order_update_note_for_customer', '1' );

                    $comment_id = $order->add_order_note( $wcfm_messages, $is_customer_note );

                    $WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'status-update' );

                    do_action( 'woocommerce_order_edit_status', $order_id, $status );
                    do_action( 'wcfm_order_status_updated', $order_id, $status );
                }
            }
        }

        return '{"status": true, "message": "' . __( 'Order status updated.', 'wc-frontend-manager-ultimate' ) . '"}';
    }
}
