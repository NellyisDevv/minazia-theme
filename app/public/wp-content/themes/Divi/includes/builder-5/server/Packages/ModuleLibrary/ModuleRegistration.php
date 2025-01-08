<?php
/**
 * ModuleLibrary: Module Registration class
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\ModuleLibrary;

use ET\Builder\Packages\Conversion\Conversion;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\Module\Layout\Components\ModuleElements\ModuleElements;
use ET\Builder\Packages\Module\Options\Sticky\StickyUtils;
use ET\Builder\Packages\StyleLibrary\Declarations\Transition\TransitionUtils;
use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use WP_Block_Type;
use WP_Block_Type_Registry;
use WP_Block;
use ET\Builder\Packages\GlobalData\GlobalPreset;
use ET\Builder\Framework\Utility\Conditions;
use ET\Builder\Packages\ModuleUtils\ModuleUtils;

// phpcs:disable Squiz.Commenting.InlineComment -- Temporarily disabled to get the PR CI pass for now. TODO: Fix this later.
// phpcs:disable Squiz.PHP.CommentedOutCode.Found -- Temporarily disabled to get the PR CI pass for now. TODO: Fix this later.
// phpcs:disable ET.Sniffs.Todo.TodoFound -- Temporarily disabled to get the PR CI pass for now. TODO: Fix this later.
// phpcs:disable WordPress.NamingConventions.ValidHookName -- Temporarily disabled to get the PR CI pass for now. TODO: Fix this later.

/**
 * ModuleRegistration class.
 *
 * This is a helper class that provides an easier interface to register modules on the backend.
 *
 * @since ??
 */
class ModuleRegistration {

	/**
	 * An array of metadata folders.
	 *
	 * @var array
	 */
	private static $_metadata_folders = [];

	/**
	 * Get Core Module Name From Metadata Folder.
	 *
	 * e.g.
	 * Divi/includes/builder-5/visual-builder/packages/module-library/src/components/[module-name]/module.json
	 *
	 * @param string $metadata_folder The path to the metadata folder.
	 * @return string The core module name.
	 */
	public static function get_core_module_name_from_metadata_folder( string $metadata_folder ): string {
		$core_module_name = str_replace( ET_BUILDER_5_DIR . 'visual-builder/packages/module-library/src/components/', '', $metadata_folder );

		$core_module_name = rtrim( $core_module_name, '/' );

		return $core_module_name;
	}

	/**
	 * Registers a module with the given metadata folder and arguments.
	 *
	 * This method reads the metadata `module.json` file from the specified folder, decodes it,
	 * and merges the metadata with the default arguments. It then registers the block type
	 * using the merged arguments and returns the registered block type.
	 *
	 * @since          ??
	 *
	 * @param string $metadata_folder The path to the metadata folder.
	 * @param array $args             Optional. An array of arguments to merge with the default arguments.
	 *                                Default `[]`.
	 *                                Accepts any public property of `WP_Block_Type`. See
	 *                                `WP_Block_Type::__construct()` for more information on accepted arguments.
	 *
	 * @return WP_Block_Type|null The registered block type or `null` if the metadata file does not exist or cannot be
	 *                            decoded.
	 *
	 * @throws \Exception
	 * @example        :
	 *                 ```php
	 *                 ModuleRegistration::register_module(
	 *                 '/path/to/metadata/folder',
	 *                 [
	 *                 'title' => 'Custom Title',
	 *                 'attributes' => [
	 *                 'attr1' => 'value1',
	 *                 'attr2' => 'value2',
	 *                 ],
	 *                 ]
	 *                 );
	 *                 ```
	 * @example        :
	 *                 ```php
	 *                 ModuleRegistration::register_module( '/path/to/metadata/folder' );
	 *                 ```
	 *
	 */
	public static function register_module( string $metadata_folder, array $args = [] ): ?WP_Block_Type {
		/*
		* Get an array of metadata from a PHP file.
		* This improves performance for core blocks as it's only necessary to read a single PHP file
		* instead of reading a JSON file per-block, and then decoding from JSON to PHP.
		* Using a static variable ensures that the metadata is only read once per request.
		*/
		static $core_modules_metadata;
		if ( ! $core_modules_metadata ) {
			$core_modules_metadata = require ET_BUILDER_5_DIR . '/server/_all_modules_metadata.php';
		}

		$metadata_folder = trailingslashit( $metadata_folder );

		$metadata_file = $metadata_folder . 'module.json';

		$is_core_module = str_starts_with( $metadata_folder, ET_BUILDER_5_DIR . 'visual-builder/packages/module-library' );

		// If the module is not a core module, the metadata file must exist.
		$metadata_file_exists = $is_core_module || file_exists( $metadata_file );

		if ( ! $metadata_file_exists ) {
			return null;
		}

		// Try to get metadata from the static cache for core modules.
		$metadata = [];
		if ( $is_core_module ) {
			$core_module_name = self::get_core_module_name_from_metadata_folder( $metadata_folder );
			if ( ! empty( $core_modules_metadata[ $core_module_name ] ) ) {
				$metadata = $core_modules_metadata[ $core_module_name ];
			}
		}

		// If metadata is not found in the static cache, read it from the file.
		if ( $metadata_file_exists && empty( $metadata ) ) {
			// modeling after WP's wp_json_file_decode() function.
			// but wihh silent failing allowed, whereas
			// wp_json_file_decode() will trigger_error() if it fails.
			$filename = wp_normalize_path( realpath( $metadata_file ) );

			if ( ! $filename ) {
				return null;
			}

			$metadata = json_decode( file_get_contents( $filename ), true );
		}

		if ( JSON_ERROR_NONE !== json_last_error() || empty( $metadata ) ) {
			return null;
		}

		self::$_metadata_folders[ $metadata['name'] ] = $metadata_folder;

		$base_args_defaults = [
			'title'      => 'Module',
			'titles'     => 'Modules',
			'moduleIcon' => 'divi/module',
			'category'   => 'module',
			'attributes' => [],
		];

		$register_args = array_merge( $base_args_defaults, $metadata, $args );

		// Generate default, default printed style, and default settings attributes from module metadata.
		if ( isset( $register_args['render_callback'] ) ) {
			$render_callback = $register_args['render_callback'];

			// Modify module's render callback. Insert generated defaults attributes and ModuleElements instance.
			$register_args['render_callback'] = function( $block_attributes, $content, WP_Block $block ) use ( $render_callback, $metadata ) {

				// Get preset attributes for this module.
				$item_preset         = GlobalPreset::get_item( $block->name, $block_attributes ?? [] );
				$preset_attrs        = $item_preset->get_data_attrs();
				$preset_render_attrs = $item_preset->get_data_render_attrs();

				// Remove preset attributes that are presents in block attributes.
				if ( $preset_attrs && $block_attributes ) {
					$preset_attrs = ModuleUtils::remove_matching_attrs( $preset_attrs, $block_attributes );
				}

				// Get default attributes for this module.
				$default_attributes = ModuleRegistration::get_default_attrs( $block->name, 'default', $metadata );

				// Replace default attributes with corresponding preset attributes.
				if ( $default_attributes && $preset_attrs ) {
					$default_attributes = ModuleUtils::replace_matching_attrs( $default_attributes, $preset_attrs );
				}

				// Merge default attributes, preset attributes and user defined attributes. This ensures every module's attribute parameter
				// has considered default, preset and user defined attributes on rendering component.
				$module_attrs_with_default = array_replace_recursive( $default_attributes, $preset_render_attrs, $block_attributes );

				$filter_args = [
					'name'          => $block->name,
					'attrs'         => $module_attrs_with_default,
					'id'            => $block->parsed_block['id'],
					'storeInstance' => $block->parsed_block['storeInstance'],
				];

				$module_attrs = $module_attrs_with_default;

				if ( 'child-module' === $block->block_type->category ) {
					$only_block_attributes     = array_diff_multidimensional( $block_attributes, $default_attributes, true ); // WP merge default attributes with $block_attributes. But we need to only block attributes without default attributes.
					$parent                    = BlockParserStore::get_parent( $block->parsed_block['id'], $block->parsed_block['storeInstance'] );
					$parent_attrs              = $parent->attrs;
					$parent_default_attributes = ModuleRegistration::get_default_attrs( $parent->blockName ); // phpcs:ignore ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- WP use snakeCase in \WP_Block_Parser_Block

					// Get preset attributes for parent module.
					$parent_item_preset  = GlobalPreset::get_item( $parent->blockName, $parent_attrs ?? [] ); // phpcs:ignore ET.Sniffs.ValidVariableName.UsedPropertyNotSnakeCase -- WP use snakeCase in \WP_Block_Parser_Block
					$parent_preset_attrs = $parent_item_preset->get_data_attrs();

					$module_attrs = array_replace_recursive(
						$parent_default_attributes['children'] ?? [],
						$default_attributes ?? [],
						$parent_preset_attrs['children'] ?? [],
						$preset_render_attrs,
						$parent_attrs['children'] ?? [],
						$only_block_attributes
					);

					$filter_args['parentAttrs'] = $parent_attrs;
				}

				$module_attrs = apply_filters( 'divi_module_library_register_module_attrs', $module_attrs, $filter_args );

				$default_printed_style_attrs = ModuleRegistration::get_default_attrs( $block->name, 'defaultPrintedStyle', $metadata );

				// Check whether the current module is inside another sticky module or not. The FE
				// implementation is bit different than VB where we use store related function due
				// to we need access to the store instance to get all blocks. Meanwhile in FE, we
				// can directly check all blocks from the parsed block.
				$is_inside_sticky_module = StickyUtils::is_inside_sticky_module(
					$block->parsed_block['id'],
					BlockParserStore::get_all( $block->parsed_block['storeInstance'] )
				);

				// Create instance of ModuleElements and pass the instance as parameter for consistency and simplicity.
				$elements = new ModuleElements(
					[
						'id'                      => $block->parsed_block['id'],
						'is_custom_post_type'     => Conditions::is_custom_post_type(),
						'is_inside_sticky_module' => $is_inside_sticky_module,
						'name'                    => $block->name,
						'moduleAttrs'             => $module_attrs,
						'moduleMetadata'          => $block->block_type,
						'orderIndex'              => $block->parsed_block['orderIndex'],
						'storeInstance'           => $block->parsed_block['storeInstance'],
						'presetItem'              => $item_preset,
					]
				);

				return call_user_func(
					$render_callback,
					$module_attrs,
					$content,
					$block,
					$elements,
					$default_printed_style_attrs
				);
			};
		}

		// TODO, create the equivalent of this TS implementation in PHP:
		// const conversionOutline = getModuleConversionOutline(config, metadata?.d4Shortcode);
		// const getModuleConversionOutline = (
		// 	config: Omit<ModuleLibrary.Module.RegisterDefinition, 'metadata'>,
		// 	d4Shortcode = '',
		//   ): ModuleConversionOutline => {
		// 	if (config?.conversionOutline) {
		// 	  return config.conversionOutline;
		// 	}

		// 	if (! d4Shortcode) {
		// 	  return {};
		// 	}

		// 	return getPossibleModuleConversionOutline(d4Shortcode);
		// };

		$conversion_outline_file = $metadata_folder . 'conversion-outline.json';

		// Let's not do all conversion processing here, if not needed,
		// because this will be a performance hit, as this code runs on every page load,
		// and were not going to be converting modules on every page load.
		// is admin or is PHP Unit test
		if ( ( is_admin() || defined( 'WP_TESTS_DOMAIN' ) ) && file_exists( $conversion_outline_file ) ) {
			$conversion_outline = wp_json_file_decode( $conversion_outline_file, [ 'associative' => true ] );

			/**
			 * Filters the module conversion outline for a Divi module during conversion.
			 *
			 * This filter allows developers to modify the module conversion outline for a Divi module during  conversion.
			 * The module conversion outline is used to define how the different properties and values
			 * for the module will be ported from D4 to D5.
			 * By default, the module conversion outline is generated using the `getModuleConversionOutline` function,
			 * which takes module `config` and `d4Shortcode` as arguments.
			 *
			 * @since ??
			 *
			 * @param {array} conversion_outline The default module conversion outline.
			 */
			$module_conversion_outline = apply_filters( 'divi.moduleLibrary.conversion.moduleConversionOutline', $conversion_outline, $metadata['name'] );

			// error_log('module_conversion_outline: ' . print_r($module_conversion_outline, true));

			if ( $module_conversion_outline ) {
				$module_attrs_conversion_map = Conversion::getModuleConversionMap( $module_conversion_outline );

				// error_log('module_attrs_conversion_map: ' . print_r($module_attrs_conversion_map, true));

				if ( $module_attrs_conversion_map ) {
					add_filter(
						'divi.conversion.moduleLibrary.conversionMap',
						function ( $module_library_conversion_map ) use ( $metadata, $module_attrs_conversion_map ) {
							return array_merge(
								$module_library_conversion_map,
								[ $metadata['name'] => $module_attrs_conversion_map ]
							);
						}
					);
				}
			}
		}

		// we need to roll our own version of register_block_type_from_metadata()
		// because inside of that, there is file_exists check, and also fetching and json decoding the file,
		// which we just did above, so lets save the time from doing that all again
		// additionally, they have the concept of a PHP array, so they can even skip the json_decode step
		// so lets do that as well, and we can skip the file_exists check, because we know it exists
		// for OUR core modules.
		// The old way: register_block_type( $metadata['name'], $register_args ).
		$registered_block_type = self::register_block_type_from_metadata( $metadata['name'], $metadata_file, $register_args );

		if ( false === $registered_block_type ) {
			return null;
		}

		return $registered_block_type;
	}

	/**
	 * Registers a block type from the metadata stored in the `block.json` file.
	 *
	 * @param string $block_type    Block type name including namespace prefix.
	 * @param string $metadata_file Path to the block metadata file.
	 * @param array  $metadata      Block type metadata.
	 * @return WP_Block_Type|false The registered block type on success, or false on failure.
	 */
	public static function register_block_type_from_metadata( $block_type, $metadata_file, $metadata = array() ) {
		/*
		Divi Note:
		Skipping this section, which was for core WP blocks, because we don't need it
		Skipping from here:
		/*
		* Get an array of metadata from a PHP file.
		...
		(skipping whole $core_blocks_meta section)
		...
		// If metadata is not found in the static cache, read it from the file.
		if ( $metadata_file_exists && empty( $metadata ) ) {
			$metadata = wp_json_file_decode( $metadata_file, array( 'associative' => true ) );
		}
		... end of skipping
		*/

		// Divi Note: the below is NOT identical to the core function register_block_type_from_metadata().
		// We are skipping the file_exists check, because we know it exists.
		if ( ! is_array( $metadata ) || empty( $metadata['name'] ) ) {
			return false;
		}
		$metadata['file'] = wp_normalize_path( realpath( $metadata_file ) );
		// /Divi Note.

		// Divi Note: the below is identical to the core function register_block_type_from_metadata().
		/**
		 * Filters the metadata provided for registering a block type.
		 *
		 * @since 5.7.0
		 *
		 * @param array $metadata Metadata for registering a block type.
		 */
		$metadata = apply_filters( 'block_type_metadata', $metadata );
		// /Divi Note.

		// Divi Note: Skipping this section, which was for core WP blocks, because we don't need it
		// Add `style` and `editor_style` for core blocks if missing.
		// /Divi Note.

		// Divi Note: the below is identical to the core function register_block_type_from_metadata().
		$settings          = array();
		$property_mappings = array(
			'apiVersion'      => 'api_version',
			'name'            => 'name',
			'title'           => 'title',
			'category'        => 'category',
			'parent'          => 'parent',
			'ancestor'        => 'ancestor',
			'icon'            => 'icon',
			'description'     => 'description',
			'keywords'        => 'keywords',
			'attributes'      => 'attributes',
			'providesContext' => 'provides_context',
			'usesContext'     => 'uses_context',
			'selectors'       => 'selectors',
			'supports'        => 'supports',
			'styles'          => 'styles',
			'variations'      => 'variations',
			'example'         => 'example',
			'allowedBlocks'   => 'allowed_blocks',
		);
		$textdomain        = ! empty( $metadata['textdomain'] ) ? $metadata['textdomain'] : null;
		$i18n_schema       = get_block_metadata_i18n_schema();

		foreach ( $property_mappings as $key => $mapped_key ) {
			if ( isset( $metadata[ $key ] ) ) {
				$settings[ $mapped_key ] = $metadata[ $key ];
				// Divi Note: Skipping the file exists check, because we know it exists.
				if ( /* $metadata_file_exists && */ $textdomain && isset( $i18n_schema->$key ) ) {
					$settings[ $mapped_key ] = translate_settings_using_i18n_schema( $i18n_schema->$key, $settings[ $key ], $textdomain );
				}
			}
		}

		if ( ! empty( $metadata['render'] ) ) {
			$template_path = wp_normalize_path(
				realpath(
					dirname( $metadata['file'] ) . '/' .
					remove_block_asset_path_prefix( $metadata['render'] )
				)
			);
			if ( $template_path ) {
				/**
				 * Renders the block on the server.
				 *
				 * @since 6.1.0
				 *
				 * @param array    $attributes Block attributes.
				 * @param string   $content    Block default content.
				 * @param WP_Block $block      Block instance.
				 *
				 * @return string Returns the block content.
				 */
				$settings['render_callback'] = static function ( $attributes, $content, $block ) use ( $template_path ) {
					ob_start();
					require $template_path;
					return ob_get_clean();
				};
			}
		}

		// Divi Note: We pass in $metadata directly because we already have the metadata from the file.
		// So just know that $metadat is the equivalent of the $args param in the core function.
		$settings = array_merge( $settings, $metadata );

		$script_fields = array(
			'editorScript' => 'editor_script_handles',
			'script'       => 'script_handles',
			'viewScript'   => 'view_script_handles',
		);
		foreach ( $script_fields as $metadata_field_name => $settings_field_name ) {
			if ( ! empty( $settings[ $metadata_field_name ] ) ) {
				$metadata[ $metadata_field_name ] = $settings[ $metadata_field_name ];
			}
			if ( ! empty( $metadata[ $metadata_field_name ] ) ) {
				$scripts           = $metadata[ $metadata_field_name ];
				$processed_scripts = array();
				if ( is_array( $scripts ) ) {
					// phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed, Squiz.PHP.DisallowSizeFunctionsInLoops.Found -- This is from core.
					for ( $index = 0; $index < count( $scripts ); $index++ ) {
						$result = register_block_script_handle(
							$metadata,
							$metadata_field_name,
							$index
						);
						if ( $result ) {
							$processed_scripts[] = $result;
						}
					}
				} else {
					$result = register_block_script_handle(
						$metadata,
						$metadata_field_name
					);
					if ( $result ) {
						$processed_scripts[] = $result;
					}
				}
				$settings[ $settings_field_name ] = $processed_scripts;
			}
		}

		$module_fields = array(
			'viewScriptModule' => 'view_script_module_ids',
		);
		foreach ( $module_fields as $metadata_field_name => $settings_field_name ) {
			if ( ! empty( $settings[ $metadata_field_name ] ) ) {
				$metadata[ $metadata_field_name ] = $settings[ $metadata_field_name ];
			}
			if ( ! empty( $metadata[ $metadata_field_name ] ) ) {
				$modules           = $metadata[ $metadata_field_name ];
				$processed_modules = array();
				if ( is_array( $modules ) ) {
					// phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed, Squiz.PHP.DisallowSizeFunctionsInLoops.Found -- This is from core.
					for ( $index = 0; $index < count( $modules ); $index++ ) {
						$result = register_block_script_module_id(
							$metadata,
							$metadata_field_name,
							$index
						);
						if ( $result ) {
							$processed_modules[] = $result;
						}
					}
				} else {
					$result = register_block_script_module_id(
						$metadata,
						$metadata_field_name
					);
					if ( $result ) {
						$processed_modules[] = $result;
					}
				}
				$settings[ $settings_field_name ] = $processed_modules;
			}
		}

		$style_fields = array(
			'editorStyle' => 'editor_style_handles',
			'style'       => 'style_handles',
			'viewStyle'   => 'view_style_handles',
		);
		foreach ( $style_fields as $metadata_field_name => $settings_field_name ) {
			if ( ! empty( $settings[ $metadata_field_name ] ) ) {
				$metadata[ $metadata_field_name ] = $settings[ $metadata_field_name ];
			}
			if ( ! empty( $metadata[ $metadata_field_name ] ) ) {
				$styles           = $metadata[ $metadata_field_name ];
				$processed_styles = array();
				if ( is_array( $styles ) ) {
					// phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed, Squiz.PHP.DisallowSizeFunctionsInLoops.Found -- This is from core.
					for ( $index = 0; $index < count( $styles ); $index++ ) {
						$result = register_block_style_handle(
							$metadata,
							$metadata_field_name,
							$index
						);
						if ( $result ) {
							$processed_styles[] = $result;
						}
					}
				} else {
					$result = register_block_style_handle(
						$metadata,
						$metadata_field_name
					);
					if ( $result ) {
						$processed_styles[] = $result;
					}
				}
				$settings[ $settings_field_name ] = $processed_styles;
			}
		}

		if ( ! empty( $metadata['blockHooks'] ) ) {
			/**
			 * Map camelCased position string (from block.json) to snake_cased block type position.
			 *
			 * @var array
			 */
			$position_mappings = array(
				'before'     => 'before',
				'after'      => 'after',
				'firstChild' => 'first_child',
				'lastChild'  => 'last_child',
			);

			$settings['block_hooks'] = array();
			foreach ( $metadata['blockHooks'] as $anchor_block_name => $position ) {
				// Avoid infinite recursion (hooking to itself).
				if ( $metadata['name'] === $anchor_block_name ) {
					_doing_it_wrong(
						__METHOD__,
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Not escaping because it's a code block, and this is from core.
						__( 'Cannot hook block to itself.' ),
						'6.4.0'
					);
					continue;
				}

				if ( ! isset( $position_mappings[ $position ] ) ) {
					continue;
				}

				$settings['block_hooks'][ $anchor_block_name ] = $position_mappings[ $position ];
			}
		}

		/**
		 * Filters the settings determined from the block type metadata.
		 *
		 * @since 5.7.0
		 *
		 * @param array $settings Array of determined settings for registering a block type.
		 * @param array $metadata Metadata provided for registering a block type.
		 */
		$settings = apply_filters( 'block_type_metadata_settings', $settings, $metadata );

		$metadata['name'] = ! empty( $settings['name'] ) ? $settings['name'] : $metadata['name'];

		return WP_Block_Type_Registry::get_instance()->register(
			$metadata['name'],
			$settings
		);
	}

	/**
	 * Retrieve the default attributes of a registered block module.
	 *
	 * This function retrieves the default attributes of a registered block module based on the provided module name.
	 * It checks if the default attributes are already cached to optimize performance and returns the cached attributes if available.
	 * It check if default attributes definition file exists in the module folder. If it exists, it retrieves the default attributes from the file.
	 * If the default attributes are not cached, it retrieves the registered module using the `WP_Block_Type_Registry` class.
	 * If the registered module is found, it retrieves the attributes of the module and extracts the default values into an array.
	 *
	 * @since ??
	 *
	 * @param string     $module_name The name of the module.
	 * @param string     $default_property_name Optional. The name of the default property to use. It can be either `'default'` or `'defaultPrintedStyle'`. Default `'default'`.
	 * @param array|null $metadata Optional. The metadata of the module. Default `null`.
	 *
	 * @return array An array of default attributes for the module.
	 */
	public static function get_default_attrs( string $module_name, string $default_property_name = 'default', $metadata = null ): array {
		return self::generate_default_attrs( $module_name, $default_property_name, $metadata );
	}

	/**
	 * Get the default attributes for a module.
	 *
	 * This function returns the default attributes for the module with the provided module name and default property name.
	 * The attributes are  defined and retrieved from the module's `module.json` file.
	 *
	 * @since ??
	 *
	 * @param string     $module_name           The name of the module to retrieve the default attributes for.
	 * @param string     $default_property_name Optional. The name of the default property to use. It can be either `'default'` or `'defaultPrintedStyle'`. Default `'default'`.
	 * @param array|null $metadata              Optional. The metadata of the module. Default `null`.
	 *
	 * @return array The default attributes for the module.
	 *
	 * @example:
	 * ```php
	 * // Retrieve the default attributes for a module called 'my_module'.
	 * $default_attrs = ModuleRegistration::generate_default_attrs( 'my_module' );
	 *
	 * // Retrieve the default attributes for a module called 'another_module' using a custom default property called 'custom'.
	 * $default_attrs = ModuleRegistration::generate_default_attrs( 'another_module', 'custom' );
	 * ```
	 */
	public static function generate_default_attrs( string $module_name, string $default_property_name = 'default', $metadata = null ): array {
		static $cached = [];

		$cache_key = $module_name . '--' . $default_property_name;

		if ( isset( $cached[ $cache_key ] ) ) {
			return $cached[ $cache_key ];
		}

		$default_attributes = [];
		$metadata_folder    = self::$_metadata_folders[ $module_name ] ?? null;

		if ( $metadata_folder ) {
			$default_data_file_name = 'defaultPrintedStyle' === $default_property_name ? 'module-default-printed-style-attributes.json' : 'module-default-render-attributes.json';
			$default_data_file      = $metadata_folder . $default_data_file_name;
			$metadata_file          = $metadata_folder . 'module.json';

			if ( ! empty( $metadata ) ) {
				$metadata_exists = true;
			} else {
				$metadata_exists = file_exists( $metadata_file );
			}

			if ( $metadata_exists && file_exists( $default_data_file ) ) {
				// either it is already passed in or we need to read it from the file.
				$metadata     = $metadata ?? wp_json_file_decode( $metadata_file, [ 'associative' => true ] );
				$default_data = wp_json_file_decode( $default_data_file, [ 'associative' => true ] );

				foreach ( $metadata['attributes'] ?? [] as $attr_name => $metadata_attribute ) {
					$default_attribute = array_replace_recursive(
						$metadata_attribute[ $default_property_name ] ?? [],
						$default_data[ $attr_name ] ?? []
					);

					if ( $default_attribute ) {
						$default_attributes[ $attr_name ] = $default_attribute;
					}
				}

				$cached[ $cache_key ] = $default_attributes;
			} elseif ( $metadata_exists ) {
				// either it is already passed in or we need to read it from the file.
				$metadata = $metadata ?? wp_json_file_decode( $metadata_file, [ 'associative' => true ] );

				foreach ( $metadata['attributes'] ?? [] as $attr_name => $metadata_attribute ) {
					$default_attribute = $metadata_attribute[ $default_property_name ] ?? null;

					if ( null !== $default_attribute ) {
						$default_attributes[ $attr_name ] = $default_attribute;
					}
				}

				$cached[ $cache_key ] = $default_attributes;
			}
		}

		return $default_attributes;
	}

	/**
	 * Retrieve module selectors.
	 *
	 * Get the selectors associated with the attributes of a registered block that is defined in the module.json file.
	 *
	 * @since ??
	 *
	 * @param string $module_name The name of the module for which to retrieve the selectors.
	 *
	 * @return array An array of selectors where the key is the module attribute name and the value is the selector.
	 *
	 * @example:
	 * ```php
	 *     $selectors = ModuleRegistration::get_selectors( 'module_name' );
	 *     // Returns an array of selectors for the specified module.
	 *     // Example: ['attribute_name' => '.selector']
	 * ```
	 */
	public static function get_selectors( string $module_name ): array {
		static $cached = [];

		if ( isset( $cached[ $module_name ] ) ) {
			return $cached[ $module_name ];
		}

		$selectors         = [];
		$registered_module = WP_Block_Type_Registry::get_instance()->get_registered( $module_name );

		if ( $registered_module ) {
			$attrs = $registered_module->get_attributes();

			foreach ( $attrs as $key => $value ) {
				if ( ! isset( $value['selector'] ) ) {
					continue;
				}

				$selectors[ $key ] = $value['selector'];
			}
		}

		$cached[ $module_name ] = $selectors;

		return $selectors;
	}

	/**
	 * Check if a module is a child module.
	 *
	 * @since ??
	 *
	 * @param string $module_name The name of the module to check.
	 *
	 * @return bool True if the module is a child module, false otherwise.
	 */
	public static function is_child_module( $module_name ) {
		$registered_module = WP_Block_Type_Registry::get_instance()->get_registered( $module_name );

		$category = $registered_module->category ?? 'module';

		return 'child-module' === $category;
	}
}
