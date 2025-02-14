<?php
/**
 * Custom Post Type: pd_doc
 */

declare( strict_types=1 );

namespace J7\PowerDocs\Domains\Doc;

use J7\PowerDocs\Plugin;

/**
 * Class CPT
 */
final class CPT {
	use \J7\WpUtils\Traits\SingletonTrait;

	public const POST_TYPE = 'pd_doc';

	/**
	 * Constructor
	 */
	public function __construct() {
		Templates::instance();
		Api::instance();
		\add_action( 'init', [ $this, 'init' ] );
		\add_filter('option_elementor_cpt_support', [ $this, 'add_elementor_cpt_support' ]);
	}



	/**
	 * Initialize
	 */
	public function init(): void {
		$this->register_cpt();
	}

	/**
	 * Register power-docs custom post type
	 */
	public static function register_cpt(): void {
		$labels = [
			'name'                     => \esc_html__( 'knowledge base', 'power-docs' ),
			'singular_name'            => \esc_html__( 'knowledge base', 'power-docs' ),
			'add_new'                  => \esc_html__( 'Add new', 'power-docs' ),
			'add_new_item'             => \esc_html__( 'Add new item', 'power-docs' ),
			'edit_item'                => \esc_html__( 'Edit', 'power-docs' ),
			'new_item'                 => \esc_html__( 'New', 'power-docs' ),
			'view_item'                => \esc_html__( 'View', 'power-docs' ),
			'view_items'               => \esc_html__( 'View', 'power-docs' ),
			'search_items'             => \esc_html__( 'Search power-docs', 'power-docs' ),
			'not_found'                => \esc_html__( 'Not Found', 'power-docs' ),
			'not_found_in_trash'       => \esc_html__( 'Not found in trash', 'power-docs' ),
			'parent_item_colon'        => \esc_html__( 'Parent item', 'power-docs' ),
			'all_items'                => \esc_html__( 'All', 'power-docs' ),
			'archives'                 => \esc_html__( 'knowledge base archives', 'power-docs' ),
			'attributes'               => \esc_html__( 'knowledge base attributes', 'power-docs' ),
			'insert_into_item'         => \esc_html__( 'Insert to this power-docs', 'power-docs' ),
			'uploaded_to_this_item'    => \esc_html__( 'Uploaded to this power-docs', 'power-docs' ),
			'featured_image'           => \esc_html__( 'Featured image', 'power-docs' ),
			'set_featured_image'       => \esc_html__( 'Set featured image', 'power-docs' ),
			'remove_featured_image'    => \esc_html__( 'Remove featured image', 'power-docs' ),
			'use_featured_image'       => \esc_html__( 'Use featured image', 'power-docs' ),
			'menu_name'                => \esc_html__( 'knowledge base', 'power-docs' ),
			'filter_items_list'        => \esc_html__( 'Filter power-docs list', 'power-docs' ),
			'filter_by_date'           => \esc_html__( 'Filter by date', 'power-docs' ),
			'items_list_navigation'    => \esc_html__( 'knowledge base list navigation', 'power-docs' ),
			'items_list'               => \esc_html__( 'knowledge base list', 'power-docs' ),
			'item_published'           => \esc_html__( 'knowledge base published', 'power-docs' ),
			'item_published_privately' => \esc_html__( 'knowledge base published privately', 'power-docs' ),
			'item_reverted_to_draft'   => \esc_html__( 'knowledge base reverted to draft', 'power-docs' ),
			'item_scheduled'           => \esc_html__( 'knowledge base scheduled', 'power-docs' ),
			'item_updated'             => \esc_html__( 'knowledge base updated', 'power-docs' ),
		];

		$args = [
			'label'                 => \esc_html__( 'knowledge base', 'power-docs' ),
			'labels'                => $labels,
			'description'           => '',
			'public'                => false,
			'hierarchical'          => true,
			'exclude_from_search'   => true,
			'publicly_queryable'    => Plugin::$is_local,
			'show_ui'               => Plugin::$is_local,
			'show_in_nav_menus'     => Plugin::$is_local,
			'show_in_admin_bar'     => Plugin::$is_local,
			'show_in_rest'          => true,
			'can_export'            => true,
			'delete_with_user'      => false,
			'has_archive'           => false,
			'rest_base'             => '',
			'show_in_menu'          => Plugin::$is_local,
			'menu_position'         => 6,
			'menu_icon'             => 'dashicons-list-view',
			'capability_type'       => 'post',
			'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'author', 'page-attributes' ],
			'taxonomies'            => [],
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite'               => [
				'with_front' => true,
			],
		];

		\register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Add elementor cpt support
	 *
	 * @param array<string> $value Value.
	 *
	 * @return array<string>
	 */
	public function add_elementor_cpt_support( $value ): array {
		$value[] = self::POST_TYPE;
		return $value;
	}
}
