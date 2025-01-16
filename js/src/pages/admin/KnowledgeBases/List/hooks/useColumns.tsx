import React from 'react'
import { Table, TableProps, Tag } from 'antd'
import { TKnowledgeBaseBaseRecord } from '@/pages/admin/KnowledgeBases/List/types'
import {
	ProductName,
	ProductPrice,
	ProductTotalSales,
	ProductCat,
	ProductAction,
} from '@/components/product'
import { getPostStatus } from '@/utils'
import { DateTime } from 'antd-toolkit'
import { SecondToStr } from '@/components/general'
import { useNavigation } from '@refinedev/core'

const useColumns = () => {
	const { edit } = useNavigation()
	const onClick = (record: TKnowledgeBaseBaseRecord) => () => {
		edit('knowledge-bases', record.id)
	}
	const columns: TableProps<TKnowledgeBaseBaseRecord>['columns'] = [
		Table.SELECTION_COLUMN,
		{
			title: '商品名稱',
			dataIndex: 'name',
			width: 300,
			key: 'name',
			render: (_, record) => (
				<ProductName<TKnowledgeBaseBaseRecord>
					record={record}
					onClick={onClick(record)}
				/>
			),
		},
		{
			title: '狀態',
			dataIndex: 'status',
			width: 80,
			key: 'status',
			render: (_, record) => (
				<Tag color={getPostStatus(record?.status)?.color}>
					{getPostStatus(record?.status)?.label}
				</Tag>
			),
		},
		{
			title: '總銷量',
			dataIndex: 'total_sales',
			width: 150,
			key: 'total_sales',
			render: (_, record) => (
				<ProductTotalSales
					record={record}
					optionParams={{
						endpoint: 'knowledge-bases/options',
					}}
				/>
			),
		},
		{
			title: '價格',
			dataIndex: 'price',
			width: 150,
			key: 'price',
			render: (_, record) => <ProductPrice record={record} />,
		},
		{
			title: '商品分類 / 商品標籤',
			dataIndex: 'category_ids',
			key: 'category_ids',
			render: (_, record) => <ProductCat record={record} />,
		},
		{
			title: '操作',
			dataIndex: '_actions',
			key: '_actions',
			render: (_, record) => <ProductAction record={record} />,
		},
	]

	return columns
}

export default useColumns
