<script>
	import { navigate, Link } from 'svelte-routing'
	import Container from '../../../components/ui/Container.svelte'
	import Toast from '../../../components/ui/Toast.svelte'
	import {
		addCriterionToSubject,
		deleteCriterion,
		getSubjectById
	} from '../../../services/SubjectService'
	import Table from '../../../components/ui/Table.svelte'

	export let materia

	materia = JSON.parse(materia)

	let toastElement = null
	let toastText = 'El criterio se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let criterio = {
		id_materia: materia.id,
		criterio: '',
		porcentaje: null,
		tipo: 'grupal',
		estado: 'Sin calificar'
	}

	let ids_criterios = {
		ids: []
	}

	const handleSubmit = async () => {
		try {
			await addCriterionToSubject(criterio)
			Object.assign(criterio, {
				criterio: '',
				porcentaje: null,
				tipo: 'grupal',
				estado: ''
			})
			showToast = true
			toastText = 'Criterio creado correctamente'
			variant = 'success'
			navigate('/materias', { replace: true })
		} catch (err) {
			console.log(err)
			showToast = true
			toastText = 'Error al crear el criterio'
			variant = 'danger'
		}
	}
	const handleDelete = id => async e => {
		try {
			await deleteCriterion(id)

			showToast = true
			toastText = 'Criterio eliminado correctamente'
			variant = 'success'

			materia = await getSubjectById(materia.id)
		} catch (err) {
			console.log(err)
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
			<h2 class="text-lg font-semibold mb-4">Ingresar Datos del Criterio</h2>
			<form on:submit|preventDefault={handleSubmit}>
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
						Guardar
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
		title="Registros de empresas"
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
							on:click={handleDelete(criterion.id)}>Eliminar</button
						>
						<Link
							to="/empresas/{criterion.id}"
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
