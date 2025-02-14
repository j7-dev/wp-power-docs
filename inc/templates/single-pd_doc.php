<?php
/**
 * Template for single pd_doc
 */

use J7\PowerDocs\Plugin;
use J7\Powerhouse\Domains\Post\Utils as PostUtils;
use J7\PowerDocs\Domains\Doc\Access;

global $post;

$parent_id = $post->post_parent ? PostUtils::get_top_post_id($post->ID) : $post->ID;

$can_access = Access::can_access( (int) $parent_id);

if (!$can_access) {
	// 沒有權限，跳到404
	wp_safe_redirect(home_url('404'));
	exit;
}

$search = $_GET['search'] ?? '';//phpcs:ignore

get_header();

if ($search) {
	Plugin::get('doc-search');
} else {
	// 如果是頂層就顯示 doc-landing，否則顯示 doc-detail
	Plugin::get($post->post_parent ? 'doc-detail' : 'doc-landing');
}

get_footer();
