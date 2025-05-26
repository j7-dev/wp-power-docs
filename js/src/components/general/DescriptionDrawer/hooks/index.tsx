import { useState } from 'react'
import { DrawerProps } from 'antd'
import { useWindowSize } from '@uidotdev/usehooks'

/**
 * Editor Drawer
 * 用於 Notion 編輯器，沒有用 Form 包住
 *
 */

type TUseEditorDrawerParams = {
	drawerProps?: DrawerProps
}

export function useEditorDrawer(props?: TUseEditorDrawerParams) {
	const drawerProps = props?.drawerProps || {}
	const { width } = useWindowSize()
	const [open, setOpen] = useState(false)

	const show = () => {
		setOpen(true)
	}

	const close = () => {
		setOpen(false)
	}

	const mergedDrawerProps: DrawerProps = {
		title: `編輯內容`,
		forceRender: false,
		placement: 'left',
		onClose: close,
		open,
		width:
			(width || 576) > 1280
				? 'min(75%, calc(100% - 20rem))'
				: 'min(90%, calc(100% - 5rem))',
		...drawerProps,
	}

	return {
		open,
		setOpen,
		show,
		close,
		drawerProps: mergedDrawerProps,
	}
}
