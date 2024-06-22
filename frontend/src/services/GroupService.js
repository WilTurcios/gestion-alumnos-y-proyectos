export function getGroups() {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch('http://localhost/proyecto-DAW/backend/api/grupos', {
		headers: {
			Authorization: 'Bearer ' + token
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function getGroupById(id) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/grupos/${id}`, {
		headers: {
			Authorization: 'Bearer ' + token
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function deleteGroupById(id) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/grupos/${id}`, {
		method: 'DELETE',
		headers: {
			Authorization: 'Bearer ' + token
		}
	})
		.then(res => res.json())
		.then(data => {
			let [grupo] = data
			return grupo
		})
}

export function addGroup(group) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/grupos`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			Authorization: 'Bearer ' + token
		},
		body: JSON.stringify(group)
	})
		.then(res => res.json())
		.then(data => data)
}

export function updateGroup(group) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/grupos`, {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/json',
			Authorization: 'Bearer ' + token
		},
		body: JSON.stringify(group)
	})
		.then(res => res.json())
		.then(data => data)
}
