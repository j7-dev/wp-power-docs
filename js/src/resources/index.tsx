import {
	TableOutlined,
	// CodeOutlined,
	// SettingOutlined,
	ProductOutlined,
} from '@ant-design/icons'
import { FaPhotoVideo } from 'react-icons/fa'
import { PiStudent } from 'react-icons/pi'
import { ResourceProps } from '@refinedev/core'

export const resources: ResourceProps[] = [
	{
		name: 'posts',
		list: '/posts',
		edit: '/posts/edit/:id',
		meta: {
			label: '文章列表',
			hide: true,
		},
	},
	{
		name: 'docs',
		list: '/docs',
		edit: '/docs/edit/:id',
		meta: {
			label: '知識庫列表',
			icon: <TableOutlined />,
		},
	},
	{
		name: 'users',
		list: '/users',
		meta: {
			label: '學員管理',
			icon: <PiStudent />,
		},
	},
	{
		name: 'doc-access',
		list: '/doc-access',
		meta: {
			label: '知識庫權限綁定',
			icon: <ProductOutlined />,
		},
	},
	{
		name: 'media-library',
		list: '/media-library',
		meta: {
			label: 'Bunny 媒體庫',
			icon: <FaPhotoVideo />,
		},
	},
	// {
	// 	name: 'shortcodes',
	// 	list: '/shortcodes',
	// 	meta: {
	// 		label: '短代碼',
	// 		icon: <CodeOutlined />,
	// 	},
	// },
	// {
	// 	name: 'settings',
	// 	list: '/settings',
	// 	meta: {
	// 		label: '設定',
	// 		icon: <SettingOutlined />,
	// 	},
	// },
]
