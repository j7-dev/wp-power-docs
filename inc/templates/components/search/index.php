<?php
/**
 * 搜尋組件
 */

$search = $_GET['search'] ?? ''; // phpcs:ignore

printf(
/*html*/'
<form action="" method="get">
	<label class="pc-input pc-input-bordered flex items-center gap-2">
		<input type="text" class="grow !border-none" placeholder="搜尋" name="search" value="%1$s" />
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
$search
);
