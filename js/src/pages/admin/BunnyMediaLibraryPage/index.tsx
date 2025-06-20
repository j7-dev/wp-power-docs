import React, { useState } from 'react'
import { MediaLibrary, TBunnyVideo } from 'antd-toolkit/refine'

export const BunnyMediaLibraryPage = () => {
	const [selectedItems, setSelectedItems] = useState<TBunnyVideo[]>([])
	return (
		<MediaLibrary
			selectedItems={selectedItems}
			setSelectedItems={setSelectedItems}
			limit={undefined}
		/>
	)
}
