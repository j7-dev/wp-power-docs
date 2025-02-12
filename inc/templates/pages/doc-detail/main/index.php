<?php
/**
 * Main 主體
 *  - 麵包屑
 *  - 內文
 *  - 子章節
 */

use J7\PowerDocs\Plugin;

global $post;



echo /*html*/'<div class="px-8 pb-6 pt-0">';

echo /*html*/'<div class="flex justify-end">';
Plugin::get(
	'search',
	[
		'class' => 'w-[20rem]',
	]
	);
echo /*html*/'</div>';

Plugin::get('breadcrumb');

printf(
/*html*/'<h1 class="text-4xl font-black mb-20">%1$s</h1>
',
$post->post_title
);

echo '<div class="bn-container">';
the_content();
echo '</div>';

echo /*html*/'<div class="pc-divider my-6"></div>';

Plugin::get('related-posts/children');
Plugin::get('related-posts/prev-next');

echo /*html*/'<div class="pc-divider mt-6"></div>';

printf(
/*html*/'<p class="text-sm text-base-content/75">最近修改：%1$s</p>
',
get_the_modified_time('Y-m-d H:i')
);

echo /*html*/'</div>';
