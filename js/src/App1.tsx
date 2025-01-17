/* eslint-disable quote-props */
import '@/assets/scss/index.scss'
import { Refine } from '@refinedev/core'

import {
	ThemedLayoutV2,
	ThemedSiderV2,
	ErrorComponent,
	useNotificationProvider,
} from '@refinedev/antd'
import '@refinedev/antd/dist/reset.css'
import routerBindings, {
	UnsavedChangesNotifier,
	NavigateToResource,
} from '@refinedev/react-router-v6'
import { BackToWpAdmin } from 'antd-toolkit/wp'
import { dataProvider } from 'antd-toolkit/refine'
import {
	DocsList,
	DocsEdit,
	Users,
	DocAccess,
	Shortcodes,
	Settings,
} from '@/pages/admin'

// import { dataProvider as bunnyStreamDataProvider } from './rest-data-provider/bunny-stream'

import { HashRouter, Outlet, Route, Routes } from 'react-router-dom'
import { apiUrl, kebab, siteUrl } from '@/utils'
import { resources } from '@/resources'

// import MediaLibraryPage from '@/pages/admin/MediaLibraryPage'
import { ConfigProvider } from 'antd'
import { ReactQueryDevtools } from '@tanstack/react-query-devtools'

// import { MediaLibraryIndicator } from '@/bunny'

/**
 * TODO
 * [ ] bunny 移動到 antd-toolkit
 *
 */

function App() {
	return (
		<HashRouter>
			<Refine
				dataProvider={{
					default: dataProvider(`${apiUrl}/${kebab}`),
					'wp-rest': dataProvider(`${apiUrl}/wp/v2`),
					'wc-rest': dataProvider(`${apiUrl}/wc/v3`),
					'wc-store': dataProvider(`${apiUrl}/wc/store/v1`),

					// 'bunny-stream': bunnyStreamDataProvider(
					// 	'https://video.bunnycdn.com/library',
					// ),
				}}
				notificationProvider={useNotificationProvider}
				routerProvider={routerBindings}
				resources={resources}
				options={{
					syncWithLocation: false,
					warnWhenUnsavedChanges: true,
					projectId: 'power-docs',
					reactQuery: {
						clientConfig: {
							defaultOptions: {
								queries: {
									staleTime: 1000 * 60 * 10,
									cacheTime: 1000 * 60 * 10,
									retry: 0,
								},
							},
						},
					},
				}}
			>
				<Routes>
					<Route
						element={
							<ConfigProvider
								theme={{
									components: {
										Collapse: {
											contentPadding: '8px 8px',
										},
									},
								}}
							>
								<ThemedLayoutV2
									Sider={(props) => <ThemedSiderV2 {...props} fixed />}
									Title={({ collapsed }) => (
										<BackToWpAdmin collapsed={collapsed} siteUrl={siteUrl} />
									)}
								>
									<Outlet />
									{/* <MediaLibraryIndicator /> */}
								</ThemedLayoutV2>
							</ConfigProvider>
						}
					>
						<Route index element={<NavigateToResource resource="docs" />} />
						<Route path="docs">
							<Route index element={<DocsList />} />
							<Route path="edit/:id" element={<DocsEdit />} />
						</Route>
						<Route path="users" element={<Users />} />
						<Route path="doc-access" element={<DocAccess />} />
						<Route path="shortcodes" element={<Shortcodes />} />
						<Route path="settings" element={<Settings />} />

						{/* <Route path="media-library" element={<MediaLibraryPage />} /> */}

						<Route path="*" element={<ErrorComponent />} />
					</Route>
				</Routes>
				<UnsavedChangesNotifier />
				<ReactQueryDevtools initialIsOpen={false} />
			</Refine>
		</HashRouter>
	)
}

export default App
