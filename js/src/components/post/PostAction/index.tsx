import React, { FC, memo } from 'react'
import { TDocBaseRecord } from '@/pages/admin/Docs/List/types'
import ToggleVisibility from './ToggleVisibility'
import { ExportOutlined } from '@ant-design/icons'
import { Tooltip, Button } from 'antd'
import { DuplicateButton } from '@/components/general'

export const PostActionComponent: FC<{
	record: TDocBaseRecord
}> = ({ record }) => {
	return (
		<div className="flex gap-1">
			<DuplicateButton
				id={record?.id}
				invalidateProps={{ resource: 'docs' }}
				tooltipProps={{ title: '複製知識庫' }}
			/>
			<Tooltip title="開啟知識庫">
				<Button
					type="text"
					href={record?.permalink}
					target="_blank"
					rel="noreferrer"
					icon={<ExportOutlined className="text-gray-500" />}
					className="m-0"
				/>
			</Tooltip>
			<ToggleVisibility record={record} />
		</div>
	)
}

export const PostAction = memo(PostActionComponent)