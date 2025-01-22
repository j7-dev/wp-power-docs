import React from 'react'
import { BlockNoteDrawer as ATBlockNoteDrawer } from 'antd-toolkit'

export const BlockNoteDrawer = () => {
	return (
		<ATBlockNoteDrawer
			useBlockNoteParams={{
				apiConfig: {
					apiEndpoint: '',
				},
			}}
		/>
	)
}
