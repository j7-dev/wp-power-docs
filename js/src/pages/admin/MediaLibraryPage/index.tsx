import React, { useState } from 'react'
import { MediaLibrary, TBunnyVideo } from 'antd-toolkit/refine'

export const MediaLibraryPage = () => {
	const [selectedVideos, setSelectedVideos] = useState<TBunnyVideo[]>([])
	return (
		<MediaLibrary
			selectedVideos={selectedVideos}
			setSelectedVideos={setSelectedVideos}
		/>
	)
}
