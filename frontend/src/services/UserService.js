export function getUsers() {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch('http://localhost/proyecto-DAW/backend/api/usuarios', {
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function getJudges() {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch('http://localhost/proyecto-DAW/backend/api/jurados', {
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function getAssesors() {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch('http://localhost/proyecto-DAW/backend/api/asesores', {
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function getUserById(id) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/usuarios/${id}`, {
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => {
			let [usuario] = data
			return usuario
		})
}

export function deleteUserById(id) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/usuarios/${id}`, {
		method: 'DELETE',
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => data)
}
export function deleteMultipleUsers(ids) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/usuarios/`, {
		method: 'DELETE',
		headers: {
			Authorization: `Bearer ${token}`,
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			ids: ids // Asegúrate de enviar el array como parte del objeto
		})
	})
		.then(res => {
			if (!res.ok) {
				throw new Error('Error al eliminar usuarios')
			}
			return res.json()
		})
		.then(data => data)
		.catch(err => {
			console.error('Error:', err)
			throw err
		})
}

export function addUser(user) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/usuarios`, {
		method: 'POST',
		headers: {
			Authorization: `Bearer ${token}`,
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(user)
	})
		.then(res => res.json())
		.then(data => data)
}
export function updateUser(user) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/usuarios`, {
		method: 'PUT',
		headers: {
			Authorization: `Bearer ${token}`,
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(user)
	})
		.then(res => res.json())
		.then(data => data)
}

export function logout(user) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/auth/logout`, {
		method: 'POST'
	})
		.then(res => res.json())
		.then(data => {
			localStorage.removeItem('token')
		})
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
		.then(res => res.json().then(data => ({ status: res.status, body: data }))) // Parsear el JSON y añadir el estado de la respuesta
		.then(({ status, body }) => {
			if (status !== 200) {
				throw new Error(
					body[0]?.message || 'Error desconocido al iniciar sesión'
				)
			}

			let { token } = body
			localStorage.setItem('token', JSON.stringify(token)) // Guardar el token directamente
			return { success: true, token }
		})
		.catch(error => {
			console.error('Error al iniciar sesión:', error) // Registro de errores en la consola
			return { success: false, message: error.message }
		})
}

export function getAuthenticatedUser(user) {
	let token = JSON.parse(localStorage.getItem('token'))
	return fetch(
		`http://localhost/proyecto-DAW/backend/api/auth/usuario_autenticado`,
		{
			method: 'POST',
			headers: {
				Authorization: `Bearer ${token}`
			}
		}
	)
		.then(res => res.json())
		.then(([usuario]) => usuario)
		.catch(e => console.log(e))
}
