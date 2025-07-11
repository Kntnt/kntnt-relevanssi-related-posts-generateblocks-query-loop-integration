<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Relevanssi Related Posts and GenerateBlocks Query Loop Integration.
 * Plugin URI:        https://github.com/Kntnt/kntnt-relevanssi-related-posts-generateblocks-query-loop-integration.php
 * Description:       Replaces the query configured in GenerateBlocks Query Loop with a query returning related posts from Relevanssi if the block wrapped by GenerateBlocks Query Loop (e.g. GenerateBlocks Grid) has the class `relevanssi-related-posts`.
 * Version:           1.2.1
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


defined( 'ABSPATH' ) || die;

add_filter( 'generateblocks_query_loop_args', function ( $query_args, $attributes, $block ) {

	// Abort if Relevanssi Pro is not installed.
	if ( ! function_exists( 'relevanssi_get_related_post_ids' ) ) {
		return $query_args;
	}

	// Abort if the block doen't have the magic lass `relevanssi-related-posts`.
	$has_magic_class = in_array( 'relevanssi-related-posts', explode( ' ', $block->attributes['className'] ?? '' ) );
	if ( ! $has_magic_class ) {
		return $query_args;
	}

	/**
	 * Filter that allows external to contrl whether or not to proceed with the integration.
	 *
	 * @param bool  $proceed    Whether to proceed with the integration.
	 * @param array $query_args The query arguments.
	 *
	 * @return bool Whether to proceed with the integration.
	 */
	$proceed = apply_filters( 'kntnt-relevanssi-related-posts-generateblocks-query-loop-integration', true, $query_args );
	if ( ! $proceed ) {
		return $query_args;
	}

	global $post;
	if ( isset( $post->ID ) && $post->ID ) {
		$query_args = [
			'post__in' => relevanssi_get_related_post_ids( $post->ID ),
			'orderby' => 'post__in',
			'posts_per_page' => - 1,
			'ignore_sticky_posts' => 1,
		];
	}

	return $query_args;

}, 10, 3 );
