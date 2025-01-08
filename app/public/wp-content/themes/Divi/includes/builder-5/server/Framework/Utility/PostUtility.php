<?php
/**
 * PostUtility class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\Framework\Utility;

use ET\Builder\Packages\Module\Layout\Components\DynamicContent\DynamicContentUtils;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

/**
 * PostUtility class.
 *
 * This class contains methods to work with Post.
 *
 * @since ??
 */
class PostUtility {

	/**
	 * Truncate post content to generate post excerpt.
	 *
	 * @since ??
	 *
	 * @param integer $amount           Amount of text that should be kept.
	 * @param boolean $echo             Whether to print the output or not.
	 * @param object  $post             Post object.
	 * @param boolean $strip_shortcodes Whether to strip the shortcodes or not.
	 * @param boolean $is_words_length  Whether to cut the text based on words length or not.
	 *
	 * @return string Generated post post excerpt.
	 */
	public static function truncate_post( $amount, $echo = true, $post = '', $strip_shortcodes = false, $is_words_length = false ) {
		global $shortname;

		if ( empty( $post ) ) {
			global $post;
		}

		if ( post_password_required( $post ) ) {
			$post_excerpt = get_the_password_form();

			if ( $echo ) {
				echo et_core_intentionally_unescaped( $post_excerpt, 'html' );
				return;
			}

			return $post_excerpt;
		}

		$post_excerpt = apply_filters( 'the_excerpt', $post->post_excerpt );

		if ( 'on' === et_get_option( $shortname . '_use_excerpt' ) && ! empty( $post_excerpt ) ) {
			if ( $echo ) {
				echo et_core_intentionally_unescaped( $post_excerpt, 'html' );
			} else {
				return $post_excerpt;
			}
		} else {
			// get the post content.
			$truncate = $post->post_content;

			// remove caption shortcode from the post content.
			$truncate = preg_replace( '@\[caption[^\]]*?\].*?\[\/caption]@si', '', $truncate );

			// remove post nav shortcode from the post content
			// TODO fix(D5, PostUtility) Replace the et_pb_post_nav with D5's post navigation module.
			$truncate = preg_replace( '@\[et_pb_post_nav[^\]]*?\].*?\[\/et_pb_post_nav]@si', '', $truncate );

			// Remove audio shortcode from post content to prevent unwanted audio file on the excerpt
			// due to unparsed audio shortcode.
			$truncate = preg_replace( '@\[audio[^\]]*?\].*?\[\/audio]@si', '', $truncate );

			// Remove embed shortcode from post content.
			$truncate = preg_replace( '@\[embed[^\]]*?\].*?\[\/embed]@si', '', $truncate );

			// Apply the content filters to the post content to parse blocks.
			$truncate = apply_filters( 'the_content', $truncate );

			// Remove script and style tags from the post content.
			$truncate = wp_strip_all_tags( $truncate );

			if ( $strip_shortcodes ) {
				$truncate = et_strip_shortcodes( $truncate );
				$truncate = DynamicContentUtils::get_strip_dynamic_content( $truncate );
			} else {
				// Check if content should be overridden with a custom value.
				$custom = apply_filters( 'et_truncate_post_use_custom_content', false, $truncate, $post );
				// apply content filters.
				$truncate = false === $custom ? apply_filters( 'the_content', $truncate ) : $custom;
			}

			/**
			 * Filter automatically generated post excerpt before it gets truncated.
			 *
			 * @since 3.17.2
			 *
			 * @param string $excerpt
			 * @param integer $post_id
			 */
			$truncate = apply_filters( 'et_truncate_post', $truncate, $post->ID );

			// decide if we need to append dots at the end of the string.
			if ( strlen( $truncate ) <= $amount ) {
				$echo_out = '';
			} else {
				$echo_out = '...';
			}

			$trim_words = '';

			if ( $is_words_length ) {
				// Reset `$echo_out` text because it will be added by wp_trim_words() with
				// default WordPress `excerpt_more` text.
				$echo_out     = '';
				$excerpt_more = apply_filters( 'excerpt_more', ' [&hellip;]' );
				$trim_words   = wp_trim_words( $truncate, $amount, $excerpt_more );
			} else {
				$trim_words = et_wp_trim_words( $truncate, $amount, '' );
			}

			// trim text to a certain number of characters, also remove spaces from the end of a string ( space counts as a character ).
			$truncate = rtrim( $trim_words );

			// remove the last word to make sure we display all words correctly.
			if ( ! empty( $echo_out ) ) {
				$new_words_array = (array) explode( ' ', $truncate );
				// Remove last word if word count is more than 1.
				if ( count( $new_words_array ) > 1 ) {
					array_pop( $new_words_array );
				}

				$truncate = implode( ' ', $new_words_array );

				// Dots should not add to empty string.
				if ( '' !== $truncate ) {
					// append dots to the end of the string.
					$truncate .= $echo_out;
				}
			}

			if ( $echo ) {
				echo et_core_intentionally_unescaped( $truncate, 'html' );
			} else {
				return $truncate;
			}
		};
	}
}
