<?php
/**
 * Main 主體
 *  - 麵包屑
 *  - 內文
 *  - 子章節
 */

use J7\Powerhouse\Plugin as Powerhouse;

global $post;

/** @var array{content: string} $args */
@[
	'content' => $content,
] = $args;

echo /*html*/'<div class="px-0 xl:px-8 pb-6 pt-0">';

echo /*html*/'<div class="flex justify-end">';
Powerhouse::load_template(
	'search',
	[
		'class' => 'w-full md:w-[20rem] mb-4 md:mb-0',
	]
	);
echo /*html*/'</div>';

Powerhouse::load_template('breadcrumb');

printf(
/*html*/'<h1 class="text-2xl md:text-4xl font-black mb-10 md:mb-20">%1$s</h1>
',
$post->post_title
);

echo '<div class="bn-container">';
// 如果是 elementor 編輯或者 elementor 預覽，就用 the_content
if (isset($_GET['elementor-preview']) || !$content) {
	the_content();
} else {
	echo $content;
}
echo '</div>';

echo /*html*/'<div class="pc-divider my-6"></div>';

Powerhouse::load_template('related-posts/children');
Powerhouse::load_template('related-posts/prev-next');

echo /*html*/'<div class="pc-divider mt-6"></div>';

printf(
/*html*/'<p class="text-sm text-base-content/75">最近修改：%1$s</p>
',
get_the_modified_time('Y-m-d H:i')
);

echo /*html*/'</div>';
