import { memo } from 'react'
import { Form, Input, Select, Typography } from 'antd'
import {
	termToOptions,
	defaultSelectProps,
	Heading,
	Switch,
	CopyText,
	useEnv,
} from 'antd-toolkit'
import { FileUpload } from 'antd-toolkit/wp'
import { DescriptionDrawer } from '@/components/general'

const { Item } = Form
const { Text } = Typography

const DescriptionComponent = () => {
	const form = Form.useFormInstance()

	// const { options, isLoading } = useOptions({ endpoint: 'courses/options' })
	const { product_cats = [], product_tags = [] } = {}
	const { SITE_URL = '', DOCS_POST_TYPE = '' } = useEnv()

	const docsUrl = `${SITE_URL}/${DOCS_POST_TYPE}/`
	const watchSlug = Form.useWatch(['slug'], form)

	return (
		<>
			<div className="mb-12">
				<Heading>知識庫發佈</Heading>

				<Item name={['slug']} label="網址">
					<Input
						addonBefore={
							<Text className="max-w-[25rem] text-left" ellipsis>
								{docsUrl}
							</Text>
						}
						addonAfter={<CopyText text={`${docsUrl}${watchSlug}`} />}
					/>
				</Item>

				<Switch
					formItemProps={{
						name: ['status'],
						label: '發佈',
						initialValue: 'publish',
						getValueProps: (value) => ({ value: value === 'publish' }),
						normalize: (value) => (value ? 'publish' : 'draft'),
						hidden: true,
					}}
					switchProps={{
						checkedChildren: '發佈',
						unCheckedChildren: '草稿',
					}}
				/>
			</div>
			<div className="mb-12">
				<Heading>知識庫描述</Heading>

				<Item name={['id']} hidden normalize={() => undefined} />

				<div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
					<Item name={['name']} label="知識庫名稱">
						<Input allowClear />
					</Item>
					{/* TODO: 知識庫分類 */}
					<Item name={['category_ids']} label="知識庫分類" initialValue={[]}>
						<Select
							{...defaultSelectProps}
							options={termToOptions(product_cats)}
							placeholder="可多選"
							disabled
						/>
					</Item>
					<Item name={['tag_ids']} label="知識庫標籤" initialValue={[]}>
						<Select
							{...defaultSelectProps}
							options={termToOptions(product_tags)}
							placeholder="可多選"
							disabled
						/>
					</Item>
				</div>
				<div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
					<Item
						name={['short_description']}
						label="知識庫簡介"
						className="col-span-2"
					>
						<Input.TextArea rows={8} allowClear />
					</Item>
					<DescriptionDrawer />

					<div className="mb-8">
						<label className="mb-3 tw-block">知識庫封面圖</label>
						<FileUpload />
					</div>

					<Switch
						formItemProps={{
							name: ['need_access'],
							label: '購買才能觀看',
						}}
						switchProps={{
							checkedChildren: '需授權',
							unCheckedChildren: '免費',
						}}
					/>
				</div>
			</div>
		</>
	)
}

export const Description = memo(DescriptionComponent)
