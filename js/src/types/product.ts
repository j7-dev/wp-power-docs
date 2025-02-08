import { TProductBaseRecord, TProductType } from 'antd-toolkit/wp'
import { TLimit } from 'antd-toolkit'

export type TProductRecord = TProductBaseRecord & {
	bind_docs_data?: TBindDocData[]
}
export type TProductVariation = TProductRecord & {
	type: Extract<TProductType, 'variation' | 'subscription_variation'>
}

/**
 * 將知識庫觀看權限資料，要綁定在商品上的
 */
export type TBindDocData = TLimit & {
	id: string // 知識庫 id
	name: string // 知識庫名稱
}
