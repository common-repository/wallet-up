<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'fbpay', 'wallet_up_front_end_fbpay' );

function wallet_up_front_end_fbpay( $atts ) {
    $wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
    $fbpay_holder = isset( $wallet_up_transfer['fbpay_holder'] ) ? $wallet_up_transfer['fbpay_holder'] : '';
    $walup_pagebox = isset( $wallet_up_transfer['walup_front_box_display'] ) ? '' : 'display: inline-block;';
    $walup_pay_yes = isset( $wallet_up_transfer['walup_front_box_text'] ) && $wallet_up_transfer['walup_front_box_text'] !== '' ? $wallet_up_transfer['walup_front_box_text'] . '<br>' : 'Please <strong>Scan or Click</strong><br>to Pay';
    $walup_pay_no = 'Please <strong>Click</strong><br>to Pay';

    $atts = shortcode_atts( array(
        'scan' => 'yes',
        'amount' => '',
    ), $atts );

    $scan = $atts['scan'];
    $amount = $atts['amount'];

    if ( $scan == 'yes' ) {
        $csrc = esc_url( WALLET_UP_CHART_API ) . "https://m.me/pay/" . esc_attr( wp_kses_post( $fbpay_holder ) );
        $walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_fbpay_yes = sprintf(
            '<p style="padding:  10px  10px  10px  0; max-width:  280px; text-align: center; %s">%s<strong>%s</strong> with FB Pay<a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="FaceBook Pay" src="%s"></a></p>',
            $walup_pagebox,
            $walup_pay_yes,
            $walup_pay,
            esc_url( "https://m.me/pay/" . esc_attr( wp_kses_post( $fbpay_holder ) ) ),
            $csrc
        );

        return $url_fbpay_yes;
    } elseif ( $scan == 'no' ) {
        $imgsrc = esc_url( WALLET_UP_BASE_URL . 'assets/lib/fbpay-walup.png' );
        $walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_fbpay_no = sprintf(
            '<p style="padding:  10px  10px  10px  0; max-width:  180px; text-align: center; %s">%s<strong>%s</strong> with FB Pay<a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="FaceBook Pay" src="%s"></a></p>',
            $walup_pagebox,
            $walup_pay_no,
            $walup_pay,
            esc_url( "https://m.me/pay/" . esc_attr( wp_kses_post( $fbpay_holder ) ) ),
            $imgsrc
        );

        return $url_fbpay_no;
    }
}

include_once WALLET_UP_BASE_DIR . 'vendor/google/chart.php';
