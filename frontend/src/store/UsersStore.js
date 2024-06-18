import { writable } from 'svelte/store'

const createUsersStore = async () => {
	let INITIAL_USERS = []
	try {
		const res = await fetch(
			'http://localhost/proyecto-DAW/backend/api/usuarios'
		)
		if (!res.ok) {
			throw new Error(`HTTP error! status: ${res.status}`)
		}
		const data = await res.json()
		INITIAL_USERS = data
	} catch (error) {
		console.error('Error fetching users:', error)
	}

	const { subscribe, set, update } = writable(INITIAL_USERS)

	return {
		subscribe,
		addUser: async user => {
			const res = await fetch(
				'http://localhost/proyecto-DAW/backend/api/usuarios',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(user)
				}
			)

			if (!res.ok) console.log('bad')
			const [newUser] = await res.json()

			update(users => [...users, newUser])

			return newUser
		},
		deleteUser: async id => {
			try {
				const res = await fetch(
					`http://localhost/proyecto-DAW/backend/api/usuarios/`,
					{
						method: 'DELETE',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({ id })
					}
				)
				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}
				update(users => users.filter(user => user.id !== id))
			} catch (error) {
				console.error('Error deleting user:', error)
			}
		},
		updateUser: async userToUpdate => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/usuarios',
					{
						method: 'PUT',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(userToUpdate)
					}
				)
				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}
				update(users =>
					users.map(user =>
						user.id === userToUpdate.id ? { ...user, ...userToUpdate } : user
					)
				)
			} catch (error) {
				console.error('Failed to update user:', error)
			}
		}
	}
}

export const Users = await createUsersStore()
