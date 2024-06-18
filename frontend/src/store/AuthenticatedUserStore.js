import { writable } from 'svelte/store'

const createAuthenticatedUserStore = async () => {
	// let INITIAL_USER = []
	// try {
	// 	const res = await fetch(
	// 		'http://localhost/proyecto-DAW/backend/api/auth/usuario_autenticado'
	// 	)
	// 	if (!res.ok) {
	// 		throw new Error(`HTTP error! status: ${res.status}`)
	// 	}
	// 	const data = await res.json()
	// 	INITIAL_USER = data
	// } catch (error) {
	// 	console.error('Error fetching groups:', error)
	// }
	const { subscribe, set, update } = writable(null)

	return {
		subscribe,
		login: async user => {
			const res = await fetch(
				'http://localhost/proyecto-DAW/backend/api/auth/login',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(user)
				}
			)

			if (!res.ok) console.log('bad')
			const [authenticatedUser] = await res.json()

			set(authenticatedUser)
		},
		logout: async () => {
			try {
				const res = await fetch(
					`http://localhost/proyecto-DAW/backend/api/auth/logout`,
					{
						method: 'POST'
					}
				)

				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}
				set(null)
			} catch (error) {
				console.error('Error deleting user:', error)
			}
		}
	}
}

export const AuthenticatedUser = await createAuthenticatedUserStore()
