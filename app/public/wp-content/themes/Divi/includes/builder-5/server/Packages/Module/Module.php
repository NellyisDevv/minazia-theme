<?php
/**
 * Module: Module class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\Module;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

// phpcs:disable ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- WP use snakeCase in \WP_Block_Parser_Block

use ET\Builder\Framework\Utility\Conditions;
use ET\Builder\FrontEnd\Assets\StaticCSS;
use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\GlobalData\GlobalPreset;
use ET\Builder\Packages\Module\Layout\Components\Classnames;
use ET\Builder\Packages\Module\Layout\Components\ModuleElements\ModuleElements;
use ET\Builder\Packages\Module\Layout\Components\Wrapper\ModuleWrapper;
use ET\Builder\Packages\Module\Options\IdClasses\IdClassesClassnames;
use ET\Builder\Packages\ModuleUtils\ModuleUtils;
use WP_Block_Type_Registry;

/**
 * Module class.
 *
 * @since ??
 */
class Module {

	/**
	 * Module renderer.
	 *
	 * This function is used to render a module in FE.
	 *
	 * This function is equivalent of JS function:
	 * {@link /docs/builder-api/js/module/module Module}
	 * in `@divi/module` package.
	 *
	 * @since ??
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *
	 *     @type array    $attrs                     Optional. Module attributes data. Default `[]`.
	 *     @type array    $htmlAttrs                 Optional. Custom HTML attributes. Default `null`.
	 *     @type string   $id                        Optional. Module ID. Default empty string.
	 *                                               In Visual Builder, the ID of module is a UUIDV4 string.
	 *                                               In FrontEnd, it is module name + order index.
	 *     @type string   $children                  Optional. The children element(s). Default empty string.
	 *     @type string   $childrenIds               Optional. Module inner blocks. Default `[]`.
	 *     @type bool     $hasModule                 Optional. Whether the module has module or not. Default `true`.
	 *     @type string   $moduleCategory            Optional. Module category. Default empty string.
	 *     @type string   $classname                 Optional. Custom CSS class attribute. Default empty string.
	 *     @type bool     $isFirst                   Optional. Is first child flag. Default `false`.
	 *     @type bool     $isLast                    Optional. Is last child flag. Default `false`.
	 *     @type bool     $hasModuleClassName        Optional. Has module class name. Default `true`.
	 *     @type callable $classnamesFunction        Optional. Function that will be invoked to generate module CSS class. Default `null`.
	 *     @type array    $styles                    Optional. Custom inline style attribute. Default `[]`.
	 *     @type string   $tag                       Optional. HTML tag. Default `div`.
	 *     @type bool     $hasModuleWrapper          Optional. Has module wrapper flag. Default `false`.
	 *     @type string   $wrapperTag                Optional. Wrapper HTML tag. Default `div`.
	 *     @type array    $wrapperHtmlAttrs          Optional. Wrapper custom html attributes. Default `[]`.
	 *     @type string   $wrapperClassname          Optional. Wrapper custom CSS class. Default empty string.
	 *     @type callable $wrapperClassnamesFunction Optional. Function that will be invoked to generate module wrapper CSS class. Default `null`.
	 *     @type callable $stylesComponent           Optional. Function that will be invoked to generate module styles. Default `null`.
	 *     @type array    $parentAttrs               Optional. Parent module attributes data. Default `[]`.
	 *     @type string   $parentId                  Optional. Parent Module ID. Default empty string.
	 *                                               In Visual Builder, the ID of module is a UUIDV4 string.
	 *                                               In FrontEnd, it is parent module name + parent order index.
	 *     @type string   $parentName                Optional. Parent module name. Default empty string.
	 *     @type array    $siblingAttrs              Optional. Module sibling attributes data. Default `[]`.
	 *     @type array    $settings                  Optional. Custom settings. Default `[]`.
	 *     @type int      $orderIndex                Optional. Module order index. Default `0`.
	 *     @type int      $storeInstance             Optional. The ID of instance where this block stored in BlockParserStore class. Default `null`.
	 * }
	 *
	 * @return string The module HTML.
	 *
	 * @example:
	 * ```php
	 *  ET_Builder_Module::render( array(
	 *    'arg1' => 'value1',
	 *    'arg2' => 'value2',
	 *  ) );
	 * ```
	 *
	 * @example:
	 * ```php
	 *  $module = new ET_Builder_Module();
	 *  $module->render( array(
	 *    'arg1' => 'value1',
	 *    'arg2' => 'value2',
	 *   ) );
	 * ```
	 */
	public static function render( array $args ): string {
		$name          = $args['name'];
		$module_config = WP_Block_Type_Registry::get_instance()->get_registered( $name );

		$args = array_replace_recursive(
			[
				'attrs'                     => [],
				'elements'                  => null,
				'htmlAttrs'                 => [],
				'htmlAttributesFunction'    => null,
				'id'                        => '',
				'children'                  => '',
				'childrenIds'               => [],
				'defaultPrintedStyleAttrs'  => [],
				'hasModule'                 => true,
				'moduleCategory'            => '',
				'className'                 => '',
				'isFirst'                   => false,
				'isLast'                    => false,
				'hasModuleClassName'        => true,
				'classnamesFunction'        => null,
				'styles'                    => [],
				'tag'                       => $module_config->wrapper['tag'] ?? 'div',
				'hasModuleWrapper'          => $module_config->wrapper['status'] ?? false,
				'wrapperTag'                => 'div',
				'wrapperHtmlAttrs'          => [],
				'wrapperClassname'          => '',
				'wrapperClassnamesFunction' => null,
				'stylesComponent'           => null,
				'scriptDataComponent'       => null,
				'parentAttrs'               => [],
				'parentId'                  => '',
				'parentName'                => '',
				'siblingAttrs'              => [],
				'settings'                  => [],

				// FE only.
				'orderIndex'                => 0,
				'storeInstance'             => null,
			],
			$args
		);

		$attrs                       = $args['attrs'];
		$elements                    = $args['elements'];
		$html_attrs                  = $args['htmlAttrs'];
		$html_attributes_function    = $args['htmlAttributesFunction'];
		$id                          = $args['id'];
		$children                    = $args['children'];
		$children_ids                = $args['childrenIds'];
		$default_printed_style_attrs = $args['defaultPrintedStyleAttrs'];
		$has_module                  = $args['hasModule'];
		$module_category             = $args['moduleCategory'];
		$class_name                  = $args['className'];
		$is_first                    = $args['isFirst'];
		$is_last                     = $args['isLast'];
		$has_module_class_name       = $args['hasModuleClassName'];
		$classnames_function         = $args['classnamesFunction'];
		$styles                      = $args['styles'];
		$tag                         = $args['tag'];
		$has_module_wrapper          = $args['hasModuleWrapper'];
		$wrapper_tag                 = $args['wrapperTag'];
		$wrapper_html_attrs          = $args['wrapperHtmlAttrs'];
		$wrapper_classname           = $args['wrapperClassname'];
		$wrapper_classnames_function = $args['wrapperClassnamesFunction'];
		$styles_component            = $args['stylesComponent'];
		$script_data_component       = $args['scriptDataComponent'];
		$parent_attrs                = $args['parentAttrs'];
		$parent_id                   = $args['parentId'];
		$parent_name                 = $args['parentName'];
		$sibling_attrs               = $args['siblingAttrs'];
		$settings                    = $args['settings'];
		$order_index                 = $args['orderIndex'];
		$store_instance              = $args['storeInstance'];

		$settings = array_merge(
			[
				'disabledModuleVisibility' => 'hidden', // TODO feat(D5, Frontend Rendering): Set this value dynamically taken from from the builder settings.
			],
			$settings
		);

		// Base classnames params.
		// Both module and wrapper classnames filters need this. Module and wrapper classnames
		// action hooks need this + `classnamesInstance` property.
		$base_classnames_params = [
			'attrs'         => $attrs,
			'childrenIds'   => $children_ids,
			'hasModule'     => $has_module,
			'id'            => $id,
			'isFirst'       => $is_first,
			'isLast'        => $is_last,
			'name'          => $name,

			// FE only.
			'storeInstance' => $store_instance,
			'orderIndex'    => $order_index,
			'layoutType'    => BlockParserStore::get_layout_type(),
		];

		// Module wrapper classnames.
		$wrapper_classnames_instance = new Classnames();
		$wrapper_classnames_params   = array_merge(
			$base_classnames_params,
			[ 'classnamesInstance' => $wrapper_classnames_instance ]
		);

		$wrapper_classnames_instance->add( $wrapper_classname, ! empty( $wrapper_classname ) );

		if ( is_callable( $wrapper_classnames_function ) ) {
			call_user_func( $wrapper_classnames_function, $wrapper_classnames_params );
		}

		// Module classnames.
		$classnames_instance = new Classnames();
		$classnames_params   = array_merge(
			$base_classnames_params,
			[ 'classnamesInstance' => $classnames_instance ]
		);

		$module_class_by_name = ModuleUtils::get_module_class_by_name( $name );

		$module_class_name = ModuleUtils::get_module_class_name( $name );

		if ( ! $module_class_name ) {
			$module_class_name = $module_class_by_name;
		}

		$selector_classname = ModuleUtils::get_module_order_class_name( $id, $store_instance );

		if ( ! $selector_classname ) {
			$selector_classname = $module_class_by_name . '_' . $order_index;
		}

		$classnames_instance->add( $selector_classname );
		$classnames_instance->add( $module_class_by_name, empty( $module_class_name ) );
		$classnames_instance->add( $module_class_name, ! empty( $module_class_name ) );

		if ( is_callable( $classnames_function ) ) {
			call_user_func( $classnames_function, $classnames_params );
		}

		$classnames_instance->add( $class_name, ! empty( $class_name ) );

		$excluded_categories = [
			'structure',
			'child-module',
		];

		$classnames_instance->add(
			'et_pb_module',
			! in_array( $module_category, $excluded_categories, true ) && $has_module_class_name
		);

		// Module styles output.
		$parent_order_class = $parent_id ? '.' . ModuleUtils::get_module_order_class_name( $parent_id, $store_instance ) : '';

		if ( $parent_id && ! $parent_order_class ) {
			$parent_order_class = '.' . ModuleUtils::get_module_class_by_name( $parent_id );
		}

		// Whether $elements is an instance of ModuleElements.
		$is_module_elements_instance = $elements instanceof ModuleElements;

		if ( $is_module_elements_instance ) {
			$elements->set_order_id( $order_index );
		}

		// Fetch module htmlAttributes.
		if ( is_callable( $html_attributes_function ) ) {
			$id_class_values = call_user_func(
				$html_attributes_function,
				[
					'id'    => $id,
					'name'  => $name,
					'attrs' => $attrs,
				]
			);
		} else {
			$id_class_values = IdClassesClassnames::get_html_attributes(
				$attrs['module']['advanced']['htmlAttributes'] ?? []
			);
		}

		$html_id         = $id_class_values['id'] ?? '';
		$html_classnames = $id_class_values['classNames'] ?? '';

		// Module CSS Id.
		if ( ! empty( $html_id ) ) {
			$html_attrs['id'] = $html_id;
		}

		// Module CSS Class.
		$classnames_instance->add(
			$html_classnames,
			! empty( $html_classnames )
		);

		// Populate module preset data.
		$item_preset_selector_classname = $is_module_elements_instance
			? $elements->get_preset_item_class_name()
			: ModuleUtils::get_preset_class_name( $name, $attrs ?? [] );

		// Condition where current page builder's style has been enqueued as static css.
		$is_style_enqueued_as_static_css = StaticCSS::$styles_manager->enqueued ?? false;

		if ( is_callable( $styles_component ) ) {
			// Conditions.
			$is_custom_post_type = Conditions::is_custom_post_type();

			// Selector prefix.
			$selector_prefix = $is_custom_post_type ? '.et-db #et-boc .et-l ' : '';

			Style::set_group_style( 'preset' );

			if ( $is_module_elements_instance ) {
				$elements->set_style_group( 'preset' );
			}

			// Process Preset Style output only when preset selector is available.
			if ( $item_preset_selector_classname ) {
				// Populate parent module preset data.
				$parent_preset                    = GlobalPreset::get_item( $parent_name, $parent_attrs ?? [] );
				$parent_preset_selector_classname = $parent_id ? ModuleUtils::get_preset_class_name( $parent_name, $parent_attrs ?? [] ) : '';
				$parent_preset_order_class        = $parent_preset_selector_classname ? '.' . $parent_preset_selector_classname : '';

				// Populate sibling module preset data.
				$preset_sibling_attrs = [
					'previous' => [],
					'next'     => [],
				];

				if (
					! empty( $sibling_attrs )
					&& (
						! empty( $sibling_attrs['previous'] )
						|| ! empty( $sibling_attrs['next'] )
					)
				) {
					$sibling_previous = BlockParserStore::get_sibling( $id, 'before', $store_instance );

					if ( $sibling_previous ) {
						$sibling_previous_preset                        = GlobalPreset::get_item( $sibling_previous->blockName, $sibling_previous->attrs ?? [] );
						$sibling_previous_preset_attrs                  = $sibling_previous_preset->get_data_attrs();
						$preset_sibling_attrs['previous']['background'] = $sibling_previous_preset_attrs['module']['decoration']['background'] ?? null;
					}

					$sibling_next = BlockParserStore::get_sibling( $id, 'after', $store_instance );

					if ( $sibling_next ) {
						$sibling_next_preset                        = GlobalPreset::get_item( $sibling_next->blockName, $sibling_next->attrs ?? [] );
						$sibling_next_preset_attrs                  = $sibling_next_preset->get_data_attrs();
						$preset_sibling_attrs['next']['background'] = $sibling_next_preset_attrs['module']['decoration']['background'] ?? null;
					}
				}

				// Preset's order class names.
				$preset_base_order_class = '.' . $item_preset_selector_classname;
				$preset_order_class      = $selector_prefix . $preset_base_order_class;

				// Set styles for presets.
				if ( $is_module_elements_instance ) {
					$elements->set_order_class( $preset_order_class );
					$elements->set_base_order_class( $preset_base_order_class );
				}

				// Preset wrapper order class names.
				$preset_base_wrapper_order_class = $has_module_wrapper ? $preset_base_order_class . '_wrapper' : '';
				$preset_wrapper_order_class      = $has_module_wrapper ? $selector_prefix . $preset_base_wrapper_order_class : '';

				if ( $is_module_elements_instance ) {
					$elements->set_wrapper_order_class( $preset_wrapper_order_class );
				}

				if ( ! $is_style_enqueued_as_static_css ) {
					call_user_func(
						$styles_component,
						[
							'id'                       => $id,
							'elements'                 => $elements,
							'name'                     => $name,
							'attrs'                    => $is_module_elements_instance ? $elements->preset_item->get_data_attrs() : [],
							'parentAttrs'              => $parent_preset->get_data_attrs(),
							'siblingAttrs'             => $preset_sibling_attrs,
							'defaultPrintedStyleAttrs' => [],
							'baseOrderClass'           => $preset_base_order_class,
							'orderClass'               => $preset_order_class,
							'parentOrderClass'         => $parent_preset_order_class,
							'baseWrapperOrderClass'    => $preset_base_wrapper_order_class,
							'wrapperOrderClass'        => $preset_wrapper_order_class,
							'settings'                 => $settings,

							// Style's state is only affecting module's style component when module's settings modal is opened (edited).
							'state'                    => 'value',
							'mode'                     => 'frontend',
							'styleGroup'               => 'preset',

							// FE only.
							'storeInstance'            => $store_instance,
							'orderIndex'               => $order_index,
						]
					);
				}
			}

			Style::set_group_style( 'module' );

			if ( $is_module_elements_instance ) {
				$elements->set_style_group( 'module' );
			}

			// Process Module Style output only when module selector is available.
			if ( $selector_classname ) {
				// Order class names.
				$base_order_class = '.' . $selector_classname;
				$order_class      = $selector_prefix . $base_order_class;

				// Wrapper order class names.
				$base_wrapper_order_class = $has_module_wrapper ? '.' . $selector_classname . '_wrapper' : '';
				$wrapper_order_class      = $has_module_wrapper ? $selector_prefix . $base_wrapper_order_class : '';

				if ( $is_module_elements_instance ) {
					$elements->set_base_order_class( $base_order_class );
					$elements->set_order_class( $order_class );
					$elements->set_base_wrapper_order_class( $base_wrapper_order_class );
					$elements->set_wrapper_order_class( $wrapper_order_class );
				}

				if ( ! $is_style_enqueued_as_static_css ) {
					// Set styles for module.
					call_user_func(
						$styles_component,
						[
							'id'                       => $id,
							'isCustomPostType'         => $is_custom_post_type,
							'elements'                 => $elements,
							'name'                     => $name,
							'attrs'                    => $attrs,
							'parentAttrs'              => $parent_attrs,
							'siblingAttrs'             => $sibling_attrs,
							'defaultPrintedStyleAttrs' => $default_printed_style_attrs,
							'baseOrderClass'           => $base_order_class,
							'orderClass'               => $order_class,
							'parentOrderClass'         => $parent_order_class,
							'baseWrapperOrderClass'    => $base_wrapper_order_class,
							'wrapperOrderClass'        => $wrapper_order_class,
							'selectorPrefix'           => $selector_prefix,
							'settings'                 => $settings,

							// Style's state is only affecting module's style component when module's settings modal is opened (edited).
							'state'                    => 'value',
							'mode'                     => 'frontend',

							// FE only.
							'storeInstance'            => $store_instance,
							'orderIndex'               => $order_index,
							'styleGroup'               => 'module',
						]
					);
				}
			}
		}

		// Registering module's script data.
		if ( is_callable( $script_data_component ) ) {
			call_user_func(
				$script_data_component,
				[
					'name'          => $name,
					'attrs'         => $attrs,
					'parentAttrs'   => $parent_attrs,
					'id'            => $id,
					'selector'      => '.' . $selector_classname,
					'elements'      => $elements,

					// FE only.
					'storeInstance' => $store_instance,
					'orderIndex'    => $order_index,
				]
			);
		}

		if ( $item_preset_selector_classname ) {
			// Add preset classname to module.
			$classnames_instance->add( $item_preset_selector_classname );

			// Add preset classname (wrapper version) to module wrapper.
			$wrapper_classnames_instance->add( "{$item_preset_selector_classname}_wrapper" );
		}

		$module_classnames_value = $classnames_instance->value();

		/**
		 * Filter the module classnames.
		 *
		 * @since ??
		 *
		 * @param string $module_classnames_value The module classnames value.
		 * @param array  $base_classnames_params  The base classnames params.
		 */
		$module_classname = apply_filters(
			'divi_module_classnames_value',
			$module_classnames_value,
			$base_classnames_params
		);

		$wrapper_classnames_value = $wrapper_classnames_instance->value();

		/**
		 * Filter the module wrapper classnames.
		 *
		 * @since ??
		 *
		 * @param string $wrapper_classnames_value The wrapper classnames value.
		 * @param array  $base_classnames_params   The base classnames params.
		 */
		$module_wrapper_classname = apply_filters(
			'divi_module_wrapper_classnames_value',
			$wrapper_classnames_value,
			$base_classnames_params
		);

		// Enqueue inline font assets.
		if ( ! empty( $attrs['content']['decoration']['inlineFont'] ) ) {
			ModuleUtils::load_module_inline_font( $attrs );
		}

		return ModuleWrapper::render(
			[
				'children'         => $children,
				'classname'        => $module_classname,
				'name'             => $name,
				'styles'           => $styles,
				'htmlAttrs'        => $html_attrs,
				'parentAttrs'      => $parent_attrs,
				'siblingAttrs'     => $sibling_attrs,
				'tag'              => $tag,
				'hasModuleWrapper' => $has_module_wrapper,
				'wrapperTag'       => $wrapper_tag,
				'wrapperHtmlAttrs' => $wrapper_html_attrs,
				'wrapperClassname' => $module_wrapper_classname,
			]
		);
	}
}
