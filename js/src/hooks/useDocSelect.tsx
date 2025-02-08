import React from 'react'
import { useItemSelect } from 'antd-toolkit/wp'
import { useEnv } from 'antd-toolkit'
import { TDocBaseRecord } from '@/pages/admin/Docs/List/types'

export const useDocSelect = () => {
	const { DOCS_POST_TYPE } = useEnv()
	const data = useItemSelect<TDocBaseRecord>({
		useSelectProps: {
			resource: 'posts',
			filters: [
				{
					field: 'post_type',
					operator: 'eq',
					value: DOCS_POST_TYPE,
				},
			],
		},
	})
	return data
}
