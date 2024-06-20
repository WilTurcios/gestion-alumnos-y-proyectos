import { writable } from 'svelte/store'

const createGroupsStore = async () => {
	let INITIAL_GROUPS = []
	try {
		const res = await fetch('http://localhost/proyecto-DAW/backend/api/grupos')
		if (!res.ok) {
			throw new Error(`HTTP error! status: ${res.status}`)
		}
		const data = await res.json()
		INITIAL_GROUPS = data
	} catch (error) {
		console.error('Error fetching groups:', error)
	}

	const { subscribe, set, update } = writable(INITIAL_GROUPS)

	return {
		subscribe,
		addGroup: async group => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/grupos',
					{
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(group)
					}
				)
				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}
				const [newGroup] = await res.json()
				update(groups => [...groups, newGroup])
			} catch (error) {
				console.error('Error adding group:', error)
			}
		},
		deleteGroup: async id => {
			try {
				const res = await fetch(
					`http://localhost/proyecto-DAW/backend/api/grupos/${id}`,
					{
						method: 'DELETE'
					}
				)
				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}

				update(groups => groups.filter(group => group.id !== id))
			} catch (error) {
				console.error('Error deleting group:', error)
			}
		},
		deleteMutlipleGroups: async ids => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/grupos',
					{
						method: 'DELETE',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({ ids })
					}
				)

				if (res.ok) {
					update(groups => {
						return groups.filter(group => !ids.includes(group.id))
					})
				} else {
					console.error('Error al intentar eliminar grupos.')
				}
			} catch (error) {
				console.error('Error de red:', error)
			}
		},
		updateGroup: async groupToUpdate => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/grupos',
					{
						method: 'PUT',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(groupToUpdate)
					}
				)
				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}
				update(groups =>
					groups.map(group =>
						group.id === groupToUpdate.id
							? { ...group, ...groupToUpdate }
							: group
					)
				)
			} catch (error) {
				console.error('Failed to update group:', error)
			}
		}
	}
}

export const Groups = await createGroupsStore()
