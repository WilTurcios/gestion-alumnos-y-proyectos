<script>
	import { Link } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Table from '../../components/ui/Table.svelte'
	import { Users } from '../../store/UsersStore.js'
	import Toast from '../../components/ui/Toast.svelte'

	let toastElement = null
	let showToast = false
	let toastText = 'Registro eliminado correctament'
	let variant = 'danger'

	let ids_usuarios = {
		ids: []
	}

	const handleDeleteMultiple = () => {
		Users.deleteMutlipleUsers(ids_usuarios.ids).then(() => {
			showToast = true
			toastText = 'Usuarios eliminados correctamente'
			variant = 'success'
		})
	}

	const handleDelete = id => e => {
		Users.deleteUsers(id).then(() => {
			showToast = true
			toastText = 'Usuario eliminado correctamente'
			variant = 'success'
		})
	}
</script>

<Container>
	<header class="w-full flex justify-between items-center">
		<h2 class="text-3xl text-light font-semibold mb-4">Usuarios</h2>
		<div class="flex gap-2 justify-between items-center">
			<form class="flex gap-2 justify-between items-center">
				<button
					class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-green-600"
				>
					Buscar
				</button>
				<input
					type="text"
					class="border focus:outline focus:outline-1 px-4 py-2 rounded"
					placeholder="Buscar usuarios..."
				/>
			</form>
			<div class="flex gap-2 justify-between items-center">
				<Link
					to="/usuarios/agregar_usuario"
					class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
				>
					Agregar Usuario
				</Link>
				<button
					class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
					on:click={handleDeleteMultiple}
					disabled={ids_usuarios.ids.length === 0}
				>
					Eliminar Seleccionados
				</button>
			</div>
		</div>
	</header>
	<Table
		headers={[
			'Accion Multiple',
			'Usuario',
			'Clave',
			'Nombres',
			'Apellidos',
			'Carnet',
			'Telefono',
			'Celular',
			'Correo',
			'Acciones'
		]}
		title="Registros de usuarios"
	>
		<tbody class="text-xs">
			{#each $Users as user}
				<tr>
					<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
						<input
							type="checkbox"
							bind:group={ids_usuarios.ids}
							value={user.id}
						/>
					</td>
					<td class=" px-2 py-2 whitespace-nowrap">
						{user.nombre_usuario}
					</td>
					<td class=" px-2 py-2 whitespace-nowrap">
						{user.clave}
					</td>
					<td class=" px-2 py-2 whitespace-nowrap">
						{user.nombres}
					</td>
					<td class=" px-2 py-2 whitespace-nowrap">
						{user.apellidos}
					</td>
					<td class=" px-2 py-2 whitespace-nowrap">
						{user.carnet_docente}
					</td>
					<td class=" px-2 py-2 whitespace-nowrap">
						{user.tel}
					</td>
					<td class=" px-2 py-2 whitespace-nowrap">
						{user.celular}
					</td>
					<td class=" px-2 py-2 whitespace-nowrap">
						{user.email}
					</td>
					<td
						class="px-2 py-2 whitespace-nowrap flex justify-between items-center"
					>
						<button
							type="button"
							class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
							on:click={handleDelete(user.id)}>Eliminar</button
						>
						<div class="flex gap-2 justify-between items-center">
							<Link
								to="/usuarios/{user.id}"
								class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
							>
								Editar Usuario
							</Link>
						</div>
					</td>
				</tr>
			{/each}
		</tbody>
	</Table>
</Container>

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
