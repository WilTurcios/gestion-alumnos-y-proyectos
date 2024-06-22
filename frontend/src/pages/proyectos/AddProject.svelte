<script>
	import { navigate } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { getAssesors } from '../../services/UserService'
	import { addProject } from '../../services/ProjectService'
	import { getCompanies } from '../../services/CompanyService'
	import { AuthenticatedUser } from '../../store/AuthenticatedUserStore'

	let toastElement = null
	let toastText = 'El usuario se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let empresas = getCompanies()
	let asesores = getAssesors()

	let proyecto = {
		tema: null,
		id_empresa: null,
		id_asesor: null,
		objetivos: null,
		alcances_limitantes: null,
		observaciones: null,
		cd: false,
		estado: 'Sin evaluar',
		motivo: null,
		justificacion: null,
		resultados_esperados: null,
		fecha_presentacion: null,
		doc: null,
		creado_por: $AuthenticatedUser.id
	}

	function handleSubmit() {
		addProject(proyecto)
			.then(res => {
				proyecto = {
					tema: null,
					id_empresa: null,
					id_asesor: null,
					objetivos: null,
					alcances_limitantes: null,
					observaciones: null,
					cd: false,
					estado: 'Sin evaluar',
					motivo: null,
					justificacion: null,
					resultados_esperados: null,
					fecha_presentacion: null,
					doc: null,
					creado_por: $AuthenticatedUser.id
				}
				showToast = true
				toastText = 'Usuario creado correctamente'
				variant = 'success'

				navigate('/proyectos', { replace: true })
			})
			.catch(err => console.log(err))
	}
</script>

<Container>
	<!-- <StudentsForm/> -->
	<div
		class="w-[600px] mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4">Ingresar Datos del usuario</h2>

			<form on:submit|preventDefault={handleSubmit}>
				<div>
					<label for="tema" class="block text-sm font-medium text-gray-700"
						>Tema</label
					>
					<input
						id="tema"
						name="tema"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.tema}
					/>
				</div>

				<div>
					<label
						for="id_empresa"
						class="block text-sm font-medium text-gray-700">Empresa</label
					>
					<select
						id="id_empresa"
						name="id_empresa"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.id_empresa}
					>
						{#await empresas then empresas}
							{#each empresas as empresa}
								<option value={empresa.id}>{empresa.nombre}</option>
							{/each}
						{/await}
					</select>
				</div>

				<div>
					<label for="id_asesor" class="block text-sm font-medium text-gray-700"
						>Asesor</label
					>
					<select
						id="id_asesor"
						name="id_asesor"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.id_asesor}
					>
						{#await asesores then asesores}
							{#each asesores as asesor}
								<option value={asesor.id}
									>{asesor.nombres} {asesor.apellidos}</option
								>
							{/each}
						{/await}
					</select>
				</div>

				<div>
					<label for="objetivos" class="block text-sm font-medium text-gray-700"
						>Objetivos</label
					>
					<textarea
						id="objetivos"
						name="objetivos"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.objetivos}
					></textarea>
				</div>

				<div>
					<label
						for="alcances_limitantes"
						class="block text-sm font-medium text-gray-700"
						>Alcances y Limitantes</label
					>
					<textarea
						id="alcances_limitantes"
						name="alcances_limitantes"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.alcances_limitantes}
					></textarea>
				</div>

				<div>
					<label
						for="observaciones"
						class="block text-sm font-medium text-gray-700">Observaciones</label
					>
					<textarea
						id="observaciones"
						name="observaciones"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.observaciones}
					></textarea>
				</div>

				<div>
					<label for="cd" class="block text-sm font-medium text-gray-700"
						>CD</label
					>
					<input
						type="checkbox"
						id="cd"
						name="cd"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:checked={proyecto.cd}
					/>
				</div>

				<div>
					<label for="estado" class="block text-sm font-medium text-gray-700"
						>Estado</label
					>
					<select
						id="estado"
						name="estado"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.estado}
					>
						<option value="Evaluado">Evaluado</option>
						<option value="Sin evaluar">Sin evaluar</option>
					</select>
				</div>

				<div>
					<label for="motivo" class="block text-sm font-medium text-gray-700"
						>Motivo</label
					>
					<textarea
						id="motivo"
						name="motivo"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.motivo}
					></textarea>
				</div>

				<div>
					<label
						for="justificacion"
						class="block text-sm font-medium text-gray-700">Justificación</label
					>
					<textarea
						id="justificacion"
						name="justificacion"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.justificacion}
					></textarea>
				</div>

				<div>
					<label
						for="resultados_esperados"
						class="block text-sm font-medium text-gray-700"
						>Resultados Esperados</label
					>
					<textarea
						id="resultados_esperados"
						name="resultados_esperados"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.resultados_esperados}
					></textarea>
				</div>

				<div>
					<label
						for="fecha_presentacion"
						class="block text-sm font-medium text-gray-700"
						>Fecha de Presentación</label
					>
					<input
						type="date"
						id="fecha_presentacion"
						name="fecha_presentacion"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.fecha_presentacion}
					/>
				</div>

				<div>
					<label for="doc" class="block text-sm font-medium text-gray-700"
						>Documento</label
					>
					<input
						type="text"
						id="doc"
						name="doc"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={proyecto.doc}
					/>
				</div>

				<div class="mt-6">
					<button
						type="submit"
						class="w-full p-3 bg-blue-500 text-white rounded-md hover:bg-blue-600"
						>Guardar</button
					>
				</div>
			</form>
		</div>
	</div>
</Container>

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
