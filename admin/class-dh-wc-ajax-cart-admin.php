<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Dh_Wc_Ajax_Cart
 * @subpackage Dh_Wc_Ajax_Cart/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dh_Wc_Ajax_Cart
 * @subpackage Dh_Wc_Ajax_Cart/admin
 * @author     Your Name <email@example.com>
 */
class Dh_Wc_Ajax_Cart_Admin {

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
	 * @param      string    $dh_wc_ajax_cart       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $dh_wc_ajax_cart, $version ) {

		$this->dh_wc_ajax_cart = $dh_wc_ajax_cart;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->dh_wc_ajax_cart, plugin_dir_url( __FILE__ ) . 'css/dh-wc-ajax-cart-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->dh_wc_ajax_cart, plugin_dir_url( __FILE__ ) . 'js/dh-wc-ajax-cart-admin.js', array( 'jquery' ), $this->version, false );

	}

}
