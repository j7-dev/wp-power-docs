import { memo } from 'react'
import { Form, Input, Select } from 'antd'
import {
	termToOptions,
	defaultSelectProps,
	Heading,
	Switch,
	CopyText,
	PluginProvider,
} from 'antd-toolkit'
import { FileUpload, productKeyLabelMapper } from 'antd-toolkit/wp'
import { BlockNoteDrawer } from '@/components/general'

const { Item } = Form

const DescriptionComponent = () => {
	const form = Form.useFormInstance()

	// const { options, isLoading } = useOptions({ endpoint: 'courses/options' })
	const { product_cats = [], product_tags = [] } = {}
	const { SITE_URL = '', DOCS_POST_TYPE = '' } = PluginProvider.usePlugin()
	const docsUrl = `${SITE_URL}/${DOCS_POST_TYPE}/`
	const slug = Form.useWatch(['slug'], form)

	return (
		<>
			<div className="mb-12">
				<Heading>知識庫發佈</Heading>

				<Item name={['slug']} label="網址">
					<Input
						addonBefore={docsUrl}
						addonAfter={
							<>
								<CopyText text={`${docsUrl}${slug}`} />
							</>
						}
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
					<Item
						name={['category_ids']}
						label={productKeyLabelMapper('product_category_id')}
						initialValue={[]}
					>
						<Select
							{...defaultSelectProps}
							options={termToOptions(product_cats)}
							placeholder="可多選"
						/>
					</Item>
					<Item
						name={['tag_ids']}
						label={productKeyLabelMapper('product_tag_id')}
						initialValue={[]}
					>
						<Select
							{...defaultSelectProps}
							options={termToOptions(product_tags)}
							placeholder="可多選"
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
					<BlockNoteDrawer />

					<div className="mb-8">
						<label className="mb-3 tw-block">知識庫封面圖</label>
						<FileUpload />
						<Item hidden name={['files']} label="知識庫封面圖">
							<Input />
						</Item>
						<Item hidden name={['images']} initialValue={[]}>
							<Input />
						</Item>
					</div>
				</div>
			</div>
		</>
	)
}

export const Description = memo(DescriptionComponent)
