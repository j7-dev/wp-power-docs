<?php
/**
 * Compatibility 不同版本間的相容性設定
 */

declare (strict_types = 1);

namespace J7\PowerDocs\Compatibility;

use J7\PowerDocs\Domains\Doc\CPT;

/**  Compatibility */
final class Compatibility {
	use \J7\WpUtils\Traits\SingletonTrait;

	/** Constructor */
	public function __construct() {
		// 升級成功後執行
		\add_action( 'upgrader_process_complete', [ __CLASS__, 'compatibility' ]);
	}


	/**
	 * 執行排程
	 *
	 * @return void
	 */
	public static function compatibility(): void {

		/**
		 * ============== START 相容性代碼 ==============
		 */

		self::set_editor_meta_to_chapter();

		/**
		 * ============== END 相容性代碼 ==============
		 */
	}

	/**
	 * 將 chapter 設定 editor
	 * 將使用 elementor 的 chapter 設定 editor 為 elementor
	 * 將未使用 elementor 的 chapter 設定 editor 為 power-editor
	 */
	public static function set_editor_meta_to_chapter(): void {

		$elementor_chapter_ids = \get_posts(
			[
				'post_type'   => CPT::POST_TYPE,
				'numberposts' => -1,
				'fields'      => 'ids',
				'meta_query'  => [
					'relation'              => 'AND',
					'elementor_data_clause' => [
						'key'     => '_elementor_data',
						'compare' => 'EXISTS',
					],
					'editor_clause'         => [
						'key'     => 'editor',
						'compare' => 'NOT EXISTS',
					],
				],
			]
		);

		foreach ($elementor_chapter_ids as $chapter_id) {
			\update_post_meta($chapter_id, 'editor', 'elementor');
		}

		$power_chapter_ids = \get_posts(
			[
				'post_type'   => CPT::POST_TYPE,
				'numberposts' => -1,
				'fields'      => 'ids',
				'meta_query'  => [
					'relation'              => 'AND',
					'elementor_data_clause' => [
						'key'     => '_elementor_data',
						'compare' => 'NOT EXISTS',
					],
					'editor_clause'         => [
						'key'     => 'editor',
						'compare' => 'NOT EXISTS',
					],
				],
			]
			);

		foreach ($power_chapter_ids as $chapter_id) {
			\update_post_meta($chapter_id, 'editor', 'power-editor');
		}
	}
}
