<?php
/**
 * Plugin Name: Floting Mini Cart
 * Description: A tutorial plugin for Woocommerce Floting Mini Cart
 * Plugin URI: #
 * Author: Sujan Miya
 * Author URI: #
 * Version: 1.0
 * License: GPL2 or later
 * Text Domain : minicartt
 * Domain Path :/languages
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The main plugin class
 */
final class Wooco_Floting_Mini_Cart {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();
        	/**
				 * Check if WooCommerce is active
				 **/
				if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

                    register_activation_hook( __FILE__, [ $this, 'activate' ] );
                    add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
                    add_action( 'admin_menu', [ $this, 'floatin_mini_cart_menu' ], 9 );
                    add_filter( 'plugin_action_links_'. plugin_basename(__FILE__), [ $this, 'floting_menu_links' ] );
                 }else {
                add_action( 'admin_notices', [ $this, 'woocommerce_missing_notice' ] );
        }
    }
    /**
     * setting menu page links
     */
    public function floting_menu_links($links) {
        $newLinks = sprintf( "<a href='%s'>%s</a>", 'admin.php?page=my-menu', __( 'Setting','minicartt' ) );
        $links[] = $newLinks;
        return $links;
    }
    /**
     * Add setting page option menu
     */
    public function floatin_mini_cart_menu() {
        add_menu_page(  'Floting Mini Cart', 'Floting Mini Cart', 'manage_options', 'my-menu', [ $this, 'floting_mini_cart_option_page' ], '
        dashicons-cart', 26 );

    }
   
    /**
     * add_menu_page callbacek function description 
     */
    public function floting_mini_cart_option_page() {
        ?>
            <div>
                <?php screen_icon(); ?>
                    <h2>My Plugin Page Title</h2>
                    <form method="post" action="admin.php?page=wc-settings&tab=floating_minicart">
                        <?php settings_fields( 'my-menu' ); ?>
                        <?php do_settings_sections( 'my-menu' ); ?>
                            <h3>This is my option</h3>
                            <p>Some text here.</p>
                            <table>
                                <tr valign="top">
                                    <th scope="row">
                                        <p>You Found setting In press the button</p>
                                    </th>
                                </tr>
                            </table>
                            <?php  submit_button('Floting Mini Cart Setting'); ?>
                    </form>
            </div>
        <?php
    }
    /**
     * Initializes a singleton instance
     *
     * @return \Wooco_Floting_Mini_Cart
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }
    /**
     * WooCommerce fallback notice.
     *
     * @return string
     */
    public function woocommerce_missing_notice() {
        echo '<div class="error"> Woocommerce plugin install plugin</div>';
        if ( isset( $_GET['activate'] ) )
                unset( $_GET['activate'] );	
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WOOC_MINI_CART_VERSION', self::version );
        define( 'WOOC_MINI_CART_FILE', __FILE__ );
        define( 'WOOC_MINI_CART_PATH', __DIR__ );
        define( 'WOOC_MINI_CART_URL', plugins_url( '', WOOC_MINI_CART_FILE ) );
        define( 'WOOC_MINI_CART_ASSETS', WOOC_MINI_CART_URL . 'floting-mini-card/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        if(is_admin() ) {
            include_once 'includes/admin/backend.php';
        }else {
            include_once 'includes/frontend/frontend.php';
            include_once 'includes/frontend/functions.php';
        }

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'wfm_cart_install' );

        if ( ! $installed ) {
            update_option( 'wfm_cart_install', time() );
        }

        update_option( 'wooc_mini_cart_version', WOOC_MINI_CART_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Wooco_Floting_Mini_Cart
 */
function Wooco_Floting_Mini_Cart() {
    return Wooco_Floting_Mini_Cart::init();
}

// kick-off the plugin
Wooco_Floting_Mini_Cart();