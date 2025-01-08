<?php
/**
 * Class that handles endpoints callback for upgrading site's content.
 *
 * @since ??
 *
 * @package D5_Readiness
 */

namespace Divi\D5_Readiness\Server\AJAXEndpoints;

use Divi\D5_Readiness\Server\Conversion;

use ET\Builder\Packages\Conversion\Conversion as BuilderConversion;
use ET\Builder\Packages\GlobalData\GlobalPreset;

/**
 * Class that handles endpoints callback for upgrading site's content.
 *
 * @since ??
 *
 * @package D5_Readiness
 */
class Upgrade {
	/**
	 * Register endpoints for upgrading site's content.
	 *
	 * @since ??
	 */
	public static function register_endpoints() {
		add_action( 'wp_ajax_et_d5_readiness_convert_d4_to_d5', [ self::class, 'convert_d4_to_d5' ] );
	}

	/**
	 * Ajax Callback :: Convert D4 content to D5 format.
	 */
	public static function convert_d4_to_d5() {
		et_core_security_check( 'edit_posts', 'et_d5_readiness_convert_d4_to_d5_nonce', 'wp_nonce' );

		// Retrieve raw post IDs from the request and sanitize input.
		$raw_post_ids = filter_input( INPUT_POST, 'post_ids', FILTER_SANITIZE_SPECIAL_CHARS );

		// Retrieve is_last_batch from the request and sanitize input.
		$is_last_batch = filter_input( INPUT_POST, 'is_last_batch', FILTER_VALIDATE_BOOLEAN );

		// Decode the JSON data.
		$post_ids = json_decode( $raw_post_ids, true );

		// Validate and sanitize the post IDs.
		if ( ! is_array( $post_ids ) ) {
			wp_send_json_error(
				[
					'message' => __( 'Invalid post IDs format.', 'Divi' ),
				]
			);
		}

		// Ensure all post IDs are integers and positive.
		$post_ids = array_filter(
			$post_ids,
			function ( $id ) {
				return is_int( $id ) && $id > 0;
			}
		);

		// Check if there are any post IDs to convert.
		if ( empty( $post_ids ) ) {
			wp_send_json_success(
				[
					'message' => __( 'There are no posts to convert!', 'Divi' ),
					'status'  => 'no_conversion',
				]
			);
		}

		$results = [
			'list'       => [],
			'structured' => [],
			'status'     => 'has_conversion',
		];

		BuilderConversion::initialize_shortcode_framework();
		foreach ( $post_ids as $post_id ) {
			$converted_post = Conversion::convert_d4_to_d5_single( $post_id );

			$results['list'][ $post_id ] = $converted_post;

			if ( ! isset( $results['structured'][ $converted_post['postType'] ] ) ) {
				$results['structured'][ $converted_post['postType'] ] = [];
			}

			if ( ! isset( $results['structured'][ $converted_post['postType'] ][ $converted_post['postStatus'] ] ) ) {
				$results['structured'][ $converted_post['postType'] ][ $converted_post['postStatus'] ] = [
					'upgraded' => [],
					'failed'   => [],
				];
			}

			if ( isset( $converted_post['status'] ) && 'success' === $converted_post['status'] ) {
				$results['structured'][ $converted_post['postType'] ][ $converted_post['postStatus'] ]['upgraded'][ $converted_post['postId'] ] = $converted_post;
			} else {
				$results['structured'][ $converted_post['postType'] ][ $converted_post['postStatus'] ]['failed'][ $converted_post['postId'] ] = $converted_post;
			}
		}

		// If this is the last batch, update the status of the last batch.
		if ( $is_last_batch ) {
			et_update_option( 'et_d5_readiness_conversion_finished', true );
			self::_maybe_convert_global_presets();

			et_core_clear_wp_cache();
		}

		wp_send_json_success( $results );
	}

	/**
	 * Convert global presets from D4 to D5 format.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	protected static function _maybe_convert_global_presets() {
		if ( ! empty( GlobalPreset::get_data() ) ) {
			// Presets are already converted.
			return;
		}

		$d4_presets = GlobalPreset::get_legacy_data();

		if ( empty( $d4_presets ) ) {
			return;
		}

		$d5_presets = BuilderConversion::maybe_convert_presets_data( $d4_presets );

		$processed_presets = GlobalPreset::process_presets( $d5_presets );

		GlobalPreset::save_data( $processed_presets );

		GlobalPreset::save_is_legacy_presets_imported( true );
	}
}
