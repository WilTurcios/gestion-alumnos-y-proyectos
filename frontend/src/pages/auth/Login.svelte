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
			.catch(err => {
				toastText = err.message
				variant = 'danger'
				showToast = true

				usuario.user_name = null
				usuario.clave = null
			})
	}
</script>

<div class="container w-screen h-screen bg-slate-600">
	<div
		class="w-full h-full mx-auto flex flex-col items-center justify-center bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<img
			src="public/images/logo.png"
			alt="ITCA FEPADE"
			class="mb-8 shadow-xl"
		/>
		<h1 class="text-3xl font-semibold mb-8">Inicio de Sesión</h1>
		<form
			on:submit|preventDefault={handleSubmit}
			class="shadow-lg rounded px-6 py-4"
		>
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

<!-- <StudentsForm/> -->
<!-- <div class="w-screen h-scree grid place-content-center"> -->
<!-- </div> -->

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
