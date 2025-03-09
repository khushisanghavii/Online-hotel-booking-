<div class="cs-room-order-wrapper">
    <div class="cs-room-order-date">
        <strong><?php esc_html_e( 'Date: ', 'loftocean' ); ?></strong>
        <?php echo esc_html( date( __( 'Y-m-d', 'loftocean' ), $room_order_item_data[ 'check_in' ] ) ); ?> - <?php echo esc_html( date( __( 'Y-m-d', 'loftocean' ), $room_order_item_data[ 'check_out' ] ) ); ?>
    </div>
    <div class="cs-room-order-details">
        <?php $has_children = $room_order_item_data[ 'child_number' ] > 0; ?>
        <strong><?php esc_html_e( 'Details: ', 'loftocean' ); ?></strong>
        <?php esc_html_e( 'Rooms: ', 'cozystay' ); echo esc_html( $room_order_item_data[ 'room_num_search' ] ); ?>,
        <?php esc_html_e( 'Adults: ', 'cozystay' ); echo esc_html( $room_order_item_data[ 'adult_number' ] ); echo $has_children ? ', ' : ''; ?>
        <?php if ( $has_children ) { esc_html_e( 'Children: ', 'cozystay' ); echo esc_html( $room_order_item_data[ 'child_number' ] ); } ?>
    </div><?php
    if ( isset( $room_order_item_data[ 'extra_services' ], $room_order_item_data[ 'extra_services' ][ 'services' ] ) && \LoftOcean\is_valid_array( $room_order_item_data[ 'extra_services' ][ 'services' ] ) ) : ?>
        <div class="cs-room-order-extra">
            <strong><?php esc_html_e( 'Extra Services: ', 'loftocean' ); ?></strong><?php
            $room_order_extra_services = $room_order_item_data[ 'extra_services' ];
            $titles = $room_order_extra_services[ 'titles' ];
            $prices = $room_order_extra_services[ 'prices' ];
            $method = $room_order_extra_services[ 'method' ];
            $label = $room_order_extra_services[ 'label' ];
            $unit = $room_order_extra_services[ 'unit' ];
            $quantity = $room_order_extra_services[ 'quantity' ];
            $loop_index = 0;
            foreach ( $room_order_extra_services[ 'services' ] as $index => $service_id ) {
                echo ( $loop_index > 0 ) ? ', ' : '';
                echo $titles[ $index ] . ' (';
                echo $label[ $index ];
                echo ( 'custom' == $method[ $index ] ) ? ' x ' . $quantity[ $index ] : '';
                echo ')';
                $loop_index ++;
            } ?>
        </div><?php
    endif; ?>
</div>
