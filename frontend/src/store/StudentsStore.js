import { writable } from 'svelte/store'

const createStudentsStore = async () => {
	let students = []
	try {
		const res = await fetch(
			'http://localhost/proyecto-DAW/backend/api/estudiantes'
		)
		if (!res.ok) {
			throw new Error(`HTTP error! status: ${res.status}`)
		}
		const data = await res.json()
		students = data
	} catch (error) {
		console.error('Error fetching students:', error)
	}

	const { subscribe, set, update } = writable(students)

	return {
		subscribe,
		addStudent: async student => {
			console.log(student)
			const res = await fetch(
				'http://localhost/proyecto-DAW/backend/api/estudiantes',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(student)
				}
			)

			if (!res.ok) console.log('bad')
			const [new_student] = await res.json()

			update(students => [...students, new_student])

			return new_student
		},
		deleteStudent: async id => {
			const res = await fetch(
				'http://localhost/proyecto-DAW/backend/api/estudiantes',
				{
					method: 'DELETE',

					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({ id })
				}
			)

			if (res.ok)
				update(students => {
					return students.filter(student => student.id !== id)
				})
		},
		deleteMutlipleStudents: async ids => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/estudiantes',
					{
						method: 'DELETE',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({ ids })
					}
				)

				if (res.ok) {
					update(students => {
						return students.filter(student => !ids.includes(student.id))
					})
				} else {
					console.error('Error al intentar eliminar estudiantes.')
				}
			} catch (error) {
				console.error('Error de red:', error)
			}
		},
		updateStudent: async studentToUpdate => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/estudiantes',
					{
						method: 'PUT',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(studentToUpdate)
					}
				)

				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}

				set(students => {
					return students.map(st =>
						st.id === studentToUpdate.id ? { ...st, ...studentToUpdate } : st
					)
				})
			} catch (error) {
				console.error('Failed to update student:', error)
			}
		}
	}
}

export const Students = await createStudentsStore()
