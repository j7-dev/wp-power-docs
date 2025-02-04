<?php
/**
 * Bootstrap
 */

declare (strict_types = 1);

namespace J7\PowerDocs;

use J7\PowerDocs\Utils\Base;
use Kucrut\Vite;

if ( class_exists( 'J7\PowerDocs\Bootstrap' ) ) {
	return;
}
/**
 * Class Bootstrap
 */
final class Bootstrap {
	use \J7\WpUtils\Traits\SingletonTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
		Admin\CPT::instance();
		Admin\Entry::instance();
		Resources\Doc\CPT::instance();

		\add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_script' ] );
		\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'frontend_enqueue_script' ]);
	}



	/**
	 * Admin Enqueue script
	 * You can load the script on demand
	 *
	 * @param string $hook current page hook
	 *
	 * @return void
	 */
	public static function admin_enqueue_script( $hook ): void {
		self::enqueue_script();
	}


	/**
	 * Front-end Enqueue script
	 * You can load the script on demand
	 *
	 * @return void
	 */
	public static function frontend_enqueue_script(): void {
		\wp_enqueue_style('power-docs-css', Plugin::$url . '/js/dist/css/style.css', [], Plugin::$version);
	}

	/**
	 * Enqueue script
	 * You can load the script on demand
	 *
	 * @return void
	 */
	public static function enqueue_script(): void {
		Vite\enqueue_asset(
			Plugin::$dir . '/js/dist',
			'js/src/main.tsx',
			[
				'handle'    => Plugin::$kebab,
				'in-footer' => true,
			]
		);

		$post_id   = \get_the_ID();
		$permalink = $post_id ? \get_permalink( $post_id ) : '';

		\wp_localize_script(
			Plugin::$kebab,
			Plugin::$snake . '_data',
			[
				'env' => [
					'SITE_URL'             => \untrailingslashit( \site_url() ),
					'AJAX_URL'             => \untrailingslashit( \admin_url( 'admin-ajax.php' ) ),
					'CURRENT_USER_ID'      => \wp_get_current_user()->data->ID ?? null,
					'CURRENT_POST_ID'      => $post_id,
					'PERMALINK'            => \untrailingslashit( $permalink ),
					'APP_NAME'             => Plugin::$app_name,
					'KEBAB'                => Plugin::$kebab,
					'SNAKE'                => Plugin::$snake,
					'BASE_URL'             => Base::BASE_URL,
					'APP1_SELECTOR'        => Base::APP1_SELECTOR,
					'APP2_SELECTOR'        => Base::APP2_SELECTOR,
					'API_TIMEOUT'          => Base::API_TIMEOUT,
					'AJAX_NONCE'           => \wp_create_nonce( Plugin::$kebab ),
					'DOCS_POST_TYPE'       => \J7\PowerDocs\Resources\Doc\CPT::POST_TYPE,
					'BUNNY_LIBRARY_ID'     => \get_option( 'bunny_library_id', '' ),
					'BUNNY_CDN_HOSTNAME'   => \get_option( 'bunny_cdn_hostname', '' ),
					'BUNNY_STREAM_API_KEY' => \get_option( 'bunny_stream_api_key', '' ),
				],
			]
		);

		\wp_localize_script(
			Plugin::$kebab,
			'wpApiSettings',
			[
				'root'  => \untrailingslashit( \esc_url_raw( rest_url() ) ),
				'nonce' => \wp_create_nonce( 'wp_rest' ),
			]
		);
	}
}
