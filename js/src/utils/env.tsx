/* eslint-disable @typescript-eslint/ban-ts-comment */
// @ts-nocheck

import { removeTrailingSlash } from '@/utils'

export const APP_DOMAIN = 'power_docs_data' as string
export const snake = window?.[APP_DOMAIN]?.env?.SNAKE || 'power_docs'
export const appName = window?.[APP_DOMAIN]?.env?.APP_NAME || 'Power Docs'
export const kebab = window?.[APP_DOMAIN]?.env?.KEBAB || 'power-docs'
export const app1Selector =
	window?.[APP_DOMAIN]?.env?.APP1_SELECTOR || 'power_docs'
export const app2Selector =
	window?.[APP_DOMAIN]?.env?.APP2_SELECTOR || 'power_docs_metabox'
export const apiUrl =
	removeTrailingSlash(window?.wpApiSettings?.root) || '/wp-json'
export const ajaxUrl =
	removeTrailingSlash(window?.[APP_DOMAIN]?.env?.ajaxUrl) ||
	'/wp-admin/admin-ajax.php'
export const siteUrl =
	removeTrailingSlash(window?.[APP_DOMAIN]?.env?.siteUrl) || '/'
export const currentUserId = window?.[APP_DOMAIN]?.env?.userId || '0'
export const postId = window?.[APP_DOMAIN]?.env?.postId || '0'
export const permalink =
	removeTrailingSlash(window?.[APP_DOMAIN]?.env?.permalink) || '/'
export const apiTimeout = '30000'
export const ajaxNonce = window?.[APP_DOMAIN]?.env?.nonce || ''

// bunny
export const bunny_library_id =
	window?.[APP_DOMAIN]?.env?.bunny_library_id || ''
export const bunny_cdn_hostname =
	window?.[APP_DOMAIN]?.env?.bunny_cdn_hostname || ''
export const bunny_stream_api_key =
	window?.[APP_DOMAIN]?.env?.bunny_stream_api_key || ''
