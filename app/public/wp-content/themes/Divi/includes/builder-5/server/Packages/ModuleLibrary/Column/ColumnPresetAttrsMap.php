<?php
/**
 * Module Library: Column Module Preset Attributes Map
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\ModuleLibrary\Column;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}


/**
 * Class ColumnPresetAttrsMap
 *
 * @since ??
 *
 * @package ET\Builder\Packages\ModuleLibrary\Column
 */
class ColumnPresetAttrsMap {
	/**
	 * Get the preset attributes map for the Column module.
	 *
	 * @since ??
	 *
	 * @param array  $map         The preset attributes map.
	 * @param string $module_name The module name.
	 *
	 * @return array
	 */
	public static function get_map( array $map, string $module_name ) {
		if ( 'divi/column' !== $module_name ) {
			return $map;
		}

		unset( $map['module.decoration.spacing__margin'] );

		return $map;
	}
}
