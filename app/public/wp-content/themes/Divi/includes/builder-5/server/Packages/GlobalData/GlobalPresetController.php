<?php
/**
 * REST: GlobalPresetController class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\GlobalData;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\Controllers\RESTController;
use ET\Builder\Framework\UserRole\UserRole;
use ET\Builder\Packages\GlobalData\GlobalPreset;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * GlobalPreset REST Controller class.
 *
 * @since ??
 */
class GlobalPresetController extends RESTController {

	/**
	 * Sync global preset data with the server.
	 *
	 * @since ??
	 *
	 * @param WP_REST_Request $request REST request object.
	 *
	 * @return WP_REST_Response Returns the REST response object.
	 */
	public static function sync( WP_REST_Request $request ): WP_REST_Response {
		$prepared_data = GlobalPreset::prepare_data( $request->get_param( 'presets' ) );

		$saved_data = GlobalPreset::save_data( $prepared_data );

		// Save the is converted flag for the global presets. It should save only once.
		$is_legacy_presets_imported  = GlobalPreset::is_legacy_presets_imported();
		$is_legacy_presets_importing = $request->get_param( 'converted' );
		if ( empty( $is_legacy_presets_imported ) && $is_legacy_presets_importing ) {
			GlobalPreset::save_is_legacy_presets_imported( boolval( $is_legacy_presets_importing ) );
		}

		return RESTController::response_success( (object) $saved_data );
	}

	/**
	 * Get the arguments for the sync action.
	 *
	 * This function returns an array that defines the arguments for the sync action,
	 * which is used in the `register_rest_route()` function.
	 *
	 * @since ??
	 *
	 * @return array An array of arguments for the sync action. The array should aligns with the GlobalData.Presets.RestSchemaItems TS interface.
	 */
	public static function sync_args(): array {
		return [
			'presets'   => [
				'required'   => true,
				'type'       => 'object',
				'properties' => [
					'module' => [
						'required' => true,
						'type'     => 'array',
						'items'    => [
							'type'       => 'object',
							'properties' => [
								'default'    => [
									'required' => true,
									'type'     => 'string',
									'format'   => 'text-field', // Set format to 'text-field' to get the value sanitized using sanitize_text_field.
								],
								'moduleName' => [
									'required'  => true,
									'type'      => 'string',
									'format'    => 'text-field', // Set format to 'text-field' to get the value sanitized using sanitize_text_field.
									'minLength' => 1, // Prevent empty string.
								],
								'items'      => [
									'required' => true,
									'type'     => 'array',
									'items'    => [
										'type'       => 'object',
										'properties' => [
											'type'        => [
												'required' => true,
												'type'     => 'string',
												'enum'     => [ 'module' ],
											],
											'id'          => [
												'required' => true,
												'type'     => 'string',
												'format'   => 'text-field', // Set format to 'text-field' to get the value sanitized using sanitize_text_field.
												'minLength' => 1, // Prevent empty string.
											],
											'name'        => [
												'required' => true,
												'type'     => 'string',
												'format'   => 'text-field', // Set format to 'text-field' to get the value sanitized using sanitize_text_field.
												'minLength' => 1, // Prevent empty string.
											],
											'created'     => [
												'required' => true,
												'type'     => 'integer',
											],
											'updated'     => [
												'required' => true,
												'type'     => 'integer',
											],
											'version'     => [
												'required' => true,
												'type'     => 'string',
												'format'   => 'text-field', // Set format to 'text-field' to get the value sanitized using sanitize_text_field.
												'minLength' => 1, // Prevent empty string.
											],
											'attrs'       => [
												'required' => false,
												'type'     => 'object', // Will be sanitized using GlobalPreset::prepare_data().
											],
											'renderAttrs' => [
												'required' => false,
												'type'     => 'object', // Will be sanitized using GlobalPreset::prepare_data().
											],
											'styleAttrs'  => [
												'required' => false,
												'type'     => 'object', // Will be sanitized using GlobalPreset::prepare_data().
											],
											'moduleName'  => [
												'required' => true,
												'type'     => 'string',
												'format'   => 'text-field', // Set format to 'text-field' to get the value sanitized using sanitize_text_field.
												'minLength' => 1, // Prevent empty string.
											],
										],
									],
								],
							],
						],
					],
				],
			],
			'converted' => [
				'required' => false,
				'type'     => 'boolean',
			],
		];
	}

	/**
	 * Provides the permission status for the sync action.
	 *
	 * @since ??
	 *
	 * @return bool Returns `true` if the current user has the permission to use the Visual Builder, `false` otherwise.
	 */
	public static function sync_permission(): bool {
		return UserRole::can_current_user_use_visual_builder();
	}

}
