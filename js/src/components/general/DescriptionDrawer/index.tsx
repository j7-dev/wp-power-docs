import { FC, useEffect, lazy, Suspense, useMemo, memo } from 'react'
import { Button, Form, Drawer, Input, Alert, Dropdown, Tooltip } from 'antd'
import { LoadingOutlined } from '@ant-design/icons'
import { useEditorDrawer } from './hooks'
import { useApiUrl } from '@refinedev/core'
import { useBlockNote } from '@/components/general'
import { useEnv } from 'antd-toolkit'

const { Item } = Form

const BlockNote = lazy(() =>
	import('@/components/general').then((module) => ({
		default: module.BlockNote,
	})),
)

type TDescriptionDrawerProps = {
	name?: string | string[]
}
const DescriptionDrawerComponent: FC<TDescriptionDrawerProps | undefined> = (
	props,
) => {
	const name = props?.name || ['description']
	const { NONCE, SITE_URL, ELEMENTOR_ENABLED } = useEnv()
	const apiUrl = useApiUrl()
	const form = Form.useFormInstance()
	const watchId = Form.useWatch(['id'], form)

	const { blockNoteViewProps, html, setHTML } = useBlockNote({
		apiConfig: {
			apiEndpoint: `${apiUrl}/upload`,
			headers: new Headers({
				'X-WP-Nonce': NONCE,
			}),
		},
	})

	const { editor } = blockNoteViewProps

	const { drawerProps, show, close, open } = useEditorDrawer()

	const handleConfirm = () => {
		form.setFieldValue(name, html)
		close()
	}

	useEffect(() => {
		if (watchId && open) {
			const description = form.getFieldValue(name)

			async function loadInitialHTML() {
				const blocks = await editor.tryParseHTMLToBlocks(description)
				editor.replaceBlocks(editor.document, blocks)
			}
			loadInitialHTML()
		}

		if (!watchId && open) {
			setHTML('')
			editor.removeBlocks(editor.document)
		}
	}, [watchId, open])

	return (
		<div>
			<p className="mb-2">編輯</p>
			{!!ELEMENTOR_ENABLED && (
				<Dropdown.Button
					trigger={['click']}
					placement="bottomLeft"
					menu={{
						items: [
							{
								key: 'elementor',
								label: (
									<Tooltip title={getTooltipTitle(ELEMENTOR_ENABLED)}>
										{ELEMENTOR_ENABLED ? (
											<a
												href={`${SITE_URL}/wp-admin/post.php?post=${watchId}&action=elementor`}
												target="_blank"
												rel="noreferrer"
											>
												或 使用 Elementor 編輯器
											</a>
										) : (
											'或 使用 Elementor 編輯器'
										)}
									</Tooltip>
								),
								disabled: !ELEMENTOR_ENABLED,
							},
						],
					}}
					onClick={show}
				>
					使用 Power 編輯器
				</Dropdown.Button>
			)}

			{!ELEMENTOR_ENABLED && (
				<Button type="default" onClick={show}>
					使用 Power 編輯器
				</Button>
			)}

			<Item name={name} label={`完整介紹`} hidden>
				<Input.TextArea rows={8} disabled />
			</Item>
			<Drawer
				{...drawerProps}
				extra={
					<div className="flex gap-x-4">
						<Button
							type="default"
							danger
							onClick={() => {
								setHTML('')
								editor.removeBlocks(editor.document)
							}}
						>
							一鍵清空內容
						</Button>
						<Button type="primary" onClick={handleConfirm}>
							確認變更
						</Button>
					</div>
				}
			>
				<Alert
					className="mb-4"
					message="注意事項"
					description={
						<ol className="pl-4">
							<li>
								確認變更只是確認內文有沒有變更，您還是需要儲存才會存進資料庫
							</li>
							<li>可以使用 WordPress shortcode</li>
							{/* <li>圖片在前台顯示皆為 100% ，縮小圖片並不影響前台顯示</li> */}
							<li>未來有新功能持續擴充</li>
						</ol>
					}
					type="warning"
					showIcon
					closable
				/>
				<Suspense
					fallback={
						<Button type="text" icon={<LoadingOutlined />}>
							Loading...
						</Button>
					}
				>
					<BlockNote {...blockNoteViewProps} />
				</Suspense>
			</Drawer>
		</div>
	)
}

function getTooltipTitle(canElementor: boolean) {
	if (canElementor) {
		return ''
	}
	return '您必須安裝並啟用 Elementor 外掛才可以使用 Elementor 編輯'
}

export const DescriptionDrawer = memo(DescriptionDrawerComponent)
