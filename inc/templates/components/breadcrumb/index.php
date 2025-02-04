<?php
/**
 * 麵包屑
 */

use J7\Powerhouse\Resources\Post\Utils as PostUtils;
use J7\PowerDocs\Resources\Doc\Utils;

global $post;
$parent_id = PostUtils::get_top_post_id($post->ID);

if (!$parent_id) {
	// 如果沒有父章節，就跳出
	return;
}

$breadcrumb_post_ids = Utils::get_breadcrumb_post_ids($post->ID, $parent_id);

echo /*html*/ '
<div class="pc-breadcrumbs text-sm mb-8">
	<ul class="pl-0">';

foreach ($breadcrumb_post_ids as $key => $breadcrumb_post_id) {
	/** @var \WP_Post|null $breadcrumb_post */
	$breadcrumb_post = get_post($breadcrumb_post_id);
	if (!$breadcrumb_post) {
		continue;
	}

	printf(
	/*html*/'<li><a class="text-base-content/75 hover:text-primary flex gap-x-1" style="text-decoration: none;" href="%1$s">%2$s%3$s</a></li>
	',
	get_the_permalink($breadcrumb_post_id),
	$key === 0 ? /*html*/'
	<svg
	xmlns="http://www.w3.org/2000/svg"
	fill="none"
	viewBox="0 0 24 24"
	class="h-4 w-4 stroke-current">
		<path
		stroke-linecap="round"
		stroke-linejoin="round"
		stroke-width="2"
		d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
	</svg>' : '',
	$breadcrumb_post->post_title
	);
}

echo /*html*/ '
	</ul>
</div>';
