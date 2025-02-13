<?php
/**
 * 知識庫首頁
 */

use J7\PowerDocs\Plugin;
use J7\PowerDocs\Resources\Doc\CPT;
use J7\Powerhouse\Resources\Post\Utils as PostUtils;

global $post;
$parent_id        = $post->post_parent ? PostUtils::get_top_post_id($post->ID) : $post->ID;
$all_children_ids = PostUtils::get_flatten_post_ids( (int) $parent_id);

$search = $_GET['search'] ?? ''; // phpcs:ignore
$to     = $_GET['to'] ?? 1; // phpcs:ignore

$query = new \WP_Query(
	[
		'post_type'      => CPT::POST_TYPE,
		'posts_per_page' => 20,
		'paged'          => $to,
		's'              => $search,
		'post__in'       => [
			$parent_id,
			...$all_children_ids,
		],
	]
);

$search_posts = $query->posts;

Plugin::get('hero');

echo /* html */'<div class="container mx-auto mt-8 px-4">';


Plugin::get('breadcrumb/search');

// 所有分類區塊
printf(
/*html*/'
<h6 class="text-lg md:text-2xl text-content mb-6">所有與 %1$s 相關的結果</h6>
',
$search
);

foreach ($search_posts as $search_post) {
	Plugin::get(
		'list',
		[
			'post' => $search_post,
		]
		);
}

echo '<div class="flex justify-center my-8">';
Plugin::get(
	'pagination',
	[
		'query' => $query,
	]
	);
echo '</div>';

echo /* html */'</div>';
