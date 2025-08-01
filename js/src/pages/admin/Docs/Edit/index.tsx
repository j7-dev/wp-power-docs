import { memo, useState } from 'react'
import { Edit, useForm } from '@refinedev/antd'
import { Tabs, Switch, Button } from 'antd'
import { Description, SortablePosts } from './tabs'
import { TDocRecord, TDocBaseRecord } from '@/pages/admin/Docs/List/types'
import { HttpError, useParsed } from '@refinedev/core'
import { PostEdit } from './PostEdit'
import { UserTable } from '@/components/user'
import { toFormData } from 'antd-toolkit'

const EditComponent = () => {
	const { id } = useParsed()

	// 初始化資料
	const { formProps, form, saveButtonProps, query, mutation, onFinish } =
		useForm<TDocRecord, HttpError, Partial<TDocRecord>>({
			action: 'edit',
			resource: 'posts',
			id,
			redirect: false,
			successNotification: false,
			errorNotification: false,
			queryMeta: {
				variables: {
					with_description: 'true',
					meta_keys: [
						'need_access',
						'bg_images',
						'pd_keywords',
						'pd_keywords_label',
						'unauthorized_redirect_url',
					], // 額外暴露的欄位
				},
			},
		})

	const record: TDocBaseRecord | undefined = query?.data?.data

	// 將 [] 轉為 '[]'，例如，清除原本分類時，如果空的，前端會是 undefined，轉成 formData 時會遺失
	const handleOnFinish = () => {
		const values = form.getFieldsValue()
		const { short_description, ...rest } = values

		onFinish(toFormData(rest) as Partial<TDocRecord>)
	}

	const items = [
		{
			key: 'Description',
			forceRender: true,
			label: '描述',
			children: <Description formProps={formProps} record={record} />,
		},
		{
			key: 'SortablePosts',
			forceRender: false,
			label: '文章管理',
			children: <SortablePosts PostEdit={PostEdit} />,
		},
		{
			key: 'Users',
			forceRender: false,
			label: '權限管理',
			disabled: record?.need_access !== 'yes',
			children: (
				<UserTable
					canGrantCourseAccess={true}
					cardProps={{
						showCard: false,
					}}
					initialValues={{
						granted_docs: [record?.id || ''],
					}}
				/>
			),
		},
	]

	const [activeTab, setActiveTab] = useState('Description')

	return (
		<div className="sticky-card-actions sticky-tabs-nav">
			<Edit
				resource="posts"
				title={
					<>
						{record?.name}{' '}
						<span className="text-gray-400 text-xs">#{record?.id}</span>
					</>
				}
				headerButtons={() => null}
				saveButtonProps={{
					...saveButtonProps,
					children: '儲存',
					icon: null,
					loading: mutation?.isLoading,
					onClick: handleOnFinish,
				}}
				footerButtons={({ defaultButtons }) =>
					activeTab === 'Description' ? (
						<>
							<Switch
								className="mr-4"
								checkedChildren="發佈"
								unCheckedChildren="草稿"
								value={record?.status === 'publish'}
								onChange={(checked) => {
									form.setFieldValue(['status'], checked ? 'publish' : 'draft')
								}}
							/>
							{defaultButtons}
						</>
					) : null
				}
				isLoading={query?.isLoading}
			>
				<Tabs
					activeKey={activeTab}
					onChange={setActiveTab}
					items={items}
					tabBarExtraContent={
						<Button
							className="ml-4"
							type="default"
							href={query?.data?.data?.permalink}
							target="_blank"
							rel="noreferrer"
						>
							前往知識庫
						</Button>
					}
				/>
			</Edit>
		</div>
	)
}

export const DocsEdit = memo(EditComponent)
