<?php
/**
 * Test Plugin
 *
 * Plugin Name: Test Plugin
 * Description: Outputs helpful messages all over the shop.
 * Version:     0.1
 * Author:      Kieran McClung
 * Author URI:  https://kieranmcclung.xyz
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

define( 'TEST__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( TEST__PLUGIN_DIR . 'classes/class.test.php' );

if ( ! class_exists( 'Test_Plugin' ) ) :

	class Test_Plugin {

		// Init the plugin and add some actions
		public static function init_actions() {
			// Use __CLASS__ constant to refer to current class
			add_action( 'wp_footer', array( __CLASS__, 'foot_message' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'plugin_scripts' ) );
		}

		public static function plugin_scripts() {
			wp_enqueue_style( 'plugin-css', plugins_url( 'assets/css/plugin.css', __FILE__ ), array(), '0.1', 'all' );
		}

		public static function foot_message() {
			$data = array(
				'template' => 'message',
				'message'  => 'I like feet',
			);

			return Test_Plugin::get_template($data);
		}

		public static function get_template($data) {
			// Handy little function to return view templates rather than 
			// injecting HTML into the plugin

			$template = TEST__PLUGIN_DIR . 'templates/' . $data['template'] . '.php';

			// Grab extra message from included Test_Class
			$extra_message = Test_Class::get_test_message();

			// Create message string which will be used in included template
			$message  = $data['message'] . ( $extra_message ? '<br>' . $extra_message : '' );
			
			return include( $template ); 
		}
	}

	add_action( 'plugins_loaded', array( 'Test_Plugin', 'init_actions' ) );

endif;