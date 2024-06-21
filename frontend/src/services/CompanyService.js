export function getCompanies() {
	return fetch('http://localhost/proyecto-DAW/backend/api/empresas')
		.then(res => res.json())
		.then(data => data)
}

export function getCompanyById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/empresas/${id}`)
		.then(res => res.json())
		.then(data => {
			let [empresa] = data
			return empresa
		})
}

export function deleteCompanyById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/empresas/${id}`, {
		method: 'DELETE'
	})
		.then(res => res.json())
		.then(data => data)
}

export function addCompany(company) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/empresas`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(company)
	})
		.then(res => res.json())
		.then(data => data)
}
