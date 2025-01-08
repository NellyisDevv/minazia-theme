<?php
/**
 * REST: GlobalPreset class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\GlobalData;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\VisualBuilder\Saving\SavingUtility;
use ET\Builder\Packages\GlobalData\GlobalPresetItem;
use ET\Builder\Packages\ModuleUtils\ModuleUtils;

/**
 * GlobalPreset class.
 *
 * @since ??
 */
class GlobalPreset {

	/**
	 * The data cache.
	 *
	 * @since ??
	 *
	 * @var mixed
	 */
	private static $_data = null;

	/**
	 * Get the option name for the global presets.
	 *
	 * @since ??
	 *
	 * @return string The option name.
	 */
	public static function option_name(): string {
		return 'builder_global_presets_d5';
	}

	/**
	 * Get the option name to check the legacy preset's import check.
	 *
	 * @since ??
	 *
	 * @return string The option name.
	 */
	public static function is_legacy_presets_imported_option_name(): string {
		return 'builder_is_legacy_presets_imported_to_d5';
	}

	/**
	 * Delete the data from the DB.
	 *
	 * @since ??
	 */
	public static function delete_data():void {
		et_delete_option( self::option_name() );

		// Reset the data cache.
		self::$_data = null;
	}

	/**
	 * Get the data from the DB.
	 *
	 * @since ??
	 *
	 * @return array The data from the DB. The array structure is aligns with GlobalData.Presets.Items TS interface.
	 */
	public static function get_data(): array {
		if ( null !== self::$_data ) {
			return self::$_data;
		}

		$data = et_get_option( self::option_name(), [], '', true, false, '', '', true );

		if ( is_array( $data ) ) {
			self::$_data = $data;
			return $data;
		}

		return [];
	}

	/**
	 * Get the data from the DB for legacy presets import check.
	 *
	 * @since ??
	 *
	 * @return string The data from the DB.
	 */
	public static function is_legacy_presets_imported(): string {
		$data = et_get_option( self::is_legacy_presets_imported_option_name(), '', '', true, false, '', '', true );

		return $data;
	}

	/**
	 * Prepare the data to be saved to DB.
	 *
	 * @since ??
	 *
	 * @param array $schema_items The schema items. The array structure is aligns with GlobalData.Presets.RestSchemaItems TS interface.
	 *
	 * @return array Prepared data to be saved to DB. The array structure is aligns with GlobalData.Presets.Items TS interface.
	 */
	public static function prepare_data( array $schema_items ): array {
		$prepared   = [];
		$attrs_keys = [
			'attrs',
			'renderAttrs',
			'styleAttrs',
		];

		foreach ( $schema_items as $schema_item_key => $schema_item ) {
			if ( ! isset( $prepared[ $schema_item_key ] ) ) {
				$prepared[ $schema_item_key ] = [];
			}

			foreach ( $schema_item as $record ) {
				$module_name = $record['moduleName'];
				$default     = $record['default'];

				if ( ! isset( $prepared[ $schema_item_key ][ $module_name ] ) ) {
					$prepared[ $schema_item_key ][ $module_name ] = [
						'default' => $default,
						'items'   => [],
					];
				}

				$items = $record['items'];

				foreach ( $items as $item ) {
					foreach ( $attrs_keys as $key ) {
						if ( isset( $item[ $key ] ) ) {
							$preset_attrs = $item[ $key ];

							if ( ! is_array( $preset_attrs ) ) {
								unset( $item[ $key ] );
								continue;
							}

							$preset_attrs = ModuleUtils::remove_empty_array_attributes( $preset_attrs );

							if ( empty( $preset_attrs ) ) {
								unset( $item[ $key ] );
								continue;
							}

							$item[ $key ] = SavingUtility::sanitize_block_attrs( $preset_attrs, $module_name );
						}
					}

					$prepared[ $schema_item_key ][ $module_name ]['items'][ $item['id'] ] = $item;
				}
			}
		}

		return $prepared;
	}

	/**
	 * Save the data to DB.
	 *
	 * @since ??
	 *
	 * @param array $data The data to be saved. The array structure is aligns with GlobalData.Presets.Items TS interface.
	 *
	 * @return array The saved data. The array structure is aligns with GlobalData.Presets.Items TS interface.
	 */
	public static function save_data( array $data ): array {
		et_update_option( self::option_name(), $data, false, '', '', true );

		// Reset the data cache.
		self::$_data = null;

		return self::get_data();
	}

	/**
	 * Save conversion data to DB for legacy presets import check.
	 *
	 * @since ??
	 *
	 * @param bool $data The data to be saved.
	 *
	 * @return void
	 */
	public static function save_is_legacy_presets_imported( bool $data ): void {
		et_update_option( self::is_legacy_presets_imported_option_name(), $data ? 'yes' : '', false, '', '', true );
	}

	/**
	 * Get the legacy D4 global presets data from the DB for presets format.
	 *
	 * @since ??
	 *
	 * @return array The data from the DB. The array structure is in D4 which needs to be used for converting to D5 format.
	 */
	public static function get_legacy_data(): array {
		static $presets_attributes = null;

		if ( null !== $presets_attributes ) {
			return $presets_attributes;
		}

		$all_builder_presets = et_get_option( 'builder_global_presets_ng', (object) array(), '', true, false, '', '', true );
		$presets_attributes  = array();

		// If there is no global presets then return empty array.
		if ( empty( $all_builder_presets ) ) {
			return $presets_attributes;
		}

		foreach ( $all_builder_presets as $module => $module_presets ) {
			$module_presets = is_array( $module_presets ) ? (object) $module_presets : $module_presets;

			if ( ! is_object( $module_presets ) ) {
				continue;
			}

			foreach ( $module_presets->presets as $key => $value ) {
				if ( empty( (array) $value->settings ) ) {
					continue;
				}

				// Convert preset settings object to array format.
				$value_settings  = json_decode( wp_json_encode( $value->settings ), true );
				$value->settings = (array) $value_settings;
				unset( $value->is_temp );

				$presets_attributes[ $module ]['presets'][ $key ] = (array) $value;
			}

			// Get the default preset id.
			$default_preset_id = $module_presets->default;

			// If presets are available then only set default preset id.
			if ( ! empty( $presets_attributes[ $module ]['presets'] ) ) {
				// Set the default preset id if default preset id is there otherwise set as blank.
				$presets_attributes[ $module ]['default'] = $default_preset_id;
			}
		}

		return $presets_attributes;
	}

	/**
	 * Retrieve the selected preset from a module.
	 *
	 * @since ??
	 *
	 * @param array $attrs The module attributes.
	 *
	 * @return string The selected preset ID if available, otherwise 'default'.
	 */
	public static function get_selected_preset( array $attrs ): string {
		return ! empty( $attrs['modulePreset'] ) ? $attrs['modulePreset'] : 'default';
	}

	/**
	 * Retrieve the preset item.
	 *
	 * This method is used to find the preset item for a module. It will convert the module name to the preset module name if needed.
	 *
	 * @since ??
	 *
	 * @param string $module_name  The module name.
	 * @param array  $module_attrs The module attributes.
	 *
	 * @return GlobalPresetItem The preset item instance.
	 */
	public static function get_item( string $module_name, array $module_attrs ): GlobalPresetItem {
		$all_presets = self::get_data();
		$module_name = ModuleUtils::maybe_convert_preset_module_name( $module_name, $module_attrs );
		$preset_id   = self::get_selected_preset( $module_attrs );

		if ( 'default' === $preset_id ) {
			$preset_id = $all_presets['module'][ $module_name ]['default'] ?? '';
		}

		$preset_item_array = [];

		if ( isset( $all_presets['module'][ $module_name ]['items'][ $preset_id ] ) ) {
			$preset_item_array = $all_presets['module'][ $module_name ]['items'][ $preset_id ];
		}

		return new GlobalPresetItem( $preset_item_array );
	}

	/**
	 * Retrieve the preset item by ID.
	 *
	 * @since ??
	 *
	 * @param string $module_name The module name. The module name should be already converted to the preset module name.
	 * @param string $preset_id The module attributes. The preset ID should be the actual preset ID.
	 *
	 * @return GlobalPresetItem The preset item instance.
	 */
	public static function get_item_by_id( string $module_name, string $preset_id ): GlobalPresetItem {
		$all_presets = self::get_data();

		$preset_item_array = [];

		if ( isset( $all_presets['module'][ $module_name ]['items'][ $preset_id ] ) ) {
			$preset_item_array = $all_presets['module'][ $module_name ]['items'][ $preset_id ];
		}

		return new GlobalPresetItem( $preset_item_array );
	}

	/**
	 * Retrieve the default preset ID for a module.
	 *
	 * @since ??
	 *
	 * @param string $module_name The module name.
	 *
	 * @return string The default preset ID.
	 */
	public static function get_default_preset_id( string $module_name ) {
		$all_presets = self::get_data();
		$module_name = ModuleUtils::maybe_convert_preset_module_name( $module_name, [] );

		return $all_presets['module'][ $module_name ]['default'] ?? '';
	}

	/**
	 * Process the presets.
	 * This function adapts the logic from its js counterpart `processPresets` from `visual-builder/packages/module-utils/src/process-presets/index.ts`
	 * to use during Readiness migration. This function takes an array of converted D5 presets and processes them by merging them with the existing presets.
	 * It returns the processed presets.
	 *
	 * @param array $presets The array of presets to be processed.
	 * @return array The processed presets.
	 */
	public static function process_presets( $presets ) {
		$processed_presets = [ 'module' => [] ];
		$all_presets       = self::get_data();

		foreach ( $presets['module'] as $module_name => $preset_items ) {
			if ( empty( $module_name ) ) {
				continue;
			}

			$processed_items = [];

			foreach ( $preset_items['items'] as $item_id => $item ) {
				if ( empty( $item ) ) {
					continue;
				}

				$existing_preset_item = $all_presets[ $module_name ]['items'][ $item_id ] ?? null;
				$is_default_preset    = $preset_items['default'] === $item_id;

				$current_timestamp = time();
				$new_id            = ! empty( $existing_preset_item ) ? uniqid() : $item_id;

				$processed_items[ $new_id ] = array_merge(
					$item,
					[
						'id'      => $new_id,
						'name'    => ! empty( $existing_preset_item ) || $is_default_preset ? sprintf( '%s imported', $item['name'] ) : $item['name'],
						'created' => $current_timestamp,
						'updated' => $current_timestamp,
					]
				);
			}

			// Merge processed items with all_presets.
			if ( ! empty( $processed_items ) ) {
				$all_presets[ $module_name ]['items'] = array_merge(
					$all_presets[ $module_name ]['items'] ?? [],
					$processed_items
				);

				$processed_presets['module'][ $module_name ] = [
					'items'   => $all_presets[ $module_name ]['items'],
					'default' => $preset_items['default'],
				];
			}
		}

		return $processed_presets;
	}
}
