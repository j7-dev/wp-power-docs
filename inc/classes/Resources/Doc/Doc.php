<?php
/**
 * Doc
 */

declare( strict_types=1 );

namespace J7\PowerDocs\Resources\Doc;

use J7\PowerDocs\Plugin;
use J7\WpUtils\Classes\WP;

/**
 * Class Doc
 */
final class Doc {
	/**
	 * 文件 ID
	 *
	 * @var int
	 */
	public int $id;

	/**
	 * 文件名稱
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * 文件網址別名
	 *
	 * @var string
	 */
	public string $slug;

	/**
	 * 建立日期
	 *
	 * @var string
	 */
	public string $date_created;

	/**
	 * 修改日期
	 *
	 * @var string
	 */
	public string $date_modified;

	/**
	 * 文件狀態
	 *
	 * @var string
	 */
	public string $status;

	/**
	 * 選單順序
	 *
	 * @var int
	 */
	public int $menu_order;

	/**
	 * 文件永久連結
	 *
	 * @var string|null
	 */
	public string|null $permalink = null;

	/**
	 * 分類 ID 列表
	 *
	 * @var string[]
	 */
	public array $category_ids;

	/**
	 * 標籤 ID 列表
	 *
	 * @var string[]
	 */
	public array $tag_ids;

	/**
	 * 圖片資訊
	 *
	 * @var array<array{id: string, url: string}>
	 */
	public array $images;

	/**
	 * 父文件 ID
	 *
	 * @var string
	 */
	public string $parent_id;

	/**
	 * 子文件列表
	 *
	 * @var array<array{id: string, type: string, depth: int, name: string, slug: string, date_created: string, date_modified: string, status: string, menu_order: int, permalink: string, category_ids: string[], tag_ids: string[], images: array<array{id: string, url: string, width: int, height: int, alt: string}>, parent_id: string}>|null
	 */
	public ?array $sub_docs = null;

	/**
	 * 文件內容
	 *
	 * @var string|null
	 */
	public ?string $description = null;

	/**
	 * 文件摘要
	 *
	 * @var string|null
	 */
	public ?string $short_description = null;

	/**
	 * 建構子
	 *
	 * @param \WP_Post $post 文章資料。
	 * @param int      $depth 文件深度。
	 */
	public function __construct( public \WP_Post $post, public int $depth = 0 ) {
		$this->init($post, $depth);
	}

	/**
	 * 初始化
	 *
	 * @param \WP_Post $post 文章資料。
	 * @param int      $depth 文件深度。
	 * @return void
	 */
	private function init( \WP_Post $post, int $depth ): void {
		$this->id            = $post->ID;
		$this->depth         = $depth;
		$this->name          = $post->post_title;
		$this->slug          = $post->post_name;
		$this->date_created  = $post->post_date;
		$this->date_modified = $post->post_modified;
		$this->status        = $post->post_status;
		$this->menu_order    = (int) $post->menu_order;

		$permalink          = \get_permalink($post->ID);
		$this->permalink    = $permalink ? $permalink : null;
		$this->category_ids = [];
		$this->tag_ids      = [];

		$image_id        = \get_post_thumbnail_id($post->ID);
		$image_ids       = [ $image_id ];
		$images          = array_map([ WP::class, 'get_image_info' ], $image_ids); // @phpstan-ignore-line
		$this->images    = $images;
		$this->parent_id = (string) $post->post_parent;

		$this->description       = $post->post_content;
		$this->short_description = $post->post_excerpt;
	}

	/**
	 * 轉換為陣列
	 *
	 * @param bool $with_description 是否包含描述。
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
	public function to_recursive_array( bool $with_description = false ): array {
		return Utils::format_doc_details($this->post, $with_description, $this->depth);
	}

	/**
	 * 取得子文件列表，flat
	 *
	 * @param bool $recursive 是否遞迴取得子文件列表，攤平，非階層式結構。
	 * @return self[]
	 */
	public function get_children( bool $recursive = false ): array {
		/** @var \WP_Post[] $sub_doc_posts */
		$sub_doc_posts = \get_children(
				[
					'post_parent' => $this->id,
					'post_type'   => CPT::POST_TYPE,
					'numberposts' => -1,
					'post_status' => 'any',
					'orderby'     => [
						'menu_order' => 'ASC',
						'ID'         => 'ASC',
						'date'       => 'ASC',
					],
				]
		);

		$sub_docs = [];
		foreach ( $sub_doc_posts as $sub_doc_post ) {
			$sub_docs[] = new Doc($sub_doc_post, $this->depth + 1);
		}

		if ($recursive) {
			foreach ($sub_docs as $sub_doc) {
				$sub_docs = array_merge($sub_docs, $sub_doc->get_children(true));
			}
		}

		return $sub_docs;
	}

	/**
	 * 取得最上層的文件
	 *
	 * @return self|null
	 */
	public function get_top_parent(): self|null {
		$top_parent_id = Utils::get_top_doc_id( $this->id );
		if ( !$top_parent_id ) {
			return null;
		}
		$post = \get_post( $top_parent_id );
		if ( !$post ) {
			return null;
		}
		/** @var \WP_Post $post */
		return new self( $post );
	}


	/**
	 * 檢查此文件是否可被用戶存取
	 * TODO
	 *
	 * @param int|null $user_id 用戶 ID.
	 * @return bool
	 */
	public static function can_access( ?int $user_id = null ): bool {
		$user_id = $user_id ?? \get_current_user_id();
		if ( ! $user_id ) {
			return false;
		}

		return true; // 可能可以 apply filters
	}
}
