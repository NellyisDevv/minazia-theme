<?php
/**
 * Frontend Style
 *
 * @package Divi
 *
 * @since ??
 */

namespace ET\Builder\FrontEnd\Module;

use ET\Builder\FrontEnd\Assets\CriticalCSS;
use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use ET\Builder\FrontEnd\ResourceAggregator\ResourceAggregator;
use ET\Builder\Packages\GlobalData\GlobalData;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

/**
 * Frontend Style class.
 *
 * This class is used to store and enqueue module styles.
 */
class Style {

	/**
	 * Media queries key value pairs. {@see get_media_quries()}
	 *
	 * @since ??
	 *
	 * @var array
	 */
	private static $_media_queries = [];

	/**
	 * Styles data placeholder.
	 *
	 * This static property is used to store an array of styles. Each style is represented by an associative array with
	 * keys for the 'name' and 'value' properties of the style. This property can be accessed and modified using the
	 * getter and setter methods provided in the class that uses it.
	 *
	 * @since ??
	 *
	 * @var array An array of styles, each represented by an associative array with keys for the 'name' and 'value'
	 *      properties.
	 */
	private static $_styles = [];

	/**
	 * Deferred styles data placeholder.
	 *
	 * This static property is used to store array of deferred styles.
	 * This is precisely like `$_styles` with one major differences: when critical CSS is enabled, `$_styles`
	 * is used to kept above the fold content's styles (which means it will always be rendered on page) while
	 * `$_deferred_styles` is used to keep below the fold content's styles (which means it will be not be rendered
	 * on page once static CSS of it is found and enqueued).
	 *
	 * @since ??
	 *
	 * @var array An array of styles, each represented by an associative array with keys for the 'name' and 'value'
	 *      properties.
	 */
	private static $_deferred_styles = [];

	/**
	 * Return media query from the media query name.
	 * E.g For max_width_767 media query name, this function return "@media only screen and ( max-width: 767px )".
	 *
	 * @since ??
	 *
	 * @param string $name Media query name e.g max_width_767, max_width_980.
	 *
	 * @return bool|mixed
	 */
	public static function get_media_query( string $name ) {
		if ( ! isset( self::$_media_queries[ $name ] ) ) {
			return false;
		}

		return self::$_media_queries[ $name ];
	}

	/**
	 * Return media query key value pairs.
	 *
	 * @since ??
	 *
	 * @param bool $for_js Whether media queries is for js ETBuilderBackend.et_builder_css_media_queries variable.
	 *
	 * @return array|mixed|void
	 */
	public static function get_media_quries( bool $for_js = false ) {
		$media_queries = array(
			'min_width_1405' => '@media only screen and ( min-width: 1405px )',
			'1100_1405'      => '@media only screen and ( min-width: 1100px ) and ( max-width: 1405px)',
			'981_1405'       => '@media only screen and ( min-width: 981px ) and ( max-width: 1405px)',
			'981_1100'       => '@media only screen and ( min-width: 981px ) and ( max-width: 1100px )',
			'min_width_981'  => '@media only screen and ( min-width: 981px )',
			'max_width_980'  => '@media only screen and ( max-width: 980px )',
			'768_980'        => '@media only screen and ( min-width: 768px ) and ( max-width: 980px )',
			'min_width_768'  => '@media only screen and ( min-width: 768px )',
			'max_width_767'  => '@media only screen and ( max-width: 767px )',
			'max_width_479'  => '@media only screen and ( max-width: 479px )',
		);

		$media_queries['mobile'] = $media_queries['max_width_767'];

		$media_queries = apply_filters( 'et_builder_media_queries', $media_queries );

		if ( 'for_js' === $for_js ) {
			$processed_queries = array();

			foreach ( $media_queries as $key => $value ) {
				$processed_queries[] = array( $key, $value );
			}
		} else {
			$processed_queries = $media_queries;
		}

		return $processed_queries;
	}

	/**
	 * Set media queries key value pairs.
	 *
	 * @since ??
	 */
	public static function set_media_queries() {
		self::$_media_queries = self::get_media_quries();
	}

	/**
	 * Add a new style.
	 *
	 * Adds a new style to the CSS styles data. The style will be enqueued by `self::enqueue()`.
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments for adding a style.
	 *
	 *     @type string    $id              The ID of the style.
	 *     @type string    $parentId        Optional. The ID of the parent style. Default `null`.
	 *     @type array     $styles          Optional. An array of CSS styles for the style. Default `[]`.
	 *     @type object    $storeInstance   Optional. The instance of the store. Default `null`.
	 *     @type string    $name            The name of the style.
	 *     @type int       $orderIndex      The order index of the style.
	 *     @type int       $priority        Optional. The priority of the style. Default `10`.
	 *     @type string    $group           Optional. The group of the style. Default `module`.
	 * }
	 *
	 * @return void
	 *
	 * @example
	 * ```php
	 * self::add( [
	 *     'id'          => 'style-1',
	 *     'parentId'    => 'parent-style-1',
	 *     'styles'      => ['color' => '#000', 'font-size' => '16px'],
	 *     'storeInstance' => $store,
	 *     'name'        => 'Style One',
	 *     'orderIndex'  => 1,
	 *     'priority'    => 20,
	 * ] );
	 * ```
	 */
	public static function add( array $args ): void {
		$id             = $args['id'];
		$parent_id      = $args['parentId'] ?? null;
		$styles         = $args['styles'] ?? [];
		$store_instance = $args['storeInstance'] ?? null;
		$name           = $args['name'];
		$order_index    = $args['orderIndex'];
		$priority       = $args['priority'] ?? 10;
		$group          = $args['group'] ?? self::get_group_style();

		if ( ! $parent_id ) {
			$parent = BlockParserStore::get_parent(
				$id,
				$store_instance
			);

			if ( $parent ) {
				// phpcs:disable ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- match block property name.
				$parent_id = 'divi/placeholder' === $parent->blockName
					? 'divi/root'
					: $parent->id;
			}
		}

		// Warn when $styles is string.
		if ( is_string( $styles ) ) {
			et_error( "You're Doing It Wrong! Provided styles must be in array format." );
		}

		// Remove empty styles.
		$styles = is_array( $styles ) ? array_filter( $styles ) : [];

		// Bail when there are no styles found.
		if ( ! $styles ) {
			return;
		}

		// Get the ancestor ids.
		$parent_ids = BlockParserStore::get_ancestor_ids(
			$id,
			$store_instance
		);

		// Style item properties.
		$style_properties = [
			'id'            => $id,
			'parentId'      => $parent_id,
			'styles'        => $styles,
			'storeInstance' => $store_instance,
			'name'          => $name,
			'orderIndex'    => $order_index,
			// We're padding block index and parent counts into priority to sort items by priority and parents.
			'priority'      => (int) ( $priority . self::get_number_from_block_id( $id ) . count( $parent_ids ) ),
			'group'         => $group,
		];

		/*
		 * When critical CSS should be generated, styles are split into two:
		 * - Above the fold styles is kept on `$_styles`.
		 * - Below the fold styles is kept on `$_deferred_styles`.
		 *
		 * When critical CSS should not be generated, all styles are kept on `$_styles`e.
		 */
		if ( CriticalCSS::should_generate_critical_css() ) {
			if ( CriticalCSS::is_above_the_fold() ) {
				self::$_styles[] = $style_properties;
			} else {
				self::$_deferred_styles[] = $style_properties;
			}
		} else {
			self::$_styles[] = $style_properties;
		}
	}

	/**
	 * Extract index number from the Block ID.
	 *
	 * @param string $block_id Block ID.
	 *
	 * @return int
	 */
	public static function get_number_from_block_id( string $block_id ): int {
		$last_dash_position = strrpos( $block_id, '-' );

		if ( false !== $last_dash_position ) {
			$number_part = substr( $block_id, $last_dash_position + 1 );

			return is_numeric( $number_part ) ? intval( $number_part ) : 0;
		}

		return 0;
	}

	/**
	 * Sort an array of items by their priority.
	 *
	 * This function takes an array of items. The function then sorts the array of priorities in ascending
	 * order. If two items have the same priority, they will be sorted by their original index
	 * within the input array.
	 *
	 * @since ??
	 *
	 * @param array $collection The array to be sorted. Each child item in the array should have a 'priority' key.
	 *
	 * @return array An array of items sorted by priority. The array will maintain the same keys as the input array.
	 *
	 * @example
	 * ```php
	 * $collection = [
	 *     'selector1' => ['priority' => 5, 'item' => 'A'],
	 *     'selector2' => ['priority' => 10, 'item' => 'B'],
	 *     'selector3' => ['priority' => 5, 'item' => 'C'],
	 * ];
	 *
	 * $sortedCollection = sort_by_priority($collection);
	 *
	 * // $sortedCollection will be:
	 * // [
	 * //     'selector1' => ['priority' => 5, 'item' => 'A'],
	 * //     'selector3' => ['priority' => 5, 'item' => 'C'],
	 * //     'selector2' => ['priority' => 10, 'item' => 'B'],
	 * // ]
	 * ```
	 */
	public static function sort_by_priority( array &$collection ): array {
		$keys_order = array_flip( array_keys( $collection ) );

		uksort(
			$collection,
			function ( $a, $b ) use ( $keys_order, $collection ) {
				if ( $collection[ $a ]['priority'] === $collection[ $b ]['priority'] ) {
					return $keys_order[ $a ] - $keys_order[ $b ];
				}

				return $collection[ $a ]['priority'] - $collection[ $b ]['priority'];
			}
		);

		unset( $keys_order );

		return $collection;
	}

	/**
	 * Get styles specific to the group.
	 *
	 * @since ??
	 *
	 * @param string $style_type Optional. The type of styles to retrieve.
	 * @param string $group      Optional. The group of styles to retrieve. Default is 'module'.
	 *
	 * @return array An array of styles.
	 */
	public static function get( string $style_type = 'default', string $group = 'module' ): array {
		$styles_raw      = 'deferred' === $style_type
			? self::$_deferred_styles
			: self::$_styles;
		$styles_by_group = [];

		foreach ( $styles_raw as $style ) {
			$style_group = $style['group'] ?? 'module';

			if ( $style_group === $group ) {
				$styles_by_group[] = $style;
			}
		}

		if ( empty( $styles_by_group ) ) {
			return [];
		}

		$styles_flattened = [];

		foreach ( $styles_by_group as $item ) {
			// Remove empty styles.
			$item_styles = array_filter( $item['styles'] ) ?? [];

			if ( ! $item_styles ) {
				continue;
			}

			foreach ( $item_styles as $item_style ) {
				// Skip if $item_style is empty or not an array.
				if ( ! $item_style || ! is_array( $item_style ) ) {
					continue;
				}

				foreach ( $item_style as $group ) {
					$media_query = ! empty( $group['atRules'] ) ? $group['atRules'] : 'general';

					$selector    = $group['selector'];
					$declaration = $group['declaration'];

					// prepare styles for internal content. Used in Blog/Slider modules if they contain Divi modules.
					if ( isset( $styles_flattened[ $media_query ][ $selector ]['declaration'] ) ) {
						$existing_declaration = $styles_flattened[ $media_query ][ $selector ]['declaration'];

						if ( $declaration !== $existing_declaration ) {
							$styles_flattened[ $media_query ][ $selector ]['declaration'] = sprintf(
								'%1$s %2$s',
								$existing_declaration,
								$declaration
							);
						}
					} else {
						$styles_flattened[ $media_query ][ $selector ]['declaration'] = $declaration;
					}

					$styles_flattened[ $media_query ][ $selector ]['priority'] = (int) $item['priority'];
				}
			}
		}

		return $styles_flattened;
	}

	/**
	 * Enqueue styles from the Style class.
	 *
	 * This function retrieves the styles data from the Style class and enqueues the styles on the
	 * page. It concatenates the styles into a single string and echoes them within `style` tags.
	 * The styles are sanitized and escaped before being output to the page.
	 *
	 * @since ??
	 *
	 * @param string $style_type The type of styles to enqueue.
	 * @param string $group The group of styles to enqueue. Default is 'module'.
	 *
	 * @return void
	 *
	 * @example: Enqueue styles
	 * ```php
	 * MyStyles::enqueue();
	 * ```
	 */
	public static function enqueue( string $style_type = 'default', string $group = 'module' ): void {
		$styles_output = self::render( $style_type, $group );

		if ( $styles_output ) {
			// phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf -- ignored intentionally, empty condition added for clear code understanding.
			if ( ET_BUILDER_5_EXPERIMENTS_STATIC_CSS === true && ResourceAggregator::has_post_cached_css_file( get_queried_object_id() ) ) {
				// CSS has already been cached in a static CSS file and it has been enqueued.
			} else {
				if ( ET_BUILDER_5_EXPERIMENTS_STATIC_CSS === true ) {
					ResourceAggregator::add_css( $styles_output );
				}
				echo '<style>';
				echo et_core_esc_previously( $styles_output );
				echo '</style>';
			}
		}
	}

	/**
	 * Render sorted styles as string.
	 *
	 * @since ??
	 *
	 * @param string $style_type The type of styles to enqueue.
	 * @param string $group The group of styles to enqueue. Default is 'module'.
	 *
	 * @example: Render styles
	 * ```php
	 * MyStyles::render();
	 * ```
	 */
	public static function render( string $style_type = 'default', string $group = 'module' ): string {
		$styles_data = self::get( $style_type, $group );

		if ( ! $styles_data ) {
			return '';
		}

		$styles_by_media_queries = $styles_data;
		$media_queries_order     = array_merge( array( 'general' ), array_values( self::$_media_queries ) );

		// make sure styles in the array ordered by media query correctly from bigger to smaller screensize.
		$styles_by_media_queries_sorted = array_merge( array_flip( $media_queries_order ), $styles_by_media_queries );

		$output = '';

		global $et_user_fonts_queue;

		// TODO feat(D5, FE Rendering): Need to rewrite et_builder_enqueue_user_fonts in D5.
		if ( ! empty( $et_user_fonts_queue ) ) {
			$output .= et_builder_enqueue_user_fonts( $et_user_fonts_queue );
		}

		foreach ( $styles_by_media_queries_sorted as $media_query => $styles ) {
			// Skip wrong values which were added during the array sorting.
			if ( ! is_array( $styles ) ) {
				continue;
			}

			$media_query_output    = '';
			$wrap_into_media_query = 'general' !== $media_query;

			// Sort styles by priority.
			self::sort_by_priority( $styles );

			// Merge styles with identical declarations.
			$merged_declarations = [];
			foreach ( $styles as $selector => $settings ) {
				$this_declaration = md5( $settings['declaration'] );

				// We want to skip combining anything with psuedo selectors or keyframes or free-form-css (which has
				// empty selector).
				if (
					false !== strpos( $selector, ':-' ) ||
					false !== strpos( $selector, '@keyframes' ) ||
					'' === $selector
				) {
					// set unique key so that it cant be matched.
					$unique_key                         = $this_declaration . '-' . uniqid();
					$merged_declarations[ $unique_key ] = [
						'declaration' => $settings['declaration'],
						'selector'    => $selector,
					];

					if ( ! empty( $settings['priority'] ) ) {
						$merged_declarations[ $unique_key ]['priority'] = $settings['priority'];
					}

					continue;
				}

				if ( empty( $merged_declarations[ $this_declaration ] ) ) {
					$merged_declarations[ $this_declaration ] = [
						'selector' => '',
						'priority' => '',
					];
				}

				$new_selector = ! empty( $merged_declarations[ $this_declaration ]['selector'] )
					? $merged_declarations[ $this_declaration ]['selector'] . ', ' . $selector
					: $selector;

				$merged_declarations[ $this_declaration ] = [
					'declaration' => $settings['declaration'],
					'selector'    => $new_selector,
				];

				if ( ! empty( $settings['priority'] ) ) {
					$merged_declarations[ $this_declaration ]['priority'] = $settings['priority'];
				}
			}

			$styles_index = 0;

			// Get each rule in a media query.
			foreach ( $merged_declarations as $settings ) {
				if ( empty( $settings['selector'] ) ) {
					// If the selector is empty, just append the declaration directly without brackets.
					// This is needed for free-form-css output.
					$media_query_output .= sprintf(
						'%3$s%4$s%1$s%2$s',
						'',
						$settings['declaration'],
						( 0 === $styles_index ) ? '' : "\n",
						( $wrap_into_media_query ? "\t" : '' )
					);
				} else {
					// If the selector is not empty, use sprintf with brackets.
					$media_query_output .= sprintf(
						'%3$s%4$s%1$s {%2$s}',
						$settings['selector'],
						$settings['declaration'],
						( 0 === $styles_index ) ? '' : "\n",
						( $wrap_into_media_query ? "\t" : '' )
					);
				}

				$styles_index++;
			}

			// All css rules that don't use media queries are assigned to the "general" key.
			// Wrap all non-general settings into media query.
			if ( $wrap_into_media_query && '' !== $media_query_output ) {
				$media_query_output = sprintf(
					'%3$s%3$s%1$s {%2$s%3$s}',
					$media_query,
					$media_query_output,
					"\n"
				);
			}

			$output .= $media_query_output;
		}

		return $output;
	}

	/**
	 * Reset styles data.
	 *
	 * Resets the styles data (both default and deferred) to an empty array `[]`.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function reset() {
		self::$_styles          = [];
		self::$_deferred_styles = [];
	}

	/**
	 * Provides styles for global colors.
	 *
	 * This function retrieves and prepares style data from global colors data. The values are then
	 * sanitized and escaped for secure use.
	 *
	 * It can be used in two ways:
	 * 1. Without any parameters - In this case, it returns styles for all available global colors.
	 * 2. With an array of $global_color_ids - It only returns styles for the colors associated with the provided ids.
	 *
	 * @since ??
	 *
	 * @param array $global_color_ids An optional parameter. When provided, the function will only include
	 *                                the styles for the global colors associated with these ids.
	 *                                If not provided or an empty array is passed, styles for all global colors
	 *                                will be included.
	 *
	 * @return string Returns a string containing the styles for the global colors.
	 */
	public static function get_global_colors_style( array $global_color_ids = [] ): string {
		$global_colors_style = '';
		$global_colors       = GlobalData::get_global_colors();

		foreach ( $global_colors as $key => $value ) {
			if ( ! empty( $value['color'] ) ) {
				$color = $value['color'];

				// When ids are provided, include the styles for the global colors associated with the ids.
				if ( ! empty( $global_color_ids ) && in_array( $key, $global_color_ids, true ) ) {
					$global_colors_style .= '--' . esc_html( $key ) . ': ' . esc_html( $color ) . ';';
				}

				// If there are no ids provided, include the styles for all the global colors.
				if ( empty( $global_color_ids ) ) {
					$global_colors_style .= '--' . esc_html( $key ) . ': ' . esc_html( $color ) . ';';
				}
			}
		}

		if ( ! empty( $global_colors_style ) ) {
			$global_colors_style = ':root{' . $global_colors_style . '}';
		}

		return $global_colors_style;
	}

	/**
	 * The group of the style where it will be added.
	 *
	 * @since ??
	 *
	 * @var string
	 */
	private static $_group_style = 'module';

	/**
	 * Set the group of the style where it will be added.
	 *
	 * @since ??
	 *
	 * @param string $group The group of the style.
	 *
	 * @return void
	 */
	public static function set_group_style( string $group ): void {
		self::$_group_style = $group;
	}

	/**
	 * Get the group of the style where it will be added.
	 *
	 * @since ??
	 *
	 * @return string
	 */
	public static function get_group_style(): string {
		return self::$_group_style;
	}
}
