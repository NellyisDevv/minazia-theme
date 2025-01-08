<?php
/**
 * Utils class for Dynamic Assets.
 *
 * This file combines the logic from the following Divi-4 file:
 * - includes/builder/feature/dynamic-assets/dynamic-assets.php
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\FrontEnd\Assets;

use ET\Builder\Framework\Utility\ArrayUtility;
use ET\Builder\FrontEnd\Module\ScriptData;
use ET\Builder\Packages\GlobalData\GlobalPreset;
use ET_GB_Block_Layout;
use ET_Post_Stack;

/**
 * Utils CLass.
 *
 * @since ??
 */
class DynamicAssetsUtils {

	/**
	 * Check if JavaScript On Demand is enabled.
	 *
	 * @since ??
	 *
	 * @return bool
	 */
	public static function disable_js_on_demand(): bool {

		// TODO Remove this or deprecate the function during Divi 5 test. 
		// We are temporarily returning overriding this function to force Dynamic Assets to be on to improve performance.
		if ( ! et_core_is_fb_enabled() && ! is_preview() && ! is_customize_preview() ) {
			return false;
		}

		global $shortname;
		static $et_disable_js_on_demand = null;

		if ( null === $et_disable_js_on_demand ) {
			if ( et_is_builder_plugin_active() ) {
				$options              = get_option( 'et_pb_builder_options', array() );
				$dynamic_js_libraries = $options['performance_main_dynamic_js_libraries'] ?? 'on';
			} else {
				$dynamic_js_libraries = et_get_option( $shortname . '_dynamic_js_libraries', 'on' );
			}

			if ( // Disable when theme option not enabled.
				'on' !== $dynamic_js_libraries
				// Disable when not an applicable front-end request.
				|| ! self::is_dynamic_front_end_request()
			) {
				$et_disable_js_on_demand = true;
			} else {
				$et_disable_js_on_demand = false;
			}

			/**
			 * Filters whether to disable JS on demand.
			 *
			 * This filter is the replacement of Divi 4 filter `et_disable_js_on_demand`.
			 *
			 * @since ??
			 *
			 * @param bool $et_disable_js_on_demand
			 */
			$et_disable_js_on_demand = apply_filters( 'divi_frontend_assets_dynamic_assets_utils_disable_js_on_demand', (bool) $et_disable_js_on_demand );
		}

		return $et_disable_js_on_demand;
	}

	/**
	 * Ensure cache directory exists.
	 *
	 * @since ??
	 */
	public static function ensure_cache_directory_exists() {
		// Create the base cache directory, if not exists already.
		$cache_dir = et_core_cache_dir()->path;

		et_()->ensure_directory_exists( $cache_dir );
	}

	/**
	 * Enqueues D5 Easypiechart script, and dequeues D4 version of the Easypiechart script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_easypiechart_script() {
		wp_dequeue_script( 'easypiechart' );
		wp_deregister_script( 'easypiechart' );

		wp_enqueue_script(
			'easypiechart',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-easypiechart.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 toggle script, used for toggle and accordion modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_toggle_script() {
		wp_enqueue_script(
			'divi-script-library-toggle',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-toggle.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 audio script, used for audio modules and audio post types.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_audio_script() {
		wp_enqueue_script(
			'divi-script-library-audio',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-audio.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 video overlay script, used for video/blog modules and on video post formats.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_video_overlay_script() {
		wp_enqueue_script(
			'divi-script-library-video-overlay',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-video-overlay.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 search script, used for search modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_search_script() {
		wp_enqueue_script(
			'divi-script-library-search',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-search.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 woo script, used for woo modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_woo_script() {
		wp_enqueue_script(
			'divi-script-library-woo',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-woo.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 fullwidth header script, used for fullwidth header modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_fullwidth_header_script() {
		wp_enqueue_script(
			'divi-script-library-fullwidth-header',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-fullwidth-header.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 blog script, used for modules with ajax blog.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_blog_script() {
		wp_enqueue_script(
			'divi-module-library-script-blog',
			ET_BUILDER_5_URI . '/visual-builder/build/module-library-script-blog.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 pagination script, used for modules with ajax pagination.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_pagination_script() {
		wp_enqueue_script(
			'divi-script-library-pagination',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-pagination.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 fullscreen section script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_fullscreen_section_script() {
		wp_enqueue_script(
			'divi-script-library-fullscreen-section',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-fullscreen-section.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 section dividers script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_section_dividers_script() {
		wp_enqueue_script(
			'divi-script-library-section-dividers',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-section-dividers.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 link script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_link_script() {
		wp_enqueue_script(
			'divi-script-library-link',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-link.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 slider script, used for slider modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_slider_script() {
		wp_enqueue_script(
			'divi-script-library-slider',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-slider.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 map script, used for map modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_map_script() {
		wp_enqueue_script(
			'divi-script-library-map',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-map.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 sidebar script, used for sidebar modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_sidebar_script() {
		wp_enqueue_script(
			'divi-script-library-sidebar',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-sidebar.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 testimonial script, used for testimonial modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_testimonial_script() {
		wp_enqueue_script(
			'divi-module-library-script-testimonial',
			ET_BUILDER_5_URI . '/visual-builder/build/module-library-script-testimonial.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);

		wp_enqueue_script(
			'divi-script-library-testimonial',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-testimonial.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 comments script, used for comments modules.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_comments_script() {
		wp_enqueue_script(
			'divi-script-library-comments',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-comments.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 tabs script, used for tabs modules and WooCommerce product pages.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_tabs_script() {
		wp_enqueue_script(
			'divi-script-library-tabs',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-tabs.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 fullwidth portfolio script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_fullwidth_portfolio_script() {
		wp_enqueue_script(
			'divi-script-library-fullwidth-portfolio',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-fullwidth-portfolio.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 filterable portfolio script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_filterable_portfolio_script() {
		wp_enqueue_script(
			'divi-script-library-filterable-portfolio',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-filterable-portfolio.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 video slider script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_video_slider_script() {
		wp_enqueue_script(
			'divi-script-library-video-slider',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-video-slider.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 signup script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_signup_script() {
		wp_enqueue_script(
			'divi-module-library-script-signup',
			ET_BUILDER_5_URI . '/visual-builder/build/module-library-script-signup.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 countdown timer script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_countdown_timer_script() {
		wp_enqueue_script(
			'divi-script-library-countdown-timer',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-countdown-timer.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 bar counter script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_bar_counter_script() {
		wp_enqueue_script(
			'divi-module-library-script-counter',
			ET_BUILDER_5_URI . '/visual-builder/build/module-library-script-counter.js',
			[
				'jquery',
				'easypiechart',
			],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 circle counter script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_circle_counter_script() {
		wp_enqueue_script(
			'divi-module-library-script-circle-counter',
			ET_BUILDER_5_URI . '/visual-builder/build/module-library-script-circle-counter.js',
			[
				'jquery',
				'easypiechart',
			],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 number counter script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_number_counter_script() {
		wp_enqueue_script(
			'divi-module-library-script-number-counter',
			ET_BUILDER_5_URI . '/visual-builder/build/module-library-script-number-counter.js',
			[
				'jquery',
				'easypiechart',
			],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 contact form script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_contact_form_script() {
		wp_enqueue_script(
			'divi-module-library-script-contact-form',
			ET_BUILDER_5_URI . '/visual-builder/build/module-library-script-contact-form.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 form conditions script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_form_conditions_script() {
		wp_enqueue_script(
			'divi-script-library-form-conditions',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-form-conditions.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 split testing script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_split_testing_script() {
		wp_enqueue_script(
			'divi-script-library-split-testing',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-split-testing.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 menu module script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_menu_script() {
		wp_enqueue_script(
			'divi-script-library-menu',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-menu.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 animation module script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_animation_script() {
		wp_enqueue_script(
			'divi-script-library-animation',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-animation.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 gallery module script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_gallery_script() {
		wp_enqueue_script(
			'divi-script-library-gallery',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-gallery.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 scripts only needed when logged in.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_logged_in_script() {
		wp_enqueue_script(
			'divi-script-library-logged-in',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-logged-in.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 fitvids script, and dequeues D4 version of the fitvids script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_fitvids_script() {
		wp_dequeue_script( 'fitvids' );
		wp_deregister_script( 'fitvids' );

		wp_enqueue_script(
			'fitvids',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-jquery.fitvids.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);

		wp_enqueue_script(
			'divi-script-library-fitvids-functions',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-fitvids-functions.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 jquery-mobile script, and dequeues D4 version of the jquery-mobile script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_jquery_mobile_script() {
		wp_dequeue_script( 'jquery-mobile' );
		wp_deregister_script( 'jquery-mobile' );

		wp_enqueue_script(
			'jquery-mobile',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-jquery.mobile.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 magnific-popup script, and dequeues D4 version of the magnific-popup script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_magnific_popup_script() {
		wp_dequeue_script( 'magnific-popup' );
		wp_deregister_script( 'magnific-popup' );

		wp_enqueue_script(
			'magnific-popup',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-magnific-popup.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 salvattore script, and dequeues D4 version of the salvattore script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_salvattore_script() {
		wp_dequeue_script( 'salvattore' );
		wp_deregister_script( 'salvattore' );

		wp_enqueue_script(
			'salvattore',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-salvattore.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueues D5 Google Maps API script, and dequeues D4 version of the Google Maps API script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_google_maps_script() {
		wp_dequeue_script( 'google-maps-api' );
		wp_deregister_script( 'google-maps-api' );

		wp_enqueue_script(
			'google-maps-api',
			esc_url(
				add_query_arg(
					array(
						'key' => et_pb_get_google_api_key(),
					),
					is_ssl() ? 'https://maps.googleapis.com/maps/api/js' : 'http://maps.googleapis.com/maps/api/js'
				)
			),
			array(),
			'3',
			true
		);
	}

	/**
	 * Enqueues D5 scroll-effects script, and dequeues D4 version of the scroll-effects script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_scroll_script() {
		wp_dequeue_script( 'et-builder-modules-script-motion' );
		wp_deregister_script( 'et-builder-modules-script-motion' );

		// Enqueue scroll-effects js.
		wp_enqueue_script(
			'et-builder-modules-script-motion',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-motion-effects.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);

		// if the shortcode framework is loaded, localize the motion elements.
		if ( et_is_shortcode_framework_loaded() ) {
			wp_localize_script(
				'et-builder-modules-script-motion',
				'et_pb_motion_elements',
				\ET_Builder_Element::$_scroll_effects_fields
			);
		}

		ScriptData::enqueue_data( 'scroll' );
	}

	/**
	 * Enqueues D5 sticky script, and dequeues D4 version of the sticky script.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public static function enqueue_sticky_script() {
		wp_dequeue_script( 'et-builder-modules-script-sticky' );
		wp_deregister_script( 'et-builder-modules-script-sticky' );

		wp_enqueue_script(
			'et-builder-modules-script-sticky',
			ET_BUILDER_5_URI . '/visual-builder/build/script-library-sticky-elements.js',
			[ 'jquery' ],
			ET_CORE_VERSION,
			true
		);

		// if the shortcode framework is loaded, localize the motion elements.
		if ( et_is_shortcode_framework_loaded() ) {
			wp_localize_script(
				'et-builder-modules-script-sticky',
				'et_pb_sticky_elements',
				\ET_Builder_Element::$sticky_elements
			);
		}

		ScriptData::enqueue_data( 'sticky' );
	}

	/**
	 * Get Extra Taxonomy layout ID.
	 *
	 * @since ??
	 *
	 * @return int|null
	 */
	public static function extra_get_tax_layout_id() {
		if ( function_exists( 'extra_get_tax_layout_id' ) ) {
			return extra_get_tax_layout_id();
		}
		return null;
	}

	/**
	 * Get Extra Home layout ID.
	 *
	 * @since ??
	 *
	 * @return int|null
	 */
	public static function extra_get_home_layout_id() {
		if ( function_exists( 'extra_get_home_layout_id' ) ) {
			return extra_get_home_layout_id();
		}
		return null;
	}

	/**
	 * Get all active block widgets.
	 *
	 * This method will collect all active block widgets first. Later on, the result will be
	 * cached to improve the performance.
	 *
	 * @since ??
	 *
	 * @return array List of active block widgets.
	 */
	public static function get_active_block_widgets(): array {
		global $wp_version;
		static $active_block_widgets = null;

		if ( null === $active_block_widgets ) {
			$wp_major_version = substr( $wp_version, 0, 3 );

			// Bail early if were pre WP 5.8, when block widgets were introduced.
			if ( version_compare( $wp_major_version, '5.8', '<' ) ) {
				return array();
			}

			global $wp_widget_factory;

			$active_block_widgets = array();
			$block_instance       = $wp_widget_factory->get_widget_object( 'block' );
			$block_settings       = $block_instance->get_settings();

			// Bail early if there is no active block widgets.
			if ( empty( $block_settings ) ) {
				return $active_block_widgets;
			}

			// Collect all active blocks.
			foreach ( $block_settings as $block_setting ) {
				$block_content = ArrayUtility::get_value( $block_setting, 'content' );
				$block_parsed  = parse_blocks( $block_content );
				$block_name    = ArrayUtility::get_value( $block_parsed, '0.blockName' );

				// Save and cache there result.
				if ( ! in_array( $block_name, $active_block_widgets, true ) ) {
					$active_block_widgets[] = $block_name;
				}
			}
		}

		return $active_block_widgets;
	}

	/**
	 * Returns assets list with file path.
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type string $prefix Asset Prefix.
	 *     @type string $suffix Asset Suffix.
	 *     @type string $specialty_suffix Suffix for Specialty section.
	 * }
	 *
	 * @return array
	 */
	public static function get_assets_list( array $args = [] ): array {
		$prefix = $args['prefix'] ?? '';
		$suffix = $args['suffix'] ?? '';

		$specialty_suffix = $args['specialty_suffix'] ?? '';

		return array(
			// Structure elements.
			'divi/section'                     => array(
				'css' => array(
					"{$prefix}/css/section{$suffix}.css",
					"{$prefix}/css/row{$suffix}.css", // Some fullwidth section modules use the et_pb_row class.
				),
			),
			'divi/row'                         => array(
				'css' => "{$prefix}/css/row{$suffix}.css",
			),
			'divi/row-inner'                   => array(
				'css' => "{$prefix}/css/row{$suffix}.css",
			),
			'divi/column'                      => array(),
			'divi/column-inner'                => array(),

			// Module elements.
			'divi/accordion'                   => array(
				'css' => array(
					"{$prefix}/css/accordion{$suffix}.css",
					"{$prefix}/css/toggle{$suffix}.css",
				),
			),
			'divi/accordion-item'              => array(),
			'divi/audio'                       => array(
				'css' => array(
					"{$prefix}/css/audio{$suffix}.css",
					"{$prefix}/css/audio_player{$suffix}.css",
				),
			),
			'divi/counters'                    => array(),
			'divi/counter'                     => array(
				'css' => "{$prefix}/css/counter{$suffix}.css",
			),
			'divi/blog'                        => array(
				'css' => array(
					"{$prefix}/css/blog{$suffix}.css",
					"{$prefix}/css/posts{$suffix}.css",
					"{$prefix}/css/post_formats{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
					"{$prefix}/css/audio_player{$suffix}.css",
					"{$prefix}/css/video_player{$suffix}.css",
					"{$prefix}/css/slider_base{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
					"{$prefix}/css/wp_gallery{$suffix}.css",
				),
			),
			'divi/blurb'                       => array(
				'css' => array(
					"{$prefix}/css/blurb{$suffix}.css",
					"{$prefix}/css/legacy_animations{$suffix}.css",
				),
			),
			'divi/button'                      => array(
				'css' => array(
					"{$prefix}/css/button{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/circle-counter'              => array(
				'css' => "{$prefix}/css/circle_counter{$suffix}.css",
			),
			'divi/code'                        => array(
				'css' => "{$prefix}/css/code{$suffix}.css",
			),
			'divi/comments'                    => array(
				'css' => array(
					"{$prefix}/css/comments{$suffix}.css",
					"{$prefix}/css/comments_shared{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/contact-field'               => array(),
			'divi/contact-form'                => array(
				'css' => array(
					"{$prefix}/css/contact_form{$suffix}.css",
					"{$prefix}/css/forms{$suffix}.css",
					"{$prefix}/css/forms{$specialty_suffix}{$suffix}.css",
					"{$prefix}/css/fields{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/countdown-timer'             => array(
				'css' => "{$prefix}/css/countdown_timer{$suffix}.css",
			),
			'divi/cta'                         => array(
				'css' => "{$prefix}/css/cta{$suffix}.css",
				"{$prefix}/css/buttons{$suffix}.css",
			),
			'divi/divider'                     => array(
				'css' => "{$prefix}/css/divider{$suffix}.css",
			),
			'divi/filterable-portfolio'        => array(
				'css' => array(
					"{$prefix}/css/filterable_portfolio{$suffix}.css",
					"{$prefix}/css/portfolio{$suffix}.css",
					"{$prefix}/css/grid_items{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
				),
			),
			'divi/fullwidth-code'              => array(
				'css' => "{$prefix}/css/fullwidth_code{$suffix}.css",
			),
			'divi/fullwidth-header'            => array(
				'css' => "{$prefix}/css/fullwidth_header{$suffix}.css",
				"{$prefix}/css/buttons{$suffix}.css",
			),
			'divi/fullwidth-image'             => array(
				'css' => array(
					"{$prefix}/css/fullwidth_image{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
				),
			),
			'divi/fullwidth-map'               => array(
				'css' => array(
					"{$prefix}/css/map{$suffix}.css",
					"{$prefix}/css/fullwidth_map{$suffix}.css",
				),
			),
			'divi/fullwidth-menu'              => array(
				'css' => array(
					"{$prefix}/css/menus{$suffix}.css",
					"{$prefix}/css/fullwidth_menu{$suffix}.css",
					"{$prefix}/css/header_animations.css",
					"{$prefix}/css/header_shared{$suffix}.css",
				),
			),
			'divi/fullwidth-portfolio'         => array(
				'css' => array(
					"{$prefix}/css/fullwidth_portfolio{$suffix}.css",
					"{$prefix}/css/grid_items{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
				),
			),
			'divi/fullwidth-post-content'      => array(),
			'divi/fullwidth-post-slider'       => array(
				'css' => array(
					"{$prefix}/css/post_slider{$suffix}.css",
					"{$prefix}/css/fullwidth_post_slider{$suffix}.css",
					"{$prefix}/css/slider_modules{$suffix}.css",
					"{$prefix}/css/slider_base{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
					"{$prefix}/css/posts{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/fullwidth-post-title'        => array(
				'css' => array(
					"{$prefix}/css/post_title{$suffix}.css",
					"{$prefix}/css/fullwidth_post_title{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/fullwidth-slider'            => array(
				'css' => array(
					"{$prefix}/css/fullwidth_slider{$suffix}.css",
					"{$prefix}/css/slider_modules{$suffix}.css",
					"{$prefix}/css/slider_base{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/gallery'                     => array(
				'css' => array(
					"{$prefix}/css/gallery{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
					"{$prefix}/css/grid_items{$suffix}.css",
					"{$prefix}/css/slider_base{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
					"{$prefix}/css/magnific_popup.css",
				),
			),
			'core/gallery'                     => array(
				'css' => array(
					"{$prefix}/css/wp_gallery{$suffix}.css",
					"{$prefix}/css/magnific_popup.css",
					"{$prefix}/css/overlay{$suffix}.css",
				),
			),
			'divi/heading'                     => array(
				'css' => array(
					"{$prefix}/css/heading{$suffix}.css",
				),
			),
			'divi/icon'                        => array(
				'css' => array(
					"{$prefix}/css/icon{$suffix}.css",
				),
			),
			'divi/image'                       => array(
				'css' => array(
					"{$prefix}/css/image{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
				),
			),
			'divi/login'                       => array(
				'css' => array(
					"{$prefix}/css/login{$suffix}.css",
					"{$prefix}/css/forms{$suffix}.css",
					"{$prefix}/css/forms{$specialty_suffix}{$suffix}.css",
					"{$prefix}/css/fields{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/map'                         => array(
				'css' => "{$prefix}/css/map{$suffix}.css",
			),
			'divi/map-item'                    => array(),
			'divi/menu'                        => array(
				'css' => array(
					"{$prefix}/css/menus{$suffix}.css",
					"{$prefix}/css/menu{$suffix}.css",
					"{$prefix}/css/header_animations.css",
					"{$prefix}/css/header_shared{$suffix}.css",
				),
			),
			'divi/number-counter'              => array(
				'css' => "{$prefix}/css/number_counter{$suffix}.css",
			),
			'divi/portfolio'                   => array(
				'css' => array(
					"{$prefix}/css/portfolio{$suffix}.css",
					"{$prefix}/css/grid_items{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
				),
			),
			'divi/post-content'                => array(),
			'divi/post-nav'                    => array(
				'css' => "{$prefix}/css/post_nav{$suffix}.css",
			),
			'divi/post-slider'                 => array(
				'css' => array(
					"{$prefix}/css/post_slider{$suffix}.css",
					"{$prefix}/css/posts{$suffix}.css",
					"{$prefix}/css/slider_modules{$suffix}.css",
					"{$prefix}/css/slider_base{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/post-title'                  => array(
				'css' => array(
					"{$prefix}/css/post_title{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/pricing-tables'              => array(
				'css' => array(
					"{$prefix}/css/pricing_tables{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/pricing-tables-item'         => array(),
			'divi/search'                      => array(
				'css' => "{$prefix}/css/search{$suffix}.css",
			),
			'divi/shop'                        => array(
				'css' => array(
					"{$prefix}/css/shop{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
				),
			),
			'divi/sidebar'                     => array(
				'css' => array(
					"{$prefix}/css/sidebar{$suffix}.css",
					"{$prefix}/css/widgets_shared{$suffix}.css",
				),
			),
			'divi/signup'                      => array(
				'css' => array(
					"{$prefix}/css/signup{$suffix}.css",
					"{$prefix}/css/forms{$suffix}.css",
					"{$prefix}/css/forms{$specialty_suffix}{$suffix}.css",
					"{$prefix}/css/fields{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/signup-custom-field'         => array(),
			'divi/slide'                       => array(),
			'divi/slider'                      => array(
				'css' => array(
					"{$prefix}/css/slider{$suffix}.css",
					"{$prefix}/css/slider_modules{$suffix}.css",
					"{$prefix}/css/slider_base{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/social-media-follow'         => array(
				'css' => "{$prefix}/css/social_media_follow{$suffix}.css",
			),
			'divi/social-media-follow-network' => array(),
			'divi/tab'                         => array(),
			'divi/tabs'                        => array(
				'css' => "{$prefix}/css/tabs{$suffix}.css",
			),
			'divi/team-member'                 => array(
				'css' => array(
					"{$prefix}/css/team_member{$suffix}.css",
					"{$prefix}/css/legacy_animations{$suffix}.css",
				),
			),
			'divi/testimonial'                 => array(
				'css' => "{$prefix}/css/testimonial{$suffix}.css",
			),
			'divi/text'                        => array(
				'css' => "{$prefix}/css/text{$suffix}.css",
			),
			'divi/toggle'                      => array(
				'css' => "{$prefix}/css/toggle{$suffix}.css",
			),
			'divi/video'                       => array(
				'css' => array(
					"{$prefix}/css/video{$suffix}.css",
					"{$prefix}/css/video_player{$suffix}.css",
				),
			),
			'divi/video-slider'                => array(
				'css' => array(
					"{$prefix}/css/video_slider{$suffix}.css",
					"{$prefix}/css/video_player{$suffix}.css",
					"{$prefix}/css/slider_base{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
				),
			),
			'divi/video-slider-item'           => array(),
			'divi/wc-additional-info'          => array(
				'css' => array(
					"{$prefix}/css/woo_additional_info{$suffix}.css",
				),
			),
			'divi/wc-add-to-cart'              => array(
				'css' => array(
					"{$prefix}/css/woo_add_to_cart{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/wc-breadcrumb'               => array(
				'css' => array(
					"{$prefix}/css/woo_breadcrumb{$suffix}.css",
				),
			),
			'divi/wc-cart-notice'              => array(
				'css' => array(
					"{$prefix}/css/woo_cart_notice{$suffix}.css",
					"{$prefix}/css/buttons{$suffix}.css",
				),
			),
			'divi/wc-description'              => array(
				'css' => array(
					"{$prefix}/css/woo_description{$suffix}.css",
				),
			),
			'divi/wc-gallery'                  => array(
				'css' => array(
					"{$prefix}/css/gallery{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
					"{$prefix}/css/grid_items{$suffix}.css",
					"{$prefix}/css/magnific_popup.css",
					"{$prefix}/css/slider_base{$suffix}.css",
					"{$prefix}/css/slider_controls{$suffix}.css",
				),
			),
			'divi/wc-images'                   => array(
				'css' => array(
					"{$prefix}/css/image{$suffix}.css",
					"{$prefix}/css/overlay{$suffix}.css",
					"{$prefix}/css/woo_images{$suffix}.css",
				),
			),
			'divi/wc-meta'                     => array(
				'css' => array(
					"{$prefix}/css/woo_meta{$suffix}.css",
				),
			),
			'divi/wc-price'                    => array(
				'css' => array(
					"{$prefix}/css/woo_price{$suffix}.css",
				),
			),
			'divi/wc-rating'                   => array(
				'css' => array(
					"{$prefix}/css/woo_rating{$suffix}.css",
				),
			),
			'divi/wc-related-products'         => array(
				'css' => array(
					"{$prefix}/css/woo_related_products_upsells{$suffix}.css",
				),
			),
			'divi/wc-upsells'                  => array(
				'css' => array(
					"{$prefix}/css/woo_related_products_upsells{$suffix}.css",
				),
			),
			'divi/wc-reviews'                  => array(
				'css' => array(
					"{$prefix}/css/woo_reviews{$suffix}.css",
				),
			),
			'divi/wc-stock'                    => array(
				'css' => array(
					"{$prefix}/css/woo_stock{$suffix}.css",
				),
			),
			'divi/wc-tabs'                     => array(
				'css' => array(
					"{$prefix}/css/tabs{$suffix}.css",
					"{$prefix}/css/woo_tabs{$suffix}.css",
				),
			),
			'divi/wc-title'                    => array(
				'css' => array(
					"{$prefix}/css/woo_title{$suffix}.css",
				),
			),
			'divi/wc-cart-totals'              => array(
				'css' => array(
					"{$prefix}/css/woo_cart_totals{$suffix}.css",
				),
			),
			'divi/wc-cart-products'            => array(
				'css' => array(
					"{$prefix}/css/woo_cart_products{$suffix}.css",
				),
			),
			'divi/wc-cross-sells'              => array(
				'css' => array(
					"{$prefix}/css/woo_cross_sells{$suffix}.css",
				),
			),
			'divi/wc-checkout-billing'         => array(
				'css' => array(
					"{$prefix}/css/woo_checkout_billing{$suffix}.css",
				),
			),
			'divi/wc-checkout-shipping'        => array(
				'css' => array(
					"{$prefix}/css/woo_checkout_shipping{$suffix}.css",
				),
			),
			'divi/wc-checkout-additional-info' => array(
				'css' => array(
					"{$prefix}/css/woo_checkout_info{$suffix}.css",
				),
			),
			'divi/wc-checkout-order-details'   => array(
				'css' => array(
					"{$prefix}/css/woo_checkout_details{$suffix}.css",
				),
			),
			'divi/wc-checkout-payment-info'    => array(
				'css' => array(
					"{$prefix}/css/woo_checkout_payment{$suffix}.css",
				),
			),
		);
	}

	/**
	 * Convert Shortcode to Block Name.
	 *
	 * @param string $shortcode Shortcode.
	 *
	 * @return string
	 */
	public static function get_block_name_from_shortcode( string $shortcode ): string {
		if ( 'gallery' === $shortcode ) {
			return 'core/gallery';
		}

		static $cached = [];

		if ( isset( $cached[ $shortcode ] ) ) {
			return $cached[ $shortcode ];
		}

		$block_name = str_replace( 'et_pb_', 'divi/', $shortcode );
		$block_name = str_replace( '_', '-', $block_name );

		$cached[ $shortcode ] = $block_name;

		return $block_name;
	}

	/**
	 * Convert Block Name to Shortcode.
	 *
	 * @param string $block_name Block Name.
	 *
	 * @return string
	 */
	public static function get_shortcode_name_from_block( string $block_name ): string {
		if ( 'core/gallery' === $block_name ) {
			return 'gallery';
		}

		static $cached = [];

		if ( isset( $cached[ $block_name ] ) ) {
			return $cached[ $block_name ];
		}

		$shortcode = str_replace( 'divi/', 'et_pb_', $block_name );
		$shortcode = str_replace( '-', '_', $shortcode );

		$cached[ $block_name ] = $shortcode;

		return $shortcode;
	}

	/**
	 * Retrieve Post ID from 1 of 4 sources depending on which exists:
	 * - $_POST['current_page']['id']
	 * - $_POST['et_post_id']
	 * - $_GET['post']
	 * - get_the_ID()
	 *
	 * @since ?? Copied from `ET_Builder_Element::_should_respect_post_interference()`.
	 *
	 * @return int|bool
	 */
	public static function get_current_post_id() {
		// Getting correct post id in computed_callback request.
		// phpcs:disable WordPress.Security.NonceVerification -- This function does not change any state, and is therefore not susceptible to CSRF.
		if ( wp_doing_ajax() && ArrayUtility::get_value( $_POST, 'current_page.id' ) ) {
			return absint( ArrayUtility::get_value( $_POST, 'current_page.id' ) );
		}

		if ( wp_doing_ajax() && isset( $_POST['et_post_id'] ) ) {
			return absint( $_POST['et_post_id'] );
		}

		if ( isset( $_POST['post'] ) ) {
			return absint( $_POST['post'] );
		}
		// phpcs:enable

		if ( self::should_respect_post_interference() ) {
			return get_the_ID();
		}

		return ET_Post_Stack::get_main_post_id();
	}

	/**
	 * Returns Block names based on assets list.
	 *
	 * @since ??
	 */
	public static function get_divi_block_names(): array {
		return array_keys( self::get_assets_list() );
	}

	/**
	 * Returns Shortcode slugs based on assets list.
	 *
	 * @since ??
	 */
	public static function get_divi_shortcode_slugs(): array {
		static $shortcode_slugs = null;

		if ( null !== $shortcode_slugs ) {
			return $shortcode_slugs;
		}

		$block_names     = self::get_divi_block_names();
		$shortcode_slugs = array_map( [ self::class, 'get_shortcode_name_from_block' ], $block_names );

		return $shortcode_slugs;
	}

	/**
	 * Gets the assets directory.
	 *
	 * @since ??
	 *
	 * @param bool $url check if url.
	 *
	 * @return string
	 */
	public static function get_dynamic_assets_path( bool $url = false ): string {
		$is_builder_active = et_is_builder_plugin_active();

		$template_address = $url ? get_template_directory_uri() : get_template_directory();

		if ( $is_builder_active ) {
			$template_address = $url ? ET_BUILDER_PLUGIN_URI : ET_BUILDER_PLUGIN_DIR;
		}

		// Value for the filter.
		$template_address = $template_address . '/includes/builder/feature/dynamic-assets/assets';

		/**
		 * Filters prefix for assets path.
		 *
		 * This filter is the replacement of Divi 4 filter `et_dynamic_assets_prefix`.
		 *
		 * @since ??
		 *
		 * @param string $template_address
		 */
		return apply_filters( 'divi_frontend_assets_dynamic_assets_utils_prefix', $template_address );
	}

	/**
	 * Disable dynamic icons if TP modules are present.
	 *
	 * @since ??
	 */
	public static function get_dynamic_icons_default_value(): string {
		require_once get_template_directory() . '/includes/builder/api/DiviExtensions.php';

		$tp_extensions = \DiviExtensions::get();

		if ( ! empty( $tp_extensions ) || ( is_child_theme() && ! et_is_builder_plugin_active() ) ) {
			return 'off';
		}

		return 'on';
	}

	/**
	 * Retrieves the feature detection map.
	 *
	 * @since ??
	 *
	 * @param array $options Feature Detection Options.
	 *
	 * @return array The feature detection map.
	 */
	public static function get_feature_detection_map( array $options = [] ): array {
		static $cache = [];

		$cached_key = md5( intval( $options['has_block'] ) . intval( $options['has_shortcode'] ) );

		if ( isset( $cache[ $cached_key ] ) ) {
			return $cache[ $cached_key ];
		}

		// Value for the filter.
		$feature_detection_map = [
			'animation_style'              => [
				'callback'        => [ DetectFeature::class, 'has_animation_style' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'excerpt_content_on'           => [
				'callback'        => [ DetectFeature::class, 'has_excerpt_content_on' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'gutter_widths'                => [
				'callback'        => [ DetectFeature::class, 'get_gutter_widths' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'icon_font_divi'               => [
				'callback'        => [ DetectFeature::class, 'has_icon_font' ],
				'additional_args' => [
					'type'    => 'divi',
					'options' => $options,
				],
			],
			'icon_font_fa'                 => [
				'callback'        => [ DetectFeature::class, 'has_icon_font' ],
				'additional_args' => [
					'type'    => 'fa',
					'options' => $options,
				],
			],
			'lightbox'                     => [
				'callback'        => [ DetectFeature::class, 'has_lightbox' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'fullscreen_section_enabled'   => [
				'callback'        => [ DetectFeature::class, 'has_fullscreen_section_enabled' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'scroll_effects_enabled'       => [
				'callback'        => [ DetectFeature::class, 'has_scroll_effects_enabled' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'section_dividers_enabled'     => [
				'callback'        => [ DetectFeature::class, 'has_section_dividers_enabled' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'link_enabled'                 => [
				'callback'        => [ DetectFeature::class, 'has_link_enabled' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'split_testing_enabled'        => [
				'callback'        => [ DetectFeature::class, 'has_split_testing_enabled' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'social_follow_icon_font_divi' => [
				'callback'        => [ DetectFeature::class, 'has_social_follow_icon_font' ],
				'additional_args' => [
					'type'    => 'divi',
					'options' => $options,
				],
			],
			'social_follow_icon_font_fa'   => [
				'callback'        => [ DetectFeature::class, 'has_social_follow_icon_font' ],
				'additional_args' => [
					'type'    => 'fa',
					'options' => $options,
				],
			],
			'specialty_section'            => [
				'callback'        => [ DetectFeature::class, 'has_specialty_section' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'sticky_position_enabled'      => [
				'callback'        => [ DetectFeature::class, 'has_sticky_position_enabled' ],
				'additional_args' => [
					'options' => $options,
				],
			],
			'global_color_ids'             => [
				'callback'        => [ DetectFeature::class, 'get_global_color_ids' ],
				'additional_args' => [],
			],
		];

		/**
		 * Filters feature detection map to detect use on the page.
		 *
		 * This filter is the replacement of Divi 4 filter `et_builder_module_attrs_values_used` .
		 *
		 * @since ??
		 *
		 * @param array $feature_detection_functions Feature detection callbacks.
		 */
		$feature_detection_map = (array) apply_filters(
			'divi_frontend_assets_dynamic_assets_utils_module_feature_detection_map',
			$feature_detection_map
		);

		$cache[ $cached_key ] = $feature_detection_map;

		return $feature_detection_map;
	}

	/**
	 * Returns the list of Divi modules with `icon` option.
	 *
	 * D4 version of the function: `et_pb_get_font_icon_modules`.
	 *
	 * @since ??
	 *
	 * @param string $group certain group of modules .
	 *
	 * @return array
	 */
	public static function get_font_icon_modules( $group = false ) {

		$font_icon_modules_used_in_migrations = array(
			'button'  => array(
				'divi/button',
				'divi/comments',
				'divi/contact-form',
				'divi/cta',
				'divi/fullwidth-header',
				'divi/fullwidth-post-slider',
				'divi/login',
				'divi/post-slider',
				'divi/pricing-tables',
				'divi/pricing-table',
				'divi/signup',
				'divi/slider',
				'divi/slide',
				'divi/wc-add-to-cart',
				'divi/wc-cart-notice',
			),
			'blurb'   => array(
				'divi/blurb',
			),
			'overlay' => array(
				'divi/blog',
				'divi/filterable-portfolio',
				'divi/fullwidth-image',
				'divi/fullwidth-portfolio',
				'divi/gallery',
				'divi/image',
				'divi/portfolio',
				'divi/shop',
				'divi/wc-related-products',
				'divi/wc-upsells',
			),
			'toggle'  => array(
				'divi/toggle',
			),
		);

		$other_select_icon_modules = array(
			'select_icon' => array(
				'divi/icon',
				'divi/video',
				'divi/video-slider',
				'divi/video-slider-item',
				'divi/testimonial',
				'divi/accordion',
				'divi/accordion-item',
			),
		);

		if ( false === $group ) {
			// Return all modules that use select_icon.
			$all_modules             = [];
			$all_select_icon_modules = array_merge( $font_icon_modules_used_in_migrations, $other_select_icon_modules );
			foreach ( $all_select_icon_modules as $select_icon_module ) {
				$all_modules = array_merge( $all_modules, $select_icon_module );
			}
			return $all_modules;
		} elseif ( isset( $font_icon_modules_used_in_migrations[ $group ] ) ) {
			// Return certain modules list by $group flag.
			return $font_icon_modules_used_in_migrations[ $group ];
		}

		return [];
	}

	/**
	 * Find array values in array_1 that do not exist in array_2.
	 *
	 * @since ??
	 *
	 * @param array $array_1 First array.
	 * @param array $array_2 Second array.
	 */
	public static function get_new_array_values( array $array_1, array $array_2 ): array {
		$new_array_values = array();

		foreach ( $array_1 as $key => $value ) {
			if ( empty( $array_2[ $key ] ) ) {
				$new_array_values[ $key ] = $value;
			}
		}

		return $new_array_values;
	}

	/**
	 * Get the preset attributes for the given data.
	 *
	 * @since ??
	 *
	 * @param array $preset_ids Containing block_name and preset_id.
	 *
	 * @return array The preset attributes for the given block data.
	 */
	public static function get_global_preset_attributes( array $preset_ids ): array {
		$all_presets       = GlobalPreset::get_data();
		$preset_attributes = [];

		foreach ( $preset_ids as $block ) {
			$module_name = $block['block_name'];
			$preset_id   = $block['preset_id'];

			// Get default preset id.
			if ( 'default' === $preset_id ) {
				$preset_id = $all_presets['module'][ $module_name ]['default'] ?? '';
			}

			// Include preset attrs when found.
			if ( isset( $all_presets['module'][ $module_name ]['items'][ $preset_id ]['attrs'] ) ) {
				$preset_attributes[] = $all_presets['module'][ $module_name ]['items'][ $preset_id ]['attrs'];
			}
		}

		return $preset_attributes;
	}

	/**
	 * Get the shortcode preset attributes for the given data.
	 *
	 * @since ??
	 *
	 * @param array $preset_ids Containing shortcode_name and preset_id.
	 *
	 * @return array The preset attributes for the given shortcode data.
	 */
	public static function get_shortcode_preset_attributes( array $preset_ids ): array {
		$all_presets       = GlobalPreset::get_legacy_data();
		$preset_attributes = [];

		foreach ( $preset_ids as $data ) {
			$module_name = $data['shortcode_name'];
			$preset_id   = $data['preset_id'];

			// Get default preset id.
			if ( 'default' === $preset_id ) {
				$preset_id = $all_presets[ $module_name ]['default'] ?? '';
			}

			// Include preset attrs when found.
			if ( isset( $all_presets[ $module_name ]['presets'][ $preset_id ]['settings'] ) ) {
				$preset_attributes[] = $all_presets[ $module_name ]['presets'][ $preset_id ]['settings'];
			}
		}

		return $preset_attributes;
	}

	/**
	 * Get the post IDs of active Theme Builder templates.
	 *
	 * @since ??
	 *
	 * @return array
	 */
	public static function get_theme_builder_template_ids(): array {
		$tb_layouts   = et_theme_builder_get_template_layouts();
		$template_ids = array();

		// Extract layout ids used in current request.
		if ( ! empty( $tb_layouts ) ) {
			if ( $tb_layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['override'] ) {
				if ( ! empty( $tb_layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['enabled'] ) ) {
					$template_ids[] = intval( $tb_layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['id'] );
				}
			}
			if ( $tb_layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['override'] ) {
				if ( ! empty( $tb_layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['enabled'] ) ) {
					$template_ids[] = intval( $tb_layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['id'] );
				}
			}
			if ( $tb_layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['override'] ) {
				if ( ! empty( $tb_layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['enabled'] ) ) {
					$template_ids[] = intval( $tb_layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['id'] );
				}
			}
		}

		return $template_ids;
	}

	/**
	 * Merge multiple arrays and returns an array with unique values.
	 *
	 * @since ??
	 *
	 * @return array
	 */
	public static function get_unique_array_values(): array {
		$merged_array = array();

		foreach ( func_get_args() as $array_of_value ) {
			if ( empty( $array_of_value ) ) {
				continue;
			}

			$merged_array = array_merge( $merged_array, $array_of_value );
		}

		return array_values( array_unique( $merged_array ) );
	}

	/**
	 * Get the post IDs of active WP Editor templates and template parts.
	 *
	 * @since ??
	 *
	 * @return array
	 */
	public static function get_wp_editor_template_ids(): array {
		$templates    = et_builder_get_wp_editor_templates();
		$template_ids = [];

		// Bail early if current page doesn't have templates.
		if ( empty( $templates ) ) {
			return $template_ids;
		}

		foreach ( $templates as $template ) {
			$template_ids[] = isset( $template->wp_id ) ? (int) $template->wp_id : 0;
		}

		return $template_ids;
	}

	/**
	 * Check if any widgets are currently active.
	 *
	 * @since ??
	 *
	 * @return bool
	 */
	public static function has_builder_widgets(): bool {
		global $wp_registered_sidebars;

		$sidebars = get_option( 'sidebars_widgets' );

		foreach ( $wp_registered_sidebars as $sidebar_key => $sidebar_options ) {
			if ( ! empty( $sidebars[ $sidebar_key ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check whether current block widget is active or not.
	 *
	 * @since ??
	 *
	 * @param string $block_widget_name Block widget name.
	 *
	 * @return boolean Whether current block widget is active or not.
	 */
	public static function is_active_block_widget( string $block_widget_name ): bool {
		return in_array( $block_widget_name, self::get_active_block_widgets(), true );
	}

	/**
	 * Get Extra Home layout ID.
	 *
	 * @since ??
	 *
	 * @return int|null
	 */
	public static function get_extra_home_layout_id() {
		if ( function_exists( 'extra_get_home_layout_id' ) ) {
			return extra_get_home_layout_id();
		}
		return null;
	}

	/**
	 *  Get Extra Taxonomy layout ID.
	 *
	 * @since 4.17.5
	 *
	 * @return int|null
	 */
	public static function get_extra_tax_layout_id() {
		if ( function_exists( 'extra_get_tax_layout_id' ) ) {
			return extra_get_tax_layout_id();
		}
		return null;
	}

	/**
	 * Check whether Extra Home layout is being used.
	 *
	 * @since ??
	 *
	 * @return boolean whether Extra Home layout is being used.
	 */
	public static function is_extra_layout_used_as_front(): bool {
		return function_exists( 'et_extra_show_home_layout' ) && et_extra_show_home_layout() && is_front_page();
	}

	/**
	 * Check whether Extra Home layout is being used.
	 *
	 * @since ??
	 *
	 * @return boolean whether Extra Home layout is being used.
	 */
	public static function is_extra_layout_used_as_home(): bool {
		return function_exists( 'et_extra_show_home_layout' ) && et_extra_show_home_layout() && is_home();
	}

	/**
	 * Check to see if this is a front end request applicable to Dynamic Assets.
	 *
	 * @since ??
	 *
	 * @return bool
	 */
	public static function is_dynamic_front_end_request(): bool {
		static $is_dynamic_front_end_request = null;

		if ( null === $is_dynamic_front_end_request ) {
			$is_dynamic_front_end_request = false;

			if ( // Disable for WordPress admin requests.
				! is_admin()
				// Disable for non-front-end requests.
				&& ! wp_doing_ajax()
				&& ! wp_doing_cron()
				&& ! wp_is_json_request()
				&& ! ( defined( 'REST_REQUEST' ) && REST_REQUEST )
				&& ! ( defined( 'WP_CLI' ) && WP_CLI )
				&& ! ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
				&& ! is_trackback()
				&& ! is_feed()
				&& ! get_query_var( 'sitemap' )
				// Disable when in preview modes.
				&& ! is_customize_preview()
				&& ! is_et_pb_preview()
				&& ! ET_GB_Block_Layout::is_layout_block_preview()
				&& ! is_preview()
				// Disable when using the visual builder.
				&& ! et_fb_is_enabled()
				// Disable on paginated index pages when blog style mode is enabled and when using the Divi Builder plugin.
				&& ! ( is_paged() && ( 'on' === et_get_option( 'divi_blog_style', 'off' ) || et_is_builder_plugin_active() ) )
			) {
				$is_dynamic_front_end_request = true;
			}
		}

		return $is_dynamic_front_end_request;
	}

	/**
	 * Check if current page is a taxonomy page.
	 *
	 * @since ??
	 *
	 * @return boolean
	 */
	public static function is_taxonomy(): bool {
		return is_tax() || is_category() || is_tag();
	}

	/**
	 * Check if current page is virtual.
	 *
	 * @since ??
	 *
	 * @return bool
	 */
	public static function is_virtual_page(): bool {
		global $wp;
		$slug = $wp->request;

		// Value for the filter.
		$valid_virtual_pages = [
			'homes-for-sale-search',
			'homes-for-sale-search-advanced',
		];

		/**
		 * Valid virtual pages for which dynamic css should be enabled.
		 * Virtual pages are just custom enpoints or links added via rewrite hooks,
		 * Meaning, it's not an actual page but it does have a valid link possibly,
		 * custom generated by a plugin.
		 *
		 * Add more virtual pages slug if there are known compatibility issues.
		 *
		 * This filter is the replacement of Divi 4 filter `et_builder_dynamic_css_virtual_pages`.
		 *
		 * @since ??
		 *
		 * @return array $valid_virtual_pages
		 */
		$valid_virtual_pages = apply_filters(
			'divi_frontend_assets_dynamic_assets_utils_virtual_pages',
			$valid_virtual_pages
		);

		if ( in_array( $slug, $valid_virtual_pages, true ) ) {
			return true;
		}

		// Usually custom rewrite rules will return as page but will have no ID.
		if ( is_page() && 0 === get_the_ID() ) {
			return true;
		}

		return false;
	}

	/**
	 * Get embedded media from post content.
	 *
	 * @since ??
	 *
	 * @param string $content Post Content.
	 *
	 * @return boolean false on failure, true on success.
	 */
	public static function is_media_embedded_in_content( string $content ): bool {
		if ( empty( $content ) ) {
			return false;
		}

		// regex match for youtube and vimeo urls in $content.
		$pattern = '~https?://(?:www\.)?(?:youtube\.com/watch\?v=|youtu\.be/|vimeo\.com/)([^\s]+)~i';
		preg_match_all( $pattern, $content, $matches );

		if ( empty( $matches[0] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check to see if we should initiate initial class logic.
	 *
	 * @since ??
	 *
	 * @return bool.
	 */
	public static function should_initiate_dynamic_assets(): bool {
		// Bail if this is not a front-end or builder page request.
		if ( ! et_builder_is_frontend_or_builder() ) {
			return false;
		}

		// Bail if Dynamic CSS and Dynamic JS are both disabled.
		if ( ! self::use_dynamic_assets() && self::disable_js_on_demand() ) {
			return false;
		}

		// Bail if feed since CSS isn't needed for RSS/Atom.
		if ( is_feed() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the current request should generate Dynamic Assets.
	 * We only generate dynamic assets on the front end and when cache dir is writable.
	 *
	 * @since ??
	 *
	 * @return bool
	 */
	public static function should_generate_dynamic_assets(): bool {
		static $should_generate_assets = null;

		if ( null === $should_generate_assets ) {
			if ( // Cache directory must be writable.
				et_core_cache_dir()->can_write
				// Request must be an applicable front-end request.
				&& self::is_dynamic_front_end_request()
			) {
				$should_generate_assets = true;
			}
		}

		/**
		 * Filters whether to generate dynamic assets.
		 *
		 * This filter is the replacement of Divi 4 filter `et_should_generate_dynamic_assets`.
		 *
		 * @since ??
		 *
		 * @param bool $should_generate_assets
		 */
		return apply_filters( 'divi_frontend_assets_dynamic_assets_utils_should_generate_dynamic_assets', (bool) $should_generate_assets );
	}

	/**
	 * Get whether third party post interference should be respected.
	 * Current use case is for plugins like Toolset that render a
	 * loop within a layout which renders another layout for
	 * each post - in this case we must NOT override the
	 * current post so the loop works as expected.
	 *
	 * @since ?? Copied from `ET_Builder_Element::_should_respect_post_interference()`.
	 *
	 * @return boolean
	 */
	public static function should_respect_post_interference() {
		$post = ET_Post_Stack::get();

		return null !== $post && get_the_ID() !== $post->ID;
	}

	/**
	 * Check if Dynamic CSS is enabled.
	 *
	 * @since ??
	 *
	 * @return bool
	 */
	public static function use_dynamic_assets(): bool {
		// TODO Remove this or deprecate the function during Divi 5 test. 
		// We are temporarily returning overriding this function to force Dynamic Assets to be on to improve performance.
		if ( ! et_core_is_fb_enabled() && ! is_preview() && ! is_customize_preview() ) {
			return true;
		}

		global $shortname;
		static $use_dynamic_assets = null;

		if ( null === $use_dynamic_assets ) {
			if ( et_is_builder_plugin_active() ) {
				$options     = get_option( 'et_pb_builder_options', array() );
				$dynamic_css = $options['performance_main_dynamic_css'] ?? 'on';
			} else {
				$dynamic_css = et_get_option( $shortname . '_dynamic_css', 'on' );
			}

			if ( 'on' === $dynamic_css && self::should_generate_dynamic_assets() ) {
				$use_dynamic_assets = true;
			} else {
				$use_dynamic_assets = false;
			}

			/**
			 * Filters whether to use dynamic CSS.
			 *
			 * This filter is the replacement of Divi 4 filter `et_use_dynamic_css`.
			 *
			 * @since ??
			 *
			 * @param bool $use_dynamic_assets
			 */
			$use_dynamic_assets = apply_filters( 'divi_frontend_assets_dynamic_assets_utils_use_dynamic_assets', (bool) $use_dynamic_assets );
		}

		return $use_dynamic_assets;
	}

	/**
	 * Check if Dynamic Icons are enabled.
	 *
	 * @since ??
	 */
	public static function use_dynamic_icons() {
		global $shortname;
		$child_theme_active = is_child_theme();

		if ( et_is_builder_plugin_active() ) {
			$options       = get_option( 'et_pb_builder_options', [] );
			$dynamic_icons = $options['performance_main_dynamic_icons'] ?? self::get_dynamic_icons_default_value();
		} else {
			$dynamic_icons = et_get_option( $child_theme_active ? $shortname . '_dynamic_icons_child_theme' : $shortname . '_dynamic_icons', self::get_dynamic_icons_default_value() );
		}

		return $dynamic_icons;
	}

}