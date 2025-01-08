<?php
/**
 * Utils::join_declarations()
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\StyleLibrary\Utils\UtilsTraits;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait JoinDeclarationsTrait {

	/**
	 * Join array of declarations into `;` separated string, suffixed by `;`.
	 *
	 * This function is equivalent of JS function:
	 * {@link /docs/builder-api/js/style-library/join-declarations joinDeclarations} in:
	 * `@divi/style-library` package.
	 *
	 * @since ??
	 *
	 * @param array $declarations Array of declarations.
	 *
	 * @return string
	 */
	public static function join_declarations( array $declarations ): string {
		$joined = implode( '; ', $declarations );

		if ( 0 < count( $declarations ) ) {
			$joined = $joined . ';';
		}

		return $joined;
	}

}
