<script>
	import { navigate } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { addSubject } from '../../services/SubjectService'

	export let currentCompanyId

	let toastElement = null
	let toastText = 'La materia se ha registrado correctamente'
	let variant = 'success'
	$: showToast = false

	let materia = {
		nombre: null,
		contacto: null,
		direccion: null,
		email: null,
		telefono: null
	}

	const getById = id => {
		fetch(`http://localhost/proyecto-DAW/backend/api/materias/${id}`)
			.then(res => {
				return res.json()
			})
			.then(company => {
				materia.materia = company[0].materia
				materia.contacto = company[0].contacto
				materia.direccion = company[0].direccion
				materia.email = company[0].email
				materia.telefono = company[0].telefono
			})
	}

	getById(currentCompanyId)

	function handleSubmit(e) {
		addSubject(materia).then(company => {
			materia = {
				nombre: null,
				contacto: null,
				direccion: null,
				email: null,
				telefono: null
			}

			toastText = 'La materia ha sido actualizado exitosamente'
			variant = 'success'
			showToast = true

			setTimeout(() => {
				navigate('/materias', { replace: true })
			}, 1500)
		})
	}
</script>

<Container>
	<div
		class="w-[600px] mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4">Ingresar Datos del materia</h2>
			<form on:submit|preventDefault={handleSubmit}>
				<div class="grid grid-cols-2 gap-4">
					<div>
						<label for="nombre" class="block text-sm font-medium text-gray-700">
							Nombre de la materia
						</label>
						<input
							id="nombre"
							name="nombre"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.materia}
						/>
					</div>
					<div>
						<label
							for="contacto"
							class="block text-sm font-medium text-gray-700"
						>
							Contacto de la materia
						</label>
						<input
							id="contacto"
							name="contacto"
							class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
							bind:value={materia.contacto}
						/>
					</div>
				</div>
				<div class="grid grid-cols-2 gap-4">
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
							bind:value={materia.direccion}
						/>
					</div>
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
							bind:value={materia.telefono}
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
						bind:value={materia.email}
					/>
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
