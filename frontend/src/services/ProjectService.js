export function getProjects() {
	return fetch('http://localhost/proyecto-DAW/backend/api/proyectos')
		.then(res => res.json())
		.then(data => data)
}

export function getProjectById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/proyectos/${id}`)
		.then(res => res.json())
		.then(data => {
			let [proyecto] = data
			return proyecto
		})
}

export function deleteProjectById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/proyectos/${id}`, {
		method: 'DELETE'
	})
		.then(res => res.json())
		.then(data => data)
}

export function addProject(project) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/proyectos`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(project)
	})
		.then(res => res.json())
		.then(data => data)
}
