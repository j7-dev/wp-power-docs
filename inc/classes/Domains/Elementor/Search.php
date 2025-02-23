<?php
namespace J7\PowerDocs\Domains\Elementor;

if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
	return;
}

if ( class_exists( 'J7\PowerDocs\Domains\Elementor\Search' ) ) {
	return;
}

use J7\PowerDocs\Plugin;

/**
 * Search
 * 搜尋功能
 */
final class Search extends \Elementor\Widget_Base {

	/**
	 * 取得 widget 名稱
	 *
	 * @return string
	 */
	public function get_name(): string {
		return self::class;
	}

	/**
	 * 取得 widget 標題
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( '知識庫搜尋框', 'power_docs' );
	}

	/**
	 * 取得 widget 圖示
	 *
	 * @see https://elementor.github.io/elementor-icons/?referrer=wordpress.com
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-search';
	}

	/**
	 * Widget 要分類在哪個位置
	 * 可能的值: favorites, layout, basic, pro-elements, general, link-in-bio, theme-elements, elements-single, woocommerce-elements, WordPress
	 *
	 * @return array<string>
	 */
	public function get_categories(): array {
		return [ 'basic' ];
	}

	/**
	 * 關鍵字
	 *
	 * @return array<string>
	 */
	public function get_keywords(): array {
		return [ 'docs', 'doc', 'power', '知識庫', '搜尋', 'search' ];
	}

	/**
	 * 渲染
	 *
	 * @return void
	 */
	protected function render(): void {
		Plugin::get('search/with-keywords');
	}
}
