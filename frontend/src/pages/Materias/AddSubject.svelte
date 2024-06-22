<script>
	import { navigate } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { addSubject } from '../../services/SubjectService'

	let toastElement = null
	let toastText = 'La materia se ha creado correctamente'
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
		year: null
	}

	const handleSubmit = async () => {
		try {
			await addSubject(materia)
			Object.assign(materia, {
				nombre: '',
				porcentaje: null,
				porcentaje_individual: null,
				porcentaje_grupal: null,
				fecha_inicio: '',
				fecha_fin: '',
				activo: 'S',
				tipo: '',
				year: null
			})
			showToast = true
			toastText = 'Materia creada correctamente'
			variant = 'success'
			navigate('/materias', { replace: true })
		} catch (err) {
			console.log(err)
			showToast = true
			toastText = 'Error al crear la materia'
			variant = 'error'
		}
	}
</script>

<Container>
	<div
		class="w-[600px] mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4">Ingresar Datos de la Materia</h2>
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
						<input
							id="tipo"
							name="tipo"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.tipo}
						/>
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

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
