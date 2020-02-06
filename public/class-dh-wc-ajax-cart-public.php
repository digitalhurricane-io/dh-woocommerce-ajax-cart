<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Dh_Wc_Ajax_Cart
 * @subpackage Dh_Wc_Ajax_Cart/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dh_Wc_Ajax_Cart
 * @subpackage Dh_Wc_Ajax_Cart/public
 * @author     Your Name <email@example.com>
 */
class Dh_Wc_Ajax_Cart_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $dh_wc_ajax_cart    The ID of this plugin.
	 */
	private $dh_wc_ajax_cart;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $dh_wc_ajax_cart       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $dh_wc_ajax_cart, $version ) {

		$this->dh_wc_ajax_cart = $dh_wc_ajax_cart;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dh_Wc_Ajax_Cart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dh_Wc_Ajax_Cart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->dh_wc_ajax_cart, plugin_dir_url( __FILE__ ) . 'css/dh-wc-ajax-cart-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dh_Wc_Ajax_Cart_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dh_Wc_Ajax_Cart_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->dh_wc_ajax_cart, plugin_dir_url( __FILE__ ) . 'js/dh-wc-ajax-cart-public.js', array( 'jquery' ), $this->version, false );

	}

	public function register_conditional_enqueue()
	{
		// have to use template_redirect hook in order for is_cart and is_checkout 
		// template tags to become available
		add_action('template_redirect', [$this, 'do_conditional_enqueue']);
	}

	public function do_conditional_enqueue()
	{
		if (is_cart() || is_checkout()) {
			$this->enqueue_scripts();
			$this->enqueue_styles();
		}
	}

	// ajax function
	// called when user click x button to remove an item from the cart
	public function remove_item() {
		if (!wp_verify_nonce($_POST['_wpnonce'], 'woocommerce-cart')) {
			die();
		}
	
		global $woocommerce;
		$successful = $woocommerce->cart->remove_cart_item($_POST['cartKey']);
		if ($successful) {
			
			$newTotal = $woocommerce->cart->get_totals()['total'];
			wp_send_json_success($newTotal);

		} else {
			wp_send_json_error();
		}
		
	}

	// ajax function
	// when user adjusts quantity in cart, form will be serialized
	// and sent to this endpoint
	public function adjust_cart() {
		if (!wp_verify_nonce($_POST['woocommerce-cart-nonce'], 'woocommerce-cart')) {
			die();
		}

		// this can happen due to race condition in jquery
		// it was easier just to guard against it here than
		// fix the race condition
		if (!isset($_POST['cart'])) {
			wp_send_json_error('0.00');
		}

		global $woocommerce;

		$info = $_POST['cart'];
		foreach($info as $cart_item_key => $quantity_arr) {
			try {
				$woocommerce->cart->set_quantity($cart_item_key, $quantity_arr['qty']);
			} catch (Throwable $e) {}

		}

		if ($woocommerce->cart->get_cart_contents_count() == 0) {
			wp_send_json_success('0.00');
		}

		$newTotal = $woocommerce->cart->get_totals()['total'];
		wp_send_json_success($newTotal);

	}
}
