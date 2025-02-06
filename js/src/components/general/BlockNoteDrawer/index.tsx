import React from 'react'
import { BlockNoteDrawer as ATBlockNoteDrawer, useEnv } from 'antd-toolkit'
import { useApiUrl } from '@refinedev/core'

export const BlockNoteDrawer = () => {
	const apiUrl = useApiUrl()
	const { NONCE } = useEnv()

	return (
		<ATBlockNoteDrawer
			useBlockNoteParams={{
				apiConfig: {
					apiEndpoint: `${apiUrl}/upload`,
					headers: new Headers({
						'X-WP-Nonce': NONCE,
					}),
				},
			}}
		/>
	)
}
