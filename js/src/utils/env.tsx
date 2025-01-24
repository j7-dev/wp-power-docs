/* eslint-disable @typescript-eslint/ban-ts-comment */
// @ts-nocheck

import { removeTrailingSlash } from '@/utils'

export const APP_DOMAIN = 'power_docs_data' as string
export const SNAKE = window?.[APP_DOMAIN]?.env?.SNAKE || 'power_docs'
export const APP_NAME = window?.[APP_DOMAIN]?.env?.APP_NAME || 'Power Docs'
export const KEBAB = window?.[APP_DOMAIN]?.env?.KEBAB || 'power-docs'
export const APP1_SELECTOR =
	window?.[APP_DOMAIN]?.env?.APP1_SELECTOR || 'power_docs'
export const APP2_SELECTOR =
	window?.[APP_DOMAIN]?.env?.APP2_SELECTOR || 'power_docs_metabox'
export const API_URL =
	removeTrailingSlash(window?.wpApiSettings?.root) || '/wp-json'
export const AJAX_URL =
	removeTrailingSlash(window?.[APP_DOMAIN]?.env?.AJAX_URL) ||
	'/wp-admin/admin-ajax.php'
export const SITE_URL =
	removeTrailingSlash(window?.[APP_DOMAIN]?.env?.SITE_URL) || '/'
export const CURRENT_USER_ID = window?.[APP_DOMAIN]?.env?.CURRENT_USER_ID || '0'
export const CURRENT_POST_ID = window?.[APP_DOMAIN]?.env?.CURRENT_POST_ID || '0'
export const PERMALINK =
	removeTrailingSlash(window?.[APP_DOMAIN]?.env?.PERMALINK) || '/'
export const AJAX_NONCE = window?.[APP_DOMAIN]?.env?.AJAX_NONCE || ''
export const DOCS_POST_TYPE =
	window?.[APP_DOMAIN]?.env?.DOCS_POST_TYPE || 'pd_doc'

// bunny
export const BUNNY_LIBRARY_ID =
	window?.[APP_DOMAIN]?.env?.BUNNY_LIBRARY_ID || ''
export const BUNNY_CDN_HOSTNAME =
	window?.[APP_DOMAIN]?.env?.BUNNY_CDN_HOSTNAME || ''
export const BUNNY_STREAM_API_KEY =
	window?.[APP_DOMAIN]?.env?.BUNNY_STREAM_API_KEY || ''
