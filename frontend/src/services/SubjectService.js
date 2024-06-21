export function getSubjects() {
	return fetch('http://localhost/proyecto-DAW/backend/api/materias')
		.then(res => res.json())
		.then(data => data)
}

export function getSubjectById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/materias/${id}`)
		.then(res => res.json())
		.then(data => {
			let [materia] = data
			return materia
		})
}

export function deleteSubjectById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/materias/${id}`, {
		method: 'DELETE'
	})
		.then(res => res.json())
		.then(data => data)
}

export function addSubject(subject) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/materias`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(subject)
	})
		.then(res => res.json())
		.then(data => data)
}
