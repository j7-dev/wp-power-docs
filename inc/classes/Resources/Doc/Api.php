<?php
/**
 * Doc API
 */

declare(strict_types=1);

namespace J7\PowerDocs\Resources\Doc;

use J7\WpUtils\Classes\ApiBase;

/**
 * Class Api
 */
final class Api extends ApiBase {
	use \J7\WpUtils\Traits\SingletonTrait;

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
		\add_filter( 'powerhouse/post/get_meta_keys_array', [ $this, 'add_meta_keys' ], 10, 2 );
	}

	/**
	 * 針對 Docs 的 post type 額外暴露 meta keys
	 *
	 * @param array<string, mixed> $meta_keys Meta keys.
	 * @param \WP_Post             $post Post.
	 * @return array<string, mixed>
	 */
	public function add_meta_keys( array $meta_keys, \WP_Post $post ): array {
		if ( $post->post_type !== CPT::POST_TYPE ) {
			return $meta_keys;
		}

		// 是否需要購買才能觀看
		$meta_keys['need_access'] = \get_post_meta( $post->ID, 'need_access', true ) ?: 'no';
		return $meta_keys;
	}
}
