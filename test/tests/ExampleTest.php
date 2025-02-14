<?php

beforeEach(function () {
		$required_plugins = [
				'woocommerce/woocommerce.php'
		];

		add_filter('option_active_plugins', fn() => $required_plugins, 100);

		foreach ($required_plugins as $plugin) {
				require_once PLUGIN_DIR . $plugin;
		}
});

it('checks if WooCommerce class exists', function () {
		add_action('init', function() {

				expect(class_exists('WooCommerce'))->toBeTrue();
		});
		do_action('init');
});

it('checks if wc_get_product function exists', function () {
	add_action('init', function() {
				expect(function_exists('wc_get_product'))->toBeTrue();
		});
		do_action('init');
});