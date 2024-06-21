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

	const { subscribe, set, update } = writable(INITIAL_USER)

	return {
		subscribe,
		set
	}
}

export const AuthenticatedUser = await createAuthenticatedUserStore()
