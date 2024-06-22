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
	const token = JSON.parse(localStorage.getItem('token'))
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

export function deleteSubjectById(data) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/materias`, {
		method: 'DELETE',
		headers: {
			Authorization: `Bearer ${token}`
		},
		body: JSON.stringify(data)
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
export function updateSubject(subject) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/materias`, {
		method: 'PUT',
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
export function deleteCriterion(id) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(
		`http://localhost/proyecto-DAW/backend/api/materias/eliminar_criterio`,
		{
			method: 'DELETE',
			headers: {
				'Content-Type': 'application/json',
				Authorization: `Bearer ${token}`
			},
			body: JSON.stringify({ id })
		}
	)
		.then(res => res.json())
		.then(data => data)
}

export function updateCriterion(criterion) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(
		`http://localhost/proyecto-DAW/backend/api/materias/actualizar_criterio`,
		{
			method: 'PUT',
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
