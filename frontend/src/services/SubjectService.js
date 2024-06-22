export function getSubjects() {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch('http://localhost/proyecto-DAW/backend/api/materias', {
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function getSubjectById(id) {
	const token = localStorage.getItem('token')
	return fetch(`http://localhost/proyecto-DAW/backend/api/materias/${id}`, {
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => {
			let [materia] = data
			return materia
		})
}

export function deleteSubjectById(id) {
	const token = localStorage.getItem('token')
	return fetch(`http://localhost/proyecto-DAW/backend/api/materias/${id}`, {
		method: 'DELETE',
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function addSubject(subject) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/materias`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			Authorization: `Bearer ${token}`
		},
		body: JSON.stringify(subject)
	})
		.then(res => res.json())
		.then(data => data)
}

export function addCriterionToSubject(criterion) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(
		`http://localhost/proyecto-DAW/backend/api/materias/agregar_criterio`,
		{
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				Authorization: `Bearer ${token}`
			},
			body: JSON.stringify(criterion)
		}
	)
		.then(res => res.json())
		.then(data => data)
}
