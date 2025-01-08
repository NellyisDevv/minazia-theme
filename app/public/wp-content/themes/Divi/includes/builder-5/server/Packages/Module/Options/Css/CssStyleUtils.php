<?php
/**
 * Module: CssStyleUtils class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\Module\Options\Css;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\Module\Layout\Components\Style\Utils\GroupedStatements;
use ET\Builder\Packages\Module\Layout\Components\Style\Utils\Utils;

/**
 * CssStyleUtils class.
 *
 * @since ??
 */
class CssStyleUtils {

	/**
	 * Get custom CSS statements based on given params.
	 *
	 * This function retrieves the CSS statements based on the provided arguments.
	 *
	 * This function is equivalent of JS function:
	 * {@link /docs/builder-api/js/module/get-statements getStatements} in
	 * `@divi/module` package.
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type array         $selectors        Optional. An array of selectors for each breakpoint and state. Default `[]`.
	 *     @type callable      $selectorFunction Optional. The function to be called to generate CSS selector. Default `null`.
	 *     @type array         $attr             Optional. An array of module attribute data. Default `[]`.
	 *     @type array         $cssFields        Optional. An array of CSS fields. Default `[]`.
	 *     @type string|null   $orderClass       Optional. The selector class name.
	 *     @type string        $returnType       Optional. This is the type of value that the function will return.
	 *                                           Can be either `string` or `array`. Default `array`.
	 * }
	 *
	 * @return string|array The CSS statements formatted as a string.
	 *
	 * @example:
	 * ```php
	 * // Usage Example 1: Simple usage with default arguments.
	 * $args = [
	 *     'selectors'         => ['.element'],
	 *     'selectorFunction'  => null,
	 *     'attr'              => [
	 *         'desktop' => [
	 *             'state1' => [
	 *                 'custom_css1' => 'color: red;',
	 *                 'custom_css2' => 'font-weight: bold;',
	 *             ],
	 *             'state2' => [
	 *                 'custom_css1' => 'color: blue;',
	 *             ],
	 *         ],
	 *         'tablet'  => [
	 *             'state1' => [
	 *                 'custom_css1' => 'color: green;',
	 *                 'custom_css2' => 'font-size: 16px;',
	 *             ],
	 *         ],
	 *     ],
	 *     'cssFields'         => [
	 *         'custom_css1' => [
	 *             'selectorSuffix' => '::before',
	 *         ],
	 *         'custom_css2' => [
	 *             'selectorSuffix' => '::after',
	 *         ],
	 *     ],
	 * ];
	 *
	 * $cssStatements = MyClass::get_statements( $args );
	 * ```
	 * @example:
	 * ```php
	 * // Usage Example 2: Custom selector function to modify the selector and additional at-rules.
	 * $args = [
	 *     'selectors'         => ['.element'],
	 *     'selectorFunction'  => function( $args ) {
	 *         $defaultSelector = $args['selector'];
	 *         $breakpoint = $args['breakpoint'];
	 *         $state = $args['state'];
	 *         $attr = $args['attr'];
	 *
	 *         // Append breakpoint and state to the default selector.
	 *         $modifiedSelector = $defaultSelector . '-' . $breakpoint . '-' . $state;
	 *
	 *         return $modifiedSelector;
	 *     },
	 *     'attr'              => [
	 *         'desktop' => [
	 *             'state1' => [
	 *                 'custom_css1' => 'color: red;',
	 *                 'custom_css2' => 'font-weight: bold;',
	 *             ],
	 *         ],
	 *     ],
	 *     'cssFields'         => [
	 *         'custom_css1' => [
	 *             'selectorSuffix' => '::before',
	 *         ],
	 *         'custom_css2' => [
	 *             'selectorSuffix' => '::after',
	 *         ],
	 *     ],
	 * ];
	 *
	 * $cssStatements = MyClass::get_statements( $args );
	 * ```
	 */
	public static function get_statements( array $args ) {
		$args = wp_parse_args(
			$args,
			[
				'selectors'        => [],
				'selectorFunction' => null,
				'attr'             => [],
				'cssFields'        => [],
				'orderClass'       => null,
				'returnType'       => 'array',
			]
		);

		$selectors         = $args['selectors'];
		$selector_function = $args['selectorFunction'];
		$attr              = $args['attr'];
		$css_fields        = $args['cssFields'];
		$order_class       = $args['orderClass'];

		$get_selector_suffix = function( $css_name ) use ( $css_fields ) {
			if ( 'mainElement' === $css_name ) {
				return '';
			}

			if ( 'freeForm' === $css_name ) {
				return '';
			}

			if ( 'before' === $css_name ) {
				return ':before';
			}

			if ( 'after' === $css_name ) {
				return ':after';
			}

			if ( isset( $css_fields[ $css_name ]['selectorSuffix'] ) ) {
				return $css_fields[ $css_name ]['selectorSuffix'];
			}

			return false;
		};

		$grouped_statements = new GroupedStatements();

		foreach ( $attr as $breakpoint => $state_values ) {
			foreach ( $state_values as $state => $attr_value ) {
				// Each of the `css` subname value is literally entire CSS statement which requires its own
				// selector hence the additional loop on this `divi/css` specific getStatements() function.
				foreach ( $attr_value as $custom_css_name => $css_declaration ) {
					$selector        = Utils::get_selector(
						[
							'selectors'  => $selectors,
							'breakpoint' => $breakpoint,
							'state'      => $state,
							'orderClass' => $order_class,
						]
					);
					$selector_suffix = call_user_func( $get_selector_suffix, $custom_css_name );

					// If no selectorSuffix found (NOTE: mainElement returns empty string), bail.
					if ( false === $selector_suffix || ! is_string( $selector_suffix ) ) {
						continue;
					}

					if ( 'freeForm' === $custom_css_name ) {
						// Regex test: https://regex101.com/r/awNoFJ/2.
						$modified_css_declaration = preg_replace( '/\.?#?selector/', $selector, $css_declaration );

						// Remove any "\n" or "  " (double space) in CSS Custom settings.
						$modified_css_declaration = str_replace( "\n", '', $modified_css_declaration );
						$modified_css_declaration = str_replace( '  ', '', $modified_css_declaration );

						$grouped_statements->add(
							[
								'atRules'     => Utils::get_at_rules( $breakpoint ),
								'selector'    => '', // Empty selector indicating the free-form-css.
								'declaration' => wp_strip_all_tags( $modified_css_declaration ),
							]
						);
					} else {
						// Split the selectorSuffix by commas and trim extra spaces.
						$selector_suffixes = explode( ',', $selector_suffix );

						// Determine the main selector.
						$main_selector = is_callable( $selector_function )
							? call_user_func(
								$selector_function,
								[
									'selector'   => $selector,
									'breakpoint' => $breakpoint,
									'state'      => $state,
									'attr'       => $attr,
								]
							)
							: $selector;
						// Add the main selector to each suffix.
						$css_selector = implode(
							', ',
							array_map(
								fn( $suffix) => $main_selector . $suffix,
								$selector_suffixes
							)
						);

						$grouped_statements->add(
							[
								'atRules'     => Utils::get_at_rules( $breakpoint ),
								'selector'    => $css_selector,
								'declaration' => $css_declaration,
							]
						);
					}
				}
			}
		}

		if ( 'array' === $args['returnType'] ) {
			return $grouped_statements->value_as_array();
		}

		return $grouped_statements->value();
	}
}
