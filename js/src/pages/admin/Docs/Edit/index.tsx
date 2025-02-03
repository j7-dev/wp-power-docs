import React, { memo } from 'react'
import { Edit, useForm } from '@refinedev/antd'
import { Tabs, TabsProps, Form, Switch, Modal, Button, Space } from 'antd'
import { Description, SortablePosts } from './tabs'

// import { SortableDocs } from '@/components/post'

// import { mediaLibraryAtom } from '@/pages/admin/Docs/atom'
import { useAtom } from 'jotai'

// import { MediaLibrary } from '@/bunny'
// import { TBunnyVideo } from '@/bunny/types'
import { TDocRecord } from '@/pages/admin/Docs/List/types'
import { SITE_URL } from '@/utils'
import { toFormData } from 'antd-toolkit'
import { useParsed } from '@refinedev/core'
import { PostEdit } from './PostEdit'

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
				},
			},
		})

	// TAB items
	const items: TabsProps['items'] = [
		{
			key: 'Description',
			forceRender: true,
			label: '描述',
			children: <Description />,
		},
		{
			key: 'SortablePosts',
			forceRender: false,
			label: '章節管理',
			children: <SortablePosts PostEdit={PostEdit} />,
		},
		{
			key: 'CourseStudents',
			forceRender: true,
			label: '權限管理',
			children: <>權限管理</>,
		},
	]

	// 顯示
	const watchName = Form.useWatch(['name'], form)
	const watchId = Form.useWatch(['id'], form)
	const watchStatus = Form.useWatch(['status'], form)

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

			{/* <Modal
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
						limit={limit}
						selectedVideos={selectedVideos}
						setSelectedVideos={selectedVideosSetter}
						selectButtonProps={{
							onClick: () => {
								setMediaLibrary((prev) => ({
									...prev,
									modalProps: {
										...prev.modalProps,
										open: false,
									},
								}))
								setMediaLibrary((prev) => ({
									...prev,
									confirmedSelectedVideos: selectedVideos,
								}))
								if (mediaLibraryForm && name) {
									mediaLibraryForm.setFieldValue(name, {
										type: 'bunny-stream-api',
										id: selectedVideos?.[0]?.guid || '',
										meta: {},
									})
								}
							},
						}}
					/>
				</div>
			</Modal> */}
		</div>
	)
}

export const DocsEdit = memo(EditComponent)
