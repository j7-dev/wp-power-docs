import React from 'react'
import { BlockNoteDrawer as ATBlockNoteDrawer } from 'antd-toolkit'
import { useApiUrl } from '@refinedev/core'

export const BlockNoteDrawer = () => {
	const apiUrl = useApiUrl()
	return (
		<ATBlockNoteDrawer
			useBlockNoteParams={{
				apiConfig: {
					apiEndpoint: `${apiUrl}/upload`,
					headers: new Headers({
						'X-WP-Nonce': window?.wpApiSettings?.nonce,
					}),
				},
			}}
		/>
	)
}
