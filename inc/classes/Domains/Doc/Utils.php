<?php

declare(strict_types=1);

namespace J7\PowerDocs\Domains\Doc;

/**
 * Class Utils
 */
abstract class Utils {


	/**
	 * 取得快取 key
	 *
	 * @param int    $post_id 章節 ID.
	 * @param string $key 快取 key.
	 * @return string
	 */
	public static function get_cache_key( int $post_id, string $key = 'get_children_posts_html' ): string {
		return "power_docs_{$key}_{$post_id}";
	}

	/**
	 * 取得子章節的 HTML
	 *
	 * @param int                       $post_id 章節 ID.
	 * @param array<int, \WP_Post>|null $children_posts 子章節.
	 * @param int                       $depth 深度.
	 * @return string
	 */
	public static function get_children_posts_html_uncached( int $post_id, array $children_posts = null, $depth = 0 ): string {
		global $post; // 當前文章

		$html           = '';
		$children_posts = $children_posts === null ? \get_posts(
			[
				'post_type'      => CPT::POST_TYPE,
				'post_parent'    => $post_id,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => [
					'menu_order' => 'ASC',
					'ID'         => 'DESC',
					'date'       => 'DESC',
				],
			]
			) : $children_posts;

		if (!$children_posts) {
			return '';
		}

		$html .= sprintf(
		/*html*/'<ul class="m-0 p-0 list-none" %1$s>',
			$depth > 0 ? 'style="display: none;"' : ''
		);
		foreach ($children_posts as $child_post) {

			// 取得子章節的子章節
			$child_children_posts = \get_posts(
			[
				'post_type'      => CPT::POST_TYPE,
				'post_parent'    => $child_post->ID,
				'posts_per_page' => -1,
				'orderby'        => [
					'menu_order' => 'ASC',
					'ID'         => 'DESC',
					'date'       => 'DESC',
				],
			]
			);

			$html .= sprintf(
			/*html*/'
			<li data-post-id="%5$s" data-href="%1$s" class="hover:bg-primary/10 pr-2 transition-all duration-300 rounded-btn cursor-pointer flex items-center justify-between text-sm mb-1 py-2 %6$s" style="padding-left: %4$s;">
				<span>%2$s</span>
				%3$s
			</li>
			',
			\get_the_permalink($child_post->ID),
			$child_post->post_title,
				// 如果有子章節，就顯示箭頭
			$child_children_posts ? /*html*/'
				<div class="px-2 icon-arrow flex items-center">
					<svg class="w-4 h-4 fill-base-content" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g stroke-width="0"></g><g stroke-linecap="round" stroke-linejoin="round"></g><g> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.29289 4.29289C8.68342 3.90237 9.31658 3.90237 9.70711 4.29289L16.7071 11.2929C17.0976 11.6834 17.0976 12.3166 16.7071 12.7071L9.70711 19.7071C9.31658 20.0976 8.68342 20.0976 8.29289 19.7071C7.90237 19.3166 7.90237 18.6834 8.29289 18.2929L14.5858 12L8.29289 5.70711C7.90237 5.31658 7.90237 4.68342 8.29289 4.29289Z"></path> </g></svg>
				</div>
			' : '',
			( $depth + 1 ) . 'rem',
			$child_post->ID,
			$child_post->ID === $post->ID ? 'bg-primary/10 font-bold [&>a]:text-primary' : 'font-normal [&>a]:text-base-content' // 如果是當前文章，就顯示 primary 顏色
			);

			// 沒有子章節就結束
			if (!$child_children_posts) {
				continue;
			}

			// 有子章節就遞迴取得子章節的子章節
			$html .= self::get_children_posts_html_uncached($child_post->ID, $child_children_posts, $depth + 1);
		}
		$html .= /* html */'</ul>';

		return $html;
	}
}
