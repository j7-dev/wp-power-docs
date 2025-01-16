import {
	TableOutlined,
	CodeOutlined,
	SettingOutlined,
	ProductOutlined,
} from '@ant-design/icons'
import { FaPhotoVideo } from 'react-icons/fa'
import { PiStudent } from 'react-icons/pi'

export const resources = [
	{
		name: 'knowledge-bases',
		list: '/knowledge-bases',
		edit: '/knowledge-bases/edit/:id',
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
		name: 'knowledge-base-access',
		list: '/knowledge-base-access',
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
	{
		name: 'shortcodes',
		list: '/shortcodes',
		meta: {
			label: '短代碼',
			icon: <CodeOutlined />,
		},
	},
	{
		name: 'settings',
		list: '/settings',
		meta: {
			label: '設定',
			icon: <SettingOutlined />,
		},
	},
]
