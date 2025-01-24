import { TDocRecord } from '@/pages/admin/Docs/List/types'
import { useList, HttpError } from '@refinedev/core'
import { Form } from 'antd'
import { useAtomValue } from 'jotai'
import { selectedPostAtom } from '../atom'
import { PluginProvider } from 'antd-toolkit'

export const usePostsList = () => {
	const { DOCS_POST_TYPE = '' } = PluginProvider.usePlugin()
	const form = Form.useFormInstance()
	const parent_id = form?.getFieldValue('id')
	const query = useList<TDocRecord, HttpError>({
		resource: 'posts',
		filters: [
			{
				field: 'post_parent',
				operator: 'eq',
				value: parent_id,
			},
			{
				field: 'post_type',
				operator: 'eq',
				value: DOCS_POST_TYPE,
			},
			{
				field: 'with_description',
				operator: 'eq',
				value: 'true',
			},
			{
				field: 'recursive_args',
				operator: 'eq',
				value: '[]',
			},
		],
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
