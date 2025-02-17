<?php
/**
 * Template for single pd_doc
 */

use J7\PowerDocs\Plugin;
use J7\PowerDocs\Domains\Doc\Access;
use J7\Powerhouse\Domains\Post\Utils as PostUtils;
use J7\Powerhouse\Theme\FrontEnd as Theme;

global $post;

$parent_id = $post->post_parent ? PostUtils::get_top_post_id($post->ID) : $post->ID;

$can_access = Access::can_access( (int) $parent_id);
// 判斷用戶是否為 admin
$is_admin = \current_user_can('administrator');

if (!$can_access && !$is_admin) {
	// 沒有權限，跳到404
	$unauthorized_redirect_url = get_post_meta($parent_id, 'unauthorized_redirect_url', true) ?: site_url('404');
	/** @var string $unauthorized_redirect_url */
	wp_safe_redirect($unauthorized_redirect_url);
	exit;
}

$search = $_GET['search'] ?? '';//phpcs:ignore

get_header();

echo '<div class="bg-base-200 pb-20">';

if ($search) {
	Plugin::get('doc-search');
} else {
	// 如果是頂層就顯示 doc-landing，否則顯示 doc-detail
	Plugin::get($post->post_parent ? 'doc-detail' : 'doc-landing');
}

echo '</div>';

Theme::render_button();



get_footer();
