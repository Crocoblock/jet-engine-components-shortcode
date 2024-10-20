<?php
/**
 * Plugin Name: JetEngine Components Shortcode
 * Description: Register [jet_engine_component] shortcode to render JetEngine components anywhere
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class JetEngine_Components_Shortcode_Addon {

	public function __construct() {
		add_action( 'jet-engine/init', [ $this, 'init_addon' ] );
	}

	private function includes() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/jet-engine-components-shortcode-manager.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/jet-engine-components-shortcode-admin-column.php';
	}

	public function init_addon() {
		if ( ! function_exists( 'jet_engine' ) ) {
			return;
		}

		$this->includes();
		if ( class_exists( 'Jet_Engine_Component_Shortcode_Manager' ) ) {
			new Jet_Engine_Component_Shortcode_Manager();
		}

		if ( class_exists( 'Jet_Engine_Components_Shortcode_Admin_Column' ) ) {
			new Jet_Engine_Components_Shortcode_Admin_Column();
		}
	}
}

new JetEngine_Components_Shortcode_Addon();
