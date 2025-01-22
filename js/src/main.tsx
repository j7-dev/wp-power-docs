import React from 'react'
import ReactDOM from 'react-dom/client'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ReactQueryDevtools } from '@tanstack/react-query-devtools'
import {
	app1Selector,
	app2Selector,
	APP_DOMAIN,
	bunny_library_id,
	bunny_cdn_hostname,
	bunny_stream_api_key,
} from '@/utils'
import { StyleProvider } from '@ant-design/cssinjs'

import { PluginProvider } from 'antd-toolkit'
import { BunnyProvider } from 'antd-toolkit/refine'

const App1 = React.lazy(() => import('./App1'))
const App2 = React.lazy(() => import('./App2'))

const queryClient = new QueryClient({
	defaultOptions: {
		queries: {
			refetchOnWindowFocus: false,
			retry: 0,
		},
	},
})

const app1Nodes = document.querySelectorAll(app1Selector)
const app2Nodes = document.querySelectorAll(app2Selector)

const mapping = [
	{
		els: app1Nodes,
		App: App1,
	},
	{
		els: app2Nodes,
		App: App2,
	},
]

mapping.forEach(({ els, App }) => {
	if (!!els) {
		els.forEach((el) => {
			ReactDOM.createRoot(el).render(
				<React.StrictMode>
					<QueryClientProvider client={queryClient}>
						<StyleProvider hashPriority="high">
							<PluginProvider app_domain={APP_DOMAIN}>
								<BunnyProvider
									bunny_library_id={bunny_library_id}
									bunny_cdn_hostname={bunny_cdn_hostname}
									bunny_stream_api_key={bunny_stream_api_key}
								>
									<App />
								</BunnyProvider>
							</PluginProvider>
						</StyleProvider>
						<ReactQueryDevtools initialIsOpen={false} />
					</QueryClientProvider>
				</React.StrictMode>,
			)
		})
	}
})
