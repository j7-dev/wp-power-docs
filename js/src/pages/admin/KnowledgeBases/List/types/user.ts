type TChapter = {
	id: string
	name: string
	chapter_video: string
	is_finished: boolean
}

export type TExpireDate = {
	is_subscription: boolean
	subscription_id: number | null
	is_expired: boolean
	timestamp: number | null
}

export type TAVLKnowledgeBase = {
	id: string
	name: string
	expire_date: TExpireDate
	chapters?: TChapter[]
}

export type TUserRecord = {
	id: string
	user_login: string
	user_email: string
	display_name: string
	user_registered: string
	user_registered_human: string
	user_avatar_url: string
	avl_knowledge_bases: TAVLKnowledgeBase[]
	is_teacher: boolean
}
