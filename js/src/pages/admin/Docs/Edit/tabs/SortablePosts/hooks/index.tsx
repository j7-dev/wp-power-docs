import { TDocRecord } from '@/pages/admin/Docs/List/types'
import { useList, HttpError, useParsed } from '@refinedev/core'
import { Form } from 'antd'
import { useAtomValue } from 'jotai'
import { selectedPostAtom } from '../atom'
import { useEnv } from 'antd-toolkit'
import { objToCrudFilters } from 'antd-toolkit/refine'

export const usePostsList = () => {
	const { DOCS_POST_TYPE = '' } = useEnv()
	const { id: post_parent } = useParsed()
	const query = useList<TDocRecord, HttpError>({
		resource: 'posts',
		filters: objToCrudFilters({
			post_parent,
			post_type: DOCS_POST_TYPE,
			with_description: 'true',
			recursive_args: '[]',
			meta_keys: [],
		}),
		pagination: {
			current: 1,
			pageSize: -1,
		},
	})

	return query
}

export const useSelectedPost = () => {
	return useAtomValue(selectedPostAtom)
}
