<?php

beforeEach(function () {
		$user_data = [
				'role'         => 'administrator',
				'user_login'   => 'admin2',
				'user_pass'    => 'admin2',
				'user_email'   => 'admin@admin.com',
		];

		// Check if user exists
		$user = get_user_by('login', $user_data['user_login']);

		if ($user) {
				$this->user_id = $user->ID;
				fwrite(STDOUT, "\n\033[32mUser already exists: " . $this->user_id . "\033[0m\n");
				return;
		}

		$user_id_or_error = wp_insert_user($user_data);

		if (is_wp_error($user_id_or_error)) {
				error_log($user_id_or_error->get_error_message());
				throw new Exception('User creation failed: ' . $user_id_or_error->get_error_message());
		} else {
				$this->user_id = $user_id_or_error;
				fwrite(STDOUT, "\n\033[32mUser created: " . $this->user_id . "\033[0m\n");
		}
});

afterEach(function () {
		wp_delete_user($this->user_id);
});

it('has the administrator role', function () {
		$user = get_user_by('id', $this->user_id);
		expect($user)->not->toBeNull();
		expect($user->roles)->toContain('administrator');
		fwrite(STDOUT, "\n\033[32mSUCCESS: User role is administrator.\033[0m\n");
});

it('has a valid email', function () {
		$user = get_user_by('id', $this->user_id);
		expect($user)->not->toBeNull();
		expect($user->user_email)->toEqual('admin@admin.com');
		fwrite(STDOUT, "\n\033[32mSUCCESS: User email is valid.\033[0m\n");
});