<?php
/**
 * Module Library: Button Module Utilities
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\ModuleLibrary\Button;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\ModuleLibrary\Button\ButtonModuleUtilsTraits\ExtractLinkTitleTrait;

/**
 * ButtonModuleUtils class.
 *
 * @since ??
 */
class ButtonModuleUtils {

	/**
	 * Extract the title for a link.
	 *
	 * @since ??
	 *
	 * @param string $html_text The HTML content of the link.
	 *
	 * @return string The extracted title.
	 */
	public static function extract_link_title( string $html_text ): string {
		return wp_kses(
			$html_text,
			[
				'strong' => [
					'id'    => [],
					'class' => [],
					'style' => [],
				],
				'em'     => [
					'id'    => [],
					'class' => [],
					'style' => [],
				],
				'i'      => [
					'id'    => [],
					'class' => [],
					'style' => [],
				],
			]
		);
	}

}
