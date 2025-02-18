import React, { memo, useEffect } from 'react'
import { Space, Select, InputNumber, Button, Form } from 'antd'
import { useCreate, useParsed } from '@refinedev/core'
import { TDocRecord } from '@/pages/admin/Docs/List/types'
import { defaultSelectProps, useEnv } from 'antd-toolkit'

const { Item } = Form

type TFormValues = {
	depth: number
	qty: number
	post_parents: string[]
}

const AddPosts = ({ records }: { records: TDocRecord[] }) => {
	const { DOCS_POST_TYPE = '' } = useEnv()
	const { id } = useParsed()

	const [form] = Form.useForm<TFormValues>()
	const watchDepth = Form.useWatch(['depth'], form) || 0
	const watchQty = Form.useWatch(['qty'], form) || 0
	const watchPostParents = Form.useWatch(['post_parents'], form) || []
	const canAdd =
		(watchDepth === 0 && watchQty > 0) ||
		(watchDepth > 0 && watchQty > 0 && watchPostParents?.length > 0)

	const { mutate, isLoading } = useCreate({
		resource: 'posts',
	})

	const handleCreateMany = () => {
		const values = form.getFieldsValue()
		mutate({
			values,
			invalidates: ['list'],
		})
	}

	useEffect(() => {
		form.setFieldValue('post_parents', watchDepth === 0 ? [id] : [])
	}, [watchDepth])

	return (
		<Form form={form} className="w-full">
			<Space.Compact>
				<Button
					type="primary"
					loading={isLoading}
					onClick={handleCreateMany}
					disabled={!canAdd}
				>
					新增
				</Button>

				<Item name={['qty']}>
					<InputNumber className="w-40" addonAfter="個" />
				</Item>
			</Space.Compact>
			<Item name={['post_parents']} className="flex-1" initialValue={[id]} />
			<Item name={['post_type']} initialValue={DOCS_POST_TYPE} hidden />
		</Form>
	)
}

export default memo(AddPosts)
