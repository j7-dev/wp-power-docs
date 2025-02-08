import React from 'react'
import { UserTable } from '@/components/user'

export const Users = () => {
	return (
		<>
			<UserTable canGrantCourseAccess={true} />
		</>
	)
}
