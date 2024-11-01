<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cashapp', 'wallet_up_front_end_cashapp' );

function wallet_up_front_end_cashapp( $atts ) {
    $wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
    $cashapp_holder = isset( $wallet_up_transfer['cashapp_holder'] ) ? $wallet_up_transfer['cashapp_holder'] : '';
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
        $csrc = esc_url( WALLET_UP_CHART_API ) . "https://cash.app" . "/" . esc_attr( wp_kses_post( $cashapp_holder ) ) . "/" . esc_attr( wp_kses_post( $amount ) );
        $walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_cashapp_yes = sprintf(
            '<p style="padding:   10px   10px   10px   0; max-width:   280px; text-align: center; %s">%s<strong>%s</strong> with Cashapp<a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="Cashapp" src="%s" ></a></p>',
            $walup_pagebox,
            $walup_pay_yes,
            $walup_pay,
            esc_url( "https://cash.app/" . esc_attr( wp_kses_post( $cashapp_holder ) ) . "/" . esc_attr( wp_kses_post( $amount ) ) ),
            $csrc
        );

        return $url_cashapp_yes;
    } elseif ( $scan == 'no' ) {
        $imgsrc = esc_url( WALLET_UP_BASE_URL . 'assets/lib/cashapp-walup.png' );
        $walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_cashapp_no = sprintf(
            '<p style="padding:   10px   10px   10px   0; max-width:   180px; text-align: center; %s">%s<strong>%s</strong> with Cashapp<a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="Cashapp" src="%s" ></a></p>',
            $walup_pagebox,
            $walup_pay_no,
            $walup_pay,
            esc_url( "https://cash.app/" . esc_attr( wp_kses_post( $cashapp_holder ) ) . "/" . esc_attr( wp_kses_post( $amount ) ) ),
            $imgsrc
        );

        return $url_cashapp_no;
    }
}

include_once WALLET_UP_BASE_DIR . 'vendor/google/chart.php';
