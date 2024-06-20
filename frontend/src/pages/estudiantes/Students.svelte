<script>
	import Container from '../../components/ui/Container.svelte'
	import Table from '../../components/ui/Table.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { Students } from '../../store/StudentsStore'
	import { Groups } from '../../store/GroupsStore.js'
	import { Link } from 'svelte-routing'

	let toastElement = null
	let toastText = 'El alumno se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let ids_estudiantes = {
		ids: []
	}

	const handleDeleteMultiple = () => {
		Students.deleteMutlipleStudents(ids_estudiantes.ids).then(() => {
			showToast = true
			toastText = 'Estudiantes eliminados correctamente'
			variant = 'danger'
		})
	}

	const handleDelete = id => e => {
		Students.deleteStudent(id).then(() => {
			showToast = true
			toastText = 'Estudiante eliminado correctamente'
			variant = 'danger'
		})
	}
</script>

<Container>
	<header class="w-full flex justify-between items-center">
		<h2 class="text-3xl text-light font-semibold mb-4">Estudiantes</h2>
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
					placeholder="Buscar estudiantes..."
				/>
			</form>
			<div class="flex gap-2 justify-between items-center">
				<Link
					to="/estudiantes/agregar_estudiante"
					class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
				>
					Agregar Estudiante
				</Link>
				<button
					class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
					on:click={handleDeleteMultiple}
					disabled={ids_estudiantes.ids.length === 0}
				>
					Eliminar Seleccionados
				</button>
			</div>
		</div>
	</header>
	<Table
		headers={[
			'Accion Multiple',
			'Nombres',
			'Apellidos',
			'Sexo',
			'Direccion',
			'Pais',
			'Telefono',
			'Correo',
			'Acciones'
		]}
		title="Registros de Estudiantes"
	>
		<tbody class="text-xs">
			{#each $Students as alumno}
				<tr>
					<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
						<input
							type="checkbox"
							bind:group={ids_estudiantes.ids}
							value={alumno.id}
						/>
					</td>
					<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
						{alumno.nombres}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{alumno.apellidos}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{alumno.sexo}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{alumno.direccion}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{alumno.carnet}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{alumno.tel_alumno}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{alumno.email}
					</td>
					<td
						class="px-4 py-2 whitespace-nowrap flex justify-between items-center"
					>
						<button
							type="button"
							class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
							on:click={handleDelete(alumno.id)}>Eliminar</button
						>
						<Link
							to="/estudiantes/{alumno.id}"
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
