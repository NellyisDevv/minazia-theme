<?php
/**
 * ArrayUtility::get_value()
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Framework\Utility\ArrayUtilityTraits;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait GetValueTrait {

	/**
	 * Gets the array value at provided path.
	 *
	 * NOTE: This utility may lead to performance issues.
	 * If the array path is known explicitly, it is advisable to avoid using this utility.
	 * Instead, you can use the null coalescing operator (??).
	 *
	 * @internal To see the benchmark results that support this advice, run the command `yarn test-phpbench` from the `visual-builder` directory.
	 *
	 * @since ??
	 *
	 * @param array  $array    The input array.
	 * @param string $path     A dot-delimited string used as the path to retrieve the property. For instance, `path.to.retrieve` would correspond to `$array['path']['to']['retrieve']`.
	 * @param mixed  $fallback Optional. The default value that will be returned when the specified path does not exist. Default `null`.
	 *
	 * @return mixed The value at path of array, or the fallback value if the path does not exist.
	 **/
	public static function get_value( array $array, string $path, $fallback = null ) {
		if ( false === strpos( $path, '.' ) ) {
			return $array[ $path ] ?? $fallback;
		}

		$parts = explode( '.', $path );

		switch ( count( $parts ) ) {
			case 2:
				return $array[ $parts[0] ][ $parts[1] ] ?? $fallback;
			case 3:
				return $array[ $parts[0] ][ $parts[1] ][ $parts[2] ] ?? $fallback;
			case 4:
				return $array[ $parts[0] ][ $parts[1] ][ $parts[2] ][ $parts[3] ] ?? $fallback;
			case 5:
				return $array[ $parts[0] ][ $parts[1] ][ $parts[2] ][ $parts[3] ][ $parts[4] ] ?? $fallback;
			case 6:
				return $array[ $parts[0] ][ $parts[1] ][ $parts[2] ][ $parts[3] ][ $parts[4] ][ $parts[5] ] ?? $fallback;
			default:
				$value = $array;

				foreach ( $parts as $part ) {
					$value = $value[ $part ] ?? null;

					if ( null === $value ) {
						return $fallback;
					}
				}

				return $value;
		}
	}

}
