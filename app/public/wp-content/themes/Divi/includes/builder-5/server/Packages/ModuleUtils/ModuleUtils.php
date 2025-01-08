<?php
/**
 * Module Utils Class
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\ModuleUtils;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\Utility\TextTransform;
use ET\Builder\Framework\Utility\ArrayUtility;
use ET\Builder\Packages\Module\Layout\Components\MultiView\MultiViewUtils;
use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use ET\Builder\FrontEnd\Module\Fonts;
use ET\Builder\Packages\GlobalData\GlobalPreset;
use Rogervila\ArrayDiffMultidimensional;
use WP_Block_Type_Registry;
use InvalidArgumentException;

/**
 * ModuleUtils class.
 *
 * This class provides utility methods for modules.
 *
 * @since ??
 */
class ModuleUtils {

	/**
	 * Get the module breakpoints.
	 *
	 * Retrieves an array of module breakpoints including `desktop`, `tablet`, and `phone`.
	 * This function runs the value through the `divi_module_utils_breakpoints` filter.
	 *
	 * This function is equivalent of JS function:
	 * {@link /docs/builder-api/js/module-utils/breakpoints/ breakpoints } located in `@divi/module-utils`.
	 *
	 * @since ??
	 *
	 * @return array The module breakpoints.
	 *
	 * @example:
	 * ```
	 * $breakpoints = ModuleUtils::breakpoints();
	 *
	 * // Output: ['desktop', 'tablet', 'phone']
	 * ```
	 *
	 * @example:
	 * ```php
	 * $breakpoints = apply_filters( 'divi_module_utils_breakpoints', ['desktop', 'tablet', 'phone'] );
	 *
	 * // Output: ['desktop', 'tablet', 'phone']
	 * ```
	 */
	public static function breakpoints(): array {
		$breakpoints = [
			'desktop',
			'tablet',
			'phone',
		];

		/**
		 * Filters the module breakpoints.
		 *
		 * @since ??
		 *
		 * @param array $breakpoints The module breakpoints. Default `['desktop', 'tablet', 'phone']`.
		 */
		return apply_filters( 'divi_module_utils_breakpoints', $breakpoints );
	}

	/**
	 * Retrieve the inherited attribute value based on the given arguments.
	 *
	 * This function is equivalent of JS function:
	 * {@link /docs/builder-api/js/module-utils/inherit-attr-value/ inheritAttrValue} located in
	 * `@divi/module-utils`.
	 *
	 * This function takes an array of arguments and returns the value of the specified attribute.
	 * It first parses the arguments using `wp_parse_args()` and then retrieves the attribute value based on the provided `breakpoint`, `state`, and `mode`.
	 * If the attribute value for the specified `breakpoint` and `state` is not found, it retrieves the inherited value based on the specified `mode`.
	 * If no value is found, it returns `null`.
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type array  $attr        An array of attribute data.
	 *     @type string $breakpoint  The breakpoint to inherit from.
	 *     @type string $state       The state of the attribute.
	 *     @type string $inheritMode Optional. The mode of inheritance. Default `all`.
	 * }
	 *
	 * @return mixed|null The value of the attribute based on the specified arguments, or null if no value is found.
	 *
	 * @example:
	 * ``php
	 * // Get the value of the 'color' attribute for the 'tablet' breakpoint and 'hover' state.
	 * $args = [
	 *     'attr' => [
	 *         'desktop' => [
	 *             'hover' => '#000000',
	 *         ],
	 *         'tablet' => [
	 *             'hover' => '#ffffff',
	 *         ],
	 *         'phone' => [
	 *             'hover' => '#cccccc',
	 *         ],
	 *     ],
	 *     'breakpoint' => 'tablet',
	 *     'state' => 'hover',
	 *     'inheritMode' => 'all',
	 * ];
	 *
	 * $value = ModuleUtils::inherit_attr_value( $args );
	 * ```
	 *
	 * @example:
	 * ```php
	 * // Get the value of the 'font-size' attribute for the 'phone' breakpoint and 'value' state,
	 * // and inherit the closest value from larger breakpoints.
	 * $args = [
	 *     'attr' => [
	 *         'desktop' => [
	 *             'value' => '14px',
	 *         ],
	 *         'tablet' => [
	 *             'value' => '16px',
	 *         ],
	 *         'phone' => [
	 *             'value' => '18px',
	 *         ],
	 *     ],
	 *     'breakpoint' => 'phone',
	 *     'state' => 'value',
	 *     'inheritMode' => 'closest',
	 * ];
	 *
	 * $value = ModuleUtils::inherit_attr_value( $args );
	 * ```
	 */
	public static function inherit_attr_value( array $args ) {
		$args = wp_parse_args(
			$args,
			[
				'inheritMode' => 'all',
			]
		);

		$attr         = $args['attr'];
		$breakpoint   = $args['breakpoint'];
		$state        = $args['state'];
		$inherit_mode = $args['inheritMode'];

		// `state` has no order. If the state is not `value`, it means it'll fallback to existing breakpoint + value
		// before fallback to larger breakpoint + value.
		$is_default_state      = 'value' === $state;
		$is_desktop_breakpoint = 'desktop' === $breakpoint;

		// No breakpoint / state to fallback into. Exit early.
		if ( $is_desktop_breakpoint && $is_default_state ) {
			return null;
		}

		$breakpoints = [
			'desktop',
			'tablet',
			'phone',
		];

		// `breakpoints` are ordered in order (pun intended) of size. Thus breakpoints in previous order are
		// guaranteed to be larger breakpoint and cascaded in terms of order.
		// NOTE: The order should be reversed so it fallback in order
		$breakpoint_index   = array_search( $breakpoint, $breakpoints, true );
		$larger_breakpoints = array_slice( $breakpoints, 0, $breakpoint_index );
		$larger_breakpoints = array_reverse( $larger_breakpoints );

		// Populate inherited attr value.
		$inherited_attr_value = null;

		// If current state isn't default, get value of current breakpoint's default state value.
		if ( ! $is_default_state && isset( $attr[ $breakpoint ]['value'] ) ) {
			$inherited_attr_value = $attr[ $breakpoint ]['value'];
		}

		// Loop for larger breakpoint's default state value.
		$larger_breakpoints_count = count( $larger_breakpoints );
		for ( $larger_breakpoints_index = 0; $larger_breakpoints_index < $larger_breakpoints_count; $larger_breakpoints_index++ ) {
			$current_larger_breakpoint = $larger_breakpoints[ $larger_breakpoints_index ];
			$larger_breakpoint_value   = $attr[ $current_larger_breakpoint ]['value'] ?? null;

			// If the attribute value is object and inheritMode is all (combined all possible inherited value),
			// merge all object from larger breakpoints.
			if ( is_array( $larger_breakpoint_value ) && 'all' === $inherit_mode ) {
				// merge() is being used instead of spread object so that nested object can be merged.
				$inherited_attr_value = array_replace_recursive( $larger_breakpoint_value, (array) $inherited_attr_value );

				// If the attribute value is 1) not object, or 2) an object but inheritMode is closest,
				// simply overwrite the closest one if it isn't exist yet.
			} elseif ( null !== $larger_breakpoint_value && null === $inherited_attr_value ) {
				$inherited_attr_value = $larger_breakpoint_value;

				// Break loop once valid inherited attr value is found.
				break;

				// Prevent unnecessary loop. Might fall into this if state is not default and inherited attr value
				// is already found.
			} elseif ( null !== $inherited_attr_value && 'closest' === $inherit_mode ) {
				// Break loop once valid inherited attr value is found.
				break;
			}
		}

		return $inherited_attr_value;
	}

	/**
	 * Get an array of breakpoints used for inheritance.
	 *
	 * The static array returned by this function represents the breakpoints for responsive views used in the inheritance logic.
	 *
	 * Top level keys are of type `breakpoint` and second level keys are of type `AttrState`.
	 * The values of the second level keys are arrays of length 2, where both elements are strings.
	 *
	 * @since ??
	 *
	 * @return array The array of breakpoints used for inheritance.
	 *
	 * @example:
	 * ```php
	 * $inheritance = ModuleUtils::inherit_breakpoints();
	 * // Returns:
	 * // [
	 * //    'phone' => [
	 * //        'sticky' => ['phone', 'value'],
	 * //        'hover' => ['phone', 'value'],
	 * //        'value' => ['tablet', 'value']
	 * //    ],
	 * //    'tablet' => [
	 * //        'sticky' => ['tablet', 'value'],
	 * //        'hover' => ['tablet', 'value'],
	 * //        'value' => ['desktop', 'value']
	 * //    ],
	 * //    'desktop' => [
	 * //        'sticky' => ['desktop', 'value'],
	 * //        'hover' => ['desktop', 'value'],
	 * //        'value' => ['desktop', 'value']
	 * //    ]
	 * // ]
	 * ```
	 */
	public static function inherit_breakpoints(): array {
		// TODO feat(D5, Responsive Views): replace this static array with a dynamic one generated from the Builder's settings.
		return [
			'phone'   => [
				'sticky' => [
					'phone',
					'value',
				],
				'hover'  => [
					'phone',
					'value',
				],
				'value'  => [
					'tablet',
					'value',
				],
			],
			'tablet'  => [
				'sticky' => [
					'tablet',
					'value',
				],
				'hover'  => [
					'tablet',
					'value',
				],
				'value'  => [
					'desktop',
					'value',
				],
			],
			'desktop' => [
				'sticky' => [
					'desktop',
					'value',
				],
				'hover'  => [
					'desktop',
					'value',
				],
				'value'  => [
					'desktop',
					'value',
				],
			],
		];
	}

	/**
	 * Retrieve the value of an attribute based on the provided arguments.
	 *
	 * This function is equivalent of JS function:
	 * {@link /docs/builder-api/js/module-utils/get-attr-value/ getAttrValue} located in
	 * `@divi/module-utils`.
	 *
	 * This function takes an array of arguments and returns the value of the specified attribute.
	 * The function first parses the arguments using `wp_parse_args()`. It then retrieves the attribute value based on the specified breakpoint, state, and mode.
	 * If the attribute value for the specified breakpoint and state is not found, it retrieves the inherited value based on the specified mode.
	 * If no value is found, the function returns the default value specified in the arguments.
	 *
	 * Getter and inheritance model can be changed based on `mode` parameter:
	 * 1. `get`                  : Get attr value of given breakpoint + state.
	 * 2. `getAndInheritAll`     : Get attr value combined by all possible inherited attr value on all larger breakpoints.
	 * 3. `getAndInheritClosest` : Get attr value combined by inherited attr value from closest available breakpoint.
	 * 4. `getOrInheritAll`      : Get attr value or inherited attr value from all larger breakpoints.
	 * 5. `getOrInheritClosest`  : Get attr value or inherited attr value from closest available breakpoint.
	 * 6. `inheritAll`           : Get inherited attr value from all larger breakpoints.
	 * 7. `inheritClosest`       : Get inherited attr value from all closest available breakpoint.
	 *
	 *
	 * See below for inherited attribute fallback flow:
	 *
	 * |        | value | hover | sticky |
	 * |--------|-------|-------|--------|
	 * | Desktop|   *   |  <--  |  <--   |
	 * | Tablet |   ^   |  <--  |  <--   |
	 * | Phone  |   ^   |  <--  |  <--   |
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type array        $attr          The attribute to retrieve the value from.
	 *     @type string       $breakpoint    The breakpoint.
	 *     @type string       $state         The state.
	 *     @type string       $mode          Optional. The mode. Default `getOrInheritAll`.
	 *     @type mixed|null   $defaultValue  Optional. The default value. Default `null`.
	 * }
	 *
	 * @return mixed|null The value of the attribute based on the specified arguments, or the default value if no value is found.
	 */
	public static function get_attr_value( array $args ) {
		$args = wp_parse_args(
			$args,
			[
				'mode'         => 'getOrInheritAll',
				'defaultValue' => null,
			]
		);

		$attr          = $args['attr'];
		$breakpoint    = $args['breakpoint'];
		$state         = $args['state'];
		$mode          = $args['mode'];
		$default_value = $args['defaultValue'];

		// Get attribute value.
		$attr_value = isset( $attr[ $breakpoint ][ $state ] ) ? $attr[ $breakpoint ][ $state ] : null;

		// Get inherited value.
		$inherited_attr_value = null;

		switch ( $mode ) {
			case 'getAndInheritClosest':
			case 'getOrInheritClosest':
			case 'inheritClosest':
				$inherited_attr_value = self::inherit_attr_value(
					[
						'attr'        => $attr,
						'breakpoint'  => $breakpoint,
						'state'       => $state,
						'inheritMode' => 'closest',
					]
				);
				break;

			// Default is for *InheritAll mode:
			// - 'getAndInheritAll'
			// - 'getOrInheritAll'
			// - 'inheritAll'
			// - 'get'.
			default:
				$inherited_attr_value = self::inherit_attr_value(
					[
						'attr'        => $attr,
						'breakpoint'  => $breakpoint,
						'state'       => $state,
						'inheritMode' => 'all',
					]
				);
				break;
		}

		// Get returned value based on its mode.
		$returned_attr_value = null;

		switch ( $mode ) {
			case 'getAndInheritAll':
			case 'getAndInheritClosest':
				// Combine attrValue and inherited value.
				if ( is_array( $attr_value ) && is_array( $inherited_attr_value ) ) {
					$returned_attr_value = array_replace_recursive( $inherited_attr_value, $attr_value );
				} else {
					$returned_attr_value = null !== $attr_value ? $attr_value : $inherited_attr_value;
				}
				break;
			case 'getOrInheritAll':
			case 'getOrInheritClosest':
				$returned_attr_value = null !== $attr_value ? $attr_value : $inherited_attr_value;
				break;
			case 'inheritAll':
			case 'inheritClosest':
				$returned_attr_value = $inherited_attr_value;
				break;

			// Default stands for mode === 'get'.
			default:
				$returned_attr_value = $attr_value;
				break;
		}

		return null !== $returned_attr_value ? $returned_attr_value : $default_value;
	}

	/**
	 * Get the inheritance breakpoint for a given breakpoint and state.
	 *
	 * This function retrieves the target inheritance breakpoint for a given breakpoint and state.
	 * It is used to determine the inherited attribute values.
	 *
	 * @since ??
	 *
	 * @param string $breakpoint The breakpoint to get the inheritance breakpoint for.
	 *                           Accepts one of the following values: `desktop`, `tablet`, `phone`.
	 *                           Default value is `desktop`.
	 * @param string $state      The state to get the inheritance breakpoint for.
	 *                           Accepts one of the following values: `value`, `hover`, `tablet_value`, `tablet_hover`, `phone_value`, `phone_hover`.
	 *                           Default value is `value`.
	 *
	 * @return string The inheritance breakpoint for the given breakpoint and state.
	 *
	 * @example:
	 * ```php
	 * // Get the inheritance breakpoint for the 'tablet' breakpoint and 'hover' state
	 * $inherit_breakpoint = ModuleUtils::get_inherit_breakpoint('tablet', 'hover');
	 * echo $inherit_breakpoint;
	 *
	 * // Output: 'desktop'
	 * ```

	 * @example:
	 * ```php
	 * // Get the inheritance breakpoint for the default 'desktop' breakpoint and 'value' state
	 * $inherit_breakpoint = ModuleUtils::get_inherit_breakpoint();
	 * echo $inherit_breakpoint;
	 *
	 * // Output: 'desktop'
	 * ```
	 */
	public static function get_inherit_breakpoint( string $breakpoint = 'desktop', string $state = 'value' ): string {
		$inherit_breakpoints = self::inherit_breakpoints();
		return $inherit_breakpoints[ $breakpoint ][ $state ][0];
	}

	/**
	 * Get the inheritance state for a given breakpoint and state.
	 *
	 * This function retrieves the target inheritance state for a given breakpoint and state.
	 * It is used in conjunction with the ModuleUtils::get_inherit_breakpoint()` function to determine the inherited attribute values.
	 *
	 * @since ??
	 *
	 * @param string $breakpoint The breakpoint to get the inheritance state for.
	 *                           One of `desktop`, `tablet`, `phone`.
	 *                           Default `desktop`.
	 * @param string $state      The state to get the inheritance state for.
	 *                           One of `value`, `hover`, `tablet_value`, `tablet_hover`, `phone_value`, `phone_hover`.
	 *                           Default `value`.
	 *
	 * @return string The inheritance state for the given breakpoint and state.
	 *
	 * @example:
	 * ```php
	 * // Get the inheritance state for the 'tablet' breakpoint and 'hover' state
	 * $inherit_state = ModuleUtils::get_inherit_state('tablet', 'hover');
	 * echo $inherit_state;
	 *
	 * // Output: 'value_hover'
	 * ```
	 *
	 * @example:
	 * ```php
	 * // Get the inheritance state for the default 'desktop' breakpoint and 'value' state
	 * $inherit_state = ModuleUtils::get_inherit_state();
	 * echo $inherit_state;
	 *
	 * // Output: 'value'
	 * ```
	 */
	public static function get_inherit_state( string $breakpoint = 'desktop', string $state = 'value' ): string {
		$inherit_breakpoints = self::inherit_breakpoints();
		return $inherit_breakpoints[ $breakpoint ][ $state ][1];
	}

	/**
	 * Recursively trim all values in an array.
	 *
	 * This function calls `ModuleUtils::_array_trim()` to trim the values.
	 *
	 * @since ??
	 *
	 * @param array $input The input array.
	 *
	 * @return array The trimmed array.
	 */
	private static function _array_trim( array $input ): array {
		return array_filter(
			$input,
			function ( $value, $key ) {
				if ( is_array( $value ) ) {
					$value = self::_array_trim( $value );
				}
				// In the background, we have "remove" (trash icon) concept where we can remove value from certain breakpoint without
				// inheriting the value from the larger breakpoint. In this case, we need to allow empty string as a valid value for
				// certain properties.
				$is_allowed_empty_string = '' === $value && in_array(
					$key,
					[
						'url',
						'color',
					],
					true
				);

				return ! empty( $value ) || $is_allowed_empty_string;
			},
			ARRAY_FILTER_USE_BOTH
		);
	}

	/**
	 * Recursively compare two multidimensional arrays to check if they are the same.
	 *
	 * This function trims all values in the arrays recursively using the `ModuleUtils::_array_trim()` method.
	 * It then uses the `ArrayDiffMultidimensional::compare()` to compare the difference between
	 * the two multidimensional arrays.
	 *
	 * This function works like the PHP `array_diff()` function, but with multidimensional arrays.
	 *
	 * @since ??
	 *
	 * @param array $array1 The first array to compare.
	 * @param array $array2 The second array to compare.
	 *
	 * @return bool Returns `true` if the arrays are the same, `false` otherwise.
	 */
	private static function _is_same( array $array1, array $array2 ): bool {
		$array1 = self::_array_trim( $array1 );
		$array2 = self::_array_trim( $array2 );

		$diff = ArrayDiffMultidimensional::compare( $array1, $array2 );

		return empty( $diff );
	}

	/**
	 * Check if the background attribute setting is enabled.
	 *
	 * This function checks if the `enabled` attribute is set in the given attribute group.
	 *
	 * If the `enabled` attribute is not present and strict comparison is enabled, it returns `false`.
	 * If the `enabled` attribute is not present and strict comparison is not enabled, it returns true.
	 * If the `enabled` attribute is present, it returns `true` if it is set to `'on'`, and `false` otherwise.
	 *
	 * @since ??
	 *
	 * @param array $attr_group The attribute group to check.
	 * @param bool  $strict     Whether to make a strict comparison. Default `false`.
	 *
	 * @return bool Whether the background attribute setting is enabled.
	 *
	 * @example:
	 * ```php
	 *   // Example 1: Check if background is enabled without strict comparison.
	 *   $attr_group = [
	 *       'enabled' => 'on',
	 *       // other attributes...
	 *   ];
	 *   $result = ModuleUtils::_is_background_attr_enabled( $attr_group );
	 *   // Output: true
	 *
	 *   // Example 2: Check if background is enabled with strict comparison.
	 *   $attr_group = [
	 *       'enabled' => 'on',
	 *       // other attributes...
	 *   ];
	 *   $result = ModuleUtils::_is_background_attr_enabled( $attr_group, true) ;
	 *   // Output: false
	 *
	 *   // Example 3: Check if background is disabled without strict comparison.
	 *   $attr_group = [
	 *       'enabled' => 'off',
	 *       // other attributes...
	 *   ];
	 *   $result = ModuleUtils::_is_background_attr_enabled( $attr_group );
	 *   // Output: false
	 * ```
	 */
	private static function _is_background_attr_enabled( array $attr_group, bool $strict = false ): bool {
		$has_enabled = isset( $attr_group['enabled'] );

		// If we're making a strict comparison, we'll presume this is disabled if we
		// don't have an `enabled` attribute.
		if ( ! $has_enabled && $strict ) {
			return false;
		}

		// If we don't have an `enabled` attribute at this point, we'll presume that
		// the setting is enabled.
		if ( ! $has_enabled ) {
			return true;
		}

		// If we have an `enabled` attribute, we'll return whether it's set to `on`.
		return 'on' === $attr_group['enabled'];
	}

	/**
	 * Inherit attribute values for background.
	 *
	 * This function takes an array of attribute values with inherited values and a breakpoint and state
	 * to determine the appropriate inheritance. It then merges the attribute values from the specified
	 * breakpoint and state with their parent values, accounting for enabled or disabled attributes.
	 *
	 * @since ??
	 *
	 * @param array  $attr_value_with_inherited The attribute values with inherited values. This is a multi-dimensional array
	 *                                          with breakpoints and states as keys.
	 * @param string $breakpoint                The breakpoint to get the inheritance breakpoint for. One of `desktop`, `tablet`, `phone`.
	 * @param string $state                     The state to get the inheritance breakpoint for.
	 *                                          One of `value`, `hover`, `tablet_value`, `tablet_hover`, `phone_value`, `phone_hover`.
	 *
	 * @return array The attribute values with inherited values.
	 *
	 * @example:
	 * ```php
	 *     $attr_value_with_inherited = [
	 *         'desktop' => [
	 *             'value' => [
	 *                 'color' => '#000',
	 *                 'gradient' => [
	 *                     'enabled' => 'on',
	 *                     'stops' => [
	 *                         'stop1' => '#fff',
	 *                         'stop2' => '#000'
	 *                     ]
	 *                 ],
	 *                 'image' => [
	 *                     'enabled' => 'off',
	 *                     'source' => 'image.jpg'
	 *                 ]
	 *             ]
	 *         ]
	 *     ];
	 *     $breakpoint = 'desktop';
	 *     $state = 'value';
	 *
	 *     $result = ModuleUtils_inherit_background_values( $attr_value_with_inherited, $breakpoint, $state );
	 *
	 *     // $result is:
	 *     // [
	 *     //     'desktop' => [
	 *     //         'value' => [
	 *     //             'color' => '#000',
	 *     //             'gradient' => [
	 *     //                 'enabled' => 'on',
	 *     //                 'stops' => [
	 *     //                     'stop1' => '#fff',
	 *     //                     'stop2' => '#000'
	 *     //                 ]
	 *     //             ],
	 *     //             'image' => [
	 *     //                 'enabled' => 'off',
	 *     //                 'source' => 'image.jpg'
	 *     //             ]
	 *     //         ]
	 *     //     ]
	 *     // ]
	 * ```
	 */
	private static function _inherit_background_values( array $attr_value_with_inherited, string $breakpoint, string $state ): array {
		$inherit_breakpoint = self::get_inherit_breakpoint( $breakpoint, $state );
		$inherit_state      = self::get_inherit_state( $breakpoint, $state );

		$attr_values        = $attr_value_with_inherited[ $breakpoint ][ $state ] ?? [];
		$attr_parent_values = $attr_value_with_inherited[ $inherit_breakpoint ][ $inherit_state ] ?? [];

		$attr_value_with_inherited[ $breakpoint ][ $state ] = self::_array_trim(
			[
				'color'    => $attr_values['color'] ?? $attr_parent_values['color'] ?? null,
				'gradient' => self::_array_trim(
					self::_is_background_attr_enabled( $attr_values['gradient'] ?? [] )
						? array_merge(
							[],
							$attr_parent_values['gradient'] ?? [],
							$attr_values['gradient'] ?? [],
							[
								'stops' => $attr_values['gradient']['stops'] ?? $attr_parent_values['gradient']['stops'] ?? [],
							]
						)
						: [
							'enabled' => $attr_values['gradient']['enabled'] ?? 'off',
						]
				),
				'image'    => self::_array_trim(
					self::_is_background_attr_enabled( $attr_values['image'] ?? [] )
					? array_merge(
						[],
						$attr_parent_values['image'] ?? [],
						$attr_values['image'] ?? []
					)
					: [
						'enabled' => $attr_values['image']['enabled'] ?? 'off',
					]
				),
				'mask'     => self::_array_trim(
					is_array( $attr_values['mask'] ?? [] ) && self::_is_background_attr_enabled( $attr_values['mask'] ?? [] )
						? array_merge( [], $attr_parent_values['mask'] ?? [], $attr_values['mask'] ?? [] )
						: [
							'enabled' => $attr_values['mask']['enabled'],
						]
				),
				'pattern'  => self::_array_trim(
					is_array( $attr_values['pattern'] ?? [] ) && self::_is_background_attr_enabled( $attr_values['pattern'] ?? [] )
						? array_merge( [], $attr_parent_values['pattern'] ?? [], $attr_values['pattern'] ?? [] )
						: [
							'enabled' => $attr_values['pattern']['enabled'],
						]
				),
			]
		);

		return $attr_value_with_inherited;
	}

	/**
	 * Get attribute values with inherited values.
	 *
	 * This function compares each breakpoint and state using the `inheritBreakpoints` object
	 * and, starting with `phone.sticky` and moving to `desktop.value`, deletes any object
	 * that completely matches its parent breakpoint and state. It will always keep the
	 * `desktop.value` object if it exists. The function retrieves the default `attrValue` on
	 * the current breakpoint and state.
	 *
	 * @since ??
	 *
	 * @param array  $attr_to_be_returned The attribute values with inherited values.
	 * @param string $breakpoint          The breakpoint to get the inheritance breakpoint for. One of `desktop`, `tablet`, `phone`.
	 * @param string $state               The state to get the inheritance breakpoint for.
	 *                                    One of `value`, `hover`, `tablet_value`, `tablet_hover`, `phone_value`, `phone_hover`.
	 *
	 * @return array Cleaned attribute values.
	 *
	 * @example:
	 * ```php
	 * $attr_to_be_returned = [
	 *     'desktop' => [
	 *         'value' => [
	 *             'color'    => '#ffffff',
	 *             'mask'     => [],
	 *             'pattern'  => [],
	 *             'image'    => [],
	 *             'gradient' => [],
	 *         ],
	 *     ],
	 *     'tablet'  => [
	 *         'value' => [
	 *             'color'    => '#000000',
	 *             'mask'     => [],
	 *             'pattern'  => [],
	 *             'image'    => [],
	 *             'gradient' => [],
	 *         ],
	 *     ],
	 *     'phone'   => [
	 *         'value' => [
	 *             'color'    => '#ff0000',
	 *             'mask'     => [],
	 *             'pattern'  => [],
	 *         'image'    => [],
	 *         'gradient' => [],
	 *     ],
	 *    ],
	 * ];
	 * $breakpoint = 'desktop';
	 * $state = 'value';
	 *
	 * $result = ModuleUtils_return_background_values( $attr_to_be_returned, $breakpoint, $state );
	 * // $result is:
	 * // [
	 * //     'desktop' => [
	 * //         'value' => [
	 * //             'color' => '#ffffff',
	 * //         ],
	 * //     ],
	 * //     'tablet' => [
	 * //         'value' => [
	 * //             'color' => '#000000',
	 * //         ],
	 * //     ],
	 * //     'phone' => [
	 * //         'value' => [
	 * //             'color' => '#ff0000',
	 * //         ],
	 * //     ],
	 * // ]
	 * ```
	 */
	private static function _return_background_values( array $attr_to_be_returned, string $breakpoint, string $state ): array {
		$parent_breakpoint = self::get_inherit_breakpoint( $breakpoint, $state );
		$parent_state      = self::get_inherit_state( $breakpoint, $state );
		$is_desktop_value  = 'desktop' === $breakpoint && 'value' === $state;

		// Background Color.
		$parent_color           = $attr_to_be_returned[ $parent_breakpoint ][ $parent_state ]['color'] ?? null;
		$current_color          = $attr_to_be_returned[ $breakpoint ][ $state ]['color'] ?? null;
		$is_colors_match        = $current_color === $parent_color;
		$is_current_color_empty = is_null( $current_color );

		/*
		 * If the current color is an empty string, then it's intentionally blank
		 * and should not inherit the parent's value.
		 */
		$color_or_initial = '' === $current_color ? 'initial' : $current_color;

		// Add inherited background color values if necessary.
		if ( ! $is_current_color_empty && ( $is_desktop_value || ! $is_colors_match ) ) {
			$attr_to_be_returned[ $breakpoint ][ $state ]['color'] = $color_or_initial;
		} else {
			unset( $attr_to_be_returned[ $breakpoint ][ $state ]['color'] );
		}

		// Background Mask.
		$parent_mask           = $attr_to_be_returned[ $parent_breakpoint ][ $parent_state ]['mask'] ?? [];
		$current_mask          = $attr_to_be_returned[ $breakpoint ][ $state ]['mask'] ?? [];
		$is_masks_match        = self::_is_same( $current_mask, $parent_mask );
		$is_current_mask_empty = empty( self::_array_trim( $current_mask ) );

		// Add inherited background mask values if necessary.
		if ( ! $is_current_mask_empty && ( $is_desktop_value || ! $is_masks_match ) ) {
			$attr_to_be_returned[ $breakpoint ][ $state ]['mask'] = $current_mask;
		} else {
			unset( $attr_to_be_returned[ $breakpoint ][ $state ]['mask'] );
		}

		// Background Pattern.
		$parent_pattern           = $attr_to_be_returned[ $parent_breakpoint ][ $parent_state ]['pattern'] ?? [];
		$current_pattern          = $attr_to_be_returned[ $breakpoint ][ $state ]['pattern'] ?? [];
		$is_patterns_match        = self::_is_same( $current_pattern, $parent_pattern );
		$is_current_pattern_empty = empty( self::_array_trim( $current_pattern ) );

		// Add inherited background pattern values if necessary.
		if ( ! $is_current_pattern_empty && ( $is_desktop_value || ! $is_patterns_match ) ) {
			$attr_to_be_returned[ $breakpoint ][ $state ]['pattern'] = $current_pattern;
		} else {
			unset( $attr_to_be_returned[ $breakpoint ][ $state ]['pattern'] );
		}

		// Background Image and Gradient.
		$parent_gradient           = $attr_to_be_returned[ $parent_breakpoint ][ $parent_state ]['gradient'] ?? [];
		$current_gradient          = $attr_to_be_returned[ $breakpoint ][ $state ]['gradient'] ?? [];
		$is_gradients_match        = self::_is_same( $current_gradient, $parent_gradient );
		$is_current_gradient_empty = empty( self::_array_trim( $current_gradient ) );

		$parent_image           = $attr_to_be_returned[ $parent_breakpoint ][ $parent_state ]['image'] ?? [];
		$current_image          = $attr_to_be_returned[ $breakpoint ][ $state ]['image'] ?? [];
		$is_images_match        = self::_is_same( $current_image, $parent_image );
		$is_current_image_empty = empty( self::_array_trim( $current_image ) );

		$is_image_and_gradient_empty = $is_current_image_empty && $is_current_gradient_empty;

		// Add background image and gradient values together.
		if ( ! $is_image_and_gradient_empty && ( $is_desktop_value || ( ! $is_images_match || ! $is_gradients_match ) ) ) {
			if ( ! $is_current_gradient_empty ) {
				$attr_to_be_returned[ $breakpoint ][ $state ]['gradient'] = $current_gradient;
			}
			if ( ! $is_current_image_empty ) {
				$attr_to_be_returned[ $breakpoint ][ $state ]['image'] = $current_image;
			}
		} elseif ( $is_image_and_gradient_empty ) {
			// If both image and gradient are empty, inherit from parent one at a time.
			if ( ! $is_gradients_match && ! empty( $parent_gradient ) ) {
				$attr_to_be_returned[ $breakpoint ][ $state ]['gradient'] = $parent_gradient;
			}
			if ( ! $is_images_match && ! empty( $parent_image ) ) {
				$attr_to_be_returned[ $breakpoint ][ $state ]['image'] = $parent_image;
			}
		}

		if ( ! $is_desktop_value ) {
			$is_images_match    = false;
			$is_gradients_match = false;

			if (
				self::_is_same(
					$attr_to_be_returned[ $breakpoint ][ $state ]['image'] ?? [],
					$attr_to_be_returned[ $parent_breakpoint ][ $parent_state ]['image'] ?? []
				)
			) {
				$is_images_match = true;
			}

			if (
				self::_is_same(
					$attr_to_be_returned[ $breakpoint ][ $state ]['gradient'] ?? [],
					$attr_to_be_returned[ $parent_breakpoint ][ $parent_state ]['gradient'] ?? []
				)
			) {
				$is_gradients_match = true;
			}

			if ( $is_images_match && $is_gradients_match ) {
				unset( $attr_to_be_returned[ $breakpoint ][ $state ]['image'] );
				unset( $attr_to_be_returned[ $breakpoint ][ $state ]['gradient'] );
			}

			if ( isset( $attr_to_be_returned[ $breakpoint ][ $state ] ) ) {
				// If the entire background style is empty, remove it.
				if ( empty( self::_array_trim( $attr_to_be_returned[ $breakpoint ][ $state ] ) ) ) {
					unset( $attr_to_be_returned[ $breakpoint ][ $state ] );
				}

				// If the entire breakpoint style matches the parent, remove it.
				if (
					self::_is_same(
						$attr_to_be_returned[ $breakpoint ][ $state ] ?? [],
						$attr_to_be_returned[ $parent_breakpoint ][ $parent_state ] ?? []
					)
				) {
					unset( $attr_to_be_returned[ $breakpoint ][ $state ] );
				}
			}
		}

		if ( ! $is_desktop_value && isset( $attr_to_be_returned[ $breakpoint ][ $state ] ) ) {
			// If the entire background style is empty, remove it.
			if ( empty( self::_array_trim( $attr_to_be_returned[ $breakpoint ][ $state ] ) ) ) {
				unset( $attr_to_be_returned[ $breakpoint ][ $state ] );
			}

			// If the entire breakpoint style matches the parent, remove it.
			if (
				self::_is_same(
					$attr_to_be_returned[ $breakpoint ][ $state ] ?? [],
					$attr_to_be_returned[ $parent_breakpoint ][ $parent_state ] ?? []
				)
			) {
				unset( $attr_to_be_returned[ $breakpoint ][ $state ] );
			}
		}

		return $attr_to_be_returned;
	}

	/**
	 * Get and inherit background attributes for all breakpoints and states.
	 *
	 * Iterates through each breakpoint and state to inherit values from the previous
	 * breakpoint and state if they are not set. Also removes values that are the
	 * same as the inherited value.
	 *
	 * @since ???
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type array $attr An array of module attribute data.
	 * }
	 *
	 * @return array An array of background attributes with inherited values.
	 *
	 * @example:
	 * ```php
	 * $args = [
	 *     'attr' => [
	 *         'desktop' => [
	 *             'value' => 'red',
	 *             'hover' => 'blue',
	 *         ],
	 *         'tablet' => [
	 *             'value' => null,
	 *             'hover' => 'green',
	 *         ],
	 *     ],
	 * ];
	 *
	 * $result = ModuleUtils::get_and_inherit_background_attr( $args );
	 * ```
	 *
	 * @output:
	 * ```php
	 *   [
	 *       'desktop' => [
	 *           'value' => 'red',
	 *           'hover' => 'blue',
	 *       ],
	 *       'tablet' => [
	 *           'value' => 'red',
	 *           'hover' => 'green',
	 *       ],
	 *   ]
	 * ```
	 */
	public static function get_and_inherit_background_attr( array $args ): array {
		$initial_style_attr = $args['attr'] ?? [];

		// Pre-populate with the passed style attributes.
		$attr_value_with_inherited = $initial_style_attr;

		// If we have a background style, we need to check if it contains
		// multiple breakpoints and/or states. If it does, we need to step
		// through each breakpoint and state and inherit values from the
		// previous breakpoint and state if they are not set.
		if ( ! empty( $attr_value_with_inherited ) ) {
			// TODO feat(D5, Responsive Views): Replace this with a loop once we have a sort/priority system for breakpoints.

			// Desktop attributes first, if they exist.
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'desktop', 'value' );
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'desktop', 'hover' );
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'desktop', 'sticky' );

			// Tablet attributes second, if they exist.
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'tablet', 'value' );
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'tablet', 'hover' );
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'tablet', 'sticky' );

			// Phone attributes last, if they exist.
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'phone', 'value' );
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'phone', 'hover' );
			$attr_value_with_inherited = self::_inherit_background_values( $attr_value_with_inherited, 'phone', 'sticky' );
		}

		// Pre-populate with the passed style attributes.
		$attr_to_be_returned = $attr_value_with_inherited;

		// If we have a background style, we need to check if any values is the
		// same as the inherited breakpoint/state value. If it is, we can delete
		// it from the inheritor.
		if ( ! empty( $attr_to_be_returned ) ) {
			// TODO feat(D5, Responsive Views): Replace this with a loop once we have a sort/priority system for breakpoints.

			// Phone attributes first, if they exist.
			if ( array_key_exists( 'phone', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'phone', 'sticky' );
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'phone', 'hover' );
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'phone', 'value' );

				// Delete the phone breakpoint if it is empty.
				if ( empty( self::_array_trim( $attr_to_be_returned['phone'] ) ) ) {
					unset( $attr_to_be_returned['phone'] );
				}
			}

			// Tablet attributes second, if they exist.
			if ( array_key_exists( 'tablet', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'tablet', 'sticky' );
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'tablet', 'hover' );
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'tablet', 'value' );

				// Delete the tablet breakpoint if it is empty.
				if ( empty( self::_array_trim( $attr_to_be_returned['tablet'] ) ) ) {
					unset( $attr_to_be_returned['tablet'] );
				}
			}

			// Desktop attributes last, if they exist.
			if ( array_key_exists( 'desktop', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'desktop', 'sticky' );
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'desktop', 'hover' );
				$attr_to_be_returned = self::_return_background_values( $attr_to_be_returned, 'desktop', 'value' );

				// Delete the desktop breakpoint if it is empty.
				if ( empty( self::_array_trim( $attr_to_be_returned['desktop'] ) ) ) {
					unset( $attr_to_be_returned['desktop'] );
				}
			}
		}

		return $attr_to_be_returned;
	}

	/**
	 * Inherit icon style attribute values for a given breakpoint and state.
	 *
	 * This function takes an array of attribute values with inherited values and updates them
	 * for a specific breakpoint and state.
	 *
	 * If the breakpoint or state is not set, it will be defined.
	 * If the state is not set, it will be inherited completely from the previous breakpoint.
	 * Finally, it will merge the inherited printed style attribute with the attribute values and return the updated array.
	 *
	 * @since ??
	 *
	 * @param array  $attr_value_with_inherited The attribute values with inherited values.
	 * @param string $breakpoint                The breakpoint to get the inheritance breakpoint for.
	 * @param string $state                     The state to get the inheritance breakpoint for.
	 *
	 * @return array The updated attribute values with inherited values.
	 *
	 * @example:
	 * ```php
	 * $attr_value_with_inherited = [
	 *    'desktop' => [
	 *        'hover' => [
	 *            'useSize' => 'on',
	 *            'size' => '12px',
	 *        ],
	 *    ],
	 *    'tablet' => [
	 *        'value' => [
	 *            'useSize' => 'off',
	 *            'size' => '10px',
	 *        ],
	 *        'hover' => [
	 *            'useSize' => 'on',
	 *            'size' => '20px',
	 *        ],
	 *        'sticky' => [
	 *            'useSize' => 'on',
	 *            'size' => '25px',
	 *        ],
	 *    ],
	 * ];
	 *
	 * $breakpoint = 'tablet';
	 * $state = 'value';
	 *
	 * $updated_attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, $breakpoint, $state );
	 * ```
	 */
	private static function _inherit_icon_style_values( array $attr_value_with_inherited, string $breakpoint, string $state ): array {
		$inherit_breakpoint = self::get_inherit_breakpoint( $breakpoint, $state );
		$inherit_state      = self::get_inherit_state( $breakpoint, $state );

		$attr_values        = $attr_value_with_inherited[ $breakpoint ][ $state ] ?? [];
		$attr_parent_values = $attr_value_with_inherited[ $inherit_breakpoint ][ $inherit_state ] ?? [];

		$attr_value_with_inherited[ $breakpoint ][ $state ] = self::_array_trim(
			[
				'color'   => $attr_values['color'] ?? $attr_parent_values['color'] ?? null,
				'useSize' => $attr_values['useSize'] ?? $attr_parent_values['useSize'] ?? '',
				'size'    => $attr_values['size'] ?? $attr_parent_values['size'] ?? '',
				'weight'  => $attr_values['weight'] ?? $attr_parent_values['weight'] ?? '',
				'unicode' => $attr_values['unicode'] ?? $attr_parent_values['unicode'] ?? '',
				'type'    => $attr_values['type'] ?? $attr_parent_values['type'] ?? '',
				'show'    => $attr_values['show'] ?? $attr_parent_values['show'] ?? '',
			]
		);

		return $attr_value_with_inherited;
	}

	/**
	 * Return attribute values with inherited icon style CSS declarations.
	 *
	 * This function takes an array of attribute values with inherited values and calculates the final
	 * icon style CSS declarations for a given breakpoint and state.
	 * It checks if the attribute values match the inherited values and removes any redundant
	 * entries (i.e the values are the same as the parent breakpoint and state).
	 * It also filters out empty attribute values.
	 *
	 * @since ??
	 *
	 * @param array  $attr_to_be_returned The attribute values with inherited values.
	 * @param string $breakpoint          The breakpoint to calculate the inheritance for.
	 * @param string $state               The state to calculate the inheritance for.
	 *
	 * @return array The attribute values after applying inheritance and filtering.
	 *
	 * @example:
	 * ```php
	 * // Single usage example:
	 * $attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'desktop', 'hover' );
	 *
	 * // Multiple usage example:
	 * if ( array_key_exists( 'phone', $attr_to_be_returned ) ) {
	 *     $attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'phone', 'sticky' );
	 *     $attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'phone', 'hover' );
	 *     $attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'phone', 'value' );
	 * }
	 * ```
	 */
	private static function _return_icon_style_values( array $attr_to_be_returned, string $breakpoint, string $state ): array {
		$parent_breakpoint  = self::get_inherit_breakpoint( $breakpoint, $state );
		$parent_state       = self::get_inherit_state( $breakpoint, $state );
		$is_desktop_value   = 'desktop' === $breakpoint && 'value' === $state;
		$current_icon_style = $attr_to_be_returned[ $breakpoint ][ $state ] ?? [];
		$parent_icon_style  = $attr_to_be_returned[ $parent_breakpoint ][ $parent_state ] ?? [];
		$icon_styles_match  = self::_is_same( $current_icon_style, $parent_icon_style );
		$is_current_empty   = ! $current_icon_style;

		// Update the attr object to add inherited icon-style values if toJSON matches.
		if ( $is_desktop_value && ! $is_current_empty ) {
			$attr_to_be_returned[ $breakpoint ][ $state ] = $current_icon_style;
		} elseif ( ! $icon_styles_match && ! $is_current_empty ) {
			$attr_to_be_returned[ $breakpoint ][ $state ] = $current_icon_style;
		} else {
			unset( $attr_to_be_returned[ $breakpoint ][ $state ] );
		}

		return $attr_to_be_returned;
	}

	/**
	 * Get and inherit icon style CSS declarations with inheritance for all breakpoints and states.
	 *
	 * This function takes an array of attribute values with inherited values and updates them
	 * for a specific breakpoint and state. If the breakpoint or state is not set, it will be defined.
	 * If the state is not set, it will be inherited completely from the previous breakpoint.
	 * Finally, it will merge the inherited printed style attribute with the attribute values and return the updated array.
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type array $attr The attribute values with inherited values.
	 * }
	 *
	 * @return array The attribute values with updated inheritance.
	 *
	 * @example:
	 * ```php
	 *   $args = [
	 *     'attr' => [
	 *       'desktop' => [
	 *         'value' => [
	 *           'useSize' => 'on',
	 *           'size' => '2px',
	 *         ],
	 *         'hover' => [
	 *           'useSize' => 'on',
	 *         ],
	 *         'sticky' => [
	 *           'useSize' => 'on',
	 *           'size' => '8px',
	 *         ],
	 *       ],
	 *       'tablet' => [
	 *         'value' => [
	 *           'useSize' => 'on',
	 *           'size' => '22px',
	 *         ],
	 *         'hover' => [
	 *           'size' => '35px',
	 *         ],
	 *         'sticky' => [
	 *           'size' => '2px',
	 *         ],
	 *       ],
	 *       'phone' => [
	 *         'value' => [
	 *           'useSize' => 'on',
	 *           'size' => '12px',
	 *         ],
	 *         'sticky' => [
	 *           'useSize' => 'on',
	 *           'size' => '8px',
	 *         ],
	 *       ]
	 *     ]
	 *   ];
	 *
	 *   $result = ModuleUtils::get_and_inherit_icon_style_attr( $args );
	 * ```

	 * @example:
	 * ```php
	 *   $args = [
	 *     'attr' => [
	 *       'desktop' => [
	 *         'value' => [
	 *           'useSize' => 'on',
	 *           'size' => '2px',
	 *         ],
	 *         'hover' => [
	 *           'useSize' => 'on',
	 *           'size' => '2px',
	 *         ],
	 *         'sticky' => [
	 *           'useSize' => 'on',
	 *           'size' => '8px',
	 *         ],
	 *       ],
	 *       'tablet' => [
	 *         'value' => [
	 *           'useSize' => 'on',
	 *           'size' => '22px',
	 *         ],
	 *         'hover' => [
	 *           'useSize' => 'on',
	 *           'size' => '35px',
	 *         ],
	 *         'sticky' => [
	 *           'useSize' => 'on',
	 *           'size' => '2px',
	 *         ],
	 *       ],
	 *       'phone' => [
	 *         'value' => [
	 *           'useSize' => 'on',
	 *           'size' => '12px',
	 *         ],
	 *         'hover' => [
	 *           'useSize' => 'on',
	 *           'size' => '12px',
	 *         ],
	 *         'sticky' => [
	 *           'useSize' => 'on',
	 *           'size' => '8px',
	 *         ],
	 *       ]
	 *     ]
	 *   ];
	 *
	 *   $result = ModuleUtils::get_and_inherit_icon_style_attr( $args );
	 * ```
	 */
	public static function get_and_inherit_icon_style_attr( array $args ): array {
		$initial_style_attr = $args['attr'] ?? [];

		// Pre-populate with the passed style attributes.
		$attr_value_with_inherited = $initial_style_attr;

		// If we have a icon-style style, we need to check if it contains
		// multiple breakpoints and/or states. If it does, we need to step
		// through each breakpoint and state and inherit values from the
		// previous breakpoint and state if they are not set.
		if ( ! empty( $attr_value_with_inherited ) ) {
			// TODO feat(D5, Responsive Views): Replace this with a loop once we have a sort/priority system for breakpoints.

			// Desktop attributes first, if they exist.
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'desktop', 'value' );
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'desktop', 'hover' );
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'desktop', 'sticky' );

			// Tablet attributes second, if they exist.
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'tablet', 'value' );
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'tablet', 'hover' );
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'tablet', 'sticky' );

			// Phone attributes last, if they exist.
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'phone', 'value' );
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'phone', 'hover' );
			$attr_value_with_inherited = self::_inherit_icon_style_values( $attr_value_with_inherited, 'phone', 'sticky' );
		}

		// Pre-populate with the passed style attributes.
		$attr_to_be_returned = $attr_value_with_inherited;

		// If we have a icon-style style, we need to check if any values is the
		// same as the inherited breakpoint/state value. If it is, we can delete
		// it from the inheritor.
		if ( ! empty( $attr_to_be_returned ) ) {
			// TODO feat(D5, Responsive Views): Replace this with a loop once we have a sort/priority system for breakpoints.

			// Phone attributes first, if they exist.
			if ( array_key_exists( 'phone', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'phone', 'sticky' );
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'phone', 'hover' );
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'phone', 'value' );

				// Delete the phone breakpoint if it is empty.
				if ( empty( self::_array_trim( $attr_to_be_returned['phone'] ) ) ) {
					unset( $attr_to_be_returned['phone'] );
				}
			}

			// Tablet attributes second, if they exist.
			if ( array_key_exists( 'tablet', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'tablet', 'sticky' );
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'tablet', 'hover' );
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'tablet', 'value' );

				// Delete the tablet breakpoint if it is empty.
				if ( empty( self::_array_trim( $attr_to_be_returned['tablet'] ) ) ) {
					unset( $attr_to_be_returned['tablet'] );
				}
			}

			// Desktop attributes last, if they exist.
			if ( array_key_exists( 'desktop', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'desktop', 'sticky' );
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'desktop', 'hover' );
				$attr_to_be_returned = self::_return_icon_style_values( $attr_to_be_returned, 'desktop', 'value' );

				// Delete the desktop breakpoint if it is empty.
				if ( empty( self::_array_trim( $attr_to_be_returned['desktop'] ) ) ) {
					unset( $attr_to_be_returned['desktop'] );
				}
			}
		}

		return $attr_to_be_returned;
	}

	/**
	 * Inherit text shadow attribute values for a given breakpoint and state.
	 *
	 * This function takes an array of attribute values with inherited values and updates them
	 * for a specific breakpoint and state.
	 *
	 * If the breakpoint or state is not set, it will be defined.
	 * If the state is not set, it will be inherited completely from the previous breakpoint.
	 * Finally, it will merge the inherited printed style attribute with the attribute values and return the updated array.
	 *
	 * @since ??
	 *
	 * @param array  $attr_value_with_inherited The attribute values with inherited values.
	 * @param string $breakpoint                The breakpoint to get the inheritance breakpoint for.
	 * @param string $state                     The state to get the inheritance breakpoint for.
	 *
	 * @return array The updated attribute values with inherited values.
	 *
	 * @example:
	 * ```php
	 * $attr_value_with_inherited = [
	 *    'desktop' => [
	 *        'hover' => [
	 *            'color' => '#000000',
	 *            'text-shadow' => '2px 2px 2px #000000',
	 *        ],
	 *    ],
	 *    'tablet' => [
	 *        'value' => [
	 *            'color' => '#ffffff',
	 *            'text-shadow' => 'none',
	 *        ],
	 *        'hover' => [
	 *            'color' => '#ff0000',
	 *            'text-shadow' => 'none',
	 *        ],
	 *        'sticky' => [
	 *            'color' => '#00ff00',
	 *            'text-shadow' => 'none',
	 *        ],
	 *    ],
	 * ];
	 *
	 * $breakpoint = 'tablet';
	 * $state = 'value';
	 *
	 * $updated_attr_value_with_inherited = ModuleUtils::_inherit_text_shadow_values( $attr_value_with_inherited, $breakpoint, $state );
	 * ```
	 */
	private static function _inherit_text_shadow_values( array $attr_value_with_inherited, string $breakpoint, string $state ): array {
		$inherit_breakpoint = self::get_inherit_breakpoint( $breakpoint, $state );
		$inherit_state      = self::get_inherit_state( $breakpoint, $state );

		// If the breakpoint is not set, we need to define it.
		if ( ! isset( $attr_value_with_inherited[ $breakpoint ] ) ) {
			$attr_value_with_inherited[ $breakpoint ] = [];
		}

		// If the state is not set, we need to define it.
		if ( ! isset( $attr_value_with_inherited[ $breakpoint ][ $state ] ) ) {
			$attr_value_with_inherited[ $breakpoint ][ $state ] = [];
		}

		// If the state is not set, we need to inherit it completely from the previous breakpoint.
		if ( isset( $attr_value_with_inherited[ $inherit_breakpoint ][ $inherit_state ] ) && ! isset( $attr_value_with_inherited[ $breakpoint ][ $state ] ) ) {
			$attr_value_with_inherited[ $breakpoint ][ $state ] = $attr_value_with_inherited[ $inherit_breakpoint ][ $inherit_state ];
		}

		// Ensure both previous and current state values are arrays before merging.
		$inherited_values     = $attr_value_with_inherited[ $inherit_breakpoint ][ $inherit_state ] ?? [];
		$current_state_values = $attr_value_with_inherited[ $breakpoint ][ $state ] ?? [];

		// Merge the inherited printed style attribute with the attribute values.
		$attr_value_with_inherited[ $breakpoint ][ $state ] = array_merge( $inherited_values, $current_state_values );

		return $attr_value_with_inherited;
	}

	/**
	 * Return attribute values with inherited text shadow CSS declarations.
	 *
	 * This function takes an array of attribute values with inherited values and calculates the final
	 * text shadow CSS declarations for a given breakpoint and state.
	 * It checks if the attribute values match the inherited values and removes any redundant
	 * entries (i.e the values are the same as the parent breakpoint and state).
	 * It also filters out empty attribute values.
	 *
	 * @since ??
	 *
	 * @param array  $attr_to_be_returned The attribute values with inherited values.
	 * @param string $breakpoint          The breakpoint to calculate the inheritance for.
	 * @param string $state               The state to calculate the inheritance for.
	 *
	 * @return array The attribute values after applying inheritance and filtering.
	 *
	 * @example:
	 * ```php
	 * // Single usage example:
	 * $attr_to_be_returned = ModuleUtils::_return_text_shadow_values( $attr_to_be_returned, 'desktop', 'hover' );
	 *
	 * // Multiple usage example:
	 * if ( array_key_exists( 'phone', $attr_to_be_returned ) ) {
	 *     $attr_to_be_returned = ModuleUtils::_return_text_shadow_values( $attr_to_be_returned, 'phone', 'sticky' );
	 *     $attr_to_be_returned = ModuleUtils::_return_text_shadow_values( $attr_to_be_returned, 'phone', 'hover' );
	 *     $attr_to_be_returned = ModuleUtils::_return_text_shadow_values( $attr_to_be_returned, 'phone', 'value' );
	 * }
	 * ```
	 */
	private static function _return_text_shadow_values( array $attr_to_be_returned, string $breakpoint, string $state ): array {
		$inherit_breakpoint = self::get_inherit_breakpoint( $breakpoint, $state );
		$inherit_state      = self::get_inherit_state( $breakpoint, $state );

		// If the inherited breakpoint is not set, return the attribute values.
		if ( ! isset( $attr_to_be_returned[ $inherit_breakpoint ][ $inherit_state ] ) ) {
			return $attr_to_be_returned;
		}

		// If the attribute value matches the inherited value, we can delete it.
		if ( $attr_to_be_returned[ $breakpoint ][ $state ] === $attr_to_be_returned[ $inherit_breakpoint ][ $inherit_state ] ) {
			unset( $attr_to_be_returned[ $breakpoint ][ $state ] );

			return $attr_to_be_returned;
		}

		// If the attribute value matches the inherited value, we can delete it.
		if ( empty( $attr_to_be_returned[ $breakpoint ][ $state ] ) ) {
			$attr_to_be_returned[ $breakpoint ][ $state ] = array_filter( $attr_to_be_returned[ $breakpoint ][ $state ], 'strlen' );
		}

		return $attr_to_be_returned;
	}

	/**
	 * Get and inherit text shadow CSS declarations with inheritance for all breakpoints and states.
	 *
	 * This function takes an array of attribute values with inherited values and updates them
	 * for a specific breakpoint and state. If the breakpoint or state is not set, it will be defined.
	 * If the state is not set, it will be inherited completely from the previous breakpoint.
	 * Finally, it will merge the inherited printed style attribute with the attribute values and return the updated array.
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type array $attr The attribute values with inherited values.
	 * }
	 *
	 * @return array The attribute values with updated inheritance.
	 *
	 * @example:
	 * ```php
	 *   $args = [
	 *     'attr' => [
	 *       'desktop' => [
	 *         'value' => 'text-shadow: 2px 2px 2px black;',
	 *         'hover' => 'text-shadow: 4px 4px 4px black;',
	 *         'sticky' => 'text-shadow: 8px 8px 8px black;'
	 *       ],
	 *       'tablet' => [
	 *         'value' => 'text-shadow: 3px 3px 3px black;',
	 *         'hover' => 'text-shadow: 5px 5px 5px black;',
	 *         'sticky' => 'text-shadow: 9px 9px 9px black;'
	 *       ],
	 *       'phone' => [
	 *         'value' => 'text-shadow: 6px 6px 6px black;',
	 *         'hover' => 'text-shadow: 7px 7px 7px black;',
	 *         'sticky' => 'text-shadow: 10px 10px 10px black;'
	 *       ]
	 *     ]
	 *   ];
	 *
	 *   $result = ModuleUtils::get_and_inherit_text_shadow_attr( $args );
	 * ```

	 * @example:
	 * ```php
	 *   $args = [
	 *     'attr' => [
	 *       'desktop' => [
	 *         'value' => 'text-shadow: 2px 2px 2px black;',
	 *         'hover' => 'text-shadow: 4px 4px 4px black;',
	 *         'sticky' => 'text-shadow: 8px 8px 8px black;'
	 *       ]
	 *     ]
	 *   ];
	 *
	 *   $result = ModuleUtils::get_and_inherit_text_shadow_attr( $args );
	 * ```
	 */
	public static function get_and_inherit_text_shadow_attr( array $args ): array {
		$initial_style_attr = $args['attr'] ?? [];

		// Pre-populate with the passed style attributes.
		$attr_value_with_inherited = $initial_style_attr;

		// If we have a text-shadow style, we need to check if it contains
		// multiple breakpoints and/or states. If it does, we need to step
		// through each breakpoint and state and inherit values from the
		// previous breakpoint and state if they are not set.
		if ( ! empty( $attr_value_with_inherited ) ) {
			// TODO feat(D5, Responsive Views): Replace this with a loop once we have a sort/priority system for breakpoints.

			// Desktop attributes first, if they exist.
			if ( array_key_exists( 'desktop', $attr_value_with_inherited ) ) {
				$attr_value_with_inherited = self::_inherit_text_shadow_values( $attr_value_with_inherited, 'desktop', 'hover' );
				$attr_value_with_inherited = self::_inherit_text_shadow_values( $attr_value_with_inherited, 'desktop', 'sticky' );
			}

			// Tablet attributes second, if they exist.
			if ( array_key_exists( 'tablet', $attr_value_with_inherited ) ) {
				$attr_value_with_inherited = self::_inherit_text_shadow_values( $attr_value_with_inherited, 'tablet', 'value' );
				$attr_value_with_inherited = self::_inherit_text_shadow_values( $attr_value_with_inherited, 'tablet', 'hover' );
				$attr_value_with_inherited = self::_inherit_text_shadow_values( $attr_value_with_inherited, 'tablet', 'sticky' );
			}

			// Phone attributes last, if they exist.
			if ( array_key_exists( 'phone', $attr_value_with_inherited ) ) {
				$attr_value_with_inherited = self::_inherit_text_shadow_values( $attr_value_with_inherited, 'phone', 'value' );
				$attr_value_with_inherited = self::_inherit_text_shadow_values( $attr_value_with_inherited, 'phone', 'hover' );
				$attr_value_with_inherited = self::_inherit_text_shadow_values( $attr_value_with_inherited, 'phone', 'sticky' );
			}
		}

		// Pre-populate with the passed style attributes.
		$attr_to_be_returned = $attr_value_with_inherited;

		// If we have a text-shadow style, we need to check if any values is the
		// same as the inherited breakpoint/state value. If it is, we can delete
		// it from the inheritor.
		if ( ! empty( $attr_to_be_returned ) ) {
			// TODO feat(D5, Responsive Views): Replace this with a loop once we have a sort/priority system for breakpoints.

			// Phone attributes first, if they exist.
			if ( array_key_exists( 'phone', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_text_shadow_values( $attr_to_be_returned, 'phone', 'sticky' );
				$attr_to_be_returned = self::_return_text_shadow_values( $attr_to_be_returned, 'phone', 'hover' );
				$attr_to_be_returned = self::_return_text_shadow_values( $attr_to_be_returned, 'phone', 'value' );
			}

			// Tablet attributes second, if they exist.
			if ( array_key_exists( 'tablet', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_text_shadow_values( $attr_to_be_returned, 'tablet', 'sticky' );
				$attr_to_be_returned = self::_return_text_shadow_values( $attr_to_be_returned, 'tablet', 'hover' );
				$attr_to_be_returned = self::_return_text_shadow_values( $attr_to_be_returned, 'tablet', 'value' );
			}
			// Desktop attributes last, if they exist.
			if ( array_key_exists( 'desktop', $attr_to_be_returned ) ) {
				$attr_to_be_returned = self::_return_text_shadow_values( $attr_to_be_returned, 'desktop', 'sticky' );
				$attr_to_be_returned = self::_return_text_shadow_values( $attr_to_be_returned, 'desktop', 'hover' );
			}
		}

		return $attr_to_be_returned;
	}

	/**
	 * Get module class by module name.
	 *
	 * This function is equivalent of JS function getModuleClassByName located in
	 * visual-builder/packages/module-utils/src/get-module-class-by-name/index.ts.
	 *
	 * @since ??
	 *
	 * @param string $namespaced_module_name Module name including namespace.
	 *
	 * @return string Module class name with snake case format. Built-in modules will return
	 * class name with `et_pb_` prefix. Third party modules will return class name with `namespace_` prefix.
	 */
	/**
	 * Get the module class name by the given namespaced module name.
	 *
	 * This function is equivalent of JS function:
	 * {@link /docs/builder-api/js/module-utils/get-module-class-by-name/ getModuleClassByName} located in
	 * `@divi/module-utils`.
	 *
	 * This function takes a namespaced module name as input and returns the corresponding module class name.
	 * The namespaced module name should be in the format `namespace/module`.
	 * Built-in modules have a `divi` namespace and have a `et_pb_` prefix in the class name.
	 * Third-party modules have a `namespace` namespace and have a `namespace_` prefix in the class name.
	 *
	 * @since ??
	 *
	 * @param string $namespaced_module_name The namespaced module name.
	 *
	 * @return string The module class name with snake case format.
	 */
	public static function get_module_class_by_name( string $namespaced_module_name ): string {
		$parts = explode( '/', $namespaced_module_name, 2 );

		if ( 2 !== count( $parts ) || ! $parts[0] || ! $parts[1] ) {
			return '';
		}

		$prefix = 'divi' === $parts[0] ? 'et_pb' : TextTransform::snake_case( $parts[0] );

		return $prefix . '_' . TextTransform::snake_case( $parts[1] );
	}

	/**
	 * Get subname value of attr and/or its inherited value from larger breakpoint / default state.
	 *
	 * This function takes an array of arguments and retrieves the value of a subname attribute based on the provided arguments.
	 *
	 * Getter and inheritance model can be changed based on `mode` parameter:
	 * 1. `get`                  : Get attr value of given breakpoint + state.
	 * 2. `getAndInheritAll`     : Get attr value combined by all possible inherited attr value on all larger breakpoints.
	 * 3. `getAndInheritClosest` : Get attr value combined by inherited attr value from closest available breakpoint.
	 * 4. `getOrInheritAll`      : Get attr value or inherited attr value from all larger breakpoints.
	 * 5. `getOrInheritClosest`  : Get attr value or inherited attr value from closest available breakpoint.
	 * 6. `inheritAll`           : Get inherited attr value from all larger breakpoints.
	 * 7. `inheritClosest`       : Get inherited attr value from all closest available breakpoint.
	 *
	 * See below for inherited attribute fallback flow:
	 *
	 * |        | value | hover | sticky |
	 * |--------|-------|-------|--------|
	 * | Desktop|   *   |  <--  |  <--   |
	 * | Tablet |   ^   |  <--  |  <--   |
	 * | Phone  |   ^   |  <--  |  <--   |
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type array $attr           The main attribute array from which the subname value will be extracted.
	 *     @type string $breakpoint    The breakpoint value to consider while retrieving the subname value.
	 *     @type string $state         The state value to consider while retrieving the subname value.
	 *     @type string $defaultValue  Optional. The default value to return if the subname value is not found. Default empty string.
	 *     @type string $mode          Optional. The mode to control the retrieval behavior. Default is `getOrInheritAll`.
	 *     @type string $subname       The subname value to retrieve from the attribute array.
	 * }
	 * @return mixed The retrieved subname value.
	 *               Returns the default value if the subname value is not found.
	 *
	 * @example:
	 * ```php
	 * $args = [
	 *     'attr'           => ['desktop' => ['value' => ['position' => 'none']]],
	 *     'breakpoint'     => 'desktop',
	 *     'state'          => '',
	 *     'defaultValue'   => '',
	 *     'mode'           => 'getOrInheritAll',
	 *     'subname'        => 'position',
	 * ];
	 *
	 * $subname_value = ModuleUtils::get_attr_subname_value( $args );
	 * ```
	 *
	 * @example:
	 * ```php
	 * $args = [
	 *     'attr'           => ['desktop' => ['value' => ['alignment' => 'center']]],
	 *     'breakpoint'     => '',
	 *     'state'          => '',
	 *     'defaultValue'   => '',
	 *     'mode'           => 'getOrInheritAll',
	 *     'subname'        => 'alignment',
	 * ];
	 *
	 * $subname_value = ModuleUtils::get_attr_subname_value( $args );
	 * ```
	 */
	public static function get_attr_subname_value( array $args ) {
		$args = wp_parse_args(
			$args,
			[
				'mode'         => 'getOrInheritAll',
				'defaultValue' => '',
			]
		);

		$attr          = $args['attr'];
		$breakpoint    = $args['breakpoint'];
		$state         = $args['state'];
		$default_value = $args['defaultValue'];
		$mode          = $args['mode'];
		$subname       = $args['subname'];

		$attr_value = self::get_attr_value(
			[
				'attr'       => $attr,
				'breakpoint' => $breakpoint,
				'state'      => $state,
				'mode'       => $mode,
			]
		);

		if ( ! is_array( $attr_value ) ) {
			$attr_value = [];
		}

		return ArrayUtility::get_value( $attr_value, $subname, $default_value );
	}

	/**
	 * Get module states.
	 *
	 * This function returns an array containing the default states of a module.
	 * This function runs the value through the `divi_module_utils_states` filter.
	 *
	 * This function is equivalent of JS function:
	 * {@link /docs/builder-api/js/module-utils/states/ states } located in `@divi/module-utils`.
	 *
	 * @since ??
	 *
	 * @return array An array of module states. The default values are `['value', 'hover', 'sticky']`.
	 */
	public static function states(): array {
		$states = [
			'value',
			'hover',
			'sticky',
		];

		/**
		 * Filters the module states.
		 *
		 * @since ??
		 *
		 * @param array $states The module states. Default `['value', 'hover', 'sticky']`.
		 */
		return apply_filters( 'divi_module_utils_states', $states );
	}

	/**
	 * Check if an attribute has a value across breakpoints and states based on specified options.
	 *
	 * @since ??
	 *
	 * @param array $attr    The attribute that needs to be checked.
	 * @param array $options {
	 *     Additional options for checking the value (optional).
	 *
	 *     @type string|null   $breakpoint    Optional. The breakpoint to check for the attribute value. One of `desktop`, `tablet`, `phone`.
	 *                                        Default `null`.
	 *     @type string|null   $state         Optional. The state to check for the attribute value.
	 *                                        One of `value`, `hover`, `tablet_value`, `tablet_hover`, `phone_value`, `phone_hover`.
	 *                                        Default `null`.
	 *     @type string|null   $subName       Optional. The sub-name to extract from the attribute value. Default `null`.
	 *     @type callable|null $valueResolver Optional. A callable function to resolve the attribute value. Default `null`.
	 *     @type string|null   $inheritedMode Optional. The inherit mode specifying how the attribute value will be inherited.
	 *                                        One of `inherited`, `inheritedClosest`, `inheritedAll`, `inheritedOrClosest`,
	 *                                        `inheritedOrAll`, `closest`, `all`. Default `getAndInheritAll`.
	 *
	 * @throws InvalidArgumentException If the provided `$options['valueResolver']` is not a callable function.
	 *
	 * @return bool Whether the attribute has a value based on the specified options.
	 *
	 * @example:
	 * ```php
	 * $attr = [
	 *     'desktop' => [
	 *         'normal' => 'Value for desktop',
	 *         'hover' => 'Hover value for desktop',
	 *     ],
	 *     'mobile' => [
	 *         'normal' => 'Value for mobile',
	 *         'hover' => '',
	 *     ],
	 * ];
	 *
	 * // Check if the attribute has a value for the breakpoint 'desktop' and state 'normal'
	 * $result = ModuleUtils::has_value( $attr, [
	 *     'breakpoint' => 'desktop',
	 *     'state' => 'normal',
	 * ] );
	 *
	 * // Check if the attribute has a value for the breakpoint 'mobile' and state 'hover',
	 * // and extract the sub-name 'hover'
	 * $result = ModuleUtils::has_value( $attr, [
	 *     'breakpoint' => 'mobile',
	 *     'state' => 'hover',
	 *     'subName' => 'hover',
	 * ] );
	 *
	 * // Check if the attribute has a value for any breakpoint and state using a value resolver function
	 * $result = ModuleUtils::has_value( $attr, [
	 *     'valueResolver' => function( $value, $args ) {
	 *         // Custom value resolution logic
	 *         // ...
	 *         return $resolved_value;
	 *     },
	 * ] );
	 *
	 * // Check if the attribute has a value for the breakpoint 'desktop' and state 'hover',
	 * // using the 'inherited' mode for resolving the attribute value
	 * $result = ModuleUtils::has_value( $attr, [
	 *     'breakpoint' => 'desktop',
	 *     'state' => 'hover',
	 *     'inheritedMode' => 'inherited',
	 * ] );
	 * ```
	 */
	public static function has_value( array $attr, array $options = [] ): bool {
		if ( ! $attr ) {
			return false;
		}

		$breakpoint        = $options['breakpoint'] ?? null;
		$state             = $options['state'] ?? null;
		$breakpoint_states = MultiViewUtils::get_breakpoints_states();

		// When both breakpoint and state are specified, do not need to iterate through all breakpoints and states.
		// Simply calculate the value based on the specified breakpoint and state.
		if ( $breakpoint && $state ) {
			if ( ! self::_validate_breakpoint_and_state( $breakpoint, $state, $breakpoint_states ) ) {
				return false;
			}

			return self::_calculate_value(
				$attr,
				array_merge(
					$options,
					[
						'breakpoint' => $breakpoint,
						'state'      => $state,
					]
				)
			);
		}

		foreach ( $breakpoint_states as $breakpoint_check => $states ) {
			foreach ( $states as $state_check ) {
				if ( ! self::_validate_breakpoint_and_state( $breakpoint_check, $state_check, $breakpoint_states ) ) {
					continue;
				}

				if ( $breakpoint && $breakpoint_check !== $breakpoint ) {
					continue;
				}

				if ( $state && $state_check !== $state ) {
					continue;
				}

				if ( self::_calculate_value(
					$attr,
					array_merge(
						$options,
						[
							'breakpoint' => $breakpoint_check,
							'state'      => $state_check,
						]
					)
				) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Calculates the value based on the given attributes and options.
	 *
	 * @since ??
	 *
	 * @param array $attr The attributes array.
	 * @param array $options The options array.
	 *
	 * @return bool Returns true if the value is calculated successfully, false otherwise.
	 *
	 * @throws InvalidArgumentException If the provided `$options['valueResolver']` is not a callable function.
	 */
	private static function _calculate_value( array $attr, array $options ) {
		$breakpoint     = $options['breakpoint'] ?? 'desktop';
		$state          = $options['state'] ?? 'value';
		$sub_name       = $options['subName'] ?? null;
		$value_resolver = $options['valueResolver'] ?? null;
		$inherited_mode = $options['inheritedMode'] ?? 'getAndInheritAll';

		if ( ! isset( $attr[ $breakpoint ][ $state ] ) ) {
			return false;
		}

		if ( $inherited_mode ) {
			$value = self::get_attr_value(
				[
					'attr'       => $attr,
					'breakpoint' => $breakpoint,
					'state'      => $state,
					'mode'       => $inherited_mode,
				]
			);
		} else {
			$value = $attr[ $breakpoint ][ $state ];
		}

		if ( $sub_name ) {
			$value = ArrayUtility::get_value( $value ?? [], $sub_name );
		}

		if ( $value_resolver ) {
			if ( is_callable( $value_resolver ) ) {
				$value = call_user_func(
					$value_resolver,
					$value,
					[
						'breakpoint' => $breakpoint,
						'state'      => $state,
					]
				);
			} else {
				throw new InvalidArgumentException( 'The `valueResolver` argument must be a callable function' );
			}
		}

		if ( is_bool( $value ) ) {
			$has_value = $value;
		} elseif ( is_scalar( $value ) ) {
			// Check the value length.
			$has_value = strlen( strval( $value ) ) > 0;
		} else {
			// Check if the value is not empty.
			$has_value = ! ! $value;
		}

		if ( $has_value ) {
			return true;
		}

		return false;
	}

	/**
	 * Validates the given breakpoint and state against the provided breakpoint-states mapping.
	 *
	 * @param string $breakpoint The breakpoint to validate.
	 * @param string $state The state to validate.
	 * @param array  $breakpoint_states_mapping The mapping of breakpoints to states.
	 * @return bool Returns true if the breakpoint and state are valid, false otherwise.
	 */
	private static function _validate_breakpoint_and_state( string $breakpoint, string $state, array $breakpoint_states_mapping ): bool {
		if ( ! isset( $breakpoint_states_mapping[ $breakpoint ] ) ) {
			return false;
		}

		if ( ! in_array( $state, $breakpoint_states_mapping[ $breakpoint ], true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get module class name defined in module.json config.
	 *
	 * - If moduleClassName property in module.json config is falsy, it will fallback to
	 * use convert module name to class name.
	 *
	 * This function is equivalent of JS function getModuleClassName located in
	 * /visual-builder/packages/module-utils/src/get-module-class-name/index.ts
	 *
	 * @since ??
	 *
	 * @param string $module_name Module name.
	 *
	 * @return string Module class name configured in module.json config. Will return empty string on failure.
	 */
	public static function get_module_class_name( $module_name ) {
		$module_config = WP_Block_Type_Registry::get_instance()->get_registered( $module_name );

		$module_class_name = '';

		if ( $module_config ) {
			// phpcs:disable ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- WP use snakeCase in \WP_Block_Parser_Block
			$module_class_name = $module_config->moduleClassName ?? '';
		}

		if ( ! $module_class_name ) {
			$module_class_name = self::get_module_class_by_name( $module_name );
		}

		return $module_class_name;
	}

	/**
	 * Get module order class name base defined in module.json config.
	 *
	 * - If moduleOrderClassName property in module.json config is falsy, it will fallback to
	 * use moduleClassName property that is defined in module.json config.
	 * - If moduleClassName property in module.json config is falsy, it will fallback to
	 * convert module name to class name.
	 *
	 * This function is equivalent of JS function getModuleOrderClassBase located in
	 * /visual-builder/packages/module-utils/src/get-module-order-class-base/index.ts
	 *
	 * @since ??
	 *
	 * @param string $module_name Module name.
	 *
	 * @return string Module order class name base. Will return empty string if module is not found.
	 */
	public static function get_module_order_class_name_base( $module_name ) {
		$module_config = WP_Block_Type_Registry::get_instance()->get_registered( $module_name );

		$module_order_class_name_base = '';

		if ( $module_config ) {
			// phpcs:disable ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- WP use snakeCase in \WP_Block_Parser_Block
			$module_order_class_name_base = $module_config->moduleOrderClassName ?? '';

			if ( ! $module_order_class_name_base ) {
				// phpcs:disable ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- WP use snakeCase in \WP_Block_Parser_Block
				$module_order_class_name_base = $module_config->moduleClassName ?? '';
			}
		}

		if ( ! $module_order_class_name_base ) {
			$module_order_class_name_base = self::get_module_class_by_name( $module_name );
		}

		return $module_order_class_name_base;
	}

	/**
	 * Get module order class name defined in module.json config and add module order index as suffix.
	 *
	 * The base of module order class is populated as follows:
	 * - It will use the moduleOrderClassName property in module.json config if it is not falsy.
	 * - It will use the moduleClassName property in module.json config if it is not falsy.
	 * - It will convert module name to class name
	 *
	 * This function is equivalent of JS function getModuleOrderClassName located in
	 * /visual-builder/packages/module-utils/src/get-module-order-class-name/index.ts
	 *
	 * @since ??
	 *
	 * @param string   $module_id      Module unique ID.
	 * @param int|null $store_instance The ID of instance where this block stored in BlockParserStore class.
	 *
	 * @return string Module order class name. Will return empty string if module is not found.
	 */
	public static function get_module_order_class_name( $module_id, $store_instance = null ) {
		$module_object = BlockParserStore::get( $module_id, $store_instance );

		$layout_type = BlockParserStore::get_layout_type();

		$layout_map = apply_filters(
			'et_builder_order_class_name_suffix_map',
			[
				'default'          => '',
				'et_header_layout' => '_tb_header',
				'et_body_layout'   => '_tb_body',
				'et_footer_layout' => '_tb_footer',
			]
		);

		$selector_suffix = $layout_map[ $layout_type ] ?? '';

		if ( $module_object ) {
			// phpcs:disable ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- WP use snakeCase in \WP_Block_Parser_Block
			$module_order_class_name_base = self::get_module_order_class_name_base( $module_object->blockName );

			if ( $module_order_class_name_base ) {
				// phpcs:disable ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- WP use snakeCase in \WP_Block_Parser_Block
				return $module_order_class_name_base . '_' . $module_object->orderIndex . $selector_suffix;
			}
		}

		return '';
	}

	/**
	 * Loads inline fonts for a module.
	 *
	 * This function enqueues the inline font from a module's inline fonts list,
	 * such that the font assets will be loaded in the browser.
	 *
	 * @since ??
	 *
	 * @param array $attrs The attributes of the module.
	 *
	 * @returns void
	 *
	 * @example
	 * ```php
	 * $attrs = [
	 *     // ... rest of the attributes
	 *    'content' => [
	 *      'decoration' => [
	 *        'inlineFont' => [
	 *          'desktop' => [
	 *            'value' => [
	 *              'families' => [
	 *                'Arima',
	 *                'Yatra One',
	 *              ],
	 *            ],
	 *          ],
	 *        ],
	 *      ],
	 *   ],
	 * ];
	 *
	 * ModuleUtils::load_module_inline_font( $attrs );
	 * ```
	 */
	public static function load_module_inline_font( array $attrs ): void {
		$inline_font = $attrs['content']['decoration']['inlineFont'] ?? [];

		foreach ( $inline_font as $breakpoint => $states ) {
			foreach ( array_keys( $states ) as $state ) {
				$attr_value = self::get_attr_value(
					[
						'attr'       => $inline_font,
						'breakpoint' => $breakpoint,
						'state'      => $state,
						'mode'       => 'getAndInheritAll',
					]
				);

				$font_family_names = $attr_value['families'] ?? [];

				foreach ( $font_family_names as $font_family ) {
					Fonts::add( $font_family );
				}
			}
		}
	}

	/**
	 * Merge Attrs.
	 *
	 * This function is used to merge attrs with default attrs.
	 *
	 * This function is equivalent of JS function mergeAttrs located in
	 * visual-builder/packages/module-utils/src/merge-attrs/index.ts.
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     Optional. An array of options.
	 *
	 *     @type array $defaultAttrs Default attrs.
	 *     @type array $presetAttrs Preset attrs.
	 *     @type array $attrs Attrs.
	 * }
	 *
	 * @return array Merged attrs.
	 */
	public static function merge_attrs( array $args = [] ): array {
		$default_attrs = $args['defaultAttrs'] ?? [];
		$preset_attrs  = $args['presetAttrs'] ?? [];
		$attrs         = $args['attrs'] ?? [];

		return array_replace_recursive( [], $default_attrs, $preset_attrs, $attrs );
	}

	/**
	 * This method sorts the breakpoints based on a predetermined order.
	 *
	 * It takes an associative array as input and returns a new array that has the keys
	 * sorted based on a defined order. If a key doesn't exist in the defined order, it's assumed
	 * it should be placed last. The order currently is 'desktop', 'desktopAbove', 'tablet',
	 * 'tabletOnly', and then 'phone'.
	 *
	 * @since ??
	 *
	 * @param array $attr The associative array which keys are to be sorted.
	 *
	 * @return array $sorted_attr An associative array which keys are sorted in the defined order.
	 *
	 * @example
	 *
	 * $input = ['phone' => 'val1', 'tablet' => 'val2', 'desktop' => 'val3'];
	 * print_r(\ModuleUtils::sort_breakpoints($input));
	 * // Outputs: Array('desktop' => 'val3', 'tablet' => 'val2', 'phone' => 'val1')
	 */
	public static function sort_breakpoints( array $attr ): array {
		// TODO feat(D5, Responsive Views): Replace when we have a sort/priority system for breakpoints.
		$order = [
			'desktop',
			'desktopAbove',
			'tablet',
			'tabletOnly',
			'phone',
		];

		// A copy of the array keys in their current order.
		$keys = array_keys( $attr );

		// Sort the keys based on their position in $order.
		usort(
			$keys,
			function ( $a, $b ) use ( $order ) {
				$position_a = array_search( $a, $order, true );
				$position_b = array_search( $b, $order, true );

				// If a key is not found in $order, we assume it comes last.
				$position_a = false === $position_a ? count( $order ) : $position_a;
				$position_b = false === $position_b ? count( $order ) : $position_b;

				return $position_a <=> $position_b;
			}
		);

		// Create a new array with the keys sorted as required.
		$sorted_attr = [];
		foreach ( $keys as $key ) {
			$sorted_attr[ $key ] = $attr[ $key ];
		}

		return $sorted_attr;
	}

	/**
	 * This method sorts the states based on a predetermined order.
	 *
	 * It takes an associative array as input and returns a new array that has the keys
	 * sorted based on a defined order. If a key doesn't exist in the defined order, it's assumed
	 * it should be placed last. The order currently is 'value', 'hover', 'sticky',
	 * 'tabletOnly', and then 'phone'.
	 *
	 * @since ??
	 *
	 * @param array $attr The associative array which keys are to be sorted.
	 *
	 * @return array $sorted_attr An associative array which keys are sorted in the defined order.
	 *
	 * @example
	 *
	 * $input = ['hover' => 'val1', 'sticky' => 'val2', 'value' => 'val3'];
	 * print_r(\ModuleUtils::sort_breakpoints($input));
	 * // Outputs: Array('value' => 'val3', 'hover' => 'val2', 'sticky' => 'val1')
	 */
	public static function sort_states( array $attr ): array {
		// TODO feat(D5, Responsive Views): Replace when we have a sort/priority system for states.
		$order = [
			'value',
			'hover',
			'sticky',
		];

		// A copy of the array keys in their current order.
		$keys = array_keys( $attr );

		// Sort the keys based on their position in $order.
		usort(
			$keys,
			function ( $a, $b ) use ( $order ) {
				$position_a = array_search( $a, $order, true );
				$position_b = array_search( $b, $order, true );

				// If a key is not found in $order, we assume it comes last.
				$position_a = false === $position_a ? count( $order ) : $position_a;
				$position_b = false === $position_b ? count( $order ) : $position_b;

				return $position_a <=> $position_b;
			}
		);

		// Create a new array with the keys sorted as required.
		$sorted_attr = [];
		foreach ( $keys as $key ) {
			$sorted_attr[ $key ] = $attr[ $key ];
		}

		return $sorted_attr;
	}

	/**
	 * Get the class name for a preset.
	 *
	 * @since ??
	 *
	 * @param string $module_name The module name.
	 * @param array  $attrs The module attributes.
	 *
	 * @return string The class name for the preset.
	 */
	public static function get_preset_class_name( string $module_name, array $attrs ): string {
		$default_preset_id     = GlobalPreset::get_default_preset_id( $module_name );
		$module_name_converted = self::maybe_convert_preset_module_name( $module_name, $attrs );
		$default_preset_item   = GlobalPreset::get_item_by_id( $module_name_converted, $default_preset_id );
		$preset_item           = GlobalPreset::get_item( $module_name_converted, $attrs );
		$preset_id             = $preset_item->get_data_id();

		$is_default_preset = ! $preset_id || $default_preset_id === $preset_id;

		// If the preset is default and it has no attributes, we don't need to add any class.
		if ( $is_default_preset && ( $default_preset_item->is_empty() || ! $preset_item->has_data_attrs() ) ) {
			return '';
		}

		if ( $is_default_preset ) {
			$preset_id = 'default';
		}

		return sprintf( 'preset--module--%s--%s', TextTransform::kebab_case( $module_name_converted ), $preset_id );
	}

	/**
	 * Convert the module name for the section preset.
	 *
	 * @since ??
	 *
	 * @param string $module_name The module name.
	 * @param array  $attrs The module attributes.
	 *
	 * @return string The converted module name.
	 */
	public static function maybe_convert_preset_module_name( string $module_name, array $attrs ): string {
		if ( 'divi/section' === $module_name ) {
			$section_type = $attrs['module']['advanced']['type']['desktop']['value'] ?? null;

			if ( 'fullwidth' === $section_type ) {
					return 'divi/fullwidth-section';
			}

			if ( 'specialty' === $section_type ) {
					return 'divi/specialty-section';
			}
		}

		return $module_name;
	}

	/**
	 * Removes empty attributes.
	 *
	 * This function recursively filters the provided attributes, removing any elements that are empty arrays.
	 * It makes an exception for the 'style' attribute of a 'font' group, which is allowed to be an empty array.
	 *
	 * @since ??
	 *
	 * @param array $attrs The array of attributes to filter.
	 * @return array The filtered array with empty attributes removed.
	 */
	public static function remove_empty_array_attributes( array $attrs ): array {
		return ArrayUtility::filter_deep(
			$attrs,
			function( $value, $key, $path ) {
				// Return true if the value is an empty array and the path is the style attribute of a font group.
				$path_items = array_slice( $path, -3 );

				if ( count( $path_items ) && 'font' === $path_items[0] && 'style' === $key ) {
					return true;
				}

				return is_array( $value ) && empty( $value ) ? false : true;
			}
		);
	}

	/**
	 * Recursively removes keys from a target array that also exist in a reference array.
	 *
	 * This function compares a target array and a reference array. If a key exists in both,
	 * the key-value pair is removed from the target array. This process is performed recursively
	 * for nested arrays. If the reference array is empty, the target array is returned without changes.
	 *
	 * @since ??
	 *
	 * @param array $target_attrs The array from which keys will be removed.
	 * @param array $reference_attrs The array used to determine which keys to remove from the target.
	 *
	 * @return array The target array, modified with keys removed if they exist in the reference array.
	 */
	public static function remove_matching_attrs( array $target_attrs, array $reference_attrs ): array {
		if ( empty( $reference_attrs ) ) {
			return $target_attrs;
		}

		foreach ( $target_attrs as $key => $value ) {
			if ( array_key_exists( $key, $reference_attrs ) ) {
				$reference_value = $reference_attrs[ $key ];

				if ( is_array( $value ) && is_array( $reference_value ) ) {
					$target_attrs[ $key ] = self::remove_matching_attrs( $value, $reference_value );
				} elseif ( is_scalar( $value ) && is_scalar( $reference_value ) ) {
					unset( $target_attrs[ $key ] );
				}
			}
		}

		return $target_attrs;
	}

	/**
	 * Recursively replace value in a target array with value from a reference array.
	 *
	 * This function compares a target array and a reference array. If a key exists in both,
	 * the value in a target array will be replaced with the value from the reference array. This process is performed recursively
	 * for nested arrays. If the reference array is empty, the target array is returned without changes.
	 *
	 * @since ??
	 *
	 * @param array $target_attrs The array from which keys will be removed.
	 * @param array $reference_attrs The array used to determine which keys to remove from the target.
	 *
	 * @return array The target array, modified with keys removed if they exist in the reference array.
	 */
	public static function replace_matching_attrs( array $target_attrs, array $reference_attrs ): array {
		if ( empty( $reference_attrs ) ) {
			return $target_attrs;
		}

		foreach ( $target_attrs as $key => $value ) {
			if ( array_key_exists( $key, $reference_attrs ) ) {
				$reference_value = $reference_attrs[ $key ];

				if ( is_array( $value ) && is_array( $reference_value ) ) {
					$target_attrs[ $key ] = self::replace_matching_attrs( $value, $reference_value );
				} elseif ( is_scalar( $value ) && is_scalar( $reference_value ) ) {
					$target_attrs[ $key ] = $reference_value;
				}
			}
		}

		return $target_attrs;
	}
}
