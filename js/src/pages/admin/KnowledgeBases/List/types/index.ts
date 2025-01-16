import { TVideo } from '@/components/formItem/VideoInput/types'
import {
	TTerm,
	TStockStatus,
	TProductType,
	TProductAttribute,
	TImage,
} from 'antd-toolkit'

export * from './user'

// List 只會拿基本的欄位
export type TKnowledgeBaseBaseRecord = {
	id: string
	type: TProductType
	name: string
	slug: string
	date_created: string
	date_modified: string
	status: string
	featured: boolean
	catalog_visibility: string
	sku: string
	menu_order: number
	virtual: boolean
	downloadable: boolean
	permalink: string
	custom_rating: number
	extra_review_count: number
	price_html: string
	regular_price: string
	sale_price: string
	on_sale: boolean
	date_on_sale_from: string | null
	date_on_sale_to: string | null
	total_sales: number
	stock: number | null
	stock_status: TStockStatus
	manage_stock: boolean
	stock_quantity: number | null
	backorders: 'yes'
	backorders_allowed: boolean
	backordered: boolean
	low_stock_amount: number | null
	categories: TTerm[]
	tags: TTerm[]
	images: TImage[]
	is_free: 'yes' | 'no' | ''
	hours: number
}

export type TKnowledgeBaseLimit = {
	limit_type: 'unlimited' | 'fixed' | 'assigned' | 'follow_subscription'
	limit_value: number | ''
	limit_unit: 'second' | 'day' | 'month' | 'year' | ''
}

// Edit, Show, Create 會拿全部的欄位
export type TKnowledgeBaseRecord = TKnowledgeBaseBaseRecord &
	TKnowledgeBaseLimit & {
		purchase_note: string
		description: string
		short_description: string
		upsell_ids: number[]
		cross_sell_ids: number[]
		attributes: TProductAttribute[]
		chapters?: TChapterRecord[]
		qa_list: {
			question: string
			answer: string
		}[]
		is_popular: 'yes' | 'no' | ''
		is_featured: 'yes' | 'no' | ''
		show_review: 'yes' | 'no' | ''
		reviews_allowed: boolean
		show_review_tab: 'yes' | 'no' | ''
		show_review_list: 'yes' | 'no' | ''
		show_total_student: 'yes' | 'no' | ''
		enable_comment: 'yes' | 'no' | ''
		extra_student_count: number
		feature_video: TVideo
		trial_video: TVideo
	}

export type TChapterRecord = {
	id: string
	type: 'chapter'
	status: string
	depth: number
	name: string
	slug?: string
	date_created?: string
	date_modified?: string
	catalog_visibility?: string
	description?: string
	short_description?: string
	sku?: undefined
	menu_order?: number
	total_sales?: undefined
	permalink?: string
	chapter_length: number
	category_ids?: string[]
	tag_ids?: string[]
	images?: TImage[]
	chapters?: TChapterRecord[]
	parent_id?: string
	chapter_video?: TVideo
}
