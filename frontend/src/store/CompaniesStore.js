import { writable } from 'svelte/store'

const createCompaniesStore = async () => {
	let INITIAL_COMPANIES = []
	try {
		const res = await fetch(
			'http://localhost/proyecto-DAW/backend/api/empresas'
		)
		if (!res.ok) {
			throw new Error(`HTTP error! status: ${res.status}`)
		}
		const data = await res.json()
		INITIAL_COMPANIES = data
	} catch (error) {
		console.error('Error fetching companys:', error)
	}

	const { subscribe, set, update } = writable(INITIAL_COMPANIES)

	return {
		subscribe,
		addCompany: async company => {
			const res = await fetch(
				'http://localhost/proyecto-DAW/backend/api/empresas',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(company)
				}
			)

			if (!res.ok) console.log('bad')
			const [newCompany] = await res.json()

			console.log(newCompany)

			update(companys => [...companys, newCompany])

			return newCompany
		},
		deleteCompany: async id => {
			try {
				const res = await fetch(
					`http://localhost/proyecto-DAW/backend/api/empresas/`,
					{
						method: 'DELETE',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({ id })
					}
				)
				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}
				update(companys => companys.filter(company => company.id !== id))
			} catch (error) {
				console.error('Error deleting company:', error)
			}
		},
		deleteMutlipleCompanies: async ids => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/empresas',
					{
						method: 'DELETE',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({ ids })
					}
				)

				if (res.ok) {
					update(companies => {
						return companies.filter(company => !ids.includes(company.id))
					})
				} else {
					console.error('Error al intentar eliminar empresas.')
				}
			} catch (error) {
				console.error('Error de red:', error)
			}
		},
		updateCompany: async companyToUpdate => {
			try {
				const res = await fetch(
					'http://localhost/proyecto-DAW/backend/api/empresas',
					{
						method: 'PUT',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(companyToUpdate)
					}
				)
				if (!res.ok) {
					throw new Error(`HTTP error! status: ${res.status}`)
				}
				update(companys =>
					companys.map(company =>
						company.id === companyToUpdate.id
							? { ...company, ...companyToUpdate }
							: company
					)
				)
			} catch (error) {
				console.error('Failed to update company:', error)
			}
		}
	}
}

export const Companies = await createCompaniesStore()
