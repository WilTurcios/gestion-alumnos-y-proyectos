<script>
	import { Link } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Table from '../../components/ui/Table.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { Groups } from '../../store/GroupsStore.js'
	import { getGroupById, getGroups } from '../../services/GroupService'

	let toastElement = null
	let toastText = 'El grupo se ha creado correctamente'
	let variant = 'warning'
	let showToast = false

	let search = ''
	let timer

	$: grupos = getGroups()

	const debounce = v => {
		clearTimeout(timer)
		timer = setTimeout(() => {
			search = v
		}, 750)
	}

	let grupo = {
		nombre: null
	}

	let ids_grupos = {
		ids: []
	}

	const handleDeleteMultiple = () => {
		Groups.deleteMutlipleGroups(ids_grupos.ids).then(() => {
			showToast = true
			toastText = 'Grupos eliminados correctamente'
			variant = 'success'
		})
	}

	const handleDelete = id => () => {
		Groups.deleteGroup(id)
			.then(() => {
				showToast = true
				toastText = 'Grupo eliminado correctamente'
				variant = 'success'
			})
			.catch(error => {
				console.error('Error al eliminar el grupo:', error)
			})
	}

	function handleSearch(event) {
		const search = event.target.value
		if (!search) {
			grupos = getGroups()
		}

		clearTimeout(timer)

		timer = setTimeout(() => {
			fetch(`http://localhost/proyecto-DAW/backend/api/grupos?nombre=${search}`)
				.then(res => {
					grupos = res.json()
				})
				.catch(error => {
					console.error('Error fetching groups:', error)
				})
		}, 750)
	}
</script>

<Container>
	<header class="w-full flex justify-between items-center">
		<h2 class="text-3xl text-light font-semibold mb-4">Grupos</h2>
		<div class="flex gap-2 justify-between items-center">
			<form class="flex gap-2 justify-between items-center">
				<input
					type="text"
					class="border focus:outline focus:outline-1 px-4 py-2 rounded"
					placeholder="Buscar grupos..."
					on:keyup={handleSearch}
				/>
			</form>
			<div class="flex gap-2 justify-between items-center">
				<Link
					to="/grupos/agregar_grupo"
					class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
				>
					Agregar Grupo
				</Link>

				<button
					class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
					on:click={handleDeleteMultiple}
					disabled={ids_grupos.ids.length === 0}
				>
					Eliminar Seleccionados
				</button>
			</div>
		</div>
	</header>
	<Table
		headers={['Accion Multiple', 'Nombre del Grupo', 'Acciones']}
		title="Registros de Grupos"
	>
		<tbody class="text-xs">
			{#await grupos}
				<p>Carfando grupos...</p>
			{:then grupos}
				{#each grupos as group}
					<tr>
						<td class="px-4 py-2 whitespace-nowrap min-w-max">
							<input
								type="checkbox"
								bind:group={ids_grupos.ids}
								value={group.id}
							/>
						</td>
						<td class="min-w-max px-4 py-2 whitespace-nowrap">
							{group.nombre}
						</td>
						<td
							class="px-4 py-2 whitespace-nowrap flex justify-center items-center"
						>
							<button
								type="button"
								class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
								on:click={handleDelete(group.id)}>Eliminar</button
							>
							<Link
								to="/grupos/{group.id}"
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
