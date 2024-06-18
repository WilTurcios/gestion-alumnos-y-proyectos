<script>
	import { navigate } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { Users } from '../../store/UsersStore.js'

	export let currentUserID

	let toastElement = null
	let toastText = 'El alumno se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let usuario = {
		id: currentUserID,
		nombre_usuario: null,
		nombres: null,
		apellidos: null,
		carnet_docente: null,
		email: null,
		tel: null,
		celular: null,
		sexo: null,
		es_jurado: false,
		es_asesor: false,
		acceso_sistema: false,
		es_admin: false
	}

	const getById = id => {
		const estudiante = fetch(
			`http://localhost/proyecto-DAW/backend/api/usuarios/${id}`
		)
			.then(res => {
				return res.json()
			})
			.then(user => {
				usuario.nombre_usuario = user[0].nombre_usuario
				usuario.nombres = user[0].nombres
				usuario.apellidos = user[0].apellidos
				usuario.sexo = user[0].sexo
				usuario.email = user[0].email
				usuario.carnet_docente = user[0].carnet_docente
				usuario.tel = user[0].tel
				usuario.celular = user[0].celular
				usuario.es_jurado = user[0].es_jurado
				usuario.es_asesor = user[0].es_asesor
				usuario.acceso_sistema = user[0].acceso_sistema
				usuario.es_admin = user[0].es_admin
			})
	}

	getById(currentUserID)

	function handleSubmit(e) {
		Users.updateUser(usuario).then(user => {
			usuario.nombre_usuario = null
			usuario.nombres = null
			usuario.apellidos = null
			usuario.sexo = null
			usuario.email = null
			usuario.carnet_docente = null
			usuario.tel = null
			usuario.celular = null
			usuario.es_jurado = null
			usuario.es_asesor = null
			usuario.acceso_sistema = null
			usuario.es_admin = null

			toastText = 'El usuario se ha actualizado correctamente'
			variant = 'success'
			showToast = true

			setTimeout(() => {
				navigate('/usuarios', { replace: true })
			}, 1500)

			return user
		})
	}
</script>

<Container>
	<!-- <StudentsForm/> -->
	<div
		class="w-[600px] mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<form on:submit|preventDefault={handleSubmit}>
				<!-- <div class="grid grid-cols-2 gap-4"> -->
				<div>
					<label
						for="nombre_usuario"
						class="block text-sm font-medium text-gray-700"
					>
						Usuario
					</label>
					<input
						id="nombre_usuario"
						name="nombre_usuario"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={usuario.nombre_usuario}
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
							bind:value={usuario.clave}
						/>
					</div>
				</div> -->

				<div class="grid grid-cols-2 gap-4">
					<div>
						<label
							for="nombres"
							class="block text-sm font-medium text-gray-700"
						>
							Nombres
						</label>
						<input
							id="nombres"
							name="nombres"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={usuario.nombres}
						/>
					</div>
					<div>
						<label
							for="apellidos"
							class="block text-sm font-medium text-gray-700"
						>
							Apellidos
						</label>
						<input
							id="apellidos"
							name="apellidos"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={usuario.apellidos}
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
							bind:value={usuario.sexo}
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
							bind:value={usuario.carnet_docente}
						/>
					</div>
				</div>

				<div>
					<label for="email" class="block text-sm font-medium text-gray-700">
						Correo Electrónico
					</label>
					<input
						id="email"
						name="email"
						type="email"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={usuario.email}
					/>
				</div>

				<div class="grid grid-cols-2 gap-4">
					<div>
						<label
							for="telefono"
							class="block text-sm font-medium text-gray-700"
						>
							Telefono
						</label>
						<input
							id="telefono"
							name="telefono"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={usuario.tel}
						/>
					</div>
					<div>
						<label
							for="celular"
							class="block text-sm font-medium text-gray-700"
						>
							Celular
						</label>
						<input
							id="celular"
							name="celular"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={usuario.celular}
						/>
					</div>
				</div>

				<div class="grid grid-cols-2 gap-2 mt-4">
					<div class="grid grid-cols-2 gap-1 justify-evenly items-center">
						<label
							for="es_jurado"
							class="min-w-max text-sm font-medium text-gray-700"
						>
							Es Jurado
						</label>
						<input
							type="checkbox"
							id="es_jurado"
							name="es_jurado"
							class="mt-1 p-2 w-full border rounded-md"
							bind:checked={usuario.es_jurado}
						/>
					</div>
					<div class="grid grid-cols-2 gap-1 justify-evenly items-center">
						<label
							for="es_asesor"
							class="min-w-max text-sm font-medium text-gray-700"
						>
							Es Asesor
						</label>
						<input
							type="checkbox"
							id="es_asesor"
							name="es_asesor"
							class="mt-1 p-2 w-full border rounded-md"
							bind:checked={usuario.es_asesor}
						/>
					</div>
				</div>

				<div class="grid grid-cols-2 gap-2">
					<div class="grid grid-cols-2 gap-1 justify-evenly items-center">
						<label
							for="es_admin"
							class="min-w-max text-sm font-medium text-gray-700"
						>
							Es Administrador
						</label>
						<input
							type="checkbox"
							id="es_admin"
							name="es_admin"
							class="mt-1 p-2 w-full border rounded-md"
							bind:checked={usuario.es_admin}
						/>
					</div>
					<div class="grid grid-cols-2 gap-1 justify-evenly items-center">
						<label
							for="acceso_sistema"
							class="min-w-max text-sm font-medium text-gray-700"
						>
							Tiene Acceso al Sistema
						</label>
						<input
							type="checkbox"
							id="acceso_sistema"
							name="acceso_sistema"
							class="mt-1 p-2 w-full border rounded-md"
							bind:checked={usuario.acceso_sistema}
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
