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
import {
	DocsList,
	DocsEdit,
	Users,
	DocAccess,
	Shortcodes,
	Settings,
	MediaLibraryPage,
} from '@/pages/admin'
import { HashRouter, Outlet, Route, Routes } from 'react-router-dom'
import { API_URL, KEBAB, SITE_URL } from '@/utils'
import { resources } from '@/resources'
import { ConfigProvider } from 'antd'
import { ReactQueryDevtools } from '@tanstack/react-query-devtools'
import {
	dataProvider,
	BunnyProvider,
	MediaLibraryIndicator,
} from 'antd-toolkit/refine'

function App() {
	const { bunny_data_provider_result } = BunnyProvider.useBunny()

	return (
		<HashRouter>
			<Refine
				dataProvider={{
					default: dataProvider(`${API_URL}/v2/powerhouse`),
					'wp-rest': dataProvider(`${API_URL}/wp/v2`),
					'wc-rest': dataProvider(`${API_URL}/wc/v3`),
					'wc-store': dataProvider(`${API_URL}/wc/store/v1`),
					'bunny-stream': bunny_data_provider_result,
					'power-docs': dataProvider(`${API_URL}/${KEBAB}`),
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
										<BackToWpAdmin collapsed={collapsed} siteUrl={SITE_URL} />
									)}
								>
									<Outlet />
									<MediaLibraryIndicator />
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

						<Route path="media-library" element={<MediaLibraryPage />} />

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
