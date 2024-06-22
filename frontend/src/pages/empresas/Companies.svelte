<script>
	import { Link } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Table from '../../components/ui/Table.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import {
		deleteCompanyById,
		getCompanies
	} from '../../services/CompanyService'
	import { AuthenticatedUser } from '../../store/AuthenticatedUserStore'

	let toastElement = null
	let toastText = 'La empresa se ha registrado correctamente'
	let variant = 'success'
	let timer
	$: showToast = false

	$: empresas = getCompanies()

	let ids_empresas = {
		ids: []
	}

	const handleDeleteMultiple = () => {
		// Companies.deleteMutlipleCompanies(ids_empresas.ids).then(() => {
		// 	showToast = true
		// 	toastText = 'Estudiantes eliminados correctamente'
		// 	variant = 'danger'
		// })
	}

	const handleDelete = id => e => {
		deleteCompanyById(id).then(() => {
			showToast = true
			toastText = 'Registro eliminado correctament'
			variant = 'danger'

			empresas = getCompanies()
		})
	}

	function handleSearch(event) {
		const search = event.target.value
		if (!search) {
			empresas = getCompanies()
		}

		clearTimeout(timer)

		timer = setTimeout(() => {
			fetch(
				`http://localhost/proyecto-DAW/backend/api/empresas?nombre=${search}`
			)
				.then(res => {
					empresas = res.json()
				})
				.catch(error => {
					console.error('Error fetching groups:', error)
				})
		}, 750)
	}
</script>

<Container>
	<header class="w-full flex justify-between items-center">
		<h2 class="text-3xl text-light font-semibold mb-4">Empresas</h2>
		<div class="flex gap-2 justify-between items-center">
			<form class="flex gap-2 justify-between items-center">
				<input
					type="text"
					class="border focus:outline focus:outline-1 px-4 py-2 rounded"
					placeholder="Buscar empresas..."
					on:keyup={handleSearch}
				/>
			</form>
			<div class="flex gap-2 justify-between items-center">
				<Link
					to="/empresas/agregar_empresa"
					class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
				>
					Agregar Empresa
				</Link>
				<button
					class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
					on:click={handleDeleteMultiple}
					disabled={ids_empresas.ids.length === 0}
				>
					Eliminar Seleccionados
				</button>
			</div>
		</div>
	</header>
	<Table
		headers={[
			'Acción Multiple',
			'Nombre',
			'Contacto',
			'Direccion',
			'Telefono',
			'Correo Electrónico'
		]}
		title="Registros de empresas"
	>
		<tbody class="text-xs">
			{#await empresas}
				<p>Cargando empresas</p>
			{:then empresas}
				{#each empresas as company}
					<tr>
						<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
							<input
								type="checkbox"
								bind:group={ids_empresas.ids}
								value={company.id}
							/>
						</td>
						<td class="px-4 py-2 whitespace-nowrap min-w-max">
							{company.nombre}
						</td>
						<td class="px-4 py-2 whitespace-nowrap">
							{company.contacto}
						</td>
						<td class="px-4 py-2 whitespace-nowrap">
							{company.direccion}
						</td>
						<td class="px-4 py-2 whitespace-nowrap">
							{company.telefono}
						</td>
						<td class="px-4 py-2 whitespace-nowrap">
							{company.email}
						</td>
						<td
							class="px-4 py-2 whitespace-nowrap flex justify-between items-center"
						>
							<button
								type="button"
								class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
								on:click={handleDelete(company.id)}>Eliminar</button
							>
							<Link
								to="/empresas/{company.id}"
								class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
							>
								Editar
							</Link>
						</td>
					</tr>
				{/each}
			{/await}
		</tbody>
	</Table>
</Container>

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
