import { TreeData, TreeNode } from '@ant-design/pro-editor'
import { TDocRecord } from '@/pages/admin/Docs/List/types'

/**
 * 將章節 TDocRecord 傳換成 TreeNode<TDocRecord>
 *
 * @param {TDocRecord} chapter
 * @return {TreeNode<TDocRecord>}
 */

export function postToTreeNode(chapter: TDocRecord): TreeNode<TDocRecord> {
	const { id, children, ...rest } = chapter
	return {
		id,
		content: {
			id,
			...rest,
		},
		children: children?.map(postToTreeNode) || [],
		showExtra: false,
		collapsed: true, // 預設為折疊
	}
}

/**
 * 將 TreeData<TDocRecord> 轉換成 Create API 傳送的參數
 * 只抓出順序、parent_id、id
 *
 * @param {TreeData<TDocRecord>} treeData
 * @return {TParam[]}
 */

export type TParam = {
	id: string
	depth: number
	menu_order: number
	parent_id?: string
	name?: string
}

export function treeToParams(
	treeData: TreeData<TDocRecord>,
	topParentId: string,
): TParam[] {
	const depth0 = treeData?.map((node, index) => {
		return {
			id: node.id as string,
			depth: 0,
			menu_order: index,
			name: node?.content?.name,
			parent_id: topParentId,
		}

		// parent_id 不帶就不變更
	})
	const depth1 = treeData
		?.map((parentNode) => {
			const nodes = parentNode.children?.map((node, index) => {
				return {
					id: node.id as string,
					depth: 1,
					menu_order: index,
					parent_id: parentNode.id as string,
					name: node?.content?.name,
				}
			})
			return nodes
		})
		.flat()

	return [...depth0, ...depth1]
}
