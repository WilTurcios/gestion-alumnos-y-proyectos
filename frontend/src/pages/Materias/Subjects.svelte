<script>
	import { Link } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Table from '../../components/ui/Table.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { deleteSubjectById, getSubjects } from '../../services/SubjectService'

	let toastElement = null
	let toastText = 'La empresa se ha registrado correctamente'
	let variant = 'success'
	$: showToast = false
	let timer

	let ids_materias = {
		ids: []
	}

	$: materias = getSubjects()

	const handleDeleteMultiple = () => {
		// Subjects.deleteMutlipleSubjects(ids_materias.ids).then(() => {
		// 	showToast = true
		// 	toastText = 'Estudiantes eliminados correctamente'
		// 	variant = 'danger'
		// })
	}

	const handleDelete = id => e => {
		deleteSubjectById(id).then(() => {
			showToast = true
			toastText = 'Registro eliminado correctament'
			variant = 'danger'
		})
	}

	function handleSearch(event) {
		const search = event.target.value
		if (!search) {
			materias = getSubjects()
		}

		clearTimeout(timer)

		timer = setTimeout(() => {
			fetch(
				`http://localhost/proyecto-DAW/backend/api/materias?nombre=${search}`
			)
				.then(res => {
					materias = res.json()
				})
				.catch(error => {
					console.error('Error fetching groups:', error)
				})
		}, 750)
	}
</script>

<Container>
	<header class="w-full flex justify-between items-center">
		<h2 class="text-3xl text-light font-semibold mb-4">Materias</h2>
		<div class="flex gap-2 justify-between items-center">
			<form class="flex gap-2 justify-between items-center">
				<input
					type="text"
					class="border focus:outline focus:outline-1 px-4 py-2 rounded"
					placeholder="Buscar materias..."
					on:keyup={handleSearch}
				/>
			</form>
			<div class="flex gap-2 justify-between items-center">
				<Link
					to="/materias/agregar_materia"
					class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
				>
					Agregar Materia
				</Link>
				<button
					class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
					on:click={handleDeleteMultiple}
					disabled={ids_materias.ids.length === 0}
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
			'Porcentaje',
			'Porcentaje Individual',
			'Porcentaje Grupal',
			'Fecha Inicio',
			'Fecha Fin',
			'Acciones'
		]}
		title="Registros de empresas"
	>
		<tbody class="text-xs">
			{#await materias}
				<p>Cargando materias...</p>
			{:then materias}
				{#if materias.length === 0}
					<p>Sin resultados</p>
				{:else}
					{#each materias as subject}
						<tr>
							<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
								<input
									type="checkbox"
									bind:group={ids_materias.ids}
									value={subject.id}
								/>
							</td>
							<td class="px-4 py-2 whitespace-nowrap min-w-max">
								{subject.nombre}
							</td>
							<td class="px-4 py-2 whitespace-nowrap">
								{subject.porcentaje}
							</td>
							<td class="px-4 py-2 whitespace-nowrap">
								{subject.porcentaje_individual}
							</td>
							<td class="px-4 py-2 whitespace-nowrap">
								{subject.porcentaje_grupal}
							</td>
							<td class="px-4 py-2 whitespace-nowrap">
								{subject.fecha_inicio}
							</td>
							<td class="px-4 py-2 whitespace-nowrap">
								{subject.fecha_fin ?? 'No especificada'}
							</td>
							<td
								class="px-4 py-2 whitespace-nowrap flex justify-between items-center"
							>
								<button
									type="button"
									class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
									on:click={handleDelete(subject.id)}
								>
									Eliminar
								</button>
								<Link
									to="/materias/{subject.id}"
									class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
								>
									Editar
								</Link>
								<Link
									to="/materias/gestionar_criterios/{JSON.stringify(subject)}"
									class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
								>
									Gestionar Criterios
								</Link>
							</td>
						</tr>
					{/each}
				{/if}
			{/await}
		</tbody>
	</Table>
</Container>

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
