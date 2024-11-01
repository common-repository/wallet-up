<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'zelle', 'wallet_up_front_end_zelle' );

function wallet_up_front_end_zelle( $atts ) {
    $wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
    $zelle_holder = isset( $wallet_up_transfer['zelle_holder'] ) ? $wallet_up_transfer['zelle_holder'] : '';
    $walup_pagebox = isset( $wallet_up_transfer['walup_front_box_display'] ) ? '' : 'display: inline-block;';
    $walup_pay_yes = isset( $wallet_up_transfer['walup_front_box_text'] ) && $wallet_up_transfer['walup_front_box_text'] !== '' ? $wallet_up_transfer['walup_front_box_text'] . '<br>' : 'Please Pay';
    $walup_pay_no = 'Click & Pay';

    $atts = shortcode_atts( array(
        'scan' => 'yes',
        'amount' => '',
        'bank' => '',
    ), $atts );

    $scan = $atts['scan'];
    $amount = $atts['amount'];

    if ( $scan == 'yes' ) {
        $csrc = esc_url( WALLET_UP_CHART_API ) . "https://enroll.zellepay.com/?data=". esc_attr( wp_kses_post( $zelle_holder ) );
        $walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_zelle_yes = sprintf(
            '<p style="padding:  10px  10px  10px  0; max-width:  280px; text-align: center; %s">%s<strong>%s</strong> to <br><strong>%s</strong><a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="Zelle" src="%s" ></a></p>',
            $walup_pagebox,
            $walup_pay_yes,
            $walup_pay,
            $zelle_holder,
            esc_url( "https://enroll.zellepay.com/?data=" . esc_attr( wp_kses_post( $zelle_holder ) ) ),
            $csrc
        );

        return $url_zelle_yes;
    } elseif ( $scan == 'no' ) {
        $imgsrc = esc_url( WALLET_UP_BASE_URL . 'assets/lib/zelle-walup.png' );
        $walup_pay = esc_attr( wp_kses_post( ( $amount !== '' ? ' $' : '' ) . $amount ) );
        $url_zelle_no = sprintf(
            '<p style="padding:  10px  10px  10px  0; max-width:  180px; text-align: center; %s">%s<strong>%s</strong> to <br><strong>%s</strong><a href="%s" target="_blank" rel="noopener"><img style="float: none!important; max-height:180px!important; max-width:180px!important;" alt="Zelle" src="%s" ></a></p>',
            $walup_pagebox,
            $walup_pay_no,
            $walup_pay,
            $zelle_holder,
            esc_url( "https://enroll.zellepay.com/?data=" . esc_attr( wp_kses_post( $zelle_holder ) ) ),
            $imgsrc
        );

        return $url_zelle_no;
    }
}

include_once WALLET_UP_BASE_DIR . 'vendor/google/chart.php';
