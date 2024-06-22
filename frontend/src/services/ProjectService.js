export function getProjects() {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch('http://localhost/proyecto-DAW/backend/api/proyectos', {
		headers: {
			Authorization: 'Bearer ' + token
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function getProjectById(id) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/proyectos/${id}`, {
		headers: {
			Authorization: 'Bearer ' + token
		}
	})
		.then(res => res.json())
		.then(data => {
			let [proyecto] = data
			return proyecto
		})
}

export function deleteProjectById(data) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/proyectos/`, {
		method: 'DELETE',
		headers: {
			Authorization: 'Bearer ' + token
		},
		body: JSON.stringify(data)
	})
		.then(res => res.json())
		.then(data => data)
}

export function addProject(project) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/proyectos`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			Authorization: 'Bearer ' + token
		},
		body: JSON.stringify(project)
	})
		.then(res => res.json())
		.then(data => data)
}
export function updateProject(project) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/proyectos`, {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/json',
			Authorization: 'Bearer ' + token
		},
		body: JSON.stringify(project)
	})
		.then(res => res.json())
		.then(data => data)
}
