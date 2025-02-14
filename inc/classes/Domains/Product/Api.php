<?php
/**
 * Product API
 */

declare(strict_types=1);

namespace J7\PowerDocs\Domains\Product;

use J7\WpUtils\Classes\ApiBase;

/**
 * Class Api
 */
final class Api extends ApiBase {
	use \J7\WpUtils\Traits\SingletonTrait;

	/**
	 * 綁定知識庫資料的 product meta key
	 *
	 * @var string
	 */
	const BOUND_META_KEY = 'bound_docs_data';

	/**
	 * Namespace
	 *
	 * @var string
	 */
	protected $namespace = 'power-docs';

	/**
	 * APIs
	 *
	 * @var array{endpoint:string,method:string,permission_callback: ?callable }[]
	 */
	protected $apis = [];



	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		\add_filter( 'powerhouse/product/get_meta_keys_array', [ $this, 'add_meta_keys' ], 10, 2 );
	}

	/**
	 * 針對 Docs 的 post type 額外暴露 meta keys
	 *
	 * @param array<string, mixed> $meta_keys Meta keys.
	 * @param \WC_Product          $product Product.
	 * @return array<string, mixed>
	 */
	public function add_meta_keys( array $meta_keys, \WC_Product $product ): array {
		if (!isset($meta_keys[ self::BOUND_META_KEY ])) {
			return $meta_keys;
		}
		$meta_keys[ self::BOUND_META_KEY ] = \get_post_meta( $product->get_id(), self::BOUND_META_KEY, true ) ?: [];
		return $meta_keys;
	}
}
