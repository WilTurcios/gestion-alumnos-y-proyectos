import { writable } from 'svelte/store'
import { getAuthenticatedUser } from '../services/UserService'

const createAuthenticatedUserStore = async () => {
	const usuario = await getAuthenticatedUser()

	const { subscribe, set, update } = writable(usuario)

	return {
		subscribe,
		set,
		update
	}
}

export const AuthenticatedUser = await createAuthenticatedUserStore()
