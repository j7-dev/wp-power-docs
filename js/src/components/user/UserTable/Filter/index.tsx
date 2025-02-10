import { memo } from 'react'
import { FormProps, Form, Input, Button, FormInstance, Select } from 'antd'
import { UndoOutlined, SearchOutlined } from '@ant-design/icons'
import { useDocSelect } from '@/hooks'

export type TFilterValues = {
	search?: string
	granted_docs?: string[]
	include?: string[]
}

const { Item } = Form

const Filter = ({ formProps }: { formProps: FormProps }) => {
	const form = formProps?.form as FormInstance<TFilterValues>
	const { selectProps } = useDocSelect()

	return (
		<div className="mb-2">
			<Form {...formProps} layout="vertical">
				<div className="grid grid-cols-2 md:grid-cols-3 2xl:grid-cols-5 gap-x-4">
					<Item name="search" label="關鍵字搜尋">
						<Input
							placeholder="可以輸入用戶ID, 帳號, Email, 顯示名稱"
							allowClear
						/>
					</Item>
					<Item
						name="granted_docs"
						label="已開通指定知識庫"
						className="col-span-2"
					>
						<Select {...selectProps} />
					</Item>

					<Item name="include" label="包含指定用戶" className="col-span-2">
						<Select mode="tags" placeholder="輸入用戶 ID" allowClear />
					</Item>
					{/* <Item name="bought_product_ids" label="已買過指定商品的用戶">
						<Input
							placeholder="可以輸入用戶ID, 帳號, Email, 顯示名稱"
							allowClear
						/>
					</Item> */}
				</div>
				<div className="grid grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-x-4">
					<Button
						htmlType="submit"
						type="primary"
						className="w-full"
						icon={<SearchOutlined />}
					>
						篩選
					</Button>
					<Button
						type="default"
						className="w-full"
						onClick={() => {
							form.resetFields()
							form.submit()
						}}
						icon={<UndoOutlined />}
					>
						重置
					</Button>
				</div>
			</Form>
		</div>
	)
}

export default memo(Filter)
