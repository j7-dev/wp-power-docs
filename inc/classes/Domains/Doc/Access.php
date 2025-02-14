<?php

declare( strict_types=1 );

namespace J7\PowerDocs\Domains\Doc;

/**
 * 知識庫存取相關
 */
final class Access {
	use \J7\WpUtils\Traits\SingletonTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
		// \add_action( 'woocommerce_order_status_completed', [ $this, 'grant_access' ], 10, 1 );
	}

		/**TODO
	 * 訂單完成時將元數據添加到訂單中的可用課程。
	 *
	 * 此函數遍歷訂單中的每個商品，檢查是否為商品。如果是，則將課程的限制條件（如限制類型、限制值和限制單位）
	 * 紀錄到訂單中。根據這些限制條件，計算並設定課程的到期日存入 avl_coursemeta 表中。
	 *
	 * @param int $order_id 訂單ID。
	 * @return void
	 */
	public function grant_access( int $order_id ): void {
		$order = \wc_get_order($order_id);

		if (!( $order instanceof \WC_Order )) {
			return;
		}

		$items = $order->get_items();
		foreach ( $items as $item ) {
			/**
			 * @var \WC_Order_Item_Product $item
			 */
			$product_id = $item->get_product_id();

			$bind_courses_data = $item->get_meta( '_bind_courses_data' ) ?: [];
			$is_course         = CourseUtils::is_course_product( $product_id );

			// 如果 "不是課程商品" 或 "沒有綁定課程"，就什麼也不做
			if ( !$is_course && !$bind_courses_data ) {
				continue;
			}

			// 如果是單一課程，就處理單一課程
			if ($is_course) {
				$this->handle_single_course( $order, $item );
			}

			// 如果有綁定課程，就處理綁定課程
			if ($bind_courses_data) {
				$this->handle_bind_courses( $order, $item );
			}
		}
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
