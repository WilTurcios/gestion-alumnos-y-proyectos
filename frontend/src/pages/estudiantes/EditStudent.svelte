<script>
	import Container from '../../components/ui/Container.svelte'
	import Table from '../../components/ui/Table.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { Students } from '../../store/StudentsStore'
	import { Groups } from '../../store/GroupsStore.js'
	import { navigate } from 'svelte-routing'

	export let current_student_id

	let toastElement = null
	let toastText = 'El alumno se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let current_student = {
		id: current_student_id,
		carnet: null,
		nombres: null,
		apellidos: null,
		sexo: null,
		email: null,
		jornada: null,
		direccion: null,
		tel_alumno: null,
		responsable: null,
		tel_responsable: null,
		// clave: null,
		estado_alumno: null,
		year_ingreso: null,
		id_grupo: null
	}

	const getById = id => {
		const estudiante = fetch(
			`http://localhost/proyecto-DAW/backend/api/estudiantes/${id}`
		)
			.then(res => {
				return res.json()
			})
			.then(student => {
				current_student.carnet = student[0].carnet
				current_student.nombres = student[0].nombres
				current_student.apellidos = student[0].apellidos
				current_student.sexo = student[0].sexo.toLowerCase()
				current_student.email = student[0].email
				current_student.jornada = student[0].jornada.toLowerCase()
				current_student.direccion = student[0].direccion
				current_student.tel_alumno = student[0].tel_alumno
				current_student.responsable = student[0].responsable
				current_student.tel_responsable = student[0].tel_responsable
				// current_student.clave = student[0].clave
				current_student.estado_alumno = student[0].estado_alumno
				current_student.year_ingreso = student[0].year_ingreso
				current_student.id_grupo = student[0].grupo.id
			})
	}

	getById(current_student_id)

	function handleSubmit(e) {
		let new_student = Students.updateStudent(current_student).then(student => {
			showToast = true
			toastText = 'El alumno se ha actualizado correctamente'
			variant = 'success'

			return student
		})

		navigate('/estudiantes', { replace: true })
	}
</script>

<Container>
	<div
		class="w-[600px] mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4">Ingresar Datos del Estudiante</h2>
			<form on:submit|preventDefault={handleSubmit}>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label
							for="nombres"
							class="block text-sm font-medium text-gray-700"
						>
							Nombres del Estudiante
						</label>
						<input
							id="nombres"
							name="nombres"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={current_student.nombres}
						/>
					</div>
					<div>
						<label
							for="apellidos"
							class="block text-sm font-medium text-gray-700"
						>
							Apellidos del Estudiante
						</label>
						<input
							id="apellidos"
							name="apellidos"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={current_student.apellidos}
						/>
					</div>
				</div>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label for="sexo" class="block text-sm font-medium text-gray-700">
							Sexo
						</label>
						<select
							id="sexo"
							name="sexo"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={current_student.sexo}
						>
							<option value="masculino"> Masculino </option>
							<option value="femenino"> Femenino </option>
						</select>
					</div>
					<div>
						<label for="carnet" class="block text-sm font-medium text-gray-700">
							Carnet
						</label>
						<input
							id="carnet"
							name="carnet"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={current_student.carnet}
						/>
					</div>
				</div>

				<!-- <div class="grid grid-cols-2 gap-4"> -->
				<div>
					<label for="email" class="block text-sm font-medium text-gray-700">
						Correo Electrónico
					</label>
					<input
						id="email"
						name="email"
						type="email"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={current_student.email}
					/>
				</div>
				<!-- <div>
						<label for="clave" class="block text-sm font-medium text-gray-700">
							Clave
						</label>
						<input
							id="clave"
							name="clave"
							type="password"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={current_student.clave}
						/>
					</div> -->
				<!-- </div> -->

				<div class="grid grid-cols-2 gap-4">
					<div>
						<label
							for="jornada"
							class="block text-sm font-medium text-gray-700"
						>
							Jornada
						</label>
						<select
							id="jornada"
							name="jornada"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={current_student.jornada}
						>
							<option value="matutina"> Matutina </option>
							<option value="vespertina"> Vespertina </option>
						</select>
					</div>

					<div>
						<label for="estado" class="block text-sm font-medium text-gray-700">
							Estado del Estudiante
						</label>
						<select
							id="estado"
							name="estado"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={current_student.estado_alumno}
						>
							<option value="H"> Activo </option>
							<option value="I"> Inactivo </option>
							<option value="D"> Desertor </option>
						</select>
					</div>
				</div>

				<div>
					<label
						for="year_ingreso"
						class="block text-sm font-medium text-gray-700"
					>
						Año de Ingreso
					</label>
					<input
						type="number"
						id="year_ingreso"
						name="year_ingreso"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={current_student.year_ingreso}
					/>
				</div>
				<div>
					<label
						for="direccion"
						class="block text-sm font-medium text-gray-700"
					>
						Direccion
					</label>
					<input
						id="direccion"
						name="direccion"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={current_student.direccion}
					/>
				</div>

				<div>
					<label for="telefono" class="block text-sm font-medium text-gray-700">
						Telefono del Estudiante
					</label>
					<input
						id="telefono"
						name="telefono"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={current_student.tel_alumno}
					/>
				</div>
				<div>
					<label
						for="responsable"
						class="block text-sm font-medium text-gray-700"
					>
						Responsable del Estudiante
					</label>
					<input
						id="responsable"
						name="responsable"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={current_student.responsable}
					/>
				</div>
				<div>
					<label
						for="tel_responsable"
						class="block text-sm font-medium text-gray-700"
					>
						Telefono del Responsable
					</label>
					<input
						id="tel_responsable"
						name="tel_responsable"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={current_student.tel_responsable}
					/>
				</div>
				<div>
					<label for="grupo" class="block text-sm font-medium text-gray-700">
						Grupo al que Pertenece
					</label>
					<select
						id="grupo"
						name="grupo"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={current_student.id_grupo}
					>
						{#each $Groups as grupo}
							<option value={grupo.id}>{grupo.nombre_grupo}</option>
						{/each}
					</select>
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

<!-- <Container>
	<Table
		headers={[
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
						{alumno.nombres}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						{alumno.apellidos}
					</td>
					<td class="px-4 py-2 whitespace-nowrap">
						<select
							name="sexo"
							id="sexo"
							class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
							bind:value={alumno.sexo}
						>
							<option value="masculino">Masculino</option>
							<option value="femenino">Femenino</option>
						</select>
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
						<button
							type="button"
							class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
							on:click={handleUpdate(alumno.id)}>Editar</button
						>
					</td>
				</tr>
			{/each}
		</tbody>
	</Table>
</Container> -->

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
