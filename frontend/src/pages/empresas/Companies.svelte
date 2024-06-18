<script>
	import { Link } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Table from '../../components/ui/Table.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { Companies } from '../../store/CompaniesStore'

	let toastElement = null
	let toastText = 'La empresa se ha registrado correctamente'
	let variant = 'success'
	$: showToast = false

	const handleDelete = id => e => {
		Companies.deleteCompany(id).then(() => {
			showToast = true
			toastText = 'Registro eliminado correctament'
			variant = 'danger'
		})
	}
</script>

<Container>
	<header class="w-full flex justify-between items-center">
		<h2 class="text-3xl text-light font-semibold mb-4">Empresas</h2>
		<div class="flex gap-2 justify-between items-center">
			<form class="flex gap-2 justify-between items-center">
				<button
					class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-green-600"
				>
					Buscar
				</button>
				<input
					type="text"
					class="border focus:outline focus:outline-1 px-4 py-2 rounded"
					placeholder="Buscar empresas..."
				/>
			</form>
			<Link
				to="/empresas/agregar_empresa"
				class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
			>
				Agregar Empresa
			</Link>
		</div>
	</header>
	<Table
		headers={[
			'Nombre',
			'Contacto',
			'Direccion',
			'Telefono',
			'Correo Electrónico'
		]}
		title="Registros de empresas"
	>
		<tbody class="text-xs">
			{#each $Companies as company}
				<tr>
					<td class="px-4 py-2 whitespace-nowrap min-w-max">
						{company.empresa}
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
		</tbody>
	</Table>
</Container>

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
