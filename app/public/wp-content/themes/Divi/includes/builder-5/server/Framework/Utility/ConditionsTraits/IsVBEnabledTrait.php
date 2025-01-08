<?php
/**
 * Conditions::is_vb_enabled()
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Framework\Utility\ConditionsTraits;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

trait IsVBEnabledTrait {

	/**
	 * Determine if Visual Builder (VB) is enabled on a post/page.
	 *
	 * This function is proxy function of existing D4 function `et_core_is_fb_enabled`.
	 *
	 * @since ??
	 *
	 * @return bool
	 */
	public static function is_vb_enabled() {
		return et_core_is_fb_enabled();
	}

}
