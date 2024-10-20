<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jet_Engine_Components_Shortcode_Admin_Column {

	public function __construct() {
		if ( ! isset( $_GET['entry_type'] ) || ( isset( $_GET['entry_type'] ) && 'component' === $_GET['entry_type'] ) ) {
			add_filter( 'manage_jet-engine_posts_columns', [ $this, 'add_admin_column' ], 11 );
			add_action( 'manage_jet-engine_posts_custom_column', [ $this, 'render_admin_column' ], 10, 2 );
		}
	}

	public function add_admin_column( $columns ) {
		$new_columns = [];
		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			if ( 'for-post-type-taxonomy' === $key ) {
				$new_columns['shortcode'] = __( 'Shortcode' );
			}
		}
		return $new_columns;
	}

	public function render_admin_column( $column, $post_id ) {

		if ( 'shortcode' !== $column ) {
			return;
		}

		$component = jet_engine()->listings->components->get( $post_id, 'id' );

		if ( ! $component ) {
			return;
		}

		$component_props = $component->get_props();
		$component_styles = $component->get_styles();

		if ( ! empty( $component_props ) || ! empty( $component_styles ) ) {
			$shortcode = '[jet_engine_component id="' . esc_attr( $post_id ) . '" context="default_object"';

			if ( ! empty( $component_props ) && is_array( $component_props ) ) {
				foreach ( $component_props as $prop ) {
					$name = isset( $prop['control_name'] ) ? $prop['control_name'] : '';
					if ( $name ) {
						$value = '';
						if ( isset( $prop['control_default_image'] ) ) {
							$default = $prop['control_default_image'];
							if ( is_array( $default ) && isset( $default['id'] ) ) {
								$value = esc_attr( $default['id'] );
							}
						} elseif ( isset( $prop['control_default'] ) ) {
							$default = $prop['control_default'];
							$value = is_array( $default ) ? implode( ', ', array_map( 'esc_attr', $default ) ) : esc_attr( $default );
						}
						$shortcode .= ' ' . esc_attr( $name ) . '="' . $value . '"';
					}
				}
			}

			if ( ! empty( $component_styles ) && is_array( $component_styles ) ) {
				foreach ( $component_styles as $style ) {
					$name = isset( $style['control_name'] ) ? $style['control_name'] : '';
					if ( $name ) {
						$default = isset( $style['control_default'] ) ? $style['control_default'] : '';
						$value = is_array( $default ) ? implode( ', ', array_map( 'esc_attr', $default ) ) : esc_attr( $default );
						$shortcode .= ' ' . esc_attr( $name ) . '="' . $value . '"';
					}
				}
			}

			$shortcode .= ']';
			echo '<input readonly type="text" onclick="this.select()" value="' . esc_attr( $shortcode ) . '" style="width: 100%; box-sizing: border-box;" />';
		}
	}
}
