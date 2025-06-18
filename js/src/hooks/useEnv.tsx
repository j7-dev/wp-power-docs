import { useEnv as useATEnv, TEnv } from 'antd-toolkit'
import { AxiosInstance } from 'axios'

type Env = TEnv & {
	AXIOS_INSTANCE: AxiosInstance
	APP1_SELECTOR: string
	ELEMENTOR_ENABLED: boolean
}

export const useEnv = () => {
	const values = useATEnv<Env>()
	return values
}
