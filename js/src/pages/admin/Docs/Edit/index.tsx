import { memo, useState } from 'react'
import { Edit, useForm } from '@refinedev/antd'
import { Tabs, TabsProps, Form, Switch, Modal, Button } from 'antd'
import { Description, SortablePosts } from './tabs'
import { useAtom } from 'jotai'
import { TDocRecord } from '@/pages/admin/Docs/List/types'
import { useParsed } from '@refinedev/core'
import { PostEdit } from './PostEdit'
import { UserTable } from '@/components/user'

import {
	mediaLibraryAtom,
	MediaLibrary,
	TBunnyVideo,
} from 'antd-toolkit/refine'

// TAB items
const defaultItems: TabsProps['items'] = [
	{
		key: 'Description',
		forceRender: true,
		label: '描述',
		children: <Description />,
	},
	{
		key: 'SortablePosts',
		forceRender: false,
		label: '文章管理',
		children: <SortablePosts PostEdit={PostEdit} />,
	},
]

const EditComponent = () => {
	const { id } = useParsed()

	// 初始化資料
	const { formProps, form, saveButtonProps, query, mutation, onFinish } =
		useForm<TDocRecord>({
			action: 'edit',
			resource: 'posts',
			id,
			redirect: false,
			queryMeta: {
				variables: {
					with_description: 'true',
					meta_keys: [
						'need_access',
						'pd_keywords',
						'bg_images',
						'editor',
						'pd_keywords_label',
					], // 額外暴露的欄位
				},
			},
		})

	// 顯示
	const watchName = Form.useWatch(['name'], form)
	const watchId = Form.useWatch(['id'], form)
	const watchStatus = Form.useWatch(['status'], form)
	const watchNeedAccess: boolean =
		Form.useWatch(['need_access'], form) === 'yes'

	const items = watchNeedAccess
		? [
				...defaultItems,
				{
					key: 'Users',
					forceRender: false,
					label: '權限管理',
					children: (
						<UserTable
							canGrantCourseAccess={true}
							cardProps={{
								showCard: false,
							}}
							initialValues={{
								granted_docs: [watchId],
							}}
						/>
					),
				},
			]
		: defaultItems

	// 處理 media library
	// 處理 media library
	const [mediaLibrary, setMediaLibrary] = useAtom(mediaLibraryAtom)
	const { modalProps } = mediaLibrary
	const [selectedVideos, setSelectedVideos] = useState<TBunnyVideo[]>([])

	return (
		<div className="sticky-card-actions sticky-tabs-nav">
			<Edit
				resource="posts"
				title={
					<>
						{watchName}{' '}
						<span className="text-gray-400 text-xs">#{watchId}</span>
					</>
				}
				headerButtons={() => null}
				saveButtonProps={{
					...saveButtonProps,
					children: '儲存',
					icon: null,
					loading: mutation?.isLoading,
				}}
				footerButtons={({ defaultButtons }) => (
					<>
						<Switch
							className="mr-4"
							checkedChildren="發佈"
							unCheckedChildren="草稿"
							value={watchStatus === 'publish'}
							onChange={(checked) => {
								form.setFieldValue(['status'], checked ? 'publish' : 'draft')
							}}
						/>
						{defaultButtons}
					</>
				)}
				isLoading={query?.isLoading}
			>
				<Form {...formProps} layout="vertical">
					<Tabs
						items={items}
						tabBarExtraContent={
							<a
								href={query?.data?.data?.permalink}
								target="_blank"
								rel="noreferrer"
							>
								<Button className="ml-4" type="default">
									前往知識庫
								</Button>
							</a>
						}
					/>
				</Form>
			</Edit>

			<Modal
				{...modalProps}
				onCancel={() => {
					setMediaLibrary((prev) => ({
						...prev,
						modalProps: {
							...prev.modalProps,
							open: false,
						},
					}))
				}}
			>
				<div className="max-h-[75vh] overflow-x-hidden overflow-y-auto pr-4">
					<MediaLibrary
						mediaLibraryProps={{
							selectedVideos,
							setSelectedVideos,
							limit: 1,
							selectButtonProps: {
								onClick: () => {
									setMediaLibrary((prev) => ({
										...prev,
										modalProps: {
											...prev.modalProps,
											open: false,
										},
										confirmedSelectedVideos: selectedVideos,
									}))
								},
							},
						}}
					/>
				</div>
			</Modal>
		</div>
	)
}

export const DocsEdit = memo(EditComponent)
