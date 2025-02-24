<?php
/**
 * 搜尋組件
 */

use J7\PowerDocs\Domains\Doc\Utils;

$search = $_GET['search'] ?? ''; // phpcs:ignore

/** @var array{class: string|null} $args */
@[
	'class' => $class,
] = $args;

global $post;
$top_parent_id = Utils::get_top_doc_id( $post->ID );
$top_parent_id = $top_parent_id ?? $post->ID;

printf(
/*html*/'
<form action="%1$s" method="get" class="%3$s">
	<label class="pc-input pc-input-bordered flex items-center gap-2">
		<input type="text" class="pc-search-input grow !border-none h-fit bg-inherit" placeholder="搜尋" name="search" value="%2$s" />
		<button type="submit" class="!bg-transparent !border-none !outline-none !m-0 !p-4">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 16 16"
				class="h-4 w-4 opacity-70 fill-gray-400">
				<path
					fill-rule="evenodd"
					d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
					clip-rule="evenodd" />
			</svg>
		</button>
	</label>
</form>
',
get_the_permalink( $top_parent_id ),
$search,
$class ?? '',
);
