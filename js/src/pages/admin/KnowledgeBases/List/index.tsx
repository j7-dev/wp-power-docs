import React, { memo } from 'react'
import Table from '@/pages/admin/KnowledgeBases/List/Table'
import { List } from '@refinedev/antd'

// TODO  有空把 Item.*hidden 簡化一下

const ListComponent = () => {
	return (
		<List title="">
			<Table />
		</List>
	)
}

export const KnowledgeBasesList = memo(ListComponent)
