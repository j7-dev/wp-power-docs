<?php

declare( strict_types=1 );

namespace J7\PowerDocs\Domains\Doc;

use J7\Powerhouse\Domains\Limit\Models\BoundItemsData;
use J7\Powerhouse\Domains\Limit\Models\BoundItemData;
use J7\Powerhouse\Domains\Limit\Utils\MetaCRUD;
use J7\Powerhouse\Domains\Limit\Models\ExpireDate;

/**
 * 知識庫存取相關
 * 購買商品獲得知識庫授權
 */
final class Access {
	use \J7\WpUtils\Traits\SingletonTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
		\add_action( 'woocommerce_order_status_completed', [ $this, 'grant_access' ], 10, 1 );
	}

	/**
	 * 下單特定商品，授權知識庫權限
	 *
	 * @param int $order_id 訂單ID。
	 * @return void
	 */
	public function grant_access( int $order_id ): void {
		try {

			$order = \wc_get_order($order_id);

			if (!( $order instanceof \WC_Order )) {
				return;
			}

			$user_id = $order->get_customer_id();
			// 如果是未登入的訂單，就什麼也不做
			if (!$user_id) {
				return;
			}

			$items = $order->get_items();
			foreach ( $items as $item ) {
				/**
				 * @var \WC_Order_Item_Product $item
				 */
				$product_id = $item->get_product_id();

				// 檢查商品是否有連接知識庫授權，檢查有沒有 bound_docs_data 這個 meta_key
				$bound_docs_data_instance = new BoundItemsData($product_id, 'bound_docs_data');

				/** @var BoundItemData[] $bound_docs_data */
				$bound_docs_data = $bound_docs_data_instance->get_data();
				if (!$bound_docs_data) {
					// 沒有就檢查下個商品
					continue;
				}

				foreach ($bound_docs_data as $bound_docs_item) {
					$bound_docs_item->grant_user($user_id, $order);
				}
			}
		} catch (\Throwable $th) {
			\J7\WpUtils\Classes\WC::log( $th->getMessage(), '授權知識庫時錯誤 grant_access' );
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

		// 如果用戶已登入就檢查 ph_access_itemmeta table 的到期日過期沒
		/** @var string $expire_date */
		$expire_date          = MetaCRUD::get($post_id, $user_id, 'expire_date', true);
		$expire_date_instance = new ExpireDate($expire_date);

		// 沒到期就可以存取
		return !$expire_date_instance->is_expired;
	}
}
