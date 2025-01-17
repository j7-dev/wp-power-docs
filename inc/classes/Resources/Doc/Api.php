<?php
/**
 * Doc API
 */

declare(strict_types=1);

namespace J7\PowerDocs\Resources\Doc;

use J7\WpUtils\Classes\WP;
use J7\WpUtils\Classes\General;
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
	 * @var array{endpoint:string,method:string,permission_callback: callable|null }[]
	 * - endpoint: string
	 * - method: 'get' | 'post' | 'patch' | 'delete'
	 * - permission_callback : callable
	 */
	protected $apis = [
		[
			'endpoint'            => 'docs',
			'method'              => 'get',
			'permission_callback' => null,
		],
		[
			'endpoint'            => 'docs',
			'method'              => 'post',
			'permission_callback' => null,
		],
		[
			'endpoint'            => 'docs/(?P<id>\d+)',
			'method'              => 'post',
			'permission_callback' => null,
		],
		[
			'endpoint'            => 'docs',
			'method'              => 'delete',
			'permission_callback' => null,
		],
		[
			'endpoint'            => 'docs/(?P<id>\d+)',
			'method'              => 'delete',
			'permission_callback' => null,
		],
		[
			'endpoint'            => 'docs/sort',
			'method'              => 'post',
			'permission_callback' => null,
		],
	];

	/**
	 * Get docs callback
	 *
	 * @param \WP_REST_Request $request Request.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 * @phpstan-ignore-next-line
	 */
	public function get_docs_callback( $request ) { // phpcs:ignore

		$params = $request->get_query_params();

		$params = WP::sanitize_text_field_deep( $params, false );

		$default_args = [
			'post_type'      => CPT::POST_TYPE,
			'posts_per_page' => - 1,
			'post_status'    => 'any',
			'orderby'        => [
				'menu_order' => 'ASC',
				'ID'         => 'ASC',
				'date'       => 'ASC',
			],

		];

		$args = \wp_parse_args(
			$params,
			$default_args,
		);

		$docs = \get_posts($args);
		$docs = array_values(array_map( [ Utils::class, 'format_doc_details' ], $docs )); // @phpstan-ignore-line

		$response = new \WP_REST_Response( $docs );

		return $response;
	}


	/**
	 * 處理並分離產品資訊
	 *
	 * 根據請求分離產品資訊，並處理描述欄位。
	 *
	 * @param \WP_REST_Request $request 包含產品資訊的請求對象。
	 * @throws \Exception 當找不到商品時拋出異常。.
	 * @return array{data: array<string, mixed>, meta_data: array<string, mixed>} 包含產品對象、資料和元數據的陣列。
	 * @phpstan-ignore-next-line
	 */
	private function separator( $request ): array {
		$body_params = $request->get_body_params();
		$file_params = $request->get_file_params();

		// 將 key 做轉換
		$body_params = Utils::converter( $body_params );

		$skip_keys = [
			'post_content',
		];
		/** @var array<string, mixed> $body_params 過濾字串，防止 XSS 攻擊 */
		$body_params = WP::sanitize_text_field_deep($body_params, true, $skip_keys);

		// 將 '[]' 轉為 []
		$body_params = General::format_empty_array( $body_params );

		$separated_data = WP::separator( $body_params, 'post', $file_params['files'] ?? [] );

		if (\is_wp_error($separated_data)) {
			throw new \Exception($separated_data->get_error_message());
		}

		return $separated_data;
	}

	/**
	 * Post Doc callback
	 * 創建文件
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 * @throws \Exception 當新增文件失敗時拋出異常
	 * @phpstan-ignore-next-line
	 */
	public function post_docs_callback( $request ): \WP_REST_Response|\WP_Error {

		try {
			[
				'data'      => $data,
				'meta_data' => $meta_data,
			] = $this->separator( $request );

			$qty = (int) ( $meta_data['qty'] ?? 1 );
			unset($meta_data['qty']);

			$post_parents = $meta_data['post_parents'];
			unset($meta_data['post_parents']);
			$post_parents = is_array( $post_parents ) ? $post_parents : [];

			// 不需要紀錄 depth，深度是由 post_parent 決定的
			unset($meta_data['depth']);
			// action 用來區分是 create 還是 update ，目前只有 create ，所以不用判斷
			unset($meta_data['action']);

			$data['meta_input'] = $meta_data;

			$success_ids = [];

			foreach ($post_parents as $post_parent) {
				$data['post_parent'] = $post_parent;
				for ($i = 0; $i < $qty; $i++) {
					$post_id = Utils::create_doc( $data );
					if (is_numeric($post_id)) {
						$success_ids[] = $post_id;
					} else {
						throw new \Exception( "新增文件失敗 : {$post_id->get_error_message()}");
					}
				}
			}

			return new \WP_REST_Response(
				[
					'code'    => 'create_success',
					'message' => '新增文件成功',
					'data'    => $success_ids,
				],
			);
		} catch (\Throwable $th) {
			return new \WP_REST_Response(
				[
					'code'    => 'create_failed',
					'message' => $th->getMessage(),
					'data'    => null,
				],
				400
			);
		}
	}

	/**
	 * Post Doc Sort callback
	 * 處理排序
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 * @phpstan-ignore-next-line
	 */
	public function post_docs_sort_callback( $request ): \WP_REST_Response|\WP_Error {

		$body_params = $request->get_json_params();

		$body_params = WP::sanitize_text_field_deep( $body_params, false );

		/** @var array{from_tree: array<array{id: string}>, to_tree: array<array{id: string}>} $body_params */
		$sort_result = Utils::sort_docs( $body_params );

		if ( $sort_result !== true ) {
			return $sort_result;
		}

		return new \WP_REST_Response(
			[
				'code'    => 'sort_success',
				'message' => '修改排序成功',
				'data'    => null,
			]
		);
	}

	/**
	 * Patch Doc callback
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 * @throws \Exception 當更新文件失敗時拋出異常
	 * @phpstan-ignore-next-line
	 */
	public function post_docs_with_id_callback( $request ): \WP_REST_Response|\WP_Error {
		try {
			$id = $request['id'] ?? null;
			if (!$id) {
				throw new \Exception('缺少 id');
			}

			[
			'data'      => $data,
			'meta_data' => $meta_data,
			] = $this->separator( $request );

			$data['ID']         = $id;
			$data['meta_input'] = $meta_data;

			$update_result = \wp_update_post($data);

			if ( !is_numeric( $update_result ) ) {
				return $update_result;
			}

			return new \WP_REST_Response(
			[
				'code'    => 'update_success',
				'message' => '更新成功',
				'data'    => [
					'id' => $id,
				],
			]
			);

		} catch (\Throwable $th) {
			return new \WP_REST_Response(
			[
				'code'    => 'update_failed',
				'message' => $th->getMessage(),
				'data'    => null,
			],
			400
			);
		}
	}

	/**
	 * Delete Doc callback
	 * 刪除文件
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response
	 * @throws \Exception 當刪除文件失敗時拋出異常
	 * @phpstan-ignore-next-line
	 */
	public function delete_docs_with_id_callback( $request ): \WP_REST_Response {
		try {
			$id = $request['id'] ?? null;
			if (!$id) {
				throw new \Exception('缺少 id');
			}
			$result = \wp_trash_post( (int) $id );
			if (!$result) {
				throw new \Exception('刪除失敗');
			}

			return new \WP_REST_Response(
			[
				'code'    => 'delete_success',
				'message' => '刪除成功',
				'data'    => [
					'id' => $id,
				],
			]
			);
		} catch (\Throwable $th) {
			return new \WP_REST_Response(
				[
					'code'    => 'delete_failed',
					'message' => $th->getMessage(),
					'data'    => [
						'id' => $id,
					],
				],
				400
				);
		}
	}

	/**
	 * 批量刪除文件資料
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 * @throws \Exception 當刪除文件資料失敗時拋出異常
	 * @phpstan-ignore-next-line
	 */
	public function delete_docs_callback( $request ): \WP_REST_Response|\WP_Error {

		$body_params = $request->get_json_params();

		/** @var array<string, mixed> $body_params */
		$body_params = WP::sanitize_text_field_deep( $body_params, false );

		$ids = $body_params['ids'] ?? [];
		/** @var array<string> $ids */
		$ids = is_array( $ids ) ? $ids : [];

		try {
			foreach ($ids as $id) {
				$result = \wp_trash_post( (int) $id );
				if (!$result) {
					throw new \Exception(__('刪除文件資料失敗', 'power-course') . " #{$id}");
				}
			}

			return new \WP_REST_Response(
				[
					'code'    => 'delete_success',
					'message' => '刪除成功',
					'data'    => $ids,
				]
			);
		} catch (\Throwable $th) {
			return new \WP_REST_Response(
				[
					'code'    => 'delete_failed',
					'message' => $th->getMessage(),
					'data'    => $ids,
				],
				400
			);
		}
	}
}
