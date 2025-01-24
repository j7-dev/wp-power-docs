declare global {
	var wpApiSettings: {
		root: string
		nonce: string
	}
	var appData: {
		env: {
			SITE_URL: string
			AJAX_URL: string
			CURRENT_USER_ID: string
			CURRENT_POST_ID: string
			PERMALINK: string
			APP_NAME: string
			KEBAB: string
			SNAKE: string
			BASE_URL: string
			APP1_SELECTOR: string
			APP2_SELECTOR: string
			API_TIMEOUT: string
			AJAX_NONCE: string
		}
	}
	var wp: {
		blocks: any
	}
}

export {}
