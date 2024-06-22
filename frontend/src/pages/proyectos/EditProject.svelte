<script>
	import { navigate } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { getAssesors, getJudges } from '../../services/UserService'
	import {
		addJudgeToProject,
		addStudentToProject,
		deleteJudgeFromProject,
		deleteStudentFromProject,
		updateProject
	} from '../../services/ProjectService'
	import { getCompanies } from '../../services/CompanyService'
	import Table from '../../components/ui/Table.svelte'
	import { getStudents } from '../../services/StudentService'

	export let currentProjectID

	let toastElement = null
	let toastText = 'El usuario se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let ids_jurados = {
		ids: []
	}
	let ids_estudiantes = {
		ids: []
	}

	let empresas = []
	let asesores = []
	let students = []
	let judges = []
	let proyecto = {
		id: currentProjectID,
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
		creador_por: null,
		estudiantes: [],
		jurados: []
	}

	let estudiante = {
		id_estudiante: null,
		id_proyecto: currentProjectID
	}

	let jurado = {
		id_jurado: null,
		id_proyecto: currentProjectID
	}

	const fetchData = async () => {
		empresas = await getCompanies()
		asesores = await getAssesors()
		judges = await getJudges()
		students = await getStudents()
		const res = await fetch(
			`http://localhost/proyecto-DAW/backend/api/proyectos/${currentProjectID}`,
			{
				headers: {
					Authorization: 'Bearer ' + JSON.parse(localStorage.getItem('token'))
				}
			}
		)
		const project = await res.json()

		proyecto = {
			...project[0],
			id_empresa: project[0].empresa.id,
			id_asesor: project[0].asesor.id,
			creador_por: project[0].creado_por.id,
			estudiantes: [...project[0].estudiantes],
			jurados: [...project[0].jurados]
		}
	}

	fetchData()

	const handleSubmit = async () => {
		try {
			await updateProject(proyecto)
			Object.assign(proyecto, {
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
				creador_por: null
			})
			showToast = true
			toastText = 'Usuario creado correctamente'
			variant = 'success'
			navigate('/proyectos', { replace: true })
		} catch (err) {
			console.log(err)
		}
	}
	const handleSubmitJudge = async () => {
		try {
			await addJudgeToProject(jurado)
			Object.assign(proyecto, {
				id_jurado: null
			})
			showToast = true
			toastText = 'Jurado creado correctamente'
			variant = 'success'

			fetchData()
		} catch (err) {
			console.log(err)
		}
	}

	const handleSubmitStudent = async () => {
		try {
			await addStudentToProject(estudiante)
			Object.assign(proyecto, {
				id_estudiante: null
			})
			showToast = true
			toastText = 'Estudiante agregado correctamente'
			variant = 'success'

			fetchData()
		} catch (err) {
			console.log(err)
		}
	}

	const handleDeleteJudge = id_jurado => async e => {
		try {
			await deleteJudgeFromProject({
				id_jurado,
				id_proyecto: currentProjectID
			})
			showToast = true
			toastText = 'Jurado removido correctamente'
			variant = 'success'
			await fetchData()
		} catch (e) {}
	}
	const handleDeleteStudent = id_alumno => async e => {
		try {
			await deleteStudentFromProject({
				id_alumno,
				id_proyecto: currentProjectID
			})

			showToast = true
			toastText = 'Estudiante removido correctamente'
			variant = 'success'

			await fetchData()
		} catch (e) {}
	}
</script>

<Container>
	<div
		class="xl:w-[1000px] grid grid-cols-2 gap-4 mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4">Ingresar Datos del Proyecto</h2>
			<form on:submit|preventDefault={handleSubmit} class="flex flex-col gap-4">
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
						{#each empresas as empresa}
							<option value={empresa.id}>{empresa.nombre}</option>
						{/each}
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
						{#each asesores as asesor}
							<option value={asesor.id}
								>{asesor.nombres} {asesor.apellidos}</option
							>
						{/each}
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
		<div>
			<div class="p-4">
				<h2 class="text-lg font-semibold mb-4">Agregar Jurados</h2>
				<form
					on:submit|preventDefault={handleSubmitJudge}
					class="flex justify-between items-center"
				>
					<div class="">
						<label
							for="id_jurado"
							class="block text-sm font-medium text-gray-700">Jurados</label
						>
						<select
							id="id_jurado"
							name="id_jurado"
							class="flex mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={jurado.id_jurado}
						>
							{#each judges as judge}
								<option value={judge.id}
									>{judge.nombres} {judge.apellidos}</option
								>
							{/each}
						</select>
					</div>
					<div class="mt-6">
						<button
							type="submit"
							class="w-full p-3 bg-blue-500 text-white rounded-md hover:bg-blue-600"
						>
							Agregar Jurado
						</button>
					</div>
				</form>
				<Table
					headers={['Accion Multiple', 'Nombre Jurado', 'Acciones']}
					title="Jurados del Proyecto"
				>
					<tbody>
						{#each proyecto.jurados as jurado}
							<tr>
								<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
									<input
										type="checkbox"
										bind:group={ids_jurados.ids}
										value={jurado.id}
									/>
								</td>
								<td class=" px-2 py-2 whitespace-nowrap">
									{jurado.nombres}
									{jurado.apellidos}
								</td>
								<td class=" px-2 py-2 whitespace-nowrap">
									<button
										type="button"
										class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
										on:click={handleDeleteJudge(jurado.id)}>Eliminar</button
									>
								</td>
							</tr>
						{/each}
					</tbody>
				</Table>
			</div>
			<div class="p-4">
				<h2 class="text-lg font-semibold mb-4">Agregar Estudiantes</h2>
				<form
					on:submit|preventDefault={handleSubmitStudent}
					class="flex justify-between items-center"
				>
					<div class="">
						<label
							for="id_estudiante"
							class="block text-sm font-medium text-gray-700">Estudiantes</label
						>
						<select
							id="id_estudiante"
							name="id_estudiante"
							class="flex mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={estudiante.id_estudiante}
						>
							{#each students as student}
								<option value={student.id}
									>{student.nombres} {student.apellidos}</option
								>
							{/each}
						</select>
					</div>
					<div class="mt-6">
						<button
							type="submit"
							class="w-full p-3 bg-blue-500 text-white rounded-md hover:bg-blue-600"
						>
							Agregar Estudiante
						</button>
					</div>
				</form>
				<Table
					headers={['Accion Multiple', 'Nombre del Alumno', 'Acciones']}
					title="Estudiantes del Proyecto"
				>
					<tbody>
						{#each proyecto.estudiantes as std}
							<tr>
								<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
									<input
										type="checkbox"
										bind:group={ids_estudiantes.ids}
										value={std.id}
									/>
								</td>
								<td class=" px-2 py-2 whitespace-nowrap">
									{std.nombres}
									{std.apellidos}
								</td>
								<td class=" px-2 py-2 whitespace-nowrap">
									<button
										type="button"
										class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
										on:click={handleDeleteStudent(std.id)}
									>
										Eliminar
									</button>
								</td>
							</tr>
						{/each}
					</tbody>
				</Table>
			</div>
		</div>
	</div>
</Container>

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
