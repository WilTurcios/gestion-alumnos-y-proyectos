export function getUsers() {
	return fetch('http://localhost/proyecto-DAW/backend/api/usuarios')
		.then(res => res.json())
		.then(data => data)
}

export function getUserById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/usuarios/${id}`)
		.then(res => res.json())
		.then(data => {
			let [usuario] = data
			return usuario
		})
}

export function deleteUserById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/usuarios/${id}`, {
		method: 'DELETE'
	})
		.then(res => res.json())
		.then(data => data)
}

export function addUser(user) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/usuarios`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(user)
	})
		.then(res => res.json())
		.then(data => data)
		.catch(e => console.log(e))
}
export function login(user) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/auth/login`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(user)
	})
		.then(res => res.json())
		.then(data => {
			let [usuario] = data
			return usuario
		})
		.catch(e => console.log(e))
}

export function logout(user) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/auth/logout`, {
		method: 'POST'
	})
		.then(res => res.json())
		.then(data => data)
		.catch(e => console.log(e))
}

export function getAuthenticatedUser(user) {
	return fetch(
		`http://localhost/proyecto-DAW/backend/api/auth/usuario_autenticado`,
		{
			method: 'POST'
		}
	)
		.then(res => res.json())
		.then(data => data)
		.catch(e => console.log(e))
}
