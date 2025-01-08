<?php
/**
 * Module: DynamicContentOptionPostDate class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\Module\Layout\Components\DynamicContent;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\Module\Layout\Components\DynamicContent\DynamicContentUtils;
use ET\Builder\Packages\Module\Layout\Components\DynamicContent\DynamicContentElements;

/**
 * Module: DynamicContentOptionPostDate class.
 *
 * @since ??
 */
class DynamicContentOptionPostDate extends DynamicContentOptionBase implements DynamicContentOptionInterface {

	/**
	 * Get the name of the post date option.
	 *
	 * @since ??
	 *
	 * @return string The name of the post date option.
	 */
	public function get_name(): string {
		return 'post_date';
	}

	/**
	 * Get the label for the post date option.
	 *
	 * This function retrieves the localized label for the post date option,
	 * which is used to describe the post date in user interfaces.
	 *
	 * @since ??
	 *
	 * @return string The label for the post date option.
	 */
	public function get_label(): string {
		// Translators: %1$s: Post type name.
		return __( '%1$s Publish Date', 'et_builder' );
	}

	/**
	 * Callback for registering post date option .
	 *
	 * This function is a callback for the `divi_module_dynamic_content_options` filter .
	 * This function is used to register options for post date by adding them to the options array passed to the function .
	 * It checks if the current module's name exists as a key in the options array.
	 * If not, it adds the module's name as a key and the specific options for that module as the value.
	 *
	 * @since ??
	 *
	 * @param array  $options The options array to be registered.
	 * @param int    $post_id The post ID.
	 * @param string $context The context in which the options are retrieved e.g `edit`, `display`.
	 *
	 * @return array The options array.
	 */
	public function register_option_callback( array $options, int $post_id, string $context ): array {
		if ( ! isset( $options[ $this->get_name() ] ) ) {
			$options[ $this->get_name() ] = [
				'id'     => $this->get_name(),
				'label'  => esc_html( sprintf( $this->get_label(), DynamicContentUtils::get_post_type_label( $post_id ) ) ),
				'type'   => 'text',
				'custom' => false,
				'group'  => 'Default',
				'fields' => [
					'before'             => [
						'label'   => esc_html__( 'Before', 'et_builder' ),
						'type'    => 'text',
						'default' => '',
					],
					'after'              => [
						'label'   => esc_html__( 'After', 'et_builder' ),
						'type'    => 'text',
						'default' => '',
					],
					'date_format'        => [
						'label'   => esc_html__( 'Date Format', 'et_builder' ),
						'type'    => 'select',
						'options' => [
							'default' => et_builder_i18n( 'Default' ),
							'M j, Y'  => esc_html__( 'Aug 6, 1999 (M j, Y)', 'et_builder' ),
							'F d, Y'  => esc_html__( 'August 06, 1999 (F d, Y)', 'et_builder' ),
							'm/d/Y'   => esc_html__( '08/06/1999 (m/d/Y)', 'et_builder' ),
							'm.d.Y'   => esc_html__( '08.06.1999 (m.d.Y)', 'et_builder' ),
							'j M, Y'  => esc_html__( '6 Aug, 1999 (j M, Y)', 'et_builder' ),
							'l, M d'  => esc_html__( 'Tuesday, Aug 06 (l, M d)', 'et_builder' ),
							'custom'  => esc_html__( 'Custom', 'et_builder' ),
						],
						'default' => 'default',
					],
					'custom_date_format' => [
						'label'   => esc_html__( 'Custom Date Format', 'et_builder' ),
						'type'    => 'text',
						'default' => '',
						'show_if' => [
							'date_format' => 'custom',
						],
					],
				],
			];
		}

		return $options;
	}

	/**
	 * Render callback for post date option.
	 *
	 * Retrieves the value of post date option based on the provided arguments and settings.
	 * This is a callback for `divi_module_dynamic_content_resolved_value` filter.
	 *
	 * @since ??
	 *
	 * @param mixed $value     The current value of the post date option.
	 * @param array $data_args {
	 *     Optional. An array of arguments for retrieving the post date.
	 *     Default `[]`.
	 *
	 *     @type string  $name       Optional. Option name. Default empty string.
	 *     @type array   $settings   Optional. Option settings. Default `[]`.
	 *     @type integer $post_id    Optional. Post Id. Default `null`.
	 * }
	 *
	 * @return string The formatted value of the post date option.
	 *
	 * @example:
	 * ```php
	 *  $element = new MyDynamicContentElement();
	 *
	 *  // Render the element with a specific value and data arguments.
	 *  $html = $element->render_callback( $value, [
	 *      'name'     => 'my_element',
	 *      'settings' => [
	 *          'post_id' => 123,
	 *          'foo'     => 'bar',
	 *      ],
	 *      'post_id'  => 456,
	 *  ] );
	 * ```
	 */
	public function render_callback( $value, array $data_args = [] ): string {
		$name     = $data_args['name'] ?? '';
		$settings = $data_args['settings'] ?? [];
		$post_id  = $data_args['post_id'] ?? null;

		if ( $this->get_name() !== $name ) {
			return $value;
		}

		$post = is_int( $post_id ) && 0 !== $post_id ? get_post( $post_id ) : false;

		if ( $post ) {
			$format        = $settings['date_format'] ?? 'default';
			$custom_format = $settings['custom_date_format'] ?? '';

			if ( 'custom' === $format ) {
				$format = $custom_format;
			} elseif ( 'default' === $format ) {
				$format = '';
			}

			$value = esc_html( get_the_date( $format, $post_id ) );
		}

		return DynamicContentElements::get_wrapper_element(
			[
				'post_id'  => $post_id,
				'name'     => $name,
				'value'    => $value,
				'settings' => $settings,
			]
		);
	}
}