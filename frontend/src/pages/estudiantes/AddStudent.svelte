<script>
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { Students } from '../../store/StudentsStore'
	import { Groups } from '../../store/GroupsStore.js'
	import { navigate } from 'svelte-routing'

	let toastElement = null
	let toastText = 'El alumno se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let estudiante = {
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
		clave: null,
		estado_alumno: null,
		year_ingreso: null,
		id_grupo: null
	}

	function handleSubmit(e) {
		Students.addStudent(estudiante).then(student => {
			estudiante.carnet = null
			estudiante.nombres = null
			estudiante.apellidos = null
			estudiante.sexo = null
			estudiante.email = null
			estudiante.jornada = null
			estudiante.direccion = null
			estudiante.tel_alumno = null
			estudiante.responsable = null
			estudiante.tel_responsable = null
			estudiante.clave = null
			estudiante.estado_alumno = null
			estudiante.year_ingreso = null
			estudiante.id_grupo = null

			toastText = 'El alumno se ha creado correctamente'
			variant = 'success'
			showToast = true

			setTimeout(() => {
				navigate('/estudiantes', { replace: true })
			}, 1500)
		})
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
							bind:value={estudiante.nombres}
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
							bind:value={estudiante.apellidos}
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
							bind:value={estudiante.sexo}
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
							bind:value={estudiante.carnet}
						/>
					</div>
				</div>

				<div class="grid grid-cols-2 gap-4">
					<div>
						<label for="email" class="block text-sm font-medium text-gray-700">
							Correo Electrónico
						</label>
						<input
							id="email"
							name="email"
							type="email"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={estudiante.email}
						/>
					</div>
					<div>
						<label for="clave" class="block text-sm font-medium text-gray-700">
							Clave
						</label>
						<input
							id="clave"
							name="clave"
							type="password"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={estudiante.clave}
						/>
					</div>
				</div>

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
							bind:value={estudiante.jornada}
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
							bind:value={estudiante.estado_alumno}
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
						bind:value={estudiante.year_ingreso}
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
						bind:value={estudiante.direccion}
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
						bind:value={estudiante.tel_alumno}
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
						bind:value={estudiante.responsable}
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
						bind:value={estudiante.tel_responsable}
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
						bind:value={estudiante.id_grupo}
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

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
