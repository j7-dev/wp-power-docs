import React, { memo } from 'react'
import { Select, Button, Space, message, Form, SelectProps } from 'antd'
import {
	useCustomMutation,
	useApiUrl,
	useInvalidate,
	UseSelectProps,
	HttpError,
	MetaQuery,
} from '@refinedev/core'
import { useItemSelect } from 'antd-toolkit/wp'
import { TDocBaseRecord } from '@/pages/admin/Docs/List/types'
import { TLimit } from 'antd-toolkit'

type TBindItemsProps = {
	product_ids: string[] // 要綁在哪些商品上
	useCustomMutationParams: UseCustomMutationParams<TDocBaseRecord> // 綁定 API，最少需要填 url
	useSelectProps: UseSelectProps<TDocBaseRecord, HttpError, TDocBaseRecord> // 選擇資源的 API useSelectProps
	selectProps?: SelectProps // 選擇資源的 select props
	label?: string // 資源名稱
}

/**
 * 通用的綁定項目元件
 * 可以把項目的使用期限綁定在商品上
 * @interface TBindItemsProps
 * @property {string[]}                                                  product_ids                   - 要綁定的商品 ID 陣列
 * @property {UseCustomMutationParams<TDocBaseRecord>}                   useCustomMutationParams       - 綁定 API 參數
 * @property {UseSelectProps<TDocBaseRecord, HttpError, TDocBaseRecord>} useSelectProps                - 選擇資源 API props
 * @property {SelectProps}                                               [selectProps]                 - Select 元件 props
 * @property {string}                                                    [label]                       - 資源名稱
 */
const BindItemsComponent = ({
	product_ids,
	label = '',
	useSelectProps,
	selectProps,
	useCustomMutationParams,
}: TBindItemsProps) => {
	const { selectProps: selectResourceProps, itemIds: item_ids } =
		useItemSelect<TDocBaseRecord>({
			selectProps,
			useSelectProps,
		})

	const { mutate, isLoading } = useCustomMutation()
	const apiUrl = useApiUrl()
	const invalidate = useInvalidate()
	const form = Form.useFormInstance()
	const resource = useSelectProps.resource

	const handleClick = () => {
		const values: TLimit = form.getFieldsValue()
		mutate(
			{
				url: `${apiUrl}/products/bind-courses`,
				method: 'post',
				values: {
					product_ids,
					item_ids,
					...values,
				},
				config: {
					headers: {
						'Content-Type': 'multipart/form-data;',
					},
				},
				...(useCustomMutationParams as any),
			},
			{
				onSuccess: () => {
					message.success({
						content: `綁定${label}成功！`,
						key: `bind-${resource}`,
					})
					invalidate({
						resource: 'products',
						invalidates: ['list'],
					})
				},
				onError: () => {
					message.error({
						content: `綁定${label}失敗！`,
						key: `bind-${resource}`,
					})
				},
			},
		)
	}

	return (
		<>
			{label && <label className="tw-block mb-2">{label}</label>}
			<Space.Compact className="w-full">
				<Select {...selectResourceProps} />
				<Button
					type="primary"
					loading={isLoading}
					disabled={!product_ids.length || !item_ids.length}
					onClick={handleClick}
				>
					綁定其他{label}
				</Button>
			</Space.Compact>
		</>
	)
}

export const BindItems = memo(BindItemsComponent)

// refine useCustomMutation 的型別
type UseCustomMutationParams<TVariables> = {
	url: string
	method?: 'post' | 'put' | 'patch' | 'delete'
	values?: TVariables
	meta?: MetaQuery
	metaData?: MetaQuery
	dataProviderName?: string
	config?: {
		headers?: {}
	}
}
