<?php
/**
 * Front-End Resource Aggregator
 *
 * This class provides a mechanism to collect and merge CSS and JS content from across the page,
 * packaging it into .css and .js files linked to a specific post ID. It then enqueues these
 * resources for page loading, effectively reducing inline CSS/JS rendering and mitigating the
 * "Flash of Unstyled Content" issue, thereby improving page load speed.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\FrontEnd\ResourceAggregator;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Framework\Utility\Conditions;

/**
 * A class which collects and merges CSS/JS, reducing inline code and improving load speed.
 *
 * This class provides a mechanism to collect and merge CSS and JS content from across the page,
 * packaging it into .css and .js files linked to a specific post ID. It then enqueues these
 * resources for page loading, effectively reducing inline CSS/JS rendering and mitigating the
 * "Flash of Unstyled Content" issue, thereby improving page load speed.
 *
 * @since ??
 */
class ResourceAggregator implements DependencyInterface {
	/**
	 * The cache directory path.
	 *
	 * @since ??
	 * @var string
	 */
	private static $_cache_dir_path;

	/**
	 * The collected CSS content, stores all the CSS content passed to ResourceAggregator class.
	 *
	 * @since ??
	 * @var string
	 */
	private static $_css_content;

	/**
	 * Initialization state of this class.
	 *
	 * @var bool
	 */
	private static $_is_initialized = false;

	/**
	 * Initializes the resource aggregator by setting up the cache directory and hooking into WordPress actions.
	 *
	 * @since ??
	 */
	public function load() {
		if ( ET_BUILDER_5_EXPERIMENTS_STATIC_CSS === false ) {
			return;
		}

		if ( self::$_is_initialized ) {
			return;
		}

		if ( ! Conditions::is_d5_enabled() ) {
			return;
		}

		/**
		 * Check to make sure we are on FE.
		 * Note: This a temporarily check until the issue with Conditions::is_vb_enabled not working correctly is handled.
		 */
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- ignored intentionally, check note above.
		$is_on_vb = isset( $_GET['et_fb'] ) && current_user_can( 'edit_posts' );
		if ( $is_on_vb ) {
			return;
		}

		// Setup the cache directory path.
		self::$_cache_dir_path = self::create_cache_directory();

		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_post_specific_css' ] );

		add_action( 'wp_footer', [ __CLASS__, 'create_and_assign_post_cache_file' ], PHP_INT_MAX );

		add_action( 'save_post', [ __CLASS__, 'purge_cache_on_post_update' ] );

		// Set class as initialized so loading it again wouldn't cause problems.
		self::$_is_initialized = true;
	}

	/**
	 * Purges the cache for a specific post update.
	 *
	 * @since ??
	 * @param int $post_id The ID of the post for which the cache should be purged.
	 */
	public static function purge_cache_on_post_update( $post_id ) {
		self::remove_post_cache_file( $post_id );
	}

	/**
	 * Creates a directory for caching if it doesn't exist.
	 *
	 * @since ??
	 * @return string The path to the created cache directory.
	 */
	public static function create_cache_directory() {
		// Get the upload directory info in a multisite compatible way.
		$upload   = wp_get_upload_dir();
		$base_dir = $upload['basedir'];

		$cache_directory = $base_dir . '/temp_cache';

		// If dir doesn't exist create it.
		if ( ! file_exists( $cache_directory ) ) {
			wp_mkdir_p( $cache_directory );

			// Prevent directory listings by placing an empty index.php file.
			if ( ! file_exists( $cache_directory . '/index.php' ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents -- ignored intentionally, prototype version.
				file_put_contents( $cache_directory . '/index.php', '<?php // Silence is golden' );
			}
		}

		return $cache_directory;
	}

	/**
	 * Creates and assigns a cache file for the current post's CSS content.
	 *
	 * If a cache file for the current post doesn't exist, this method creates it, writes the collected CSS content to it,
	 * and updates the post meta to include the path to the cache file.
	 *
	 * @since ??
	 */
	public static function create_and_assign_post_cache_file() {
		$post_id = get_queried_object_id();

		$cache_file_path = self::get_cache_file_path( $post_id );

		if ( ! file_exists( $cache_file_path ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents -- ignored intentionally, prototype version.
			file_put_contents( $cache_file_path, self::$_css_content );

			update_post_meta( $post_id, '_post_specific_css_file', str_replace( WP_CONTENT_DIR, '', $cache_file_path ) );
		}
	}

	/**
	 * Enqueues the post-specific CSS file for the current post.
	 *
	 * This method checks if there's a cached CSS file for the current post and enqueues it if present.
	 *
	 * @since ??
	 * @param int $post_id The ID of the post for which to enqueue the CSS.
	 */
	public static function enqueue_post_specific_css( $post_id ) {
		$post_id = get_queried_object_id();

		if ( self::has_post_cached_css_file( $post_id ) ) {
			$relative_path = get_post_meta( $post_id, '_post_specific_css_file', true );

			wp_enqueue_style( 'post-specific-css-' . $post_id, content_url( $relative_path ), [], '1.0.0' );
		}
	}

	/**
	 * Gets the cache file path for a specific post.
	 *
	 * @since ??
	 * @param int $post_id The ID of the post for which to get the cache file path.
	 * @return string The path to the cache file for the specified post.
	 */
	public static function get_cache_file_path( $post_id ) {
		return self::$_cache_dir_path . '/post-' . $post_id . '.css';
	}

	/**
	 * Removes the cache file for a specific post.
	 *
	 * @since ??
	 * @param int $post_id The ID of the post for which to remove the cache file.
	 */
	public static function remove_post_cache_file( $post_id ) {
		$cache_file = self::get_cache_file_path( $post_id );
		if ( file_exists( $cache_file ) ) {
			unlink( $cache_file );
			delete_post_meta( $post_id, '_post_specific_css_file' );
		}
	}

	/**
	 * Adds CSS content to be cached.
	 *
	 * This method appends the given CSS content to the current collection of CSS to be cached.
	 *
	 * @since ??
	 * @param string $css The CSS content to add to the cache.
	 */
	public static function add_css( $css ) {
		self::$_css_content .= $css;
	}

	/**
	 * Checks if there is a cached CSS file for a specific post.
	 *
	 * @since ??
	 * @param int $post_id The ID of the post to check for a cached CSS file.
	 * @return bool True if a cached CSS file exists for the post, false otherwise.
	 */
	public static function has_post_cached_css_file( $post_id ) {
		$post_has_assigned_cache_file = get_post_meta( $post_id, '_post_specific_css_file', true );
		$cache_file                   = self::get_cache_file_path( $post_id );

		if ( file_exists( $cache_file ) && $post_has_assigned_cache_file ) {
			return true;
		}

		return false;
	}

	/**
	 * Purges all caches managed by this class.
	 *
	 * This method removes all files in the cache directory, effectively clearing the cache.
	 *
	 * @since ??
	 */
	public static function purge_all_caches() {
		$files = glob( self::$_cache_dir_path . '/*' );
		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}
	}
}
