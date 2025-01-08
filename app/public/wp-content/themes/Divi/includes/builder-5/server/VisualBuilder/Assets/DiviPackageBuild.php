<?php
/**
 * Divi's PackageBuild class.
 *
 * @package Divi
 *
 * @since ??
 */

namespace ET\Builder\VisualBuilder\Assets;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\VisualBuilder\Assets\PackageBuild;
use ET\Builder\Framework\Utility\Conditions;

/**
 * Extended class of PackageBuild specifically for handling Divi 5's package build.
 *
 * PackageBuild is intentionally made to be generic so it can be used by third party a well.
 * Significant aspect of Divi 5's package build is consistent so they can be inferred based
 * on convetion used for organizing Divi's code so this class is specifically made for Divi 5's
 * package build.
 *
 * @since ??
 */
class DiviPackageBuild extends PackageBuild {
	/**
	 * Package build's name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Build dependencies that are inferred from package build's *.assets.php that is generated on build process.
	 *
	 * @var string
	 */
	public $build_dependencies;

	/**
	 * Build version that is inferred from package build's *.assets.php that is generated  on build process.
	 *
	 * @var string
	 */
	public $build_version;

	/**
	 * Whether this divi package build has asset file or not.
	 *
	 * The asset file is a dependency file generated by webpack during VB's build process, using the `@wordpress/dependency-extraction-webpack-plugin`.
	 *
	 * @var boolean
	 */
	public $has_asset = false;

	/**
	 * Property that keep track whether current site uses Divi as its theme.
	 *
	 * @var bool
	 */
	private $_is_divi_theme = false;

	/**
	 * Property that keep track whether current site uses Divi as its parent theme.
	 *
	 * @var bool
	 */
	private $_is_divi_parent_theme = false;

	/**
	 * Property that keep track whether current site uses Divi Builder Plugin.
	 *
	 * @var bool
	 */
	private $_is_divi_builder_plugin = false;

	/**
	 * Package build's constructor.
	 *
	 * @since ??
	 *
	 * @param array $params Package build's constructor params.
	 */
	public function __construct( $params ) {
		// Check whether Divi is the active theme.
		$this->_is_divi_theme = 'Divi' === wp_get_theme()->get( 'Name' );

		// If there is a child theme, check whether Divi is the active parent theme.
		$this->_is_divi_parent_theme = is_child_theme() && 'Divi' === wp_get_theme()->parent()->get( 'Name' );

		// Check whether Divi Builder Plugin is active.
		$this->_is_divi_builder_plugin = function_exists( 'is_plugin_active' ) && is_plugin_active( 'divi-builder/divi-builder.php' );

		$this->name = $params['name'] ?? '';

		$this->set_build_properties();

		$this->generate_properties(
			$params['script'] ?? [],
			$params['style'] ?? []
		);
	}

	/**
	 * Parse and set build properties based on package build's asset file that is generated from build process.
	 *
	 * @since ??
	 *
	 * @return boolean
	 */
	public function set_build_properties() {
		// Get asset name. Divi package build's name is mostly prefixed by `divi-*`
		// but the asset name has the `divi-` prefix removed.
		$asset_name = str_replace( 'divi-', '', $this->name );

		// Get path to asset file.
		$asset_path = sprintf(
			'%s/%s/%s.asset.php',
			untrailingslashit( ET_BUILDER_5_DIR ),
			'visual-builder/build',
			$asset_name
		);

		$this->has_asset = file_exists( $asset_path );

		if ( ! $this->has_asset ) {
			return false;
		}

		$asset = require $asset_path;

		// Get script dependencies that is automatically generated during build process.
		$dependencies = isset( $asset['dependencies'] ) ? array_unique(
			array_map(
				function( $dep ) {
					return explode( '/', $dep )[0];
				},
				$asset['dependencies']
			)
		) : [];

		// Get script that is automatically generated during build process.
		$version = $asset['version'] ?? '';

		$this->build_dependencies = $dependencies;
		$this->build_version      = $version;

		return true;
	}

	/**
	 * Generate package build's properties.
	 *
	 * @since ??
	 *
	 * @param array $script Scripts that are used by package build.
	 * @param array $style Styles that are used by package build.
	 */
	public function generate_properties( $script = [], $style = [] ) {
		// Get Divi version.
		$divi_version = et_get_theme_version();

		// Builder uri & directory.
		$builder_uri = untrailingslashit( ET_BUILDER_5_URI );
		$builder_dir = untrailingslashit( ET_BUILDER_5_DIR );

		// Get asset name. Divi package build's name is mostly prefixed by `divi-*`
		// but the asset name has the `divi-` prefix removed.
		$asset_name = str_replace( 'divi-', '', $this->name );

		// Get script source.
		$script_src = sprintf(
			'%s/visual-builder/build/%s.js',
			$builder_uri,
			$asset_name
		);

		// Get style source.
		$style_src = sprintf(
			'%s/visual-builder/build/%s.css',
			$builder_uri,
			$asset_name
		);

		// Get style path.
		$style_path = sprintf(
			'%s/visual-builder/build/%s.css',
			$builder_dir,
			$asset_name
		);

		// Generated settings.
		$generated_style  = [
			'src' => $style_src,
		];
		$generated_script = [
			'src'  => $script_src,
			'deps' => array_merge(
				isset( $script['deps'] ) && is_array( $script['deps'] ) ? $script['deps'] : [],
				$this->build_dependencies
			),
			'args' => [
				'in_footer' => true,
			],
		];

		if ( file_exists( $style_path ) ) {
			// If file exists, set the deps.
			if ( ! isset( $style['deps'] ) ) {
				$generated_style['deps'] = [];
			}

			// Maybe prepend 'divi-style' to the package css dependencies.
			if ( ( $this->_is_divi_theme || $this->_is_divi_builder_plugin ) && ! Conditions::is_tb_admin_screen() ) {
				array_unshift( $generated_style['deps'], 'divi-style' );
			}

			// Maybe prepend 'divi-style-parent' to the package css dependencies.
			// NOTE: When Divi is being used as parent theme, `divi-style-parent` is expected to be dependency on
			// `divi-style` behalf because there's a very high chance for Child theme to intentionally dequeue and
			// deregister `divi-style`. Doing so will cause package builds' style not to be enqueued due to missing deps.
			if ( $this->_is_divi_parent_theme && ! Conditions::is_tb_admin_screen() ) {
				array_unshift( $generated_style['deps'], 'divi-style-parent' );
			}
		} else {
			$generated_style['enqueue_top_window'] = false;
			$generated_style['enqueue_app_window'] = false;
		}

		// Generate version.
		$version = $divi_version . '-' . $this->build_version;

		$this->set_properties(
			[
				'name'    => $this->name,
				'version' => $version,
				'script'  => array_merge(
					$generated_script,
					$script
				),
				'style'   => wp_parse_args(
					$generated_style,
					$style
				),
			]
		);
	}
}