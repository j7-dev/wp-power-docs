import React from 'react'
import { TableProps, Typography } from 'antd'
import { TUserRecord, TAVLCourse } from '@/pages/admin/Users/types'
import { UserName } from 'antd-toolkit/wp'
import { WatchStatusTag, getWatchStatusTagTooltip } from 'antd-toolkit'

type TUseColumnsParams = {
	onClick?: (_record: TUserRecord | undefined) => () => void
}

const { Text } = Typography

const useColumns = (params?: TUseColumnsParams) => {
	const handleClick = params?.onClick
	const columns: TableProps<TUserRecord>['columns'] = [
		{
			title: '會員',
			dataIndex: 'id',
			width: 180,
			render: (_, record) => <UserName record={record} onClick={handleClick} />,
		},
		{
			title: '已開通知識庫',
			dataIndex: 'avl_courses',
			width: 240,
			render: (avl_courses: TAVLCourse[], { id: user_id, display_name }) => {
				return avl_courses?.map(
					({ id: course_id, name: course_name, expire_date }) => (
						<div
							key={course_id}
							className="grid grid-cols-[1fr_4rem_12rem] gap-1 my-1"
						>
							<div>
								<Text
									className="cursor-pointer"
									ellipsis={{
										tooltip: (
											<>
												<span className="text-gray-400 text-xs">
													#{course_id}
												</span>{' '}
												{course_name || '未知的課程名稱'}
											</>
										),
									}}
								>
									<span className="text-gray-400 text-xs">#{course_id}</span>{' '}
									{course_name || '未知的課程名稱'}
								</Text>
							</div>

							<div className="text-center">
								<WatchStatusTag expireDate={expire_date} />
							</div>

							<div className="text-left">
								{getWatchStatusTagTooltip(expire_date)}
							</div>
						</div>
					),
				)
			},
		},
		{
			title: '註冊時間',
			dataIndex: 'user_registered',
			width: 180,
			render: (user_registered, record) => (
				<>
					<p className="m-0">已註冊 {record?.user_registered_human}</p>
					<p className="m-0 text-gray-400 text-xs">{user_registered}</p>
				</>
			),
		},
	]

	return columns
}

export default useColumns
