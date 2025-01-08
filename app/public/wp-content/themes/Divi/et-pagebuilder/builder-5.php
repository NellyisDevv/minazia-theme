<?php
/**
 * Builder-5 Helpers.
 *
 * @package Divi
 * @since ??
 */

define( 'ET_BUILDER_5_DIR', get_template_directory() . '/includes/builder-5/' );
define( 'ET_BUILDER_5_URI', get_template_directory_uri() . '/includes/builder-5' );

if ( ! function_exists( 'et_builder_d5_enabled' ) ) :
	/**
	 * Check whether D5 is enabled.
	 *
	 * @since ?? Removed the `et_enable_d5` option check because we no longer let people use the Divi 4 Visual Builder.
	 * @since 5.0.0-dev-alpha.10
	 *
	 * @return bool
	 */
	function et_builder_d5_enabled(): bool {
		static $enabled;

		// Early return if `et_builder_d5_enabled` was previously run, so that
		// we don't apply the `et_builder_d5_enabled` filter more than once.
		if ( isset( $enabled ) ) {
			return $enabled;
		}

		// Defining this here for clarity during doc generation.
		$enabled = true;

		/**
		 * Filter for D5 activation status
		 *
		 * If the `$enabled` variable has just been set, then pass its value
		 * here (but use the filter to allow other code to override this).
		 *
		 * @since 5.0.0-dev-alpha.10
		 *
		 * @param bool $enabled
		 */
		$enabled = apply_filters( 'et_builder_d5_enabled', $enabled );

		return $enabled;
	}
endif;

/**
 * Load D5 file.
 *
 * @since ??
 */
function et_setup_builder_5() {
	require_once ET_BUILDER_5_DIR . 'server/bootstrap.php';

	remove_filter( 'the_content', 'et_pb_fix_builder_shortcodes' );
	remove_filter( 'et_builder_render_layout', 'et_pb_fix_builder_shortcodes' );

	remove_filter( 'the_content', 'et_pb_the_content_prep_code_module_for_wpautop', 0 );
	remove_filter( 'et_builder_render_layout', 'et_pb_the_content_prep_code_module_for_wpautop', 0 );
}
add_action( 'init', 'et_setup_builder_5', 0 );

/**
 * Load D4 shortcode framework if d5 vb should be loaded.
 */
function et_setup_builder_5_shortcode_framework() {
	$is_vb_enabled = et_core_is_fb_enabled();
	$is_app_window = isset( $_GET['app_window'] ) && '1' === $_GET['app_window'];
	$has_x_wp_nonce = isset( $_SERVER['HTTP_X_WP_NONCE'] );

	$should_load_shortcode_framework = false;

	if (
		( $is_vb_enabled && $is_app_window )
		|| $has_x_wp_nonce
		// TODO, should be able to make this more specific to One or
		// many of these, or similar, conditions are needed for
		// the GB -> Use Divi Builder button to work,
		// to trigger auto-activation of the VB.
		// || ( $has_x_wp_nonce && $is_editpost_action )
		// || ( $has_x_wp_nonce && $is_post_php_edit )
		// || ( $has_x_wp_nonce && $is_rest_request )
	) {
		$should_load_shortcode_framework = true;
	}

	if ( $should_load_shortcode_framework ) {
		add_filter( 'et_should_load_shortcode_framework', '__return_true' );
	}
}
add_action( 'init', 'et_setup_builder_5_shortcode_framework', -1 );
