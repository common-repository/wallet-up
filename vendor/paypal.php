<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'paypal', 'wallet_up_front_end_paypal' );

function wallet_up_front_end_paypal( $atts ) {
    $wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
    $paypal_holder = isset( $wallet_up_transfer['paypal_holder'] ) ? $wallet_up_transfer['paypal_holder'] : '';
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
        $psrc = esc_url( WALLET_UP_CHART_API ) . "https://www.paypal.com/paypalme" . "/" . esc_attr( wp_kses_post( $paypal_holder ) ) . "/" . esc_attr( wp_kses_post( $amount ) );
        $walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_paypal_yes = sprintf(
            '<p style="padding:  10px  10px  10px  0; max-width:  280px; text-align: center; %s">%s<strong>%s</strong> with Paypal<a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="Paypal" src="%s"></a></p>',
            $walup_pagebox,
            $walup_pay_yes,
            $walup_pay,
            esc_url( "https://www.paypal.com/paypalme/" . esc_attr( wp_kses_post( $paypal_holder ) ) . "/" . esc_attr( wp_kses_post( $amount ) ) ),
            $psrc
        );

        return $url_paypal_yes;
    } elseif ( $scan == 'no' ) {
        $imgsrc = esc_url( WALLET_UP_BASE_URL . 'assets/lib/paypal-walup.png' );
        $walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_paypal_no = sprintf(
            '<p style="padding:  10px  10px  10px  0; max-width:  180px; text-align: center; %s">%s<strong>%s</strong> with Paypal<a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="Paypal" src="%s"></a></p>',
            $walup_pagebox,
            $walup_pay_no,
            $walup_pay,
            esc_url( "https://www.paypal.com/paypalme/" . esc_attr( wp_kses_post( $paypal_holder ) ) . "/" . esc_attr( wp_kses_post( $amount ) ) ),
            $imgsrc
        );

        return $url_paypal_no;
    }
}

include_once WALLET_UP_BASE_DIR . 'vendor/google/chart.php';
