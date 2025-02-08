<?php
/**
 * Doc Utils
 */

declare(strict_types=1);

namespace J7\PowerDocs\Resources\Doc;

use J7\WpUtils\Classes\WP;

/**
 * Class Utils
 */
abstract class Utils {

	const TEMPLATE = '';

	/**
	 * Create a new doc
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_insert_post/
	 *
	 * 簡單的新增，沒有太多參數，所以不使用 Converter
	 *
	 * @param array<string, mixed> $args Arguments.
	 *
	 * @return int|\WP_Error
	 */
	public static function create_doc( array $args = [] ): int|\WP_Error {
		$args['post_title']    = $args['post_title'] ?? '新文件';
		$args['post_status']   = 'publish';
		$args['post_author']   = \get_current_user_id();
		$args['post_type']     = CPT::POST_TYPE;
		$args['page_template'] = self::TEMPLATE;

		/** @var array{ID?: int, post_author?: int, post_date?: string, post_date_gmt?: string, post_content?: string, post_content_filtered?: string, post_title?: string, post_excerpt?: string, ...} $args */
		return \wp_insert_post($args);
	}

	/**
	 * Format Doc details
	 * WP_Post 轉 array
	 *
	 * @param \WP_Post $post             Doc.
	 * @param bool     $with_description With description.
	 * @param int      $depth            Depth.
	 *
	 * @return array{
	 *  id: string,
	 *  type: string,
	 *  depth: int,
	 *  name: string,
	 *  slug: string,
	 *  date_created: string,
	 *  date_modified: string,
	 *  status: string,
	 *  menu_order: int,
	 *  permalink: string,
	 *  category_ids: string[],
	 *  tag_ids: string[],
	 *  images: array<array{id: string, url: string, width: int, height: int, alt: string}>,
	 *  parent_id: string,
	 *  sub_docs?: array<array{id: string, type: string, depth: int, name: string, slug: string, date_created: string, date_modified: string, status: string, menu_order: int, permalink: string, category_ids: string[], tag_ids: string[], images: array<array{id: string, url: string, width: int, height: int, alt: string}>, parent_id: string}>,
	 *  description?: string,
	 *  short_description?: string,
	 * }
	 */
	public static function format_doc_details(
		\WP_Post $post,
		?bool $with_description = false,
		?int $depth = 0
	) {
		$date_created  = $post->post_date;
		$date_modified = $post->post_modified;

		$image_id  = \get_post_thumbnail_id($post->ID);
		$image_ids = [ $image_id ];
		$images    = array_map([ WP::class, 'get_image_info' ], $image_ids); // @phpstan-ignore-line

		$description_array = $with_description ? [
			'description'       => $post->post_content,
			'short_description' => $post->post_excerpt,
		] : [];

		$sub_docs = array_values(
			\get_children(
				[
					'post_parent' => $post->ID,
					'post_type'   => CPT::POST_TYPE,
					'numberposts' => -1,
					'post_status' => 'any',
					'orderby'     => [
						'menu_order' => 'ASC',
						'ID'         => 'ASC',
						'date'       => 'ASC',
					],
				]
			)
		);
		$sub_docs = array_values(
			array_map(
			[ __CLASS__, 'format_doc_details' ], // @phpstan-ignore-line
			$sub_docs,
			array_fill(0, count($sub_docs), false),
				array_fill(0, count($sub_docs), $depth + 1)
			)
		);

		$children = !!$sub_docs ? [
			'sub_docs' => $sub_docs,
		] : [];

		$base_array = [
			// Get Product General Info
			'id'            => (string) $post->ID,
			'depth'         => $depth,
			'name'          => $post->post_title,
			'slug'          => $post->post_name,
			'date_created'  => $date_created,
			'date_modified' => $date_modified,
			'status'        => $post->post_status,
			'menu_order'    => (int) $post->menu_order,
			'permalink'     => \get_permalink($post->ID),
			'category_ids'  => [],
			'tag_ids'       => [],
			'images'        => $images,
			'parent_id'     => (string) $post->post_parent,
		] + $children;

		// @phpstan-ignore-next-line
		return array_merge(
			$description_array,
			$base_array
		);
	}

	/**
	 * Sort docs
	 * 改變文件順序
	 *
	 * @param array{from_tree: array<array{id: string}>, to_tree: array<array{id: string}>} $params Parameters.
	 *
	 * @return true|\WP_Error
	 */
	public static function sort_docs( array $params ): bool|\WP_Error {
		$from_tree = $params['from_tree'] ?? []; // @phpstan-ignore-line
		$to_tree   = $params['to_tree'] ?? []; // @phpstan-ignore-line

		$delete_ids = [];
		foreach ($from_tree as $from_node) {
			$from_id = $from_node['id'];
			$to_node = array_filter($to_tree, fn ( $node ) => $node['id'] === $from_id);
			if (empty($to_node)) {
				$delete_ids[] = $from_id;
			}
		}
		foreach ($to_tree as $node) {
			$to_id          = $node['id'];
			$is_new_chapter = strpos($to_id, 'new-') === 0; // 用 new- 開頭的 id 是新章節
			$args           = self::converter($node);

			if ($is_new_chapter) {
				$insert_result = self::create_doc($args);
			} else {
				$insert_result = self::update_chapter($to_id, $args);
			}
			if (\is_wp_error($insert_result)) {
				return $insert_result;
			}
		}

		foreach ($delete_ids as $id) {
			\wp_trash_post( (int) $id );
		}

		return true;
	}

	/**
	 * Converter 轉換器
	 * 把 key 轉換/重新命名，將 前端傳過來的欄位轉換成 wp_update_post 能吃的參數
	 *
	 * 前端圖片欄位就傳 'image_ids' string[] 就好
	 *
	 * @param array{id?: string, depth?: int, name?: string, slug?: string, description?: string, short_description?: string, status?: string, category_ids?: string[], tag_ids?: string[], parent_id?: string} $args    Arguments.
	 *
	 * @return array{ID?: string, post_title?: string, post_name?: string, post_content?: string, post_excerpt?: string, post_status?: string, post_category?: string[], tags_input?: string[], post_parent?: string}
	 */
	public static function converter( array $args ): array {

		unset($args['id']); // 不存 id
		unset($args['depth']); // 不存 depth

		$fields_mapper = [
			'id'                => 'ID',
			'name'              => 'post_title',
			'slug'              => 'post_name',
			'description'       => 'post_content',
			'short_description' => 'post_excerpt',
			'status'            => 'post_status',
			'category_ids'      => 'post_category',
			'tag_ids'           => 'tags_input',
			'parent_id'         => 'post_parent',
		];

		$formatted_args = [];
		foreach ($args as $key => $value) {
			if (in_array($key, array_keys($fields_mapper), true)) {
				$formatted_args[ $fields_mapper[ $key ] ] = $value;
			} else {
				$formatted_args[ $key ] = $value;
			}
		}

		/** @var array{ID?: string, post_title?: string, post_name?: string, post_content?: string, post_excerpt?: string, post_status?: string, post_category?: string[], tags_input?: string[], post_parent?: string} $formatted_args */
		return $formatted_args;
	}

	/**
	 * Update a chapter
	 *
	 * @param string               $id   chapter id.
	 * @param array<string, mixed> $args Arguments.
	 *
	 * @return integer|\WP_Error
	 */
	public static function update_chapter( string $id, array $args ): int|\WP_Error {

		$args['ID']            = $id;
		$args['post_title']    = $args['post_title'] ?? '新章節';
		$args['post_status']   = $args['status'] ?? 'publish';
		$args['post_author']   = \get_current_user_id();
		$args['post_type']     = CPT::POST_TYPE;
		$args['page_template'] = self::TEMPLATE;

		/** @var array{ID?: int, post_author?: int, post_date?: string, post_date_gmt?: string, post_content?: string, post_content_filtered?: string, post_title?: string, post_excerpt?: string, ...} $args */
		$update_result = \wp_update_post($args);

		return $update_result;
	}

	/**
	 * 取得最上層的文件 ID
	 *
	 * @param int $doc_id 文件 ID.
	 * @return int|null
	 */
	public static function get_top_doc_id( int $doc_id ): int|null {
		$ancestors = \get_post_ancestors( $doc_id );
		if ( empty( $ancestors ) ) {
			return null;
		}
		// 取最後一個
		return $ancestors[ count( $ancestors ) - 1 ] ?? null;
	}


	/**
	 * 取得子章節的 HTML
	 *
	 * @param int                       $post_id 章節 ID.
	 * @param array<int, \WP_Post>|null $children_posts 子章節.
	 * @param int                       $depth 深度.
	 * @return string
	 */
	public static function get_children_posts_html( int $post_id, array $children_posts = null, $depth = 0 ): string {
		global $post;

		$html           = '';
		$children_posts = $children_posts === null ? get_posts(
			[
				'post_type'      => CPT::POST_TYPE,
				'post_parent'    => $post_id,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => [
					'menu_order' => 'ASC',
					'ID'         => 'ASC',
					'date'       => 'ASC',
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
			$child_children_posts = get_posts(
			[
				'post_type'      => CPT::POST_TYPE,
				'post_parent'    => $child_post->ID,
				'posts_per_page' => -1,
				'orderby'        => [
					'menu_order' => 'ASC',
					'ID'         => 'ASC',
					'date'       => 'ASC',
				],
			]
			);

			$html .= sprintf(
			/*html*/'
			<li data-post-id="%5$s" class="hover:bg-primary/10 pr-2 transition-all duration-300 rounded-btn cursor-pointer flex items-center justify-between text-sm mb-1 %6$s" style="padding-left: %4$s;">
				<a class="py-2 tw-block flex-1" href="%1$s">%2$s</a>
				%3$s
			</li>
			',
			get_the_permalink($child_post->ID),
			$child_post->post_title,
				// 如果有子章節，就顯示箭頭
			$child_children_posts ? /*html*/'
				<div class="p-2 icon-arrow flex items-center">
					<svg class="w-4 h-4 fill-base-content" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g stroke-width="0"></g><g stroke-linecap="round" stroke-linejoin="round"></g><g> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.29289 4.29289C8.68342 3.90237 9.31658 3.90237 9.70711 4.29289L16.7071 11.2929C17.0976 11.6834 17.0976 12.3166 16.7071 12.7071L9.70711 19.7071C9.31658 20.0976 8.68342 20.0976 8.29289 19.7071C7.90237 19.3166 7.90237 18.6834 8.29289 18.2929L14.5858 12L8.29289 5.70711C7.90237 5.31658 7.90237 4.68342 8.29289 4.29289Z"></path> </g></svg>
				</div>
			' : '',
			( $depth + 1 ) . 'rem',
			$child_post->ID,
			$child_post->ID === $post->ID ? 'bg-primary/10 font-bold [&>a]:text-primary' : 'font-normal [&>a]:text-base-content'
			);

			// 沒有子章節就結束
			if (!$child_children_posts) {
				continue;
			}

			// 有子章節就遞迴取得子章節的子章節
			$html .= self::get_children_posts_html($child_post->ID, $child_children_posts, $depth + 1);
		}
		$html .= /* html */'</ul>';

		return $html;
	}


	/**
	 * 取得麵包屑的 post id，由下到上
	 *
	 * @param int $current_post_id 當前章節 ID.
	 * @param int $top_parent_id 頂層章節 ID.
	 * @param int $depth 深度.
	 * @return array<int>
	 */
	private static function get_breadcrumb_post_ids_reversed( int $current_post_id, int $top_parent_id, $depth = 0 ): array {

		$init                = [ $current_post_id ];
		$breadcrumb_post_ids = array_reduce(
			$init,
			function ( $acc, $id ) use ( $top_parent_id, $depth ) {

				$post = get_post($id);
				/** @var \WP_Post|null $post */
				$parent_id = $post?->post_parent;

				if (!$parent_id) {
					return $acc;
				}

				/** @var array<int> $acc */
				$acc[] = $parent_id;

				// 如果父章節是頂層章節，就停止
				if ($parent_id === $top_parent_id) {
					return $acc;
				}

				return [
					...$acc,
					...self::get_breadcrumb_post_ids_reversed($parent_id, $top_parent_id, $depth + 1),
				];
			},
			$depth > 0 ? [] : [ $current_post_id ]
			);

		return $breadcrumb_post_ids;
	}

	/**
	 * 取得麵包屑的 post id，由上到下
	 *
	 * @param int $current_post_id 當前章節 ID.
	 * @param int $top_parent_id 頂層章節 ID.
	 * @param int $depth 深度.
	 * @return array<int>
	 */
	public static function get_breadcrumb_post_ids( int $current_post_id, int $top_parent_id, $depth = 0 ): array {
		$breadcrumb_post_ids = self::get_breadcrumb_post_ids_reversed($current_post_id, $top_parent_id, $depth);

		// 原本取得順序是下到上，反轉後為上到下
		return array_reverse($breadcrumb_post_ids);
	}


	/**
	 * 檢查當前用戶是否可以觀看
	 *
	 * @param int  $post_id 章節 ID.
	 * @param ?int $user_id 用戶 ID.
	 * @return bool
	 */
	public static function can_access( int $post_id, ?int $user_id = null ): bool {

		// 先檢查 知識庫是否需要權限，不用就 return true
		$need_access = \get_post_meta( $post_id, 'need_access', true ) ?: 'no';
		if ( $need_access === 'no' ) {
			return true;
		}

		$user_id = $user_id ?? \get_current_user_id();

		// 如果需要權限，就檢查用戶是否已登入
		if ( ! $user_id ) {
			return false;
		}

		// TODO 如果用戶已登入就檢查 用戶 db 資料

		return true;
	}
}
