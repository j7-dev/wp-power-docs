<?php
/**
 * 知識庫首頁
 */

use J7\PowerDocs\Plugin;
use J7\PowerDocs\Resources\Doc\CPT;

global $post;

$children_posts = get_posts(
	[
		'post_type'      => CPT::POST_TYPE,
		'post_parent'    => $post->ID,
		'posts_per_page' => -1,
		'orderby'     => [
			'menu_order' => 'ASC',
			'ID'         => 'ASC',
			'date'       => 'ASC',
		],
	]
	);

// HERO 區塊
printf(
/*html*/'
<div
	class="pc-hero"
	style="background-image: url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp);">
	<div class="pc-hero-overlay bg-opacity-60"></div>
	<div class="pc-hero-content w-[80rem] text-neutral-content text-center py-24">
	<div class="w-full">
		<h1 class="mb-5 text-3xl font-bold text-base-100">%1$s</h1>
		<p class="mb-5 text-base-300">%2$s</p>
		<label class="pc-form-control">
			<label class="pc-input pc-input-bordered flex items-center gap-2">
				<input type="text" class="grow !border-none" placeholder="搜尋" />
				<svg
					xmlns="http://www.w3.org/2000/svg"
					viewBox="0 0 16 16"
					class="h-4 w-4 opacity-70 fill-gray-400">
					<path
						fill-rule="evenodd"
						d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
						clip-rule="evenodd" />
				</svg>
			</label>
			<div class="pc-label">
				<span class="pc-label-text-alt text-base-300">大家都再搜：
					<div class="pc-badge pc-badge-ghost pc-badge-sm">基本操作</div>
					<div class="pc-badge pc-badge-ghost pc-badge-sm">我可以進入你的 wp-admin 嗎</div>
					<div class="pc-badge pc-badge-ghost pc-badge-sm">蛇出來了</div>
				</span>
				<span class="pc-label-text-alt text-base-300">搜尋歷史</span>
			</div>
		</label>
	</div>
	</div>
</div>
',
$post->post_title,
$post->post_excerpt
);

echo /* html */'<div class="container mx-auto mt-8">';

// 所有分類區塊
echo /* html */'<h6 class="text-2xl text-content mb-6">瀏覽所有分類</h6>';
echo /* html */'<div class="grid grid-cols-3 gap-14">';

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
