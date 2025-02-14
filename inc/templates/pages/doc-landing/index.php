<?php
/**
 * 知識庫首頁
 */

use J7\PowerDocs\Plugin;
use J7\PowerDocs\Domains\Doc\CPT;

global $post;

$editor = get_post_meta( $post->ID, 'editor', true );

// 如果是 elementor 編輯或者 elementor 預覽，就用 the_content
if (isset($_GET['elementor-preview']) || $editor === 'elementor') {
	the_content();
	return;
}

$children_posts = get_posts(
	[
		'post_type'      => CPT::POST_TYPE,
		'post_parent'    => $post->ID,
		'posts_per_page' => -1,
		'orderby'        => [
			'menu_order' => 'ASC',
			'ID'         => 'ASC',
			'date'       => 'ASC',
		],
	]
	);

Plugin::get('hero');

echo /* html */'<div class="container mx-auto mt-8 px-4">';

// 所有分類區塊
echo /* html */'<h6 class="text-lg md:text-2xl text-content mb-6">瀏覽所有分類</h6>';
echo /* html */'<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-8 xl:gap-14">';

foreach ($children_posts as $child_post) {
	Plugin::get(
		'card',
		[
			'post' => $child_post,
		]
		);
}

echo /* html */'</div>';

echo /* html */'</div>';
