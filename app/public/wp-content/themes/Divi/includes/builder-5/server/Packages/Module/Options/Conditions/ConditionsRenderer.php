<?php
/**
 * Conditions: ConditionsRenderer.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\Module\Options\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\Module\Options\Conditions\Conditions;


/**
 * Conditions option custom renderer.
 */
class ConditionsRenderer {

	/**
	 * Register the conditions option custom renderer: `render_block` filter for the `ET_Core_Portability` class.
	 *
	 * This method registers the `render_block` filter for the `ET_Core_Portability` class.
	 * The filter callback function is `should_render`.
	 *
	 * The renderer checks module's conditions option and decides if a module should be rendered or not.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function register(): void {
		add_filter( 'render_block', [ __CLASS__, 'should_render' ], 10, 3 );
	}

	/**
	 * Determines if a module should be rendered based on its conditions.
	 *
	 * This function checks the conditions option of a module and decides whether the module should be rendered or not.
	 * It returns the original block content if the module is conditionally displayable, and an empty string otherwise.
	 *
	 * @since ??
	 *
	 * @param string    $block_content The content of the block.
	 * @param array     $block         The full block, including name and attributes.
	 * @param \WP_Block $instance      The block instance.
	 *
	 * @return string The original block content if the module is conditionally displayable, empty string otherwise.
	 *
	 * @example:
	 * ```php
	 * $block_content = "<div class='module'>This is the content of the module</div>";
	 * $block = [
	 *     'attrs' => [
	 *         'module' => [
	 *             'decoration' => [
	 *                 'conditions' => [
	 *                     'desktop' => [
	 *                         'value' => [
	 *                             [
	 *                                 'id'                => '10ba038e-48da-487b-96e8-8d3b99b6d18a',
	 *                                 'conditionName'     => 'loggedInStatus',
	 *                                 'conditionSettings' => [
	 *                                     'displayRule'     => 'loggedIn',
	 *                                     'adminLabel'      => 'Logged In Status',
	 *                                     'enableCondition' => 'on',
	 *                                 ],
	 *                                 'operator'          => 'OR',
	 *                             ]
	 *                         ],
	 *                     ],
	 *                 ],
	 *             ],
	 *         ],
	 *     ],
	 * ];
	 * $instance = new \WP_Block();
	 * $displayable = ConditionsRenderer::should_render($block_content, $block, $instance);
	 * $echo $displayable;
	 *
	 * // Result: "<div class='module'>This is the content of the module</div>"
	 * ```
	 */
	public static function should_render( string $block_content, array $block, \WP_Block $instance ): string {
		static $is_display_conditions_enabled = null;

		// We only need to run this filter this once,
		// especially because we dont even send params to this filter.
		if ( null === $is_display_conditions_enabled ) {
			/**
			 * Filters "Display Conditions" functionality to determine whether to enable or disable the functionality or not.
			 *
			 * Useful for disabling/enabling "Display Condition" feature site-wide.
			 *
			 * @since ??
			 *
			 * @param boolean True to enable the functionality, False to disable it.
			 */
			$is_display_conditions_enabled = apply_filters( 'et_is_display_conditions_functionality_enabled', true );
		}

		if ( ! $is_display_conditions_enabled ) {
			return $block_content;
		}

		$is_displayable = true;
		// Check if the block has conditions and if it is displayable,
		// if this module even has conditions enabled.
		if ( isset( $block['attrs']['module']['decoration']['conditions'] ) && isset( $block['attrs']['module']['decoration']['conditions']['desktop']['value'] ) ) {
			$display_conditions = new Conditions();
			$is_displayable     = $display_conditions->is_displayable( $block['attrs']['module']['decoration']['conditions']['desktop']['value'] ?? [] );
		}

		return $is_displayable ? $block_content : '';
	}

}
