<?php
/**
 * 顯示子文章
 */

use J7\PowerDocs\Domains\Doc\CPT;

global $post;

/** @var array<int, WP_Post> $children_posts */
$children_posts = \get_children(
	[
		'post_parent' => $post->ID,
		'post_type'   => CPT::POST_TYPE,
		'post_status' => 'publish',
		'numberposts' => -1,
		'orderby'     => [
			'menu_order' => 'ASC',
			'ID'         => 'ASC',
			'date'       => 'ASC',
		],
	]
);


if (!$children_posts) {
	return;
}

echo '<h3 class="text-lg md:text-2xl font-black mb-4">相關文章</h3>';
echo '<div class="grid grid-cols-2 xl:grid-cols-4 gap-2 md:gap-4 mb-12">';

foreach ($children_posts as $child_post) {
	printf(
	/*html*/'
	<a href="%1$s" class="group w-full rounded-box border border-solid border-base-content/30 p-4">
		<p class="m-0 text-sm md:text-base text-base-content group-hover:text-primary">%2$s</p>
	</a>
	',
	get_the_permalink($child_post->ID),
	$child_post->post_title
	);
}

echo '</div>';
