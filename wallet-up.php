<?php
/**
 * Plugin Name: Wallet Up
 * Description: The Wallet Up "Virtual Wallet" has it all: Easily Integrate Zelle, Facebook pay, Cash App, Venmo, PayPal with a generated QR Code anywhere on your Online Web Site. Get started now!
 * Plugin URI: https://walletup.app/wallet-up
 * Author: WalletUp.com
 * Version: 3.4.1
 * Author URI: https://walletup.app/
 *
 * Text Domain: walletup
 *
 * @package Wallet Up
 * @category Core
 *
 * Wallet Up is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Wallet Up is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// Check if Freemius SDK is already initialized
if ( ! function_exists( 'wallet_up_fs' ) ) {
    // Initialize Freemius SDK
    function wallet_up_fs() {
        global $wallet_up_fs;

        if ( ! isset( $wallet_up_fs ) ) {
            // Include Freemius SDK
            require_once dirname(__FILE__) . '/freemius/start.php';

            $wallet_up_fs = fs_dynamic_init( array(
                'id'                  => '12319',
                'slug'                => 'wallet-up',
                'type'                => 'plugin',
                'public_key'          => 'pk_d19cef6c52dca0f60e60df34f248e',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'wallet-up',
                    'first-path'     => 'admin.php?page=wallet-up',
                    'account'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $wallet_up_fs;
    }

    // Call the Freemius initialization function
    wallet_up_fs();
    // Signal that SDK was initiated
    do_action( 'wallet_up_fs_loaded' );
}

// Include necessary WordPress core files
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
include_once( ABSPATH . 'wp-includes/pluggable.php' );
include_once( ABSPATH . 'wp-includes/option.php' );

// Add filters for shortcode support in widgets
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );

// Define plugin paths
define( 'WALLET_UP_VERSION', '3.4.1' );

define( 'WALLET_UP_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'WALLET_UP_BASENAME', plugin_basename( __FILE__ ) );
define( 'WALLET_UP_BASE_URL', plugins_url( '/', __FILE__ ) );

// Define plugin admin access capability
if ( !defined( 'WALUP_ADMIN_CAP' ) )
	define( 'WALUP_ADMIN_CAP', 'manage_options' );

// Define plugin menu access capability
if ( !defined( 'WALUP_MENU_ACCESS_CAP' ) )
	define( 'WALUP_MENU_ACCESS_CAP', 'manage_options' );

// Define paths to CSS files
if(!defined('WALUP_ADMIN_MIN_CSS'))
  define('WALUP_ADMIN_MIN_CSS', WALLET_UP_BASE_URL . 'assets/css/walletup.min.css');

if(!defined('WALUP_ADMIN_TAB_CSS'))
  define('WALUP_ADMIN_TAB_CSS', WALLET_UP_BASE_URL . 'assets/css/walletup.tabs.css');

if(!defined('WALUP_FONTAWESOME_CSS'))
  define('WALUP_FONTAWESOME_CSS', WALLET_UP_BASE_URL . 'assets/css/walletup.fontawesome.css');

// Redirect to settings page after activation
if ( current_user_can( WALUP_ADMIN_CAP ) ) {
	add_action( 'activated_plugin', function ( $plugin ) {
		if ( $plugin == WALLET_UP_BASENAME ) {
			exit( wp_redirect( admin_url( 'admin.php?page=wallet-up', __FILE__ ) ) );
		}
	} );

	// Include settings link file
	include_once WALLET_UP_BASE_DIR . 'core/dash/settings-link.php';
}

// Enqueue scripts and styles
require_once WALLET_UP_BASE_DIR . 'core/enqueue-walletup.php';

?>
