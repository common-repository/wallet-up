<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'venmo', 'wallet_up_front_end_venmo' );

function wallet_up_front_end_venmo( $atts ) {
    $wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
    $venmo_holder = isset( $wallet_up_transfer['venmo_holder'] ) ? $wallet_up_transfer['venmo_holder'] : '';
    $walup_pagebox = isset( $wallet_up_transfer['walup_front_box_display'] ) ? '' : 'display: inline-block;';
    $walup_pay_yes = isset( $wallet_up_transfer['walup_front_box_text'] ) && $wallet_up_transfer['walup_front_box_text'] !== '' ? $wallet_up_transfer['walup_front_box_text'] . '<br>' : 'Please <strong>Scan or Click</strong><br>to Pay';
    $walup_pay_no = 'Please <strong>Click</strong><br>to Pay';

    $atts = shortcode_atts( array(
        'scan' => 'yes',
        'amount' => '',
        'note' => '',
    ), $atts );

    $scan = $atts['scan'];
    $amount = $atts['amount'];
    $note = $atts['note'];

    if ( $scan == 'yes' ) {
        $vsrc = esc_url( WALLET_UP_CHART_API ) . "https://venmo.com" . "/" . esc_attr( wp_kses_post( $venmo_holder ) ) . urlencode( esc_attr( wp_kses_post( "?txn=pay&amount=" . $amount . '&note=' . $note ) ) );
				$walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_venmo_yes = sprintf(
            '<p style="padding:  10px  10px  10px  0; max-width:  180px; text-align: center; %s">%s<strong>%s</strong> with Venmo<a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="Venmo" src="%s" ></a></p>',
            $walup_pagebox,
            $walup_pay_yes,
            $walup_pay,
            esc_url( "https://venmo.com/" . esc_attr( wp_kses_post( $venmo_holder ) ) . "?txn=pay&amount=" . esc_attr( wp_kses_post( $amount ) ) . "&note=" . esc_attr( wp_kses_post( $note ) ) ),
            $vsrc
        );

        return $url_venmo_yes;
    } elseif ( $scan == 'no' ) {
        $imgsrc = esc_url( WALLET_UP_BASE_URL . 'assets/lib/venmo-walup.png' );
				$walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_venmo_no = sprintf(
            '<p style="padding:  10px  10px  10px  0; max-width:  180px; text-align: center; %s">%s<strong>%s</strong> with Venmo<a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="Venmo" src="%s" ></a></p>',
            $walup_pagebox,
            $walup_pay_no,
            $walup_pay,
            esc_url( "https://venmo.com/" . esc_attr( wp_kses_post( $venmo_holder ) ) . "?txn=pay&amount=" . esc_attr( wp_kses_post( $amount ) ) . "&note=" . esc_attr( wp_kses_post( $note ) ) ),
            $imgsrc
        );

        return $url_venmo_no;
    }
}

include_once WALLET_UP_BASE_DIR . 'vendor/google/chart.php';
