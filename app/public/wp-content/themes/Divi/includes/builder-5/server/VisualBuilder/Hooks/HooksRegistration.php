<?php
/**
 * Hooks: Hooks class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\VisualBuilder\Hooks;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\VisualBuilder\Fonts\FontsUtility;
use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\VisualBuilder\REST\Portability\PortabilityController;

/**
 * `HooksRegistration` class is consisted of WordPress hook functions used in Visual Builder, It registers them upon calling `load()`.
 *
 * This is a dependency class and can be used as dependency for `DependencyTree`.
 *
 * @since ??
 */
class HooksRegistration implements DependencyInterface {

	/**
	 * Check the file type and extension for font files.
	 *
	 * Filters the "real" file type of the given font file.
	 *
	 * @since ??
	 *
	 * @param array  $checked_filetype_and_ext {
	 *     Values for the extension, mime type, and corrected filename.
	 *     An associative array containing the file extension and file type.
	 *
	 *     @type string|false $ext             File extension, or false if the file doesn't match a mime type.
	 *     @type string|false $type            File mime type, or false if the file doesn't match a mime type.
	 *     @type string|false $proper_filename File name with its correct extension, or false if it cannot be determined.
	 * }
	 * @param string $file                     The full path to the font file.
	 * @param string $filename                 The name of the font file. (may differ from `$file` due to
	 *                                          `$file` being in a tmp directory).
	 *
	 * @return array An associative array containing the file extension, file type, and the sanitized file name.
	 *
	 * @example
	 * ```php
	 *      $checked_filetype_and_ext = array(
	 *          'ext'  => 'ttf',
	 *          'type' => 'application/octet-stream',
	 *      );
	 *      $file = '/path/to/file.ttf';
	 *      $filename = 'font.ttf';
	 *
	 *      FontsUtility::check_filetype_and_ext_font( $checked_filetype_and_ext, $file, $filename );
	 * ```
	 *
	 * @example:
	 * ```php
	 *      $checked_filetype_and_ext = array(
	 *          'ext'  => false,
	 *          'type' => false,
	 *      );
	 *      $file = '/path/to/invalid_file.ttf';
	 *      $filename = 'invalid_font.ttf';
	 *
	 *      FontsUtility::check_filetype_and_ext_font( $checked_filetype_and_ext, $file, $filename );
	 * ```
	 */
	public static function check_filetype_and_ext_font( array $checked_filetype_and_ext, string $file, string $filename ): array {
		$mimes_font = FontsUtility::mime_types_font();

		// Only process if the file exist and PHP extension "fileinfo" is loaded.
		if ( file_exists( $file ) && extension_loaded( 'fileinfo' ) ) {
			$ext = pathinfo( $filename, PATHINFO_EXTENSION );

			if ( $ext && $ext !== $filename && isset( $mimes_font[ $ext ] ) ) {
				// Get the real mime type.
				$finfo     = finfo_open( FILEINFO_MIME_TYPE );
				$real_mime = finfo_file( $finfo, $file );
				finfo_close( $finfo );

				if ( $real_mime && in_array( $real_mime, $mimes_font[ $ext ], true ) ) {
					return array(
						'ext'             => $ext,
						'type'            => $real_mime,
						'proper_filename' => sanitize_file_name( $filename ),
					);
				}
			}

			return array(
				'ext'             => false,
				'type'            => false,
				'proper_filename' => false,
			);
		}

		$ext  = isset( $checked_filetype_and_ext['ext'] ) ? $checked_filetype_and_ext['ext'] : false;
		$type = isset( $checked_filetype_and_ext['type'] ) ? $checked_filetype_and_ext['type'] : false;

		if ( $ext && $type && isset( $mimes_font[ $ext ] ) && in_array( $type, $mimes_font[ $ext ], true ) ) {
			return $checked_filetype_and_ext;
		}

		return array(
			'ext'             => false,
			'type'            => false,
			'proper_filename' => false,
		);
	}

	/**
	 * Filters the "real" file type of the given JSON file.
	 *
	 * @since ??
	 *
	 * @param array  $checked_filetype_and_ext {
	 *     Values for the extension, mime type, and corrected filename.
	 *
	 *     @type string|false $ext             File extension, or false if the file doesn't match a mime type.
	 *     @type string|false $type            File mime type, or false if the file doesn't match a mime type.
	 *     @type string|false $proper_filename File name with its correct extension, or false if it cannot be determined.
	 * }
	 *
	 * @param string $file                      Full path to the file.
	 * @param string $filename                  The name of the file (may differ from $file due to
	 *                                          $file being in a tmp directory).
	 *
	 * @return array
	 */
	public static function check_filetype_and_ext_json( array $checked_filetype_and_ext, string $file, string $filename ): array {
		$mimes_json = PortabilityController::mime_types_json();

		// Only process if the file exist and PHP extension "fileinfo" is loaded.
		if ( file_exists( $file ) && extension_loaded( 'fileinfo' ) ) {
			$ext = pathinfo( $filename, PATHINFO_EXTENSION );

			if ( $ext && $ext !== $filename && isset( $mimes_json[ $ext ] ) ) {
				// Get the real mime type.
				$finfo     = finfo_open( FILEINFO_MIME_TYPE );
				$real_mime = finfo_file( $finfo, $file );
				finfo_close( $finfo );

				// sometimes finfo_file() returns "text/html" or similar for JSON files/JSON content.
				// in this case, we need to check if the file has valid JSON content.
				// if it is, we can safely assume that the file is a JSON file.
				// see https://github.com/elegantthemes/Divi/issues/39203.
				if ( ! in_array( $real_mime, $mimes_json[ $ext ], true ) && 'json' === $ext ) {
					global $wp_filesystem;

					json_decode( $wp_filesystem->get_contents( $file ) );

					if ( json_last_error() === JSON_ERROR_NONE ) {
						$real_mime = 'application/json';
					}
				}

				if ( $real_mime && in_array( $real_mime, $mimes_json[ $ext ], true ) ) {
					return array(
						'ext'             => $ext,
						'type'            => $real_mime,
						'proper_filename' => sanitize_file_name( $filename ),
					);
				}
			}

			return array(
				'ext'             => false,
				'type'            => false,
				'proper_filename' => false,
			);
		}

		$ext  = $checked_filetype_and_ext['ext'] ?? false;
		$type = $checked_filetype_and_ext['type'] ?? false;

		if ( $ext && $type && isset( $mimes_json[ $ext ] ) && in_array( $type, $mimes_json[ $ext ], true ) ) {
			return $checked_filetype_and_ext;
		}

		return array(
			'ext'             => false,
			'type'            => false,
			'proper_filename' => false,
		);
	}

	/**
	 * Set uploads dir for the custom font files.
	 *
	 * Adds a custom subdirectory '/et-fonts' to the upload directory paths and URLs for font file uploads.
	 * If the $directory argument is passed with a 'basedir' key, the function will append the '/et-fonts' subdirectory to the directory path.
	 * If the $directory argument is passed with a 'baseurl' key, the function will append the '/et-fonts' subdirectory to the directory URL.
	 * Additionally, it sets the 'subdir' key in the $directory array to '/et-fonts'.
	 *
	 * @since ??
	 *
	 * @param array $directory {
	 *     An array of upload directory information.
	 *
	 *     @type string $basedir The base directory path for the upload directory.
	 *     @type string $path    The full path to the upload directory including the subdirectory '/et-fonts'.
	 *     @type string $url     The full URL to the upload directory including the subdirectory '/et-fonts'.
	 *     @type string $subdir  The subdirectory '/et-fonts'.
	 * }
	 *
	 * @return array The modified $directory array with the 'path', 'url', and 'subdir' keys.
	 *
	 * @example:
	 * ```php
	 *   Example 1: Adding '/et-fonts' subdirectory to the upload directory
	 *
	 *   $directory = array(
	 *       'basedir' => '/var/www/uploads',
	 *       'baseurl' => 'http://example.com/uploads'
	 *   );
	 *
	 *   $modified_directory = HooksRegistration::upload_dir_font( $directory );
	 * ```
	 *
	 * @output:
	 * ```php
	 *   // The $modified_directory array will be:
	 *   array (
	 *       'basedir' => '/var/www/uploads',
	 *       'path'    => '/var/www/uploads/et-fonts',
	 *       'baseurl' => 'http://example.com/uploads',
	 *       'url'     => 'http://example.com/uploads/et-fonts',
	 *       'subdir'  => '/et-fonts',
	 *   )
	 * ```
	 */
	public static function upload_dir_font( array $directory ): array {
		$subdir = '/et-fonts';

		if ( isset( $directory['basedir'] ) ) {
			$directory['path'] = $directory['basedir'] . $subdir;
		}

		if ( isset( $directory['baseurl'] ) ) {
			$directory['url'] = $directory['baseurl'] . $subdir;
		}

		$directory['subdir'] = $subdir;

		return $directory;
	}

	/**
	 * Load and register hook functions used in Visual Builder.
	 *
	 * Adds actions to update cached assets when custom fonts are added or removed.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public function load(): void {
		// Add action to update cached assets because custom fonts are included in static helpers.
		add_action( 'divi_visual_builder_fonts_custom_font_added', 'et_fb_delete_builder_assets' );
		add_action( 'divi_visual_builder_fonts_custom_font_removed', 'et_fb_delete_builder_assets' );
	}

	/**
	 * Filters the "real" file type of the given image file.
	 *
	 * @since ??
	 *
	 * @param array  $checked_filetype_and_ext {
	 *     Values for the extension, mime type, and corrected filename.
	 *
	 *     @type string|false $ext             File extension, or false if the file doesn't match a mime type.
	 *     @type string|false $type            File mime type, or false if the file doesn't match a mime type.
	 *     @type string|false $proper_filename File name with its correct extension, or false if it cannot be determined.
	 * }
	 *
	 * @param string $file                      Full path to the file.
	 * @param string $filename                  The name of the file (may differ from $file due to
	 *                                          $file being in a tmp directory).
	 *
	 * @return array
	 */
	public static function check_filetype_and_ext_image( array $checked_filetype_and_ext, string $file, string $filename ): array {
		// Supported image mime types. This list is retrieved from the WordPress core function `wp_get_mime_types()`.
		$mimes_image = [
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpe'  => 'image/jpeg',
			'gif'  => 'image/gif',
			'png'  => 'image/png',
			'bmp'  => 'image/bmp',
			'tiff' => 'image/tiff',
			'tif'  => 'image/tiff',
			'webp' => 'image/webp',
			'avif' => 'image/avif',
			'ico'  => 'image/x-icon',
			'heic' => 'image/heic',
		];

		// Only process if the file exists and PHP extension "fileinfo" is loaded.
		if ( file_exists( $file ) && extension_loaded( 'fileinfo' ) ) {
			$ext = pathinfo( $filename, PATHINFO_EXTENSION );

			if ( $ext && isset( $mimes_image[ $ext ] ) ) {
				// Get the real mime type.
				$finfo     = finfo_open( FILEINFO_MIME_TYPE );
				$real_mime = finfo_file( $finfo, $file );
				finfo_close( $finfo );

				if ( $real_mime && $real_mime === $mimes_image[ $ext ] ) {
					return array(
						'ext'             => $ext,
						'type'            => $real_mime,
						'proper_filename' => sanitize_file_name( $filename ),
					);
				}
			}

			return array(
				'ext'             => false,
				'type'            => false,
				'proper_filename' => false,
			);
		}

		$ext  = $checked_filetype_and_ext['ext'] ?? false;
		$type = $checked_filetype_and_ext['type'] ?? false;

		if ( $ext && $type && isset( $mimes_image[ $ext ] ) && $type === $mimes_image[ $ext ] ) {
			return $checked_filetype_and_ext;
		}

		return array(
			'ext'             => false,
			'type'            => false,
			'proper_filename' => false,
		);
	}

}