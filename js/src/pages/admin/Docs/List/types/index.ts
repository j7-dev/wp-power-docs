import { TImage, TTerm, TPostStatus } from 'antd-toolkit/wp'
import { TLimit } from 'antd-toolkit'

// List 只會拿基本的欄位
export type TDocBaseRecord = {
	id: string
	depth: number
	name: string
	slug: string
	date_created: string
	date_modified: string
	status: TPostStatus
	menu_order: number
	permalink: string
	category_ids: TTerm[]
	tag_ids: TTerm[]
	images: TImage[]
	parent_id: string
	sub_docs: TDocBaseRecord[]
}

// Edit, Show, Create 會拿全部的欄位
export type TDocRecord = TDocBaseRecord &
	TLimit & {
		description: string
		short_description: string
	}