import { AuthenticatedUser } from '../store/AuthenticatedUserStore.js'

export function getCompanies() {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch('http://localhost/proyecto-DAW/backend/api/empresas', {
		headers: {
			Authorization: 'Bearer ' + token
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function getCompanyById(id) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/empresas/${id}`, {
		headers: {
			Authorization: 'Bearer ' + token
		}
	})
		.then(res => res.json())
		.then(data => {
			let [empresa] = data
			return empresa
		})
}

export function deleteCompanyById(id) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/empresas/${id}`, {
		method: 'DELETE',
		headers: {
			Authorization: 'Bearer ' + token
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function addCompany(company) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/empresas`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			Authorization: 'Bearer ' + token
		},
		body: JSON.stringify(company)
	})
		.then(res => res.json())
		.then(data => data)
}
export function updateCompany(company) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/empresas`, {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/json',
			Authorization: 'Bearer ' + token
		},
		body: JSON.stringify(company)
	})
		.then(res => res.json())
		.then(data => data)
}
