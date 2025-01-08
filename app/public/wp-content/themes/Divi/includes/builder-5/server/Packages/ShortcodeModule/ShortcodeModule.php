<?php
/**
 * ShortcodeModule: ShortcodeModule.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\ShortcodeModule;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\ShortcodeModule\Module\Module;
use ET\Builder\Framework\DependencyManagement\DependencyTree;

/**
 * `ShortcodeModule` main class.
 *
 * @since ??
 */
class ShortcodeModule {

	/**
	 * Create an instance of `ShortcodeModule` class.
	 *
	 * Creates an instance of `ShortcodeModule` class and calls `divi_module_library_modules_dependency_tree` action passing self::register_module`.
	 *
	 * @since ??
	 */
	public function __construct() {
		add_action( 'divi_module_library_modules_dependency_tree', [ self::class, 'register_module' ] );
	}

	/**
	 * Register Shortcode module.
	 *
	 * @param DependencyTree $dependency_tree Dependency tree for VisualBuilder to load.
	 *
	 * @since ??
	 */
	/**
	 * Registers a Shortcode module with the given dependency tree.
	 *
	 * This function adds a module dependency to the specified dependency tree.
	 *
	 * @since ??
	 *
	 * @param DependencyTree $dependency_tree The dependency tree to which the module will be added.
	 *
	 * @return void
	 */
	public static function register_module( DependencyTree $dependency_tree ): void {
		$dependency_tree->add_dependency( new Module() );
	}
}

new ShortcodeModule();
