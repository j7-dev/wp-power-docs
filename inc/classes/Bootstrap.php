<?php

declare (strict_types = 1);

namespace J7\PowerDocs;

use J7\PowerDocs\Utils\Base;
use J7\Powerhouse\Utils\Base as PowerhouseUtils;
use Kucrut\Vite;
use J7\Powerhouse\Settings\Model\Settings as SettingsDTO;

if ( class_exists( 'J7\PowerDocs\Bootstrap' ) ) {
	return;
}
/** Class Bootstrap */
final class Bootstrap {
	use \J7\WpUtils\Traits\SingletonTrait;

	/** Constructor */
	public function __construct() {
		Admin\Entry::instance();
		Domains\Doc\Loader::instance();
		Domains\Product\Api::instance();
		Domains\User\Api::instance();
		Domains\Elementor\Loader::instance();
		Compatibility\Compatibility::instance();

		\add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_script' ] );
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

		/** @var array<string> $active_plugins */
		$active_plugins = \get_option( 'active_plugins', [] );

		$encrypt_env = PowerhouseUtils::simple_encrypt(
			[
				'SITE_URL'             => \untrailingslashit( \site_url() ),
				'API_URL'              => \untrailingslashit( \esc_url_raw( rest_url() ) ),
				'CURRENT_USER_ID'      => \get_current_user_id(),
				'CURRENT_POST_ID'      => $post_id,
				'PERMALINK'            => \untrailingslashit( $permalink ),
				'APP_NAME'             => Plugin::$app_name,
				'KEBAB'                => Plugin::$kebab,
				'SNAKE'                => Plugin::$snake,
				'BUNNY_LIBRARY_ID'     => SettingsDTO::instance()->bunny_library_id,
				'BUNNY_CDN_HOSTNAME'   => SettingsDTO::instance()->bunny_cdn_hostname,
				'BUNNY_STREAM_API_KEY' => SettingsDTO::instance()->bunny_stream_api_key,
				'NONCE'                => \wp_create_nonce( 'wp_rest' ),
				'APP1_SELECTOR'        => Base::APP1_SELECTOR,
				'DOCS_POST_TYPE'       => \J7\PowerDocs\Domains\Doc\CPT::POST_TYPE,
				'BOUND_META_KEY'       => \J7\PowerDocs\Domains\Product\Api::BOUND_META_KEY,
				'ELEMENTOR_ENABLED'    => \in_array( 'elementor/elementor.php', $active_plugins, true ), // 檢查 elementor 是否啟用
			]
		);

		\wp_localize_script(
			Plugin::$kebab,
			Plugin::$snake . '_data',
			[
				'env' => $encrypt_env,
			]
		);
	}
}
