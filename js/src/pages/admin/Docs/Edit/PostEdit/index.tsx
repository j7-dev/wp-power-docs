import React, { memo, useEffect } from 'react'
import { Form, Input, Switch } from 'antd'
import { toFormData } from 'antd-toolkit'
import { BlockNoteDrawer } from '@/components/general'
import { TDocRecord } from '@/pages/admin/Docs/List/types'
import { Edit, useForm } from '@refinedev/antd'
import { ExclamationCircleFilled } from '@ant-design/icons'

const { Item } = Form

const PostEditComponent = ({ record }: { record: TDocRecord }) => {
	const { id, name } = record

	// 初始化資料
	const { formProps, form, saveButtonProps, mutation, onFinish } = useForm({
		action: 'edit',
		resource: 'posts',
		id,
		redirect: false,
		queryOptions: {
			enabled: false,
		},
		invalidates: ['list', 'detail'],
		warnWhenUnsavedChanges: true,
	})

	// 取得課程深度，用來判斷是否為子章節
	const watchDepth = Form.useWatch(['depth'], form)
	const label = watchDepth === 0 ? '章節' : '單元'
	const watchStatus = Form.useWatch(['status'], form)

	useEffect(() => {
		form.setFieldsValue(record)
	}, [record])

	// 將 [] 轉為 '[]'，例如，清除原本分類時，如果空的，前端會是 undefined，轉成 formData 時會遺失
	const handleOnFinish = (values: Partial<TDocRecord>) => {
		onFinish(toFormData(values))
	}

	return (
		<Edit
			resource="posts"
			recordItemId={id}
			breadcrumb={null}
			goBack={null}
			headerButtons={() => null}
			title={
				<div className="pl-4">
					《編輯》 {name} <span className="text-gray-400 text-xs">#{id}</span>
				</div>
			}
			saveButtonProps={{
				...saveButtonProps,
				children: `儲存${label}`,
				icon: null,
				loading: mutation?.isLoading,
			}}
			footerButtons={({ defaultButtons }) => (
				<>
					<div className="text-red-500 font-bold mr-8">
						<ExclamationCircleFilled />{' '}
						章節/單元和課程是分開儲存的，編輯完成請記得儲存
					</div>

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
			wrapperProps={{
				style: {
					boxShadow: '0px 0px 16px 0px #ddd',
					paddingTop: '1rem',
					borderRadius: '0.5rem',
				},
			}}
		>
			<Form {...formProps} onFinish={handleOnFinish} layout="vertical">
				<Item name={['name']} label={'名稱'}>
					<Input />
				</Item>
				<div className="mb-8">
					<BlockNoteDrawer />
				</div>

				<Item name={['status']} hidden />
				<Item name={['depth']} hidden />
				<Item name={['id']} hidden />
			</Form>
		</Edit>
	)
}

export const PostEdit = memo(PostEditComponent)
