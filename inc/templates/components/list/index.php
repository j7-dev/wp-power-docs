<?php

use J7\PowerDocs\Plugin;

global $post;

/** @var array{post: WP_Post|null} $args */
@[
	'post'    => $the_post,
] = $args;

$the_post = $the_post ?? $post;

if (!( $the_post instanceof \WP_Post )) {
	echo '$the_post 不是 WP_Post 實例';
	return;
}

$breadcrumb = Plugin::get(
	'breadcrumb',
	[
		'post'  => $the_post,
		'class' => 'text-sm mb-0',
	],
	false
);

printf(
/*html*/'
<div>
	<a href="%1$s" class="text-base-content hover:text-primary">
		<h2 class="text-lg font-bold mb-0">%2$s</h2>
	</a>
	%3$s
	<p>%4$s</p>
	<div class="pc-divider"></div>
</div>
',
get_the_permalink($the_post->ID),
$the_post->post_title,
$breadcrumb,
substr(\wp_strip_all_tags($the_post->post_content), 0, 100)
);
