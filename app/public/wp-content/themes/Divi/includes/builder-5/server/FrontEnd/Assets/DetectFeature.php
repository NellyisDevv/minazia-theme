<?php
/**
 * Feature Detection class.
 *
 * @since   ??
 * @package Divi
 */

namespace ET\Builder\FrontEnd\Assets;

use ET\Builder\Packages\ModuleLibrary\SocialMediaFollowItem\SocialMediaFollowItemModule;

/**
 * Detects Feature based on content.
 *
 * @since ??
 */
class DetectFeature {

	/**
	 * Retrieves the block names from the given content.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for block names.
	 *
	 * @return array The array of block names.
	 */
	public static function get_block_names( string $content ): array {
		// Perform a quick check to see if "wp:" is in the content at all.
		if ( empty( $content ) || false === strpos( $content, 'wp:' ) ) {
			// Bail early if no relevant blocks or shortcodes are found.
			return [];
		}

		/*
		 * This pattern is used to detect block names in the content.
		 * test regex: https://regex101.com/r/tvl4FK/1
		 */
		static $pattern = '/<!--\s+wp:([^<>\s]+)\s+/';

		// Perform regex search for block names in Gutenberg content.
		preg_match_all( $pattern, $content, $matches );

		$verified_blocks = DynamicAssetsUtils::get_divi_block_names();
		$blocks          = array_unique( $matches[1] );

		// Return unique block names against verified block names.
		return array_values( array_intersect( $verified_blocks, $blocks ) );
	}

	/**
	 * Retrieves the shortcode names from the given content.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for shortcode names.
	 *
	 * @return array The array of shortcode names.
	 */
	public static function get_shortcode_names( string $content ): array {
		// Perform a quick check to see "[" (for shortcodes) is in the content at all.
		if ( empty( $content ) || false === strpos( $content, '[' ) ) {
			return [];
		}

		/*
		 * This pattern is used to detect shortcode in the content.
		 * test regex: https://regex101.com/r/b6kgSm/1
		 */
		static $pattern = '@\[([^<>&/\[\]\x00-\x20=]++)@';

		// Perform regex search for shortcodes in the content.
		preg_match_all( $pattern, $content, $matches );

		$verified_shortcodes = apply_filters( 'divi_frontend_assets_shortcode_whitelist', DynamicAssetsUtils::get_divi_shortcode_slugs() );
		$shortcodes          = array_unique( $matches[1] );

		// Return unique shortcode names against verified shortcode names.
		return array_values( array_intersect( $verified_shortcodes, $shortcodes ) );
	}

	/**
	 * Retrieves the block names and preset IDs from a given content in Gutenberg format.
	 *
	 * D4 attribute names: `_module_preset`.
	 * D5 attribute paths: `modulePreset`.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for block names and preset IDs.
	 *
	 * @return array {
	 *     An array of block names and preset IDs.
	 *
	 *     @type string $block_name The name of the block.
	 *     @type string $preset_id  The ID of the preset.
	 * }
	 */
	public static function get_block_preset_ids( string $content ): array {
		// Perform a quick check to see if "modulePreset" or "wp:" is in the content.
		if ( empty( $content ) || ( false === strpos( $content, '"modulePreset"' ) && false === strpos( $content, 'wp:' ) ) ) {
			return [];
		}

		/*
		 * This pattern is used to detect block name and preset ID in the content.
		 * test regex: https://regex101.com/r/QbIrs6/2
		 */
		static $pattern = '/<!-- wp:(?<block_name>[^ ]+) {(?:"module":{"advanced":{"type".*?value":"(?<type>[^"]*)")?.*?"modulePreset":"(?<preset_id>[^"]*)".*?} (?:\/)?-->/';

		// Perform regex search.
		preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER );

		// Initialize the array to store the results.
		$results = [];

		// Process each match to extract block names and preset IDs.
		foreach ( $matches as $match ) {
			$block_name = $match['block_name'];

			// Optimize section renaming logic.
			if ( 'divi/section' === $block_name ) {
				$type = $match['type'] ?? '';
				if ( 'fullwidth' === $type ) {
					$block_name = 'divi/fullwidth-section';
				} elseif ( 'specialty' === $type ) {
					$block_name = 'divi/specialty-section';
				}
			}

			// If a preset ID is found, add it to the results.
			if ( isset( $match['preset_id'] ) ) {
				$results[] = [
					'block_name' => $block_name,
					'preset_id'  => $match['preset_id'],
				];
			}
		}

		return $results;
	}

	/**
	 * Retrieves the shortcode names and preset IDs from a given content in shortcode format.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for shortcode names and preset IDs.
	 *
	 * @return array {
	 *     An array of shortcode names and preset IDs.
	 *
	 *     @type string $shortcode_name The name of the shortcode.
	 *     @type string $preset_id  The ID of the preset.
	 * }
	 */
	public static function get_shortcode_preset_ids( string $content ): array {
		// Perform a quick check to see if "_module_preset=" is in the content at all.
		if ( empty( $content ) || false === strpos( $content, '_module_preset=' ) ) {
			return [];
		}

		/*
		 * This pattern is used to detect shortcode name and preset ID in the content.
		 * test regex: https://regex101.com/r/evHAVd/1
		 */
		static $pattern = '/\[(?P<shortcode_name>[^\s\]]+)[^\[\]]*_module_preset="(?P<preset_id>[^"]+)"[^\[\]]*/';

		// Perform regex search.
		preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER );

		// Initialize the array to store results.
		$results = [];

		// Process each match to extract shortcode names and preset IDs.
		foreach ( $matches as $match ) {
			$shortcode_name = $match['shortcode_name'];

			// Check if it's a section and handle special cases for 'fullwidth' and 'specialty'.
			if ( 'et_pb_section' === $shortcode_name ) {
				if ( false !== strpos( $match[0], 'fullwidth="on"' ) ) {
					$shortcode_name = 'et_pb_fullwidth_section';
				} elseif ( false !== strpos( $match[0], 'specialty="on"' ) ) {
					$shortcode_name = 'et_pb_specialty_section';
				}
			}

			// Add the match to results if a preset ID is found.
			if ( isset( $match['preset_id'] ) ) {
				$results[] = [
					'shortcode_name' => $shortcode_name,
					'preset_id'      => $match['preset_id'],
				];
			}
		}

		return $results;
	}

	/**
	 * Retrieves the global color IDs from a given content in Gutenberg/Shortcode format.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search.
	 *
	 * @return array The array of global color IDs (empty if none found).
	 */
	public static function get_global_color_ids( string $content ): array {
		static $cached = [];

		$cache_key = md5( $content );

		// If cached result exists, return it early.
		if ( isset( $cached[ $cache_key ] ) ) {
			return $cached[ $cache_key ];
		}

		// Perform a quick check to see if "gcid-" is in the content at all.
		if ( empty( $content ) || ( false === strpos( $content, 'gcid-' ) ) ) {
			$cached[ $cache_key ] = [];
			return [];
		}

		/*
		 * The pattern to search for global color ids in the content.
		 * regex test: https://regex101.com/r/Yba3oz/1
		 */
		static $pattern = '(gcid-[0-9a-z-]*)';

		// Perform regex search.
		preg_match_all( "~$pattern~", $content, $matches );

		// Merge matches from both capture groups if both patterns were used.
		$global_color_ids = $matches[1] ?? [];

		// Track unique global color IDs and reset array keys.
		$cached[ $cache_key ] = ! empty( $global_color_ids ) ? array_values( array_unique( $global_color_ids ) ) : [];

		return $cached[ $cache_key ];
	}

	/**
	 * Retrieves the global module Post IDs from a given content in Gutenberg/Shortcode format.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search.
	 *
	 * @return array The array of global module Post IDs (empty if none found).
	 */
	public static function get_global_module_ids( string $content ): array {
		// Perform a quick check to see if "globalModule" or "global_module=" is in the content at all.
		if ( empty( $content ) || ( false === strpos( $content, 'globalModule' ) && false === strpos( $content, 'global_module=' ) ) ) {
			return [];
		}

		/*
		 * The pattern to search for global modules in Gutenberg content.
		 * A global module is indicated by the "globalModule" attribute with a PostID value.
		 * regex test: https://regex101.com/r/nxsrTU/1
		 */
		static $pattern_gutenberg = '"globalModule":\s*"(\d+)"';

		/*
		 * The pattern to search for global modules in Shortcode content.
		 * A global module is indicated by the "global_module" attribute with a PostID value.
		 * regex test: https://regex101.com/r/wC4AN0/1
		 */
		static $pattern_shortcode = 'global_module="(\d+)"';

		// Combine both patterns as it's too early to determine whether we have shortcode or not.
		// Perform regex search.
		preg_match_all( "~$pattern_gutenberg|$pattern_shortcode~", $content, $matches );

		// Merge matches from both capture groups if both patterns were used.
		$module_ids = array_filter(
			array_merge( $matches[1], $matches[2] ?? [] )
		);

		// Return unique module IDs and reset array keys.
		return ! empty( $module_ids ) ? array_values( array_unique( $module_ids ) ) : [];
	}

	/**
	 * Retrieves the gutter widths for a given content in Gutenberg/Shortcode format.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for gutter widths.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return array  The gutter width values.
	 */
	public static function get_gutter_widths( string $content, array $options = [] ): array {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"gutter"' ) && false === strpos( $content, 'use_custom_gutter=' ) )
		) {
			return [];
		}

		/*
		 * The pattern to get gutter width in Gutenberg content.
		 * test regex: https://regex101.com/r/S6MPuW/1
		 */
		static $pattern_gutenberg = '("gutter":{.*?"enable":"on"(.*?width":"(?<gutenberg_width>[^"]*)")?.*?}}})';

		/*
		 * The pattern to get gutter width in Shortcode content.
		 * test regex: https://regex101.com/r/BFl7oi/1
		 */
		static $pattern_shortcode = '([^\[\]]*use_custom_gutter="on"(.*?gutter_width="(?<shortcode_width>[^"]*)")?[^\[\]]*)';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search for gutter widths.
		preg_match_all( $pattern, $content, $matches );

		// Initialize an array to store the gutter widths.
		$widths = [];

		// Process both Gutenberg and shortcode gutter width matches.
		$gutenberg_widths = $matches['gutenberg_width'] ?? [];
		$shortcode_widths = $matches['shortcode_width'] ?? [];
		$found_widths     = array_filter(
			array_merge( $gutenberg_widths, $shortcode_widths )
		);

		/*
		 * Counting one of $matches['gutenberg_width'] and $matches['shortcode_width'] is sufficient to
		 * get the count of found items. If $total_found is greater than $found_widths (we removed null values from
		 * $found_widths). That means one of the item doesn't have width value defined, this happens for default
		 * gutter width which is 3. So we're injecting 3 to the results.
		 *
		 * Note: Ignore the following ugly checks.
		 */
		$total_found = ! empty( $gutenberg_widths )
			? count( $gutenberg_widths )
			: ( ! empty( $shortcode_widths )
				? count( $shortcode_widths )
				: 0 );

		if ( $total_found > count( $found_widths ) ) {
			$widths[] = 3;
		}

		foreach ( $found_widths as $match ) {
			// Add the value to the array if it's not already included.
			if ( ! in_array( $match, $widths, true ) ) {
				$widths[] = $match;
			}
		}

		// Return unique gutter widths as integers.
		return array_map( 'intval', $widths );
	}

	/**
	 * Checks if the content has animation style.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for animation style.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool  True if the content has animation style, false otherwise.
	 */
	public static function has_animation_style( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"animation"' ) && false === strpos( $content, 'animation_style=' ) )
		) {
			return false;
		}

		/*
		 * The pattern to search for animation style in Gutenberg content.
		 * The animation style can be one of the following: `fade`, `slide`, `bounce`, `zoom`, `flip`, `fold`, and `roll`.
		 * test regex: https://regex101.com/r/iLe1yv/1
		 */
		static $pattern_gutenberg = '"animation":{.*:{.*?"style":"(?:\bfade|slide|bounce|zoom|flip|fold|roll\b)".*?}';

		/*
		 * The pattern to search for animation style in Shortcode content.
		 * regex test: https://regex101.com/r/cGzMw9/1
		 */
		static $pattern_shortcode = 'animation_style="[^"]+"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any animation style was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Retrieves the status of content length for a given content block in Gutenberg/Shortcode format.
	 *
	 * Modules that use this attribute: Blog.
	 *
	 * D4 attribute name: `show_content`
	 * D5 attribute path: `post.advanced.excerptContent.*`
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool  The content length status.
	 */
	public static function has_excerpt_content_on( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"excerptContent"' ) && false === strpos( $content, 'show_content=' ) )
		) {
			return false;
		}

		/*
		 * Look for a JSON object containing an "on" `excerptContent` attribute.
		 *
		 * The excerptContent attribute is a nested object. The value we're looking
		 * for will be found within a breakpoint->state structure.
		 *
		 * When this attribute is 'on', the Content Length setting is set to "Show
		 * Content"; if 'off', the Content Length setting is set to "Show Excerpt".
		 *
		 * Typically, if Content Length uses the default value, a value will not be
		 * set in the JSON object. If it is set to anything other than 'on', it is
		 * not considered enabled.
		 *
		 * Test Regex: https://regex101.com/r/s90yfc/1
		 */
		static $pattern_gutenberg = '"excerptContent"(?:(?!}}).)*"on"';

		/*
		 * The pattern to search for animation style in Shortcode content.
		 * regex test: https://regex101.com/r/a7VwWm/1
		 */
		static $pattern_shortcode = 'show_content="on"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any excerpt content status was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Checks if Divi and/or Font Awesome icons are used in the given content in Gutenberg format.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search.
	 * @param string $type    Type of font detection. Valid type: fa | divi.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool True if Font Awesome ('fa') or Divi ('divi') icons are used in the content.
	 */
	public static function has_icon_font( string $content, string $type, array $options = [] ): bool {
		static $cached = [];

		$cache_key = md5( $content . implode( '', $options ) );

		// If cached result exists, return it early.
		if ( isset( $cached[ $cache_key ] ) ) {
			return $cached[ $cache_key ][ $type ] ?? false;
		}

		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"unicode"' ) && false === strpos( $content, '_icon' ) )
		) {
			$cached[ $cache_key ] = [
				'fa'   => false,
				'divi' => false,
			];

			return false;
		}

		/*
		 * There are cases where we should check "enabled":"on" value of a button/element to consider a font type use
		 * for icons, for example buttonOne|buttonTwo attributes of the fullwidth-header module; however many other
		 * modules' attribute doesn't have enabled value. Also, the enabled value could be present in `desktop` or
		 * `tablet` breakpoint but then `tablet` / `phone` breakpoint will not have the enabled value as it would be
		 * inherited from the higher breakpoint. Given the complexity, and for performance reason, ignored the enabled
		 * value to detect the fa|divi font type use.
		 *
		 * Regex test: https://regex101.com/r/AmKu3P/1.
		 */
		static $pattern = '/{.*?"unicode":"(?:.*?)","type":"(?<type>[^"]*)","weight":"(?:.*?)".*?}/';

		$font_types = [];

		if ( $options['has_block'] ) {
			// Perform regex search to find icon types in the content.
			preg_match_all( $pattern, $content, $matches );

			// Initialize the results array to track the detected font types.
			$font_types = array_values( $matches['type'] ?? [] );
		}

		if ( $options['has_shortcode'] ) {
			// Checks all the divi icons use based on shortcode content.
			if ( et_pb_check_if_post_contains_fa_font_icon( $content ) ) {
				$font_types[] = 'fa';
			}

			// Checks all the fa icons use based on shortcode content.
			if ( et_pb_check_if_post_contains_divi_font_icon( $content ) ) {
				$font_types[] = 'divi';
			}
		}

		// Track the presence of Font Awesome and Divi icons and cache the result to avoid repeated checks.
		$cached[ $cache_key ] = [
			'fa'   => in_array( 'fa', $font_types, true ),
			'divi' => in_array( 'divi', $font_types, true ),
		];

		// Return the result indicating the presence of Font Awesome or Divi icons.
		return $cached[ $cache_key ][ $type ] ?? false;
	}

	/**
	 * Retrieves the lightbox status for a given content in Gutenberg/Shortcode format.
	 *
	 * Modules that use this attribute: Image, Fullwidth Image.
	 *
	 * D4 attribute name: `show_in_lightbox`
	 * D5 attribute path: `image.advanced.lightbox.*`
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for lightbox status.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool  The lightbox status.
	 */
	public static function has_lightbox( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"lightbox"' ) && false === strpos( $content, 'show_in_lightbox=' ) )
		) {
			return false;
		}

		/*
		 * Look for a JSON object containing a `lightbox` attribute with a nested object.
		 *
		 * The lightbox attribute is a nested object. The value we are looking
		 * for is in the `desktop.value` key.
		 *
		 * Typically, if the lightbox is not enabled, the value will not be set.
		 * If it is set to anything other than 'on', it is not considered enabled.
		 *
		 * Test Regex: https://regex101.com/r/oBYH80/1
		 */
		static $pattern_gutenberg = '"lightbox":{"(?:\bdesktop|tablet|phone\b)":{"(?:\bvalue|sticky|hover\b)":"on"}}}';

		/*
		 * The pattern to search for show_in_lightbox in Shortcode content.
		 * regex test: https://regex101.com/r/zZv4ZY/1
		 */
		static $pattern_shortcode = 'show_in_lightbox="on"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any lightbox attribute was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Retrieves the fullscreen section status for a given content in Gutenberg format.
	 *
	 * Modules that use this attribute: Fullwidth Header.
	 *
	 * D5 attribute path: `module.advanced.headerFullscreen.*`
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for fullscreen section status.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool  The fullscreen section status.
	 */
	public static function has_fullscreen_section_enabled( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"headerFullscreen"' ) && false === strpos( $content, 'header_fullscreen=' ) )
		) {
			return false;
		}

		/*
		 * Look for a JSON object containing a `headerFullscreen` in Gutenberg content.
		 *
		 * If it is set to anything other than 'on', it is not considered enabled.
		 *
		 * Test Regex: https://regex101.com/r/U4ZpBL/1
		 */
		static $pattern_gutenberg = '"headerFullscreen":{"(?:\bdesktop|tablet|phone\b)":{"(?:\bvalue|sticky|hover\b)":"on"}}';

		/*
		 * The pattern to search for header_fullscreen in Shortcode content.
		 * regex test: https://regex101.com/r/qJGeeN/1
		 */
		static $pattern_shortcode = 'header_fullscreen="on"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any fullscreen section attribute was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Checks if any of the module has scroll effects enabled.
	 *
	 * Scroll effects matched include `rotating`, `scaling`, `verticalMotion`, `horizontalMotion`, `blur`, and `fade`.
	 * If any one of these is enabled, the function returns true.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for scroll effects.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool True if the content has scroll effect, false otherwise.
	 */
	public static function has_scroll_effects_enabled( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"scroll"' ) && false === strpos( $content, 'scroll_' ) )
		) {
			return false;
		}

		/*
		 * this patterns matches any of the scroll effects that may be enabled enabled.
		 * these include rotating, scaling, verticalMotion, horizontalMotion, blur, fade.
		 * test regex 101: https://regex101.com/r/ZFCPup/1
		 */
		static $pattern_gutenberg = '"(?:\brotating|scaling|verticalMotion|horizontalMotion|blur|fade\b)":{[^{}]*?"enable":"on".+?}';

		/*
		 * The pattern to search for show_in_lightbox in Shortcode content.
		 * regex test: https://regex101.com/r/bYbq6x/1
		 */
		static $pattern_shortcode = 'scroll_(?:rotating|scaling|vertical_motion|horizontal_motion|blur|fade)_enable="on"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any scroll effect was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Checks if any of the page has split testing active.
	 *
	 * Split testing sets ab_goal to "on" when a test is active.
	 * If any element has ab_goal set to "on" this will return true.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for split testing.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool True if the content has split testing, false otherwise.
	 */
	public static function has_split_testing_enabled( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| false === strpos( $content, 'ab_goal' )
		) {
			return false;
		}

		/*
		 * The patterns matches any ab_goal attribute that is "on".
		 */
		static $pattern_gutenberg = '/"ab_goal":"on"/';
		static $pattern_shortcode = '@ab_goal="on"@';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any split testing attribute was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Checks if any of the page has section dividers in use.
	 *
	 * Sections will have a dividers attribiute with a style attribute that contains a string.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for section dividers.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool True if the content has section dividers, false otherwise.
	 */
	public static function has_section_dividers_enabled( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"dividers"' ) && false === strpos( $content, '_divider_style=' ) )
		) {
			return false;
		}

		/*
		 * The pattern matches dividers.(top|bottom).[breakpoint].[state].style with any contents.
		 * test regex: https://regex101.com/r/1yWyP5/1
		 */
		static $pattern_gutenberg = '"dividers":{.*?"(?:\btop|bottom\b)":{.*?"style":\s*"(.*?)".*?}}';

		/*
		 * The patterns matches any top_divider_style/bottom_divider_style attribute with any contents.
		 * test regex: https://regex101.com/r/PKBUF5/1
		 */
		static $pattern_shortcode = '(?:top|bottom)_divider_style="[^"]+"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any section divider was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Checks if any modules on the page use the link option.
	 *
	 * Modules will have a link attribute containing a string.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for section dividers.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool True if the content has section dividers, false otherwise.
	 */
	public static function has_link_enabled( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"link"' ) && false === strpos( $content, 'link_option_url=' ) )
		) {
			return false;
		}

		/*
		 * This patterns matches link.[breakpoint].[state].url with any contents.
		 * test regex: https://regex101.com/r/TiYGqE/2
		 */
		static $pattern_gutenberg = '"link":{.*?"url":\s*"(?<url_gutenberg>.*?)".*?}}}';

		/*
		 * The patterns matches any link_option_url attribute with any contents.
		 * test regex: https://regex101.com/r/9DjC1w/1
		 */
		static $pattern_shortcode = 'link_option_url="(?<url_shortcode>.*?)"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any link URL was detected in either Gutenberg or shortcode content.
		return ! empty( $matches['url_gutenberg'] ) || ! empty( $matches['url_shortcode'] );
	}

	/**
	 * Checks if Divi and/or Font Awesome icons are used in the Social Media Follow Network blocks.
	 *
	 * This function searches the provided content string for social media follow network blocks
	 * and determines if any Font Awesome or Divi icons are used within those blocks.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for social media follow network blocks.
	 * @param string $type    Type of font detection. Valid type: fa | divi.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool True if Font Awesome ('fa') or Divi ('divi') icons are used in the content.
	 */
	public static function has_social_follow_icon_font( string $content, string $type, array $options = [] ): bool {
		static $cached = [];

		$cache_key = md5( $content . intval( $options['has_block'] ) . intval( $options['has_shortcode'] ) );

		// If cached result exists, return it early.
		if ( isset( $cached[ $cache_key ] ) ) {
			return $cached[ $cache_key ][ $type ] ?? false;
		}

		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"socialNetwork"' ) && false === strpos( $content, 'social_network=' ) )
		) {
			$cached[ $cache_key ] = [
				'fa'   => false,
				'divi' => false,
			];

			return false;
		}

		/*
		 * Define the regex pattern to match the icon names within the social media follow network blocks.
		 * The icon names are expected to appear in the `"title"` key within the nested JSON structure.
		 *
		 * Test regex: https://regex101.com/r/kdfF1I/1
		 */
		static $pattern_gutenberg = '"socialNetwork":{.*?"innerContent":.*?"title":\s*"(?<gutenberg_icon>.*?)".*?}';

		/*
		 * The patterns matches social_network attribute with any contents.
		 * Test regex: https://regex101.com/r/349wYc/1
		 */
		static $pattern_shortcode = 'social_network="(?<shortcode_icon>.*?)"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search for both Gutenberg and shortcode gutter widths.
		preg_match_all( $pattern, $content, $matches );

		// Process both Gutenberg and shortcode matches.
		$gutenberg_icons = $matches['gutenberg_icon'] ?? [];
		$shortcode_icons = $matches['shortcode_icon'] ?? [];
		$found_icons     = array_filter(
			array_merge( $gutenberg_icons, $shortcode_icons )
		);

		// Retrieve the list of Font Awesome icons.
		$font_awesome_icons = SocialMediaFollowItemModule::font_awesome_icons();

		// Track the presence of Font Awesome and Divi icons.
		$cached[ $cache_key ] = [
			'fa'   => ! empty( array_intersect( $found_icons, $font_awesome_icons ) ),
			'divi' => ! empty( array_diff( $found_icons, $font_awesome_icons ) ),
		];

		// Return the result indicating the presence of Font Awesome or Divi icons.
		return $cached[ $cache_key ][ $type ];
	}

	/**
	 * Checks if the content has a specialty section.
	 *
	 * A specialty section has type set to "specialty", defined in the module -> advanced settings.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for specialty section.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool True if the content has a specialty section, false otherwise.
	 */
	public static function has_specialty_section( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"specialty"' ) && false === strpos( $content, 'specialty=' ) )
		) {
			return false;
		}

		/*
		 * The pattern to search for a specialty section.
		 * A specialty section has type set to "specialty", defined in the module -> advanced settings.
		 * regex test: https://regex101.com/r/C3G1gH/1
		 */
		static $pattern_gutenberg = 'wp:divi\/section {"module":{.*?"advanced":{.*?"type":{"(?:\bdesktop|tablet|phone\b)":{"value":"specialty".*?}';

		/*
		 * The pattern to search for a specialty section in shortcode content.
		 * regex test: https://regex101.com/r/vHj89y/1
		 */
		static $pattern_shortcode = 'specialty="on"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any specialty section was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Checks if sticky position is enabled for a given content in Gutenberg format.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for sticky position.
	 * @param array  $options Whether to check shortode use.
	 *
	 * @return bool True if sticky position is enabled, false otherwise.
	 */
	public static function has_sticky_position_enabled( string $content, array $options = [] ): bool {
		// Bail early, if needed..
		if (
			empty( $content )
			|| ( ! $options['has_block'] && ! $options['has_shortcode'] )
			|| ( false === strpos( $content, '"sticky"' ) && false === strpos( $content, 'sticky_position=' ) )
		) {
			return false;
		}

		/*
		 * This pattern is used to detect sticky position in the content.
		 * The pattern matches for position "top", "bottom" or "topBottom".
		 * If any of these positions are found, it means sticky position is enabled.
		 * test regex: https://regex101.com/r/zCeJhv/1
		 */
		static $pattern_gutenberg = '"sticky":{.*?"position":"(?:\btop|bottom|topBottom\b)".*?}';

		/*
		 * The patterns matches any sticky_position attribute with any contents.
		 * test regex: https://regex101.com/r/wfsOGn/1
		 */
		static $pattern_shortcode = 'sticky_position="[^"]+"';

		// Conditionally build the pattern based.
		if ( $options['has_block'] && $options['has_shortcode'] ) {
			$pattern = "~$pattern_gutenberg|$pattern_shortcode~";
		} elseif ( $options['has_block'] ) {
			$pattern = "~$pattern_gutenberg~";
		} elseif ( $options['has_shortcode'] ) {
			$pattern = "~$pattern_shortcode~";
		}

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any scroll effect was detected, false otherwise.
		return ! empty( $matches );
	}

	/**
	 * Checks if Woo module exists for a given content in shortcode format.
	 *
	 * @since ??
	 *
	 * @param string $content The content string to search for Woo module shortcode names.
	 *
	 * @return bool True if content has Woo module, false otherwise.
	 */
	public static function has_woo_module_shortcode( string $content ): bool {
		// Perform a quick check to see "[" (for shortcodes) is in the content at all.
		if ( empty( $content ) || false === strpos( $content, '[et_pb_wc_' ) ) {
			return false;
		}

		// This pattern is used to detect Woo module shortcode in the content.
		// Test regex: https://regex101.com/r/h2VifQ/1.
		static $pattern = '@\[et_pb_wc_([^<>&/\[\]\x00-\x20=]++)@';

		// Perform a single regex search based on the combined pattern.
		preg_match( $pattern, $content, $matches );

		// Return true if any Woo modules was detected, false otherwise.
		return ! empty( $matches );
	}
}
