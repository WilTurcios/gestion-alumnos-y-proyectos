export function getGroups() {
	return fetch('http://localhost/proyecto-DAW/backend/api/grupos')
		.then(res => res.json())
		.then(data => data)
}

export function getGroupById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/grupos/${id}`)
		.then(res => res.json())
		.then(data => data)
}

export function deleteGroupById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/grupos/${id}`, {
		method: 'DELETE'
	})
		.then(res => res.json())
		.then(data => {
			let [grupo] = data
			return grupo
		})
}

export function addGroup(group) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/grupos`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(group)
	})
		.then(res => res.json())
		.then(data => data)
}
