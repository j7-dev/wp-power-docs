import { memo } from 'react'
import { Form, Input, Select } from 'antd'
import { keyLabelMapper } from '@/components/product/ProductTable/utils'
import useOptions from '@/components/product/ProductTable/hooks/useOptions'
import {
	siteUrl,
	course_permalink_structure,
	defaultSelectProps,
} from '@/utils'
import { Heading } from '@/components/general'
import { FiSwitch, VideoInput, DescriptionDrawer } from '@/components/formItem'
import { termToOptions } from 'antd-toolkit'
import { FileUpload } from 'antd-toolkit/wp'

const { Item } = Form

const DescriptionComponent = () => {
	const form = Form.useFormInstance()
	const { options, isLoading } = useOptions({ endpoint: 'courses/options' })
	const { product_cats = [], product_tags = [] } = options

	return (
		<>
			<div className="mb-12">
				<Heading>課程發佈</Heading>

				<FiSwitch
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
				<Heading>課程描述</Heading>

				<Item name={['id']} hidden normalize={() => undefined} />

				<div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
					<Item name={['name']} label="課程名稱">
						<Input allowClear />
					</Item>
					<Item
						name={['category_ids']}
						label={keyLabelMapper('product_category_id')}
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
						label={keyLabelMapper('product_tag_id')}
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
						label="課程簡介"
						className="col-span-2"
					>
						<Input.TextArea rows={8} allowClear />
					</Item>
					<DescriptionDrawer />

					<div className="mb-8">
						<label className="mb-3 tw-block">課程封面圖</label>
						<FileUpload />
						<Item hidden name={['files']} label="課程封面圖">
							<Input />
						</Item>
						<Item hidden name={['images']} initialValue={[]}>
							<Input />
						</Item>
					</div>
					<div className="mb-8">
						<p className="mb-3">課程封面影片</p>
						<VideoInput name={['feature_video']} />
					</div>
					<div className="mb-8">
						<p className="mb-3">課程免費試看影片</p>
						<VideoInput name={['trial_video']} />
					</div>
				</div>
			</div>
		</>
	)
}

export const Description = memo(DescriptionComponent)
