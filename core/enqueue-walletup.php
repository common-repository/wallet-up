<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Wallet_Up {
    private $wallet_up_transfer;

    function __construct() {
        add_action( 'admin_menu', array( $this, 'wallet_up_menu' ),  9 );
        add_action( 'admin_init', array( $this, 'wallet_up_page_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'wallet_up_admin_css' ) );
    }

    function wallet_up_admin_css() {
        $wallet_up_screen = get_current_screen();
        if ( $wallet_up_screen->id === 'toplevel_page_wallet-up' ) {
            $this->enqueue_styles(array(
                'walup-tabs' => WALUP_ADMIN_TAB_CSS,
                'wallet-up-css' => WALUP_ADMIN_MIN_CSS,
                'walup-font-awesome' => WALUP_FONTAWESOME_CSS
            ));
        }
    }

    private function enqueue_styles(array $styles) {
        foreach ($styles as $handle => $src) {
            wp_register_style($handle, $src);
            wp_enqueue_style($handle);
        }
    }

    function wallet_up_menu() {
        $main_slug = 'wallet-up';
        $capability = current_user_can(WALUP_ADMIN_CAP);

        if ($capability) {
            add_menu_page(null, 'Wallet Up', $capability, $main_slug, array($this, 'wallet_up_settings_page'), 'dashicons-bank',  20);
        }
    }

    function wallet_up_settings_page() {
        require_once WALLET_UP_BASE_DIR . 'core/dash/settings.php';
    }

    function wallet_up_page_init() {
        register_setting(
            'wallet_up_transfer_settings',
            'wallet_up_transfer_call',
            array($this, 'wallet_up_sanitize')
        );

				/*
				 * Section Profile
				 */
				add_settings_section(
					'wallet_up_profile_section', // id
					'Your Profile', // title
					array( $this, 'wallet_up_section_profile' ), // callback
					'wallet-up-admin' // page
				);
				add_settings_field(
					'holder_owner', // id
					'Full Name', // title
					array( $this, 'wallet_up_holder_owner_callback' ), // callback
					'wallet-up-admin', // page
					'wallet_up_profile_section' // section
				);
				add_settings_field(
					'holder_email', // id
					'Email', // title
					array( $this, 'wallet_up_holder_email_callback' ), // callback
					'wallet-up-admin', // page
					'wallet_up_profile_section' // section
				);
				add_settings_field(
					'holder_no', // id
					'Phone Number', // title
					array( $this, 'wallet_up_holder_no_callback' ), // callback
					'wallet-up-admin', // page
					'wallet_up_profile_section' // section
				);
				/*
				 * Section Account(s) infos
				 */
				add_settings_section(
					'wallet_up_required_info_section', // id
					'Your Account(s)', // title
					array( $this, 'wallet_up_section_info' ), // callback
					'wallet-up-admin' // page
				);
				add_settings_field(
					'fbpay_holder', // id https://www.facebook.com/walupllc
					'Type your FaceBook Pay username or ID (Example: <a href="https://m.me/pay/Walupllc" target="_blank" rel="noopener">Walupllc or 100082575054653</a>)', // title
					array( $this, 'wallet_up_fbpay_holder_callback' ), // callback
					'wallet-up-admin', // page
					'wallet_up_required_info_section' // section
				);
				add_settings_field(
					'paypal_holder', // id
					'Type your PayPal.me username (Example: <a href="https://www.paypal.com/paypalme/WalletUp/1" target="_blank" rel="noopener">WalletUp</a>)', // title
					array( $this, 'wallet_up_paypal_holder_callback' ), // callback
					'wallet-up-admin', // page
					'wallet_up_required_info_section' // section
				);
				add_settings_field(
					'cashapp_holder', // id
					'Type your $cashtag (Example: <a href="https://cash.app/WalletUpLLC/1" target="_blank" rel="noopener">$WalletUpLLC</a>)', // title
					array( $this, 'wallet_up_cashapp_holder_callback' ), // callback
					'wallet-up-admin', // page
					'wallet_up_required_info_section' // section
				);
				add_settings_field(
					'venmo_holder', // id
					'Type your venmo username (Example:<a href="https://venmo.com/WalletUp?txn=pay&amount=1&note=Demo%20Only" target="_blank" rel="noopener">WalletUp</a>)', // title
					array( $this, 'wallet_up_venmo_holder_callback' ), // callback
					'wallet-up-admin', // page
					'wallet_up_required_info_section' // section
				);
				add_settings_field(
					'zelle_holder', // id
					'Type your Zelle username <br>(It is typically your phone number or email Example:<a href="https://enroll.zellepay.com/?data=walup@walletup.app" target="_blank" rel="noopener">walup@walletup.app</a>)', // title
					array( $this, 'wallet_up_zelle_holder_callback' ), // callback
					'wallet-up-admin', // page
					'wallet_up_required_info_section' // section
				);
    }

    function wallet_up_sanitize($input) {
        // Sanitize all input fields
        foreach ($input as $key => $value) {
            $input[$key] = sanitize_text_field($value);
        }
        return $input;
    }

		/*
		 * Sections callback functions
		 */
		function wallet_up_section_profile() {
			echo __( '', 'wallet-up' );
		}

		function wallet_up_section_info() {
			echo __( '', 'wallet-up' );
		}

		/*
		 * Fields callback functions
		 */
		function wallet_up_holder_owner_callback() {
			printf(
				'<input class="walup-text" type="text" name="wallet_up_transfer_call[holder_owner]" id="holder_owner" value="%s">',
				isset( $this->wallet_up_transfer[ 'holder_owner' ] ) ? esc_attr( $this->wallet_up_transfer[ 'holder_owner' ] ) : ''
			);
		}

		function wallet_up_holder_email_callback() {
			printf(
				'<input class="walup-text" type="text" name="wallet_up_transfer_call[holder_email]" id="holder_email" value="%s">',
				isset( $this->wallet_up_transfer[ 'holder_email' ] ) ? esc_attr( $this->wallet_up_transfer[ 'holder_email' ] ) : ''
			);
		}

		function wallet_up_holder_no_callback() {
			printf(
				'<input class="walup-text" type="text" name="wallet_up_transfer_call[holder_no]" id="holder_no" value="%s">',
				isset( $this->wallet_up_transfer[ 'holder_no' ] ) ? esc_attr( $this->wallet_up_transfer[ 'holder_no' ] ) : ''
			);
		}

		function wallet_up_fbpay_holder_callback() {
			$wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
			if ( isset( $wallet_up_transfer[ 'fbpay_holder' ] ) ) {
				$walup_try_it = '<a class="link-primary" href="https://m.me/pay/' . $wallet_up_transfer[ 'fbpay_holder' ] . '" target="_blank" rel="noopener"><br><form><input type="button" value="See how it works"></form></a>';
			} else {
				$walup_try_it = null;
			}
			printf(
				'<input class="walup-text" type="text" name="wallet_up_transfer_call[fbpay_holder]" id="fbpay_holder" value="%s"> ' . $walup_try_it,
				isset( $this->wallet_up_transfer[ 'fbpay_holder' ] ) ? esc_attr( $this->wallet_up_transfer[ 'fbpay_holder' ] ) : ''
			);
		}

			function wallet_up_paypal_holder_callback() {
				$wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
				if ( isset( $wallet_up_transfer[ 'paypal_holder' ] ) ) {
					$walup_try_it = '<a class="link-primary" href="https://www.paypal.com/paypalme/' . $wallet_up_transfer[ 'paypal_holder' ] . "/" . 1 . '" target="_blank" rel="noopener"><br><form><input type="button" value="Click here to try it"></form></a>';
				} else {
					$walup_try_it = null;
				}
				printf(
					'<input class="walup-text" type="text" name="wallet_up_transfer_call[paypal_holder]" id="paypal_holder" value="%s">' . $walup_try_it,
					isset( $this->wallet_up_transfer[ 'paypal_holder' ] ) ? esc_attr( $this->wallet_up_transfer[ 'paypal_holder' ] ) : ''
				);
			}

		function wallet_up_cashapp_holder_callback() {
			$wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
			if ( isset( $wallet_up_transfer[ 'cashapp_holder' ] ) ) {
				$walup_try_it = '<a class="link-primary" href="https://cash.app/' . $wallet_up_transfer[ 'cashapp_holder' ] . "/" . 1 . '" target="_blank" rel="noopener"><br><form><input type="button" value="Click here to try it"></form></a>';
			} else {
				$walup_try_it = null;
			}
			printf(
				'<input class="walup-text" type="text" name="wallet_up_transfer_call[cashapp_holder]" id="cashapp_holder" value="%s"> ' . $walup_try_it,
				isset( $this->wallet_up_transfer[ 'cashapp_holder' ] ) ? esc_attr( $this->wallet_up_transfer[ 'cashapp_holder' ] ) : ''
			);
		}

		function wallet_up_venmo_holder_callback() {
			$wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
			if ( isset( $wallet_up_transfer[ 'venmo_holder' ] ) ) {
				$walup_try_it = '<a class="link-primary" href="https://venmo.com/' . esc_attr( wp_kses_post( $wallet_up_transfer[ 'venmo_holder' ] ) ) . '?txn=pay&amount=1&note=Wallet Up Demo" target="_blank" rel="noopener"><br><form><input type="button" value="Click here to try it"></form></a>';
			} else {
				$walup_try_it = null;
			}
			printf(
				'<input class="walup-text" type="text" name="wallet_up_transfer_call[venmo_holder]" id="venmo_holder" value="%s">' . $walup_try_it,
				isset( $this->wallet_up_transfer[ 'venmo_holder' ] ) ? esc_attr( $this->wallet_up_transfer[ 'venmo_holder' ] ) : ''
			);
		}

		function wallet_up_zelle_holder_callback() {
			$wallet_up_transfer = get_option( 'wallet_up_transfer_call' );
			if ( isset( $wallet_up_transfer[ 'zelle_holder' ] ) ) {
				$walup_try_it = '<a class="link-primary" href="https://enroll.zellepay.com/?data=' . $wallet_up_transfer[ 'zelle_holder' ] . '" target="_blank" rel="noopener"><br><form><input type="button" value="See how it works"></form></a>';
			} else {
				$walup_try_it = null;
			}
			printf(
				'<input class="walup-text" type="text" name="wallet_up_transfer_call[zelle_holder]" id="zelle_holder" value="%s"> ' . $walup_try_it,
				isset( $this->wallet_up_transfer[ 'zelle_holder' ] ) ? esc_attr( $this->wallet_up_transfer[ 'zelle_holder' ] ) : ''
			);
		}

}

if (is_admin()) {
    $wallet_up = new Wallet_Up();
}

require_once WALLET_UP_BASE_DIR . 'core/shortcodes.php';

?>
