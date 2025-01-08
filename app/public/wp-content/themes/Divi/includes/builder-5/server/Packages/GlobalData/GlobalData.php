<?php
/**
 * Global Data: GlobalData Class
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Packages\GlobalData;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

/**
 * Handles the Global Data.
 *
 * @since ??
 */
class GlobalData {
	/**
	 * The cached global data.
	 *
	 * @since ??
	 *
	 * @var array|null
	 */
	private static $_cached_data = null;

	/**
	 * List of customizer colors.
	 *
	 * @since ??
	 *
	 * @var array
	 */
	public static $customizer_colors = [
		'gcid-primary-color'   => [
			'label'       => 'Primary Color',
			'option_name' => 'accent_color',
			'default'     => '#2ea3f2',
		],
		'gcid-secondary-color' => [
			'label'       => 'Secondary Color',
			'option_name' => 'secondary_accent_color',
			'default'     => '#2ea3f2',
		],
		'gcid-heading-color'   => [
			'label'       => 'Heading Text Color',
			'option_name' => 'header_color',
			'default'     => '#666666',
		],
		'gcid-body-color'      => [
			'label'       => 'Body Text Color',
			'option_name' => 'font_color',
			'default'     => '#666666',
		],
	];

	/**
	 * Converts the global colors data array into a format compatible with the Divi 5.
	 *
	 * @since ??
	 *
	 * @param array  $data              The global colors data array.
	 * @param string $non_active_status One of: active | inactive | temporary, to be set when `active` is `no` or not defined.
	 *
	 * @return array[] {
	 *     The converted Global Colors array.
	 *
	 *     @type int      $id          The global ID.
	 *     @type string   $color       Global color value
	 *     @type string   $status      Global color status: active | inactive | temporary,
	 *     @type string   $lastUpdated Last updated datetime.
	 *     @type string[] $usedInPosts Array of Post ID where the color has been used.
	 * }
	 *
	 * @example:
	 * ```php
	 * GlobalData::convert_global_colors_data([
	 *   'gcid-8ce98ce1-4460-49e4-9cd7-b148b47c216c' => [
	 *     'color'  => '#fcf6f0',
	 *     'active' => 'yes',
	 *   ],
	 *   'gcid-27d27316-00ff-460e-9cc1-5df31af25225' => [
	 *      'color'  => '#f0f0f0',
	 *      'active' => 'no',
	 *    ],
	 * ]);
	 * ```
	 */
	public static function convert_global_colors_data( array $data, string $non_active_status = 'inactive' ): array {
		if ( empty( $data ) ) {
			return [];
		}

		// Validate $non_active_status.
		if ( ! in_array( $non_active_status, [ 'active', 'inactive' ], true ) ) {
			$non_active_status = 'inactive';
		}

		$converted_data = [];

		foreach ( $data as $key => $value ) {
			// Convert only when `color` value and `active` status is set, and id starts with `gcid-`.
			if (
				! empty( $value['color'] ) &&
				isset( $value['active'] ) &&
				substr( $key, 0, 5 ) === 'gcid-'
			) {
				$converted_data[ sanitize_text_field( $key ) ] = [
					'color'       => sanitize_text_field( $value['color'] ),
					'folder'      => '', // <-- not until D6
					'label'       => '', // <-- not until D6
					'lastUpdated' => wp_date( 'Y-m-d\TH:i:s.v\Z' ),
					'status'      => sanitize_text_field( $value['active'] ?? '' ) === 'yes' ? 'active' : $non_active_status,
					'usedInPosts' => [],
				];
			}
		}

		return $converted_data;
	}

	/**
	 * Retrieves the global colors from the global data option.
	 *
	 * @since ??
	 *
	 * @return array[] {
	 *     The list of Global Colors data.
	 *
	 *     @type int      $id          The global ID.
	 *     @type string   $color       Global color value
	 *     @type string   $status      Global color status: active | inactive | temporary,
	 *     @type string   $lastUpdated Last updated datetime.
	 *     @type string[] $usedInPosts Array of Post ID where the color has been used.
	 * }
	 *
	 * @example:
	 * ```php
	 * GlobalData::get_global_colors();
	 * ```
	 */
	public static function get_global_colors(): array {
		if ( null === self::$_cached_data ) {
			$global_data = maybe_unserialize( et_get_option( 'et_global_data' ) );

			$global_colors_full = $global_data['global_colors'] ?? [];

			// Remove customizer global colors if exist for any reason.
			// For example if user imported global colors on old version of Divi which doesn't support customizer colors.
			foreach ( self::$customizer_colors as $color_id => $color_data ) {
				unset( $global_colors_full[ $color_id ] );
			}

			// Add fresh customizer colors.
			self::$_cached_data = array_merge(
				self::get_customizer_colors(),
				$global_colors_full
			);
		}

		return self::$_cached_data;
	}

	/**
	 * Generate the list of Customizer Global Colors.
	 *
	 * @since ??
	 *
	 * @return array[] {
	 *     The list of Global Colors data.
	 *
	 *     @type int      $id          The global ID.
	 *     @type string   $color       Global color value
	 *     @type string   $status      Global color status: active | inactive | temporary,
	 *     @type string   $lastUpdated Last updated datetime.
	 *     @type string[] $usedInPosts Array of Post ID where the color has been used.
	 * }
	 */
	public static function get_customizer_colors(): array {
		static $formatted_colors = null;

		if ( null !== $formatted_colors ) {
			return $formatted_colors;
		}

		$formatted_colors    = [];
		$global_color_labels = [
			'Primary Color'      => esc_html__( 'Primary Color', 'Divi' ),
			'Secondary Color'    => esc_html__( 'Secondary Color', 'Divi' ),
			'Heading Text Color' => esc_html__( 'Heading Text Color', 'Divi' ),
			'Body Text Color'    => esc_html__( 'Body Text Color', 'Divi' ),
		];

		foreach ( self::$customizer_colors as $color_id => $color_data ) {
			$formatted_colors[ $color_id ] = [
				'color'       => sanitize_text_field( et_get_option( $color_data['option_name'], $color_data['default'] ) ),
				'folder'      => 'customizer',
				'label'       => $global_color_labels[ $color_data['label'] ],
				'lastUpdated' => wp_date( 'Y-m-d\TH:i:s.v\Z' ),
				'status'      => 'active',
				'usedInPosts' => [],
			];
		}

		return $formatted_colors;
	}

	/**
	 * Returns the global colors from the incoming data after converting it into Divi 5 data format.
	 *
	 * This helper function used to prepare global colors data to be imported by converting data format that being
	 * imported.
	 *
	 * @since ??
	 *
	 * @param array $incoming_data The incoming data with global colors.
	 *
	 * @return array[] {
	 *     The global colors array
	 *
	 *     @type int $id The global ID.
	 *     @type string $color Global color value
	 *     @type string $status Global color status: active | inactive | temporary,
	 *     @type string $lastUpdated Last updated datetime.
	 *     @type string[] $usedInPosts Array of Post ID where the color has been used.
	 * }.
	 */
	public static function get_imported_global_colors( array $incoming_data ): array {
		$global_colors = [];

		// Sanity check.
		if ( empty( $incoming_data ) ) {
			return $global_colors;
		}

		// Only run conversion on imported data if the global colors flag is enabled.
		// @see https://github.com/elegantthemes/Divi/issues/25789.
		if ( ET_BUILDER_5_EXPERIMENTS_GLOBAL_DATA_COLORS ) {
			foreach ( $incoming_data as $data ) {
				// Global ID field.
				$key = sanitize_text_field( $data[0] );

				// Check for D4 or D5 formatted data, and prepare $global_colors accordingly.
				if (
					isset( $data[1]['active'] ) &&
					! empty( $data[1]['color'] ) &&
					substr( $key, 0, 5 ) === 'gcid-'
				) {
					$global_colors[ $key ] = [
						'color'       => sanitize_text_field( $data[1]['color'] ),
						'folder'      => '', // <-- not until D6
						'label'       => '', // <-- not until D6
						'lastUpdated' => wp_date( 'Y-m-d\TH:i:s.v\Z' ),
						'status'      => sanitize_text_field( $data[1]['active'] ) === 'yes' ? 'active' : 'inactive',
						'usedInPosts' => [],
					];
				} elseif (
					isset( $data[1]['status'] ) &&
					! empty( $data[1]['color'] ) &&
					substr( $key, 0, 5 ) === 'gcid-'
				) {
					$global_colors[ $key ] = [
						'color'       => sanitize_text_field( $data[1]['color'] ),
						'folder'      => '', // <-- not until D6
						'label'       => '', // <-- not until D6
						'lastUpdated' => wp_date( 'Y-m-d\TH:i:s.v\Z' ),
						'status'      => sanitize_text_field( $data[1]['status'] ) === 'active' ? 'active' : 'inactive',
						'usedInPosts' => [],
					];
				}
			}
		}

		return $global_colors;
	}

	/**
	 * Maybe converts the global colors data.
	 *
	 * If the et_global_data option is not set, this method retrieves the et_global_colors option,
	 * sanitizes the values, and creates a new et_global_data option with the converted data.
	 *
	 * @since ??
	 *
	 * @return void
	 *
	 * @example:
	 * ```php
	 * GlobalData::maybe_convert_global_colors_data();
	 * ```
	 */
	public static function maybe_convert_global_colors_data() {
		// Reset the cached data.
		self::$_cached_data = null;

		// Get Global Data options.
		$global_data = et_get_option( 'et_global_data', false );

		// When $global_data not found, convert and save global colors data from D4 option.
		if ( false === $global_data ) {
			// Get old global colors data.
			$d4_data = maybe_unserialize( et_get_option( 'et_global_colors', false ) );

			$global_data = [
				'global_colors' => false === $d4_data
					? []
					: self::convert_global_colors_data( $d4_data ),
			];

			// Add et_global_data option.
			et_update_option( 'et_global_data', maybe_serialize( $global_data ) );
		}
	}

	/**
	 * Sanitizes global colors data.
	 *
	 * Sanitizes the provided global colors data by removing invalid or empty values. The function
	 * applies sanitization to each key and value, ensuring they are safe for further processing.
	 *
	 * @since ??
	 *
	 * @param array $data The global colors data to sanitize.
	 *
	 * @return array The sanitized global colors data.
	 *
	 * @example:
	 *  ```php
	 * $global_colors_data = [
	 *     'gcid-98eb727ac3' => [
	 *         'color'       => 'red',
	 *         'lastUpdated' => '2024-01-01T00:00:00.000Z',
	 *         'status'      => 'active',
	 *         'usedInPosts' => [ 123, 345 ],
	 *     ],
	 *    'gcid-3cf7c930-5f16-4c4e-9613-90a9edb8c65a' => [
	 *          'color'       => '#f0f0f0',
	 *          'lastUpdated' => '2024-01-02T00:00:00.000Z',
	 *          'status'      => 'inactive',
	 *          'usedInPosts' => [],
	 *      ],
	 *  ];
	 *
	 * GlobalData::sanitize_global_colors_data( $global_colors_data );
	 */
	public static function sanitize_global_colors_data( array $data ): array {
		// Sanity check, global colors should not be empty.
		if ( empty( $data ) ) {
			return [];
		}

		// By default, the sanitized values is an empty array.
		$sanitized_data = [];

		foreach ( $data as $id => $item_data ) {
			// Drop bad data.
			if (
				'undefined' === $id ||
				empty( $item_data ) ||
				empty( $item_data['color'] ) ||
				substr( $id, 0, 5 ) !== 'gcid-'
			) {
				continue;
			}

			// Sanitize data_id (e.g: 373c75a2-4440-44da-8d3f-57b75310d4c7 ).
			$global_id = sanitize_text_field( $id );

			foreach ( $item_data as $param_key => $param_value ) {
				// Sanitize key and value ('usedInPosts' has array value).
				$sanitized_data[ $global_id ][ sanitize_text_field( $param_key ) ] = 'usedInPosts' === $param_key
					? array_map( 'sanitize_text_field', $param_value )
					: sanitize_text_field( $param_value );
			}
		}

		return $sanitized_data;
	}

	/**
	 * Sets the global colors for the Divi.
	 *
	 * This method takes an array of color data and stores it in the global data settings.
	 * The color data should be in a specific format and will be sanitized before storing.
	 *
	 * @param array $data              The array of global color data to set.
	 * @param array $already_sanitized Whether the data is sanitized or not.
	 *
	 * @return void
	 *
	 * @example:
	 *   ```php
	 *  $global_colors_data = [
	 *      'gcid-98eb727a-9088-4709-8ec8-2fee0213c5c3' => [
	 *          'color'       => 'red',
	 *          'lastUpdated' => '2024-01-01T00:00:00.000Z',
	 *          'status'      => 'active',
	 *          'usedInPosts' => [ 123, 345 ],
	 *      ],
	 *     'gcid-3cf7c9305a' => [
	 *           'color'       => '#f0f0f0',
	 *           'lastUpdated' => '2024-01-02T00:00:00.000Z',
	 *           'status'      => 'inactive',
	 *           'usedInPosts' => [],
	 *       ],
	 *   ];
	 *
	 *  GlobalData::set_global_colors( $global_colors_data );
	 */
	public static function set_global_colors( array $data, $already_sanitized = false ): void {
		// Reset the cached data.
		self::$_cached_data = null;

		// Get the global_data from the option.
		$global_data = maybe_unserialize( et_get_option( 'et_global_data' ) );

		if ( ! is_array( $global_data ) ) {
			$global_data = [];
		}

		if ( ! $already_sanitized ) {
			$data = self::sanitize_global_colors_data( $data );
		}

		// Update Customizer colors which are part of Global Colors payload.
		foreach ( self::$customizer_colors as $color_id => $color_data ) {
			if ( isset( $data[ $color_id ] ) && isset( $data[ $color_id ]['color'] ) ) {
				et_update_option( $color_data['option_name'], $data[ $color_id ]['color'] );
			}

			// Remove Customizer color from Global Colors as it stored in different place.
			unset( $data[ $color_id ] );
		}

		// Set `global_colors` on the $global_data.
		$global_data['global_colors'] = $data;

		// Only run conversion on imported data if the global colors flag is enabled.
		// @see https://github.com/elegantthemes/Divi/issues/25789.
		if ( ET_BUILDER_5_EXPERIMENTS_GLOBAL_DATA_COLORS ) {
			// Update the option.
			et_update_option( 'et_global_data', maybe_serialize( $global_data ) );
		}
	}

	/**
	 * Retrieves the global color by its ID.
	 *
	 * @param string $global_color_id The ID of the global color.
	 * @return array The global color data.
	 */
	public static function get_global_color_by_id( string $global_color_id ): array {
		$data = self::get_global_colors();

		return $data[ $global_color_id ] ?? [];
	}

	/**
	 * Gets the global color id from a CSS variable color value.
	 *
	 * If the value is a valid CSS variable color value e.g var(--gcid-2d8c4bca77), this function will return the
	 * global color id (gcid-2d8c4bca77) from the variable name. If the value is not a valid CSS variable
	 * color value with the correct global color ID pattern (`gcid-{uuid}`), this function will return `null`.
	 * This is equivalent to the JS function {@link /docs/builder-api/js-beta/divi-global-data/functions/getGlobalColorIdFromValue getGlobalColorIdFromValue}
	 *
	 * @since ??
	 *
	 * @param string $value The CSS variable color value.
	 *
	 * @return string|null The global color ID if the value is a CSS variable color value, otherwise `null`.
	 *
	 * @example
	 *
	 * Given a CSS variable color value with a short global color id:
	 *
	 * ```php
	 * GlobalData::get_global_color_id_from_value('var(--gcid-2d8c4bca77)');
	 * // 'gcid-2d8c4bca77'
	 * ```
	 *
	 * @example
	 *
	 * Given a CSS variable color value with a UUIDv4 global color id:
	 *
	 * ```php
	 * GlobalData::get_global_color_id_from_value('var(--gcid-79d0acb1-9057-46e2-b40f-979d24efd874)');
	 * // 'gcid-79d0acb1-9057-46e2-b40f-979d24efd874'
	 * ```
	 *
	 * @example
	 *
	 * Given a CSS variable color value with color id and a fallback value:
	 *
	 * ```php
	 * GlobalData::get_global_color_id_from_value('var(--gcid-4bca772d8c, #ff0000)');
	 * // 'gcid-4bca772d8c'
	 * ```
	 *
	 * @example
	 *
	 * Given no CSS variable, but only a color value:
	 *
	 * ```php
	 * GlobalData::get_global_color_id_from_value('#0ff000');
	 * // null
	 * ```
	 */
	public static function get_global_color_id_from_value( string $value ): ?string {
		if ( ! is_string( $value ) ) {
			return null;
		}

		// see https://regex101.com/r/WbG74r/2.
		$global_color_id_pattern = '/--gcid-([0-9a-z-]*)/';

		preg_match( $global_color_id_pattern, $value, $global_color_id );

		return ! empty( $global_color_id[1] ?? '' ) ? 'gcid-' . $global_color_id[1] : null;
	}
}
