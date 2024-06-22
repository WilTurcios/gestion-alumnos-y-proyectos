<script>
	import { navigate, Link } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import {
		addCriterionToSubject,
		deleteCriterion,
		getSubjectById,
		updateSubject
	} from '../../services/SubjectService'
	import Table from '../../components/ui/Table.svelte'

	export let currentMateriaID

	let toastElement = null
	let toastText = 'El criterio se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let materia = {
		nombre: '',
		porcentaje: null,
		porcentaje_individual: null,
		porcentaje_grupal: null,
		fecha_inicio: '',
		fecha_fin: '',
		activo: 'S',
		tipo: '',
		year: null,
		criterios: []
	}
	let criterio = {
		id_materia: currentMateriaID,
		criterio: '',
		porcentaje: null,
		tipo: 'grupal',
		estado: 'Sin calificar'
	}

	let ids_criterios = {
		ids: []
	}

	const fetchData = async () => {
		try {
			const subject = await getSubjectById(currentMateriaID)
			materia = { ...subject }
		} catch (error) {
			console.error('Error fetching subject:', error)
		}
	}

	fetchData()

	const handleSubmit = async () => {
		try {
			await updateSubject(materia)
			showToast = true
			toastText = 'Materia actualizada correctamente'
			variant = 'success'
			navigate('/materias', { replace: true })
		} catch (err) {
			console.error('Error updating subject:', err)
			showToast = true
			toastText = 'Error al actualizar la materia'
			variant = 'danger'
		}
	}

	const handleSubmitCriterio = async () => {
		try {
			await addCriterionToSubject(criterio)
			showToast = true
			toastText = 'Criterio creado correctamente'
			variant = 'success'

			// Clear criterio after submission
			criterio = {
				id_materia: currentMateriaID,
				criterio: '',
				porcentaje: null,
				tipo: 'grupal',
				estado: 'Sin calificar'
			}

			await fetchData()
		} catch (err) {
			console.error('Error adding criterion:', err)
			showToast = true
			toastText = 'Error al crear el criterio'
			variant = 'danger'
		}
	}

	const handleDelete = id => async () => {
		try {
			await deleteCriterion(id)
			showToast = true
			toastText = 'Criterio eliminado correctamente'
			variant = 'success'

			// Fetch updated data after deleting criterion
			await fetchData()
		} catch (err) {
			console.error('Error deleting criterion:', err)
			showToast = true
			toastText = 'Error al eliminar el criterio'
			variant = 'danger'
		}
	}
</script>

<Container>
	<div
		class="w-[600px] mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4">Modificar Datos de la Materia</h2>
			<form on:submit|preventDefault={handleSubmit}>
				<div>
					<label for="nombre" class="block text-sm font-medium text-gray-700">
						Nombre de la materia
					</label>
					<input
						id="nombre"
						name="nombre"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={materia.nombre}
					/>
				</div>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label
							for="porcentaje"
							class="block text-sm font-medium text-gray-700"
						>
							Porcentaje
						</label>
						<input
							id="porcentaje"
							name="porcentaje"
							type="number"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.porcentaje}
						/>
					</div>
					<div>
						<label
							for="porcentaje_individual"
							class="block text-sm font-medium text-gray-700"
						>
							Porcentaje Individual
						</label>
						<input
							id="porcentaje_individual"
							name="porcentaje_individual"
							type="number"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.porcentaje_individual}
						/>
					</div>
				</div>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label
							for="porcentaje_grupal"
							class="block text-sm font-medium text-gray-700"
						>
							Porcentaje Grupal
						</label>
						<input
							id="porcentaje_grupal"
							name="porcentaje_grupal"
							type="number"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.porcentaje_grupal}
						/>
					</div>
					<div>
						<label
							for="fecha_inicio"
							class="block text-sm font-medium text-gray-700"
						>
							Fecha de Inicio
						</label>
						<input
							id="fecha_inicio"
							name="fecha_inicio"
							type="date"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.fecha_inicio}
						/>
					</div>
				</div>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label
							for="fecha_fin"
							class="block text-sm font-medium text-gray-700"
						>
							Fecha de Fin
						</label>
						<input
							id="fecha_fin"
							name="fecha_fin"
							type="date"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.fecha_fin}
						/>
					</div>
					<div>
						<label for="activo" class="block text-sm font-medium text-gray-700">
							Activo
						</label>
						<select
							id="activo"
							name="activo"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.activo}
						>
							<option value="S">Sí</option>
							<option value="N">No</option>
						</select>
					</div>
				</div>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label for="tipo" class="block text-sm font-medium text-gray-700">
							Tipo
						</label>
						<select
							id="tipo"
							name="tipo"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.tipo}
						>
							<option value="práctica">Práctica</option>
							<option value="teórica">Teórica</option>
						</select>
					</div>
					<div>
						<label for="year" class="block text-sm font-medium text-gray-700">
							Año
						</label>
						<input
							id="year"
							name="year"
							type="number"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.year}
						/>
					</div>
				</div>
				<div class="mt-6">
					<button
						type="submit"
						class="w-full p-3 bg-blue-500 text-white rounded-md hover:bg-blue-600"
					>
						Guardar
					</button>
				</div>
			</form>
		</div>
	</div>
</Container>

<Container>
	<div
		class="w-[600px] mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4">Ingresar Criterio</h2>
			<form on:submit|preventDefault={handleSubmitCriterio}>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label
							for="criterio"
							class="block text-sm font-medium text-gray-700"
						>
							Criterio
						</label>
						<input
							id="criterio"
							name="criterio"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={criterio.criterio}
						/>
					</div>
					<div>
						<label
							for="porcentaje"
							class="block text-sm font-medium text-gray-700"
						>
							Porcentaje
						</label>
						<input
							id="porcentaje"
							name="porcentaje"
							type="number"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={criterio.porcentaje}
						/>
					</div>
				</div>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label for="tipo" class="block text-sm font-medium text-gray-700">
							Tipo
						</label>
						<select
							id="tipo"
							name="tipo"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={criterio.tipo}
						>
							<option value="individual">Individual</option>
							<option value="grupal">Grupal</option>
						</select>
					</div>
					<div>
						<label for="estado" class="block text-sm font-medium text-gray-700">
							Estado
						</label>
						<input
							id="estado"
							name="estado"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={criterio.estado}
						/>
					</div>
				</div>
				<div class="mt-6">
					<button
						type="submit"
						class="w-full p-3 bg-blue-500 text-white rounded-md hover:bg-blue-600"
					>
						Agregar Criterio
					</button>
				</div>
			</form>
		</div>
	</div>
</Container>

<Container>
	<Table
		headers={[
			'Acción Multiple',
			'Criterio',
			'Porcentaje',
			'Tipo',
			'Estado',
			'Creado Por',
			'Acciones'
		]}
		title="Criterios de la Materia"
	>
		<tbody class="text-xs">
			{#each materia.criterios as criterion}
				<tr>
					<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
						<input
							type="checkbox"
							bind:group={ids_criterios.ids}
							value={criterion.id}
						/>
					</td>
					<td class="px-4 py-2 whitespace-nowrap min-w-max">
						{criterion.criterio}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{criterion.porcentaje}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{criterion.tipo}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{criterion.estado}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{criterion.creado_por.nombres}
						{criterion.creado_por.apellidos}
					</td>
					<td
						class="px-4 py-2 whitespace-nowrap flex justify-between items-center"
					>
						<button
							type="button"
							class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
							on:click={handleDelete(criterion.id)}
						>
							Eliminar
						</button>
						<Link
							to={`/criterios/${criterion.id}`}
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
