<script>
	import { navigate } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Toast from '../../components/ui/Toast.svelte'
	import { Groups } from '../../store/GroupsStore.js'

	export let currentGroupID

	let toastElement = null
	let toastText = 'El grupo se ha creado correctamente'
	let variant = 'warning'
	let showToast = false

	let grupo = {
		id: currentGroupID,
		grupo: null
	}

	const getById = id => {
		fetch(`http://localhost/proyecto-DAW/backend/api/grupos/${id}`)
			.then(res => {
				return res.json()
			})
			.then(group => {
				grupo.grupo = group[0].nombre_grupo
			})
	}

	getById(currentGroupID)

	function handleSubmit(e) {
		Groups.updateGroup(grupo)
			.then(() => {
				grupo.grupo = null
				showToast = true
				toastText = 'Grupo actualizado correctamente'
				variant = 'success'

				setTimeout(() => {
					navigate('/grupos', { replace: true })
				}, 1500)
			})
			.catch(error => {
				console.error('Error al crear el grupo:', error)
			})
	}
</script>

<Container>
	<div
		class="w-[600px] mx-auto bg-white rounded-md overflow-hidden shadow-md mb-10"
	>
		<div class="p-4">
			<h2 class="text-lg font-semibold mb-4">Actualizar Grupo</h2>
			<form on:submit|preventDefault={handleSubmit}>
				<div>
					<label
						for="nombre_grupo"
						class="block text-sm font-medium text-gray-700"
						>Nombre del Grupo</label
					>
					<input
						type="text"
						id="nombre_grupo"
						name="nombre_grupo"
						class="mt-1 p-2 w-full border rounded-md focus:outline focus:outline-1"
						bind:value={grupo.grupo}
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
