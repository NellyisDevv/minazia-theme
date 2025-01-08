<?php
/**
 * ArrayUtility class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Framework\Utility;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

/**
 * ArrayUtility class.
 *
 * This class has helper methods to make working with arrays easier.
 *
 * @since ??
 */
class ArrayUtility {

	use ArrayUtilityTraits\GetValueTrait;
	use ArrayUtilityTraits\FindTrait;
	use ArrayUtilityTraits\DiffTrait;
	use ArrayUtilityTraits\IsListTrait;
	use ArrayUtilityTraits\IsAssocTrait;
	use ArrayUtilityTraits\FilterDeepTrait;
	use ArrayUtilityTraits\MapDeepTrait;

	/**
	 * Checks if a given variable is an array of strings.
	 *
	 * This function iterates over each element of the provided variable and verifies
	 * if it's a string. If any element is not a string the function returns `false`.
	 *
	 * @param mixed $var The variable to check.
	 *
	 * @return bool `true` if the variable is an array of strings, `false` otherwise.
	 */
	public static function is_array_of_strings( $var ) {
		if ( ! is_array( $var ) ) {
			return false;
		}

		foreach ( $var as $value ) {
			if ( ! is_string( $value ) ) {
				return false;
			}
		}

		return true;
	}
}
