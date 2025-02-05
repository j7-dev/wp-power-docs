import React from 'react'
import ReactDOM from 'react-dom/client'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ReactQueryDevtools } from '@tanstack/react-query-devtools'
import { APP1_SELECTOR, APP2_SELECTOR, env } from '@/utils'
console.log('⭐  main env:', env)
import { StyleProvider } from '@ant-design/cssinjs'
import { EnvProvider } from 'antd-toolkit'

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

const app1Nodes = document.querySelectorAll(APP1_SELECTOR)
const app2Nodes = document.querySelectorAll(APP2_SELECTOR)

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
						<StyleProvider hashPriority="low">
							<App />
						</StyleProvider>
						<ReactQueryDevtools initialIsOpen={false} />
					</QueryClientProvider>
				</React.StrictMode>,
			)
		})
	}
})
