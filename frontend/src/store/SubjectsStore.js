import { writable } from 'svelte/store'

const createSubjectStore = async () => {
	let INITIAL_COMPANIES = []
	try {
		const res = await fetch(
			'http://localhost/proyecto-DAW/backend/api/materias'
		)
		if (!res.ok) {
			throw new Error(`HTTP error! status: ${res.status}`)
		}
		const data = await res.json()
		INITIAL_COMPANIES = data
	} catch (error) {
		console.error('Error fetching subjects:', error)
	}

	const { subscribe, set, update } = writable(INITIAL_COMPANIES)

	return {
		subscribe,
		addSubject: async subject => {
			const res = await fetch(
				'http://localhost/proyecto-DAW/backend/api/materias',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(subject)
				}
			)

			if (!res.ok) console.log('bad')
			const [newsubject] = await res.json()

			console.log(newsubject)

			update(subjects => [...subjects, newsubject])

			return newsubject
		},
		deleteSubject: async id => {
			try {
				const res = await fetch(
					`http://localhost/proyecto-DAW/backend/api/materias/`,
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
				update(subjects => subjects.filter(subject => subject.id !== id))
			} catch (error) {
				console.error('Error deleting subject:', error)
			}
		},
		deleteMutlipleSubjects: async ids => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/materias',
					{
						method: 'DELETE',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({ ids })
					}
				)

				if (res.ok) {
					update(subjects => {
						return subjects.filter(subject => !ids.includes(subject.id))
					})
				} else {
					console.error('Error al intentar eliminar materias.')
				}
			} catch (error) {
				console.error('Error de red:', error)
			}
		},
		updateSubject: async subjectToUpdate => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/materias',
					{
						method: 'PUT',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(subjectToUpdate)
					}
				)
				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}
				update(subjects =>
					subjects.map(subject =>
						subject.id === subjectToUpdate.id
							? { ...subject, ...subjectToUpdate }
							: subject
					)
				)
			} catch (error) {
				console.error('Failed to update subject:', error)
			}
		}
	}
}

export const Subjects = await createSubjectStore()
