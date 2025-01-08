<?php
/**
 * Module Library: Tab Module Preset Attributes Map
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\ModuleLibrary\Tab;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}


/**
 * Class TabPresetAttrsMap
 *
 * @since ??
 *
 * @package ET\Builder\Packages\ModuleLibrary\Tab
 */
class TabPresetAttrsMap {
	/**
	 * Get the preset attributes map for the Tab module.
	 *
	 * @since ??
	 *
	 * @param array  $map         The preset attributes map.
	 * @param string $module_name The module name.
	 *
	 * @return array
	 */
	public static function get_map( array $map, string $module_name ) {
		if ( 'divi/tab' !== $module_name ) {
			return $map;
		}

		return [
			'title.innerContent'                           => [
				'attrName' => 'title.innerContent',
				'preset'   => 'content',
			],
			'content.innerContent'                         => [
				'attrName' => 'content.innerContent',
				'preset'   => 'content',
			],
			'module.advanced.link__url'                    => [
				'attrName' => 'module.advanced.link',
				'preset'   => 'content',
				'subName'  => 'url',
			],
			'module.advanced.link__target'                 => [
				'attrName' => 'module.advanced.link',
				'preset'   => 'content',
				'subName'  => 'target',
			],
			'module.decoration.background__color'          => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'module.decoration.background__gradient.stops' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'gradient.stops',
			],
			'module.decoration.background__gradient.enabled' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'gradient.enabled',
			],
			'module.decoration.background__gradient.type'  => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'gradient.type',
			],
			'module.decoration.background__gradient.direction' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'gradient.direction',
			],
			'module.decoration.background__gradient.directionRadial' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'gradient.directionRadial',
			],
			'module.decoration.background__gradient.repeat' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'gradient.repeat',
			],
			'module.decoration.background__gradient.length' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'gradient.length',
			],
			'module.decoration.background__gradient.overlaysImage' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'gradient.overlaysImage',
			],
			'module.decoration.background__image.url'      => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'image.url',
			],
			'module.decoration.background__image.parallax.enabled' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html', 'script' ],
				'subName'  => 'image.parallax.enabled',
			],
			'module.decoration.background__image.parallax.method' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'image.parallax.method',
			],
			'module.decoration.background__image.size'     => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'image.size',
			],
			'module.decoration.background__image.width'    => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'image.width',
			],
			'module.decoration.background__image.height'   => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'image.height',
			],
			'module.decoration.background__image.position' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'image.position',
			],
			'module.decoration.background__image.horizontalOffset' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'image.horizontalOffset',
			],
			'module.decoration.background__image.verticalOffset' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'image.verticalOffset',
			],
			'module.decoration.background__image.repeat'   => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'image.repeat',
			],
			'module.decoration.background__image.blend'    => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'image.blend',
			],
			'module.decoration.background__video.mp4'      => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'html' ],
				'subName'  => 'video.mp4',
			],
			'module.decoration.background__video.webm'     => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'html' ],
				'subName'  => 'video.webm',
			],
			'module.decoration.background__video.width'    => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'html' ],
				'subName'  => 'video.width',
			],
			'module.decoration.background__video.height'   => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'html' ],
				'subName'  => 'video.height',
			],
			'module.decoration.background__video.allowPlayerPause' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'html' ],
				'subName'  => 'video.allowPlayerPause',
			],
			'module.decoration.background__video.pauseOutsideViewport' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'html' ],
				'subName'  => 'video.pauseOutsideViewport',
			],
			'module.decoration.background__pattern.style'  => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'pattern.style',
			],
			'module.decoration.background__pattern.enabled' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'pattern.enabled',
			],
			'module.decoration.background__pattern.color'  => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'pattern.color',
			],
			'module.decoration.background__pattern.transform' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.transform',
			],
			'module.decoration.background__pattern.size'   => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.size',
			],
			'module.decoration.background__pattern.width'  => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.width',
			],
			'module.decoration.background__pattern.height' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.height',
			],
			'module.decoration.background__pattern.repeatOrigin' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.repeatOrigin',
			],
			'module.decoration.background__pattern.horizontalOffset' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.horizontalOffset',
			],
			'module.decoration.background__pattern.verticalOffset' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.verticalOffset',
			],
			'module.decoration.background__pattern.repeat' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.repeat',
			],
			'module.decoration.background__pattern.blend'  => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'pattern.blend',
			],
			'module.decoration.background__mask.style'     => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'mask.style',
			],
			'module.decoration.background__mask.enabled'   => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style', 'html' ],
				'subName'  => 'mask.enabled',
			],
			'module.decoration.background__mask.color'     => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.color',
			],
			'module.decoration.background__mask.transform' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.transform',
			],
			'module.decoration.background__mask.aspectRatio' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.aspectRatio',
			],
			'module.decoration.background__mask.size'      => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.size',
			],
			'module.decoration.background__mask.width'     => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.width',
			],
			'module.decoration.background__mask.height'    => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.height',
			],
			'module.decoration.background__mask.position'  => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.position',
			],
			'module.decoration.background__mask.horizontalOffset' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.horizontalOffset',
			],
			'module.decoration.background__mask.verticalOffset' => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.verticalOffset',
			],
			'module.decoration.background__mask.blend'     => [
				'attrName' => 'module.decoration.background',
				'preset'   => [ 'style' ],
				'subName'  => 'mask.blend',
			],
			'content.decoration.bodyFont.body.font__family' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'family',
			],
			'content.decoration.bodyFont.body.font__weight' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'weight',
			],
			'content.decoration.bodyFont.body.font__style' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.body.font__lineColor' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineColor',
			],
			'content.decoration.bodyFont.body.font__lineStyle' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineStyle',
			],
			'content.decoration.bodyFont.body.font__textAlign' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'textAlign',
			],
			'content.decoration.bodyFont.body.font__color' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.body.font__size'  => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'size',
			],
			'content.decoration.bodyFont.body.font__letterSpacing' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'letterSpacing',
			],
			'content.decoration.bodyFont.body.font__lineHeight' => [
				'attrName' => 'content.decoration.bodyFont.body.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineHeight',
			],
			'content.decoration.bodyFont.body.textShadow__style' => [
				'attrName' => 'content.decoration.bodyFont.body.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.body.textShadow__horizontal' => [
				'attrName' => 'content.decoration.bodyFont.body.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'horizontal',
			],
			'content.decoration.bodyFont.body.textShadow__vertical' => [
				'attrName' => 'content.decoration.bodyFont.body.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'vertical',
			],
			'content.decoration.bodyFont.body.textShadow__blur' => [
				'attrName' => 'content.decoration.bodyFont.body.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'blur',
			],
			'content.decoration.bodyFont.body.textShadow__color' => [
				'attrName' => 'content.decoration.bodyFont.body.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.link.font__family' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'family',
			],
			'content.decoration.bodyFont.link.font__weight' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'weight',
			],
			'content.decoration.bodyFont.link.font__style' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.link.font__lineColor' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineColor',
			],
			'content.decoration.bodyFont.link.font__lineStyle' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineStyle',
			],
			'content.decoration.bodyFont.link.font__textAlign' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'textAlign',
			],
			'content.decoration.bodyFont.link.font__color' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.link.font__size'  => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'size',
			],
			'content.decoration.bodyFont.link.font__letterSpacing' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'letterSpacing',
			],
			'content.decoration.bodyFont.link.font__lineHeight' => [
				'attrName' => 'content.decoration.bodyFont.link.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineHeight',
			],
			'content.decoration.bodyFont.link.textShadow__style' => [
				'attrName' => 'content.decoration.bodyFont.link.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.link.textShadow__horizontal' => [
				'attrName' => 'content.decoration.bodyFont.link.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'horizontal',
			],
			'content.decoration.bodyFont.link.textShadow__vertical' => [
				'attrName' => 'content.decoration.bodyFont.link.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'vertical',
			],
			'content.decoration.bodyFont.link.textShadow__blur' => [
				'attrName' => 'content.decoration.bodyFont.link.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'blur',
			],
			'content.decoration.bodyFont.link.textShadow__color' => [
				'attrName' => 'content.decoration.bodyFont.link.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.ul.font__family'  => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'family',
			],
			'content.decoration.bodyFont.ul.font__weight'  => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'weight',
			],
			'content.decoration.bodyFont.ul.font__style'   => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.ul.font__lineColor' => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineColor',
			],
			'content.decoration.bodyFont.ul.font__lineStyle' => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineStyle',
			],
			'content.decoration.bodyFont.ul.font__textAlign' => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'textAlign',
			],
			'content.decoration.bodyFont.ul.font__color'   => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.ul.font__size'    => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'size',
			],
			'content.decoration.bodyFont.ul.font__letterSpacing' => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'letterSpacing',
			],
			'content.decoration.bodyFont.ul.font__lineHeight' => [
				'attrName' => 'content.decoration.bodyFont.ul.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineHeight',
			],
			'content.decoration.bodyFont.ul.textShadow__style' => [
				'attrName' => 'content.decoration.bodyFont.ul.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.ul.textShadow__horizontal' => [
				'attrName' => 'content.decoration.bodyFont.ul.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'horizontal',
			],
			'content.decoration.bodyFont.ul.textShadow__vertical' => [
				'attrName' => 'content.decoration.bodyFont.ul.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'vertical',
			],
			'content.decoration.bodyFont.ul.textShadow__blur' => [
				'attrName' => 'content.decoration.bodyFont.ul.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'blur',
			],
			'content.decoration.bodyFont.ul.textShadow__color' => [
				'attrName' => 'content.decoration.bodyFont.ul.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.ul.list__type'    => [
				'attrName' => 'content.decoration.bodyFont.ul.list',
				'preset'   => [ 'style' ],
				'subName'  => 'type',
			],
			'content.decoration.bodyFont.ul.list__position' => [
				'attrName' => 'content.decoration.bodyFont.ul.list',
				'preset'   => [ 'style' ],
				'subName'  => 'position',
			],
			'content.decoration.bodyFont.ul.list__itemIndent' => [
				'attrName' => 'content.decoration.bodyFont.ul.list',
				'preset'   => [ 'style' ],
				'subName'  => 'itemIndent',
			],
			'content.decoration.bodyFont.ol.font__family'  => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'family',
			],
			'content.decoration.bodyFont.ol.font__weight'  => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'weight',
			],
			'content.decoration.bodyFont.ol.font__style'   => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.ol.font__lineColor' => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineColor',
			],
			'content.decoration.bodyFont.ol.font__lineStyle' => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineStyle',
			],
			'content.decoration.bodyFont.ol.font__textAlign' => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'textAlign',
			],
			'content.decoration.bodyFont.ol.font__color'   => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.ol.font__size'    => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'size',
			],
			'content.decoration.bodyFont.ol.font__letterSpacing' => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'letterSpacing',
			],
			'content.decoration.bodyFont.ol.font__lineHeight' => [
				'attrName' => 'content.decoration.bodyFont.ol.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineHeight',
			],
			'content.decoration.bodyFont.ol.textShadow__style' => [
				'attrName' => 'content.decoration.bodyFont.ol.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.ol.textShadow__horizontal' => [
				'attrName' => 'content.decoration.bodyFont.ol.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'horizontal',
			],
			'content.decoration.bodyFont.ol.textShadow__vertical' => [
				'attrName' => 'content.decoration.bodyFont.ol.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'vertical',
			],
			'content.decoration.bodyFont.ol.textShadow__blur' => [
				'attrName' => 'content.decoration.bodyFont.ol.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'blur',
			],
			'content.decoration.bodyFont.ol.textShadow__color' => [
				'attrName' => 'content.decoration.bodyFont.ol.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.ol.list__type'    => [
				'attrName' => 'content.decoration.bodyFont.ol.list',
				'preset'   => [ 'style' ],
				'subName'  => 'type',
			],
			'content.decoration.bodyFont.ol.list__position' => [
				'attrName' => 'content.decoration.bodyFont.ol.list',
				'preset'   => [ 'style' ],
				'subName'  => 'position',
			],
			'content.decoration.bodyFont.ol.list__itemIndent' => [
				'attrName' => 'content.decoration.bodyFont.ol.list',
				'preset'   => [ 'style' ],
				'subName'  => 'itemIndent',
			],
			'content.decoration.bodyFont.quote.font__family' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'family',
			],
			'content.decoration.bodyFont.quote.font__weight' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'weight',
			],
			'content.decoration.bodyFont.quote.font__style' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.quote.font__lineColor' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineColor',
			],
			'content.decoration.bodyFont.quote.font__lineStyle' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineStyle',
			],
			'content.decoration.bodyFont.quote.font__textAlign' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'textAlign',
			],
			'content.decoration.bodyFont.quote.font__color' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.quote.font__size' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'size',
			],
			'content.decoration.bodyFont.quote.font__letterSpacing' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'letterSpacing',
			],
			'content.decoration.bodyFont.quote.font__lineHeight' => [
				'attrName' => 'content.decoration.bodyFont.quote.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineHeight',
			],
			'content.decoration.bodyFont.quote.textShadow__style' => [
				'attrName' => 'content.decoration.bodyFont.quote.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'content.decoration.bodyFont.quote.textShadow__horizontal' => [
				'attrName' => 'content.decoration.bodyFont.quote.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'horizontal',
			],
			'content.decoration.bodyFont.quote.textShadow__vertical' => [
				'attrName' => 'content.decoration.bodyFont.quote.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'vertical',
			],
			'content.decoration.bodyFont.quote.textShadow__blur' => [
				'attrName' => 'content.decoration.bodyFont.quote.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'blur',
			],
			'content.decoration.bodyFont.quote.textShadow__color' => [
				'attrName' => 'content.decoration.bodyFont.quote.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'content.decoration.bodyFont.quote.border__styles.left.width' => [
				'attrName' => 'content.decoration.bodyFont.quote.border',
				'preset'   => [ 'style' ],
				'subName'  => 'styles.left.width',
			],
			'content.decoration.bodyFont.quote.border__styles.left.color' => [
				'attrName' => 'content.decoration.bodyFont.quote.border',
				'preset'   => [ 'style' ],
				'subName'  => 'styles.left.color',
			],
			'title.decoration.font.font__family'           => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'family',
			],
			'title.decoration.font.font__weight'           => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'weight',
			],
			'title.decoration.font.font__style'            => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'title.decoration.font.font__lineColor'        => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineColor',
			],
			'title.decoration.font.font__lineStyle'        => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineStyle',
			],
			'title.decoration.font.font__color'            => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'title.decoration.font.font__size'             => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'size',
			],
			'title.decoration.font.font__letterSpacing'    => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'letterSpacing',
			],
			'title.decoration.font.font__lineHeight'       => [
				'attrName' => 'title.decoration.font.font',
				'preset'   => [ 'style' ],
				'subName'  => 'lineHeight',
			],
			'title.decoration.font.textShadow__style'      => [
				'attrName' => 'title.decoration.font.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'style',
			],
			'title.decoration.font.textShadow__horizontal' => [
				'attrName' => 'title.decoration.font.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'horizontal',
			],
			'title.decoration.font.textShadow__vertical'   => [
				'attrName' => 'title.decoration.font.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'vertical',
			],
			'title.decoration.font.textShadow__blur'       => [
				'attrName' => 'title.decoration.font.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'blur',
			],
			'title.decoration.font.textShadow__color'      => [
				'attrName' => 'title.decoration.font.textShadow',
				'preset'   => [ 'style' ],
				'subName'  => 'color',
			],
			'module.decoration.spacing__padding'           => [
				'attrName' => 'module.decoration.spacing',
				'preset'   => [ 'style' ],
				'subName'  => 'padding',
			],
			'module.decoration.filters__hueRotate'         => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'hueRotate',
			],
			'module.decoration.filters__saturate'          => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'saturate',
			],
			'module.decoration.filters__brightness'        => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'brightness',
			],
			'module.decoration.filters__contrast'          => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'contrast',
			],
			'module.decoration.filters__invert'            => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'invert',
			],
			'module.decoration.filters__sepia'             => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'sepia',
			],
			'module.decoration.filters__opacity'           => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'opacity',
			],
			'module.decoration.filters__blur'              => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'blur',
			],
			'module.decoration.filters__blendMode'         => [
				'attrName' => 'module.decoration.filters',
				'preset'   => [ 'style' ],
				'subName'  => 'blendMode',
			],
			'module.decoration.transform__scale'           => [
				'attrName' => 'module.decoration.transform',
				'preset'   => [ 'style' ],
				'subName'  => 'scale',
			],
			'module.decoration.transform__translate'       => [
				'attrName' => 'module.decoration.transform',
				'preset'   => [ 'style' ],
				'subName'  => 'translate',
			],
			'module.decoration.transform__rotate'          => [
				'attrName' => 'module.decoration.transform',
				'preset'   => [ 'style' ],
				'subName'  => 'rotate',
			],
			'module.decoration.transform__skew'            => [
				'attrName' => 'module.decoration.transform',
				'preset'   => [ 'style' ],
				'subName'  => 'skew',
			],
			'module.decoration.transform__origin'          => [
				'attrName' => 'module.decoration.transform',
				'preset'   => [ 'style' ],
				'subName'  => 'origin',
			],
			'css__before'                                  => [
				'attrName' => 'css',
				'preset'   => [ 'style' ],
				'subName'  => 'before',
			],
			'css__mainElement'                             => [
				'attrName' => 'css',
				'preset'   => [ 'style' ],
				'subName'  => 'mainElement',
			],
			'css__after'                                   => [
				'attrName' => 'css',
				'preset'   => [ 'style' ],
				'subName'  => 'after',
			],
			'css__freeForm'                                => [
				'attrName' => 'css',
				'preset'   => [ 'style' ],
				'subName'  => 'freeForm',
			],
			'module.decoration.conditions'                 => [
				'attrName' => 'module.decoration.conditions',
				'preset'   => [ 'style' ],
			],
			'module.decoration.overflow__x'                => [
				'attrName' => 'module.decoration.overflow',
				'preset'   => [ 'style' ],
				'subName'  => 'x',
			],
			'module.decoration.overflow__y'                => [
				'attrName' => 'module.decoration.overflow',
				'preset'   => [ 'style' ],
				'subName'  => 'y',
			],
			'module.decoration.transition__duration'       => [
				'attrName' => 'module.decoration.transition',
				'preset'   => [ 'style' ],
				'subName'  => 'duration',
			],
			'module.decoration.transition__delay'          => [
				'attrName' => 'module.decoration.transition',
				'preset'   => [ 'style' ],
				'subName'  => 'delay',
			],
			'module.decoration.transition__speedCurve'     => [
				'attrName' => 'module.decoration.transition',
				'preset'   => [ 'style' ],
				'subName'  => 'speedCurve',
			],
			'module.decoration.position__mode'             => [
				'attrName' => 'module.decoration.position',
				'preset'   => [ 'style' ],
				'subName'  => 'mode',
			],
			'module.decoration.position__origin.relative'  => [
				'attrName' => 'module.decoration.position',
				'preset'   => [ 'style' ],
				'subName'  => 'origin.relative',
			],
			'module.decoration.position__origin.absolute'  => [
				'attrName' => 'module.decoration.position',
				'preset'   => [ 'style' ],
				'subName'  => 'origin.absolute',
			],
			'module.decoration.position__origin.fixed'     => [
				'attrName' => 'module.decoration.position',
				'preset'   => [ 'style' ],
				'subName'  => 'origin.fixed',
			],
			'module.decoration.position__offset.vertical'  => [
				'attrName' => 'module.decoration.position',
				'preset'   => [ 'style' ],
				'subName'  => 'offset.vertical',
			],
			'module.decoration.position__offset.horizontal' => [
				'attrName' => 'module.decoration.position',
				'preset'   => [ 'style' ],
				'subName'  => 'offset.horizontal',
			],
			'module.decoration.zIndex'                     => [
				'attrName' => 'module.decoration.zIndex',
				'preset'   => [ 'style' ],
			],
		];
	}
}
