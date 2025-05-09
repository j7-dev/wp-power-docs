/* eslint-disable @typescript-eslint/ban-ts-comment */
// @ts-nocheck

import { simpleDecrypt } from 'antd-toolkit'

const encryptedEnv = window?.power_docs_data?.env
export const env = simpleDecrypt(encryptedEnv)

export const API_URL = env?.API_URL || '/wp-json'
export const APP1_SELECTOR = env?.APP1_SELECTOR || 'power_docs'
export const DOCS_POST_TYPE = env?.DOCS_POST_TYPE || 'pd_doc'
