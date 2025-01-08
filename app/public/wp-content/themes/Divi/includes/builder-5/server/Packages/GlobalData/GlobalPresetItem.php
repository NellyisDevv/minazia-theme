<?php
/**
 * REST: GlobalPresetItem class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\GlobalData;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

/**
 * GlobalPresetItem class.
 *
 * @since ??
 */
class GlobalPresetItem {

	/**
	 * The data.
	 *
	 * @var array
	 */
	private $_data = [];

	/**
	 * Constructor.
	 *
	 * @param array $data The data.
	 *
	 * @return void
	 */
	public function __construct( array $data ) {
		$this->_data = $data;
	}

	/**
	 * Get the data.
	 *
	 * @since ??
	 *
	 * @return array The data.
	 */
	public function get_data(): array {
		return $this->_data;
	}

	/**
	 * Get the type.
	 *
	 * @since ??
	 *
	 * @return string|null The type of the preset.
	 */
	public function get_data_type(): ?string {
		return $this->_data['type'] ?? null;
	}

	/**
	 * Get the ID.
	 *
	 * @since ??
	 *
	 * @return string|null The ID of the preset.
	 */
	public function get_data_id(): ?string {
		return $this->_data['id'] ?? null;
	}

	/**
	 * Get the name.
	 *
	 * @since ??
	 *
	 * @return string|null The name of the preset.
	 */
	public function get_data_name(): ?string {
		return $this->_data['name'] ?? null;
	}

	/**
	 * Get the created date.
	 *
	 * @since ??
	 *
	 * @return int|null The created date of the preset.
	 */
	public function get_data_created(): ?int {
		return $this->_data['created'] ?? null;
	}

	/**
	 * Get the updated date.
	 *
	 * @since ??
	 *
	 * @return int|null The updated date.
	 */
	public function get_data_updated(): ?int {
		return $this->_data['updated'] ?? null;
	}

	/**
	 * Get the version.
	 *
	 * @since ??
	 *
	 * @return string|null The version of the preset.
	 */
	public function get_data_version(): ?string {
		return $this->_data['version'] ?? null;
	}

	/**
	 * Get the attrs.
	 *
	 * @since ??
	 *
	 * @return array The attrs of the preset.
	 */
	public function get_data_attrs(): array {
		return $this->_data['attrs'] ?? [];
	}

	/**
	 * Get the render attrs.
	 *
	 * @since ??
	 *
	 * @return array The render attrs of the preset.
	 */
	public function get_data_render_attrs(): array {
		return $this->_data['renderAttrs'] ?? [];
	}

	/**
	 * Get the style attrs.
	 *
	 * @since ??
	 *
	 * @return array The style attrs of the preset.
	 */
	public function get_data_style_attrs(): array {
		return $this->_data['styleAttrs'] ?? [];
	}

	/**
	 * Get the module name.
	 *
	 * @since ??
	 *
	 * @return string|null The module name of the preset.
	 */
	public function get_data_module_name(): ?string {
		return $this->_data['moduleName'] ?? null;
	}

	/**
	 * Check the preset has attrs.
	 *
	 * @since ??
	 *
	 * @return bool True if the preset has attrs, false otherwise.
	 */
	public function has_data_attrs() {
		return ! empty( $this->_data['attrs'] );
	}

	/**
	 * Check the preset is empty.
	 *
	 * @since ??
	 *
	 * @return bool True if the preset is empty, false otherwise.
	 */
	public function is_empty() {
		return empty( $this->_data );
	}
}
