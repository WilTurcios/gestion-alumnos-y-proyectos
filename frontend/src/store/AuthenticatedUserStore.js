import { writable } from 'svelte/store'

const createAuthenticatedUserStore = async () => {
	let INITIAL_USER = null
	try {
		const res = await fetch(
			'http://localhost/proyecto-DAW/backend/auth/usuario_autenticado',
			{
				method: 'POST'
			}
		)
		if (!res.ok) {
			INITIAL_USER = null
		} else {
			const data = await res.json()
			INITIAL_USER = data
		}
	} catch (error) {
		console.error('Error fetching user:', error)
	}

	// if(INITIAL_USER.es_admin && INITIAL_USER.acceso_sistema)
	const { subscribe, set, update } = writable(INITIAL_USER)

	return {
		subscribe,
		login: async user => {
			const res = await fetch(
				'http://localhost/proyecto-DAW/backend/auth/login',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(user)
				}
			)

			if (!res.ok) {
				throw new Error(
					'Error de autenticacion: Usuario o contraseña incorrectos'
				)
			} else {
				const [authenticatedUser] = await res.json()

				set(authenticatedUser)
			}
		},
		logout: async () => {
			try {
				const res = await fetch(
					`http://localhost/proyecto-DAW/backend/auth/logout`,
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
