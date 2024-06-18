<script>
	import { navigate } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { AuthenticatedUser } from '../../store/AuthenticatedUserStore.js'

	let toastElement = null
	let toastText = 'El alumno se ha creado correctamente'
	let variant = 'warning'
	$: showToast = false

	let usuario = {
		user_name: null,
		clave: null
	}

	function handleSubmit(e) {
		AuthenticatedUser.login(usuario)
			.then(() => {
				usuario.user_name = null
				usuario.clave = null

				toastText = 'El usuario ha iniciado sesión correctamente correctamente'
				variant = 'success'
				showToast = true

				setTimeout(() => {
					navigate('/home', { replace: true })
				}, 1000)
			})
			.catch(() => {
				toastText =
					'Usuario o contraseña incorrectos, por favor intentlo de nuevo'
				variant = 'danger'
				showToast = true
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
						bind:value={usuario.user_name}
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
						bind:value={usuario.clave}
					/>
				</div>

				<div class="mt-6">
					<button
						type="submit"
						class="w-full p-3 bg-blue-500 text-white rounded-md hover:bg-blue-600"
					>
						Iniciar Sesión
					</button>
				</div>
			</form>
		</div>
	</div>
</Container>

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
