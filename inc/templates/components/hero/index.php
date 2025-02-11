<?php

use J7\PowerDocs\Plugin;
use J7\PowerDocs\Resources\Doc\Utils;
global $post;

/** @var array{post: \WP_Post} $args */
@[
	'post' => $the_post,
] = $args;

// @phpstan-ignore-next-line
$the_post = $the_post ?? $post;

if ( ! ( $the_post instanceof \WP_Post ) ) {
	echo 'Hero 區塊錯誤：$the_post 不是 WP_Post 實例';
	return;
}

$top_parent_id = Utils::get_top_doc_id( $the_post->ID );
$top_parent_id = $top_parent_id ?? $the_post->ID;

// TEST
$badges = [
	'基本操作',
	'我可以進入你的 wp-admin 嗎',
	'蛇出來了',
];
$bg_img = 'https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp';

$badge_html = '<span class="pc-label-text-alt text-base-300">大家都再搜：';
foreach ($badges as $badge) {
	$badge_html .= sprintf(
		'<div class="pc-badge pc-badge-ghost pc-badge-sm mr-2">%s</div>',
		$badge
	);
}
$badge_html .= '</span>';


// HERO 區塊
printf(
	/*html*/'
	<div
		class="pc-hero"
		style="background-image: url(%5$s);">
		<div class="pc-hero-overlay bg-opacity-60"></div>
		<div class="pc-hero-content w-[80rem] text-neutral-content text-center py-24">
		<div class="w-full">
			<h1 class="mb-5 text-3xl font-bold text-base-100">%1$s</h1>
			<p class="mb-5 text-base-300">%2$s</p>
			<label class="pc-form-control">
				%3$s
				<div class="pc-label">
					%4$s
				</div>
			</label>
		</div>
		</div>
	</div>
	',
	$the_post->post_title,
	$the_post->post_excerpt,
	Plugin::get('search', [], false),
	$badge_html,
	$bg_img
	);
