<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Relevanssi Related Posts and GenerateBlocks Query Loop Integration.
 * Plugin URI:        https://github.com/Kntnt/kntnt-relevanssi-related-posts-generateblocks-query-loop-integration.php
 * Description:       Replaces the query configured in GenerateBlocks Query Loop with a query returning related posts from Relevanssi if the block wrapped by GenerateBlocks Query Loop (e.g. GenerateBlocks Grid) has the class `relevanssi-related-posts`.
 * Version:           1.1.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


defined( 'ABSPATH' ) || die;

add_filter( 'generateblocks_query_loop_args', function ( $query_args, $attributes, $block ) {
	global $post;
	if ( apply_filters( 'kntnt-relevanssi-related-posts-generateblocks-query-loop-integration', true, $query_args ) && isset( $post->ID ) && in_array( 'relevanssi-related-posts', explode( ' ', $block->attributes['className'] ?? '' ) ) && function_exists( 'relevanssi_get_related_post_ids' ) ) {
		$query_args = [
			'post__in' => relevanssi_get_related_post_ids( $post->ID ),
		];
	}
	return $query_args;
}, 10, 3 );
