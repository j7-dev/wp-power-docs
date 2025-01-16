<?php
/**
 * Admin Entry
 */

declare(strict_types=1);

namespace J7\PowerDocs\Admin;

use J7\PowerDocs\Plugin;
use J7\PowerDocs\Bootstrap;
use J7\PowerDocs\Utils\Base;


/**
 * Class Entry
 */
final class Entry {
	use \J7\WpUtils\Traits\SingletonTrait;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add the admin page for full-screen.
		\add_action('current_screen', [ __CLASS__, 'maybe_output_admin_page' ], 10);
	}

	/**
	 * Output the dashboard admin page.
	 */
	public static function maybe_output_admin_page(): void {
		// Exit if not in admin.
		if (!\is_admin()) {
			return;
		}

		// Make sure we're on the right screen.
		$screen = \get_current_screen();

		if (Plugin::$kebab !== $screen?->id) {
			return;
		}

		self::render_page();

		exit;
	}

	/**
	 * Output landing page header.
	 *
	 * Credit: SliceWP Setup Wizard.
	 */
	public static function render_page(): void {
		// Output header HTML.
		Bootstrap::enqueue_script();
		$blog_name = \get_bloginfo('name');
		$id        = substr(Base::APP1_SELECTOR, 1);

		?>
		<!doctype html>
		<html lang="zh_tw">

		<head>
			<meta charset="UTF-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<title>Power Docs | <?php echo $blog_name; ?></title>
		</head>

		<body class="tailwind">
			<main id="<?php echo $id; ?>"></main>
		<?php
		/**
		 * Prints any scripts and data queued for the footer.
		 *
		 * @since 2.8.0
		 */
		\do_action('admin_print_footer_scripts');

		?>
		</body>

		</html>
		<?php
	}
}
