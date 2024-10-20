<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jet_Engine_Component_Shortcode_Manager {

	public function __construct() {
		add_shortcode( 'jet_engine_component', [ $this, 'render_component_shortcode' ] );
	}

	/**
	 * Gets component by id
	 *
	 * @param string $component_id                              component id
	 * @return \Jet_Engine\Listings\Components\Component        component object
	 */
	private function get_component_by_id( $component_id ) {

		$components_manager = jet_engine()->listings->components;

		return $components_manager->get( $component_id, 'id' );
	}

	public function render_component_shortcode( $atts ) {
		if ( ! isset( $atts['id'] ) || empty( $atts['id'] ) || ! $this->get_component_by_id( sanitize_text_field( $atts['id'] ) ) ) {
			return 'Invalid Component ID';
		}

		$component_id = sanitize_text_field( $atts['id'] );

		$component = $this->get_component_by_id( $component_id );

		$component_props = $component->get_props();

		foreach ( $component_props as $prop ) {
			if ( ! isset( $atts[ $prop['control_name'] ] ) ) {
				continue;
			}

			if ( 'media' === $prop['control_type'] ) {
				$atts[ $prop['control_name'] ] = \Jet_Engine_Tools::get_attachment_image_data_array( $atts[ $prop['control_name'] ] );
			}
		}

		if ( ! empty( $atts['context'] ) ) {
			$component->set_component_context( sanitize_text_field( $atts['context'] ) );
		}

		$content = $component->get_content( $atts, false );

		$result = sprintf(
			'<div class="jet-listing-grid--%1$s" style="%2$s">%3$s</div>',
			esc_attr( $component->get_id() ),
			esc_attr( $component->css_variables_string( $atts ) ),
			$content
		);

		return $result;
	}
}
