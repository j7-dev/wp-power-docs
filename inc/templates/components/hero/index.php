<?php

use J7\PowerDocs\Plugin;
use J7\Powerhouse\Domains\Post\Utils as PostUtils;

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

$top_parent_id = PostUtils::get_top_post_id( $the_post->ID );

/** @var array<array{id: string, title: string}>|'' $badges */
$badges = get_post_meta( $top_parent_id, 'pd_keywords', true );
$badges = is_array( $badges ) ? $badges : [];

$bg_img_id  = get_post_meta( $top_parent_id, 'bg_images', true );
$bg_img_url = $bg_img_id ? wp_get_attachment_url( (int) $bg_img_id) : 'https://picsum.photos/1920/380';

$badge_html = sprintf(
	/*html*/'<span class="pc-keywords pc-label-text-alt text-base-300 text-left"><span class="mr-2">%s</span>',
	(string) get_post_meta( $top_parent_id, 'pd_keywords_label', true )
);

foreach ($badges as $badge) {
	$badge_title = $badge['title'];
	if ($badge_title) {
		$badge_html .= sprintf(
		'<div class="pc-badge pc-badge-ghost pc-badge-sm mr-2 mb-2">%s</div>',
		\esc_html( $badge_title )
		);
	}
}
$badge_html .= '</span>';


// HERO 區塊
printf(
	/*html*/'
	<div
		class="pc-hero"
		style="background-image: url(%4$s);">
		<div class="pc-hero-overlay bg-opacity-60"></div>
		<div class="pc-hero-content w-full xl:w-[80rem] text-neutral-content text-center py-24">
		<div class="w-full">
			<h1 class="pc-hero__title mb-5 text-2xl md:text-3xl font-bold text-base-100">%1$s</h1>
			<p class="pc-hero__excerpt mb-5 text-sm md:text-base text-base-300">%2$s</p>
			%3$s
		</div>
		</div>
	</div>
	',
	$the_post->post_title,
	$the_post->post_excerpt,
	Plugin::get(
		'search/with-keywords',
		[
			'post' => $the_post,
		],
		false
		),
	$bg_img_url
	);
