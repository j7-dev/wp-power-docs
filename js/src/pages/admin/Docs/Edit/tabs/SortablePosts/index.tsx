import { useState, useEffect, memo } from 'react'
import { SortableTree, TreeData } from '@ant-design/pro-editor'
import { TDocRecord } from '@/pages/admin/Docs/List/types'
import { Form, message, Button } from 'antd'
import NodeRender from './NodeRender'
import { postToTreeNode, treeToParams } from './utils'
import {
	useCustomMutation,
	useApiUrl,
	useInvalidate,
	useDeleteMany,
} from '@refinedev/core'
import { isEqual as _isEqual } from 'lodash-es'
import { PopconfirmDelete } from 'antd-toolkit'
import { usePostsList } from './hooks'
import { useAtom } from 'jotai'
import { selectedPostAtom, selectedIdsAtom } from './atom'
import AddPosts from './AddPosts'
import Loading from './Loading'

/**
 * 可排序的章節
 * @param {PostEdit} PostEdit 編輯的畫面由外部傳入
 * @return {React.FC}
 */
const SortablePostsComponent = ({
	PostEdit,
}: {
	PostEdit: React.FC<{ record: TDocRecord }>
}) => {
	const form = Form.useFormInstance()
	const courseId = form?.getFieldValue('id')

	const {
		data: postsData,
		isFetching: isListFetching,
		isLoading: isListLoading,
	} = usePostsList()
	const posts = postsData?.data || []
	const [selectedPost, setSelectedPost] = useAtom(selectedPostAtom)

	const [treeData, setTreeData] = useState<TreeData<TDocRecord>>([])
	const [originTree, setOriginTree] = useState<TreeData<TDocRecord>>([])
	const invalidate = useInvalidate()

	const apiUrl = useApiUrl()
	const { mutate } = useCustomMutation()

	useEffect(() => {
		if (!isListFetching) {
			const postTree = posts?.map(postToTreeNode)
			setTreeData((prev) => {
				// 維持原本的開合狀態
				const newPostTree = postTree.map((item) => ({
					...item,
					collapsed:
						prev?.find((prevItem) => prevItem.id === item.id)?.collapsed ??
						true,
				}))

				return newPostTree
			})
			setOriginTree(postTree)

			// 每次重新排序後，重新取得章節後，重新 set 選擇的章節

			const flattenPosts = posts.reduce((acc, c) => {
				acc.push(c)
				if (c?.children) {
					acc.push(...c?.children)
				}
				return acc
			}, [] as TDocRecord[])

			setSelectedPost(
				flattenPosts.find((c) => c.id === selectedPost?.id) || null,
			)
		}
	}, [isListFetching])

	const handleSave = (data: TreeData<TDocRecord>) => {
		// 這個儲存只存新增，不存章節的細部資料
		message.loading({
			content: '排序儲存中...',
			key: 'posts-sorting',
		})
		const from_tree = treeToParams(originTree, courseId)
		const to_tree = treeToParams(data, courseId)

		mutate(
			{
				url: `${apiUrl}/posts/sort`,
				method: 'post',
				values: {
					from_tree,
					to_tree,
				},
			},
			{
				onSuccess: () => {
					message.success({
						content: '排序儲存成功',
						key: 'posts-sorting',
					})
				},
				onError: () => {
					message.loading({
						content: '排序儲存失敗',
						key: 'posts-sorting',
					})
				},
				onSettled: () => {
					invalidate({
						resource: 'posts',
						invalidates: ['list'],
					})
				},
			},
		)
	}

	const [selectedIds, setSelectedIds] = useAtom(selectedIdsAtom)

	const { mutate: deleteMany, isLoading: isDeleteManyLoading } = useDeleteMany()

	return (
		<>
			<div className="mb-8 flex gap-x-4 justify-between items-center">
				<AddPosts records={posts} />
				<Button
					type="default"
					className="relative top-1"
					disabled={!selectedIds.length}
					onClick={() => setSelectedIds([])}
				>
					清空選取
				</Button>
				<PopconfirmDelete
					popconfirmProps={{
						onConfirm: () =>
							deleteMany(
								{
									resource: 'posts',
									ids: selectedIds,
									mutationMode: 'optimistic',
								},
								{
									onSuccess: () => {
										setSelectedIds([])
									},
								},
							),
					}}
					buttonProps={{
						type: 'primary',
						danger: true,
						className: 'relative top-1',
						loading: isDeleteManyLoading,
						disabled: !selectedIds.length,
						children: `批量刪除 ${selectedIds.length ? `(${selectedIds.length})` : ''}`,
					}}
				/>
			</div>
			<div className="grid grid-cols-1 xl:grid-cols-2 gap-6">
				{isListLoading && <Loading />}
				{!isListLoading && (
					<SortableTree
						hideAdd
						hideRemove
						treeData={treeData}
						onTreeDataChange={(data: TreeData<TDocRecord>) => {
							const from = data?.map((item) => ({
								id: item?.id,
								children: item?.children?.map((child) => child?.id),
								collapsed: false,
							}))
							const to = treeData?.map((item) => ({
								id: item?.id,
								children: item?.children?.map((child) => child?.id),
								collapsed: false,
							}))
							const isEqual = _isEqual(from, to)
							setTreeData(data)
							if (!isEqual) {
								handleSave(data)
							}
						}}
						renderContent={(node) => (
							<NodeRender
								node={node}
								selectedIds={selectedIds}
								setSelectedIds={setSelectedIds}
							/>
						)}
						indentationWidth={48}
						sortableRule={({ activeNode, projected }) => {
							const activeNodeHasChild = !!activeNode.children.length
							const sortable = projected?.depth <= (activeNodeHasChild ? 0 : 1)
							if (!sortable) message.error('超過最大深度，無法執行')
							return sortable
						}}
					/>
				)}

				{selectedPost && <PostEdit record={selectedPost} />}
			</div>
		</>
	)
}

export const SortablePosts = memo(SortablePostsComponent)
