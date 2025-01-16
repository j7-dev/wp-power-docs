import { memo } from 'react'
import { useTable } from '@refinedev/antd'
import { Table, FormInstance, Spin, Button, TableProps, Card } from 'antd'
import {
	FilterTags,
	useRowSelection,
	getDefaultPaginationProps,
	defaultTableProps,
} from 'antd-toolkit'
import { getInitialFilters } from 'antd-toolkit/refine'

// import Filter, {
// 	initialFilteredValues,
// } from '@/components/product/ProductTable/Filter'
import { HttpError, useCreate } from '@refinedev/core'
import {
	TKnowledgeBaseBaseRecord,
	TKnowledgeBaseRecord,
} from '@/pages/admin/KnowledgeBases/List/types'

// import { TFilterProps } from '@/components/product/ProductTable/types'
import useValueLabelMapper from '@/pages/admin/KnowledgeBases/List/hooks/useValueLabelMapper'
import useColumns from '@/pages/admin/KnowledgeBases/List/hooks/useColumns'
import { PlusOutlined } from '@ant-design/icons'
import DeleteButton from './DeleteButton'

const Main = () => {
	const { tableProps, searchFormProps } = useTable<
		TKnowledgeBaseBaseRecord,
		HttpError

		// TFilterProps
	>({
		resource: 'knowledge-bases',

		// onSearch,

		// filters: {
		// 	initial: getInitialFilters(initialFilteredValues),
		// },
	})

	const { valueLabelMapper } = useValueLabelMapper()

	const { rowSelection, selectedRowKeys, setSelectedRowKeys } =
		useRowSelection<TKnowledgeBaseBaseRecord>()

	const columns = useColumns()

	const { mutate: create, isLoading: isCreating } = useCreate({
		resource: 'knowledge-bases',
		invalidates: ['list'],
		meta: {
			headers: { 'Content-Type': 'multipart/form-data;' },
		},
	})

	const createKnowledgeBase = () => {
		create({
			values: {
				name: '新知識庫',
			},
		})
	}

	return (
		<Spin spinning={tableProps?.loading as boolean}>
			<Card title="篩選" className="mb-4">
				{/* <Filter
					searchFormProps={searchFormProps}
					optionParams={{
						endpoint: 'knowledge-bases/options',
					}}
				/>
				<div className="mt-2">
					<FilterTags
						form={searchFormProps?.form as FormInstance<TFilterProps>}
						keyLabelMapper={keyLabelMapper}
						valueLabelMapper={valueLabelMapper}
						booleanKeys={[
							'featured',
							'downloadable',
							'virtual',
							'sold_individually',
						]}
					/>
				</div> */}
			</Card>
			<Card>
				<div className="mb-4 flex justify-between">
					<Button
						loading={isCreating}
						type="primary"
						icon={<PlusOutlined />}
						onClick={createKnowledgeBase}
					>
						新增知識庫
					</Button>
					<DeleteButton
						selectedRowKeys={selectedRowKeys}
						setSelectedRowKeys={setSelectedRowKeys}
					/>
				</div>
				<Table
					{...(defaultTableProps as unknown as TableProps<TKnowledgeBaseRecord>)}
					{...tableProps}
					pagination={{
						...tableProps.pagination,
						...getDefaultPaginationProps({ label: '知識庫' }),
					}}
					rowSelection={rowSelection}
					columns={columns}
					rowKey={(record) => record.id.toString()}
				/>
			</Card>
		</Spin>
	)
}

export default memo(Main)
