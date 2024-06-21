<script>
	import { Link, navigate } from 'svelte-routing'
	import Container from '../../components/ui/Container.svelte'
	import Table from '../../components/ui/Table.svelte'
	import { Users } from '../../store/UsersStore.js'
	import Toast from '../../components/ui/Toast.svelte'
	import { deleteProjectById, getProjects } from '../../services/ProjectService'
	import { AuthenticatedUser } from '../../store/AuthenticatedUserStore'

	let toastElement = null
	let showToast = false
	let toastText = 'Registro eliminado correctament'
	let variant = 'danger'
	let timer

	if ($AuthenticatedUser === null) {
		navigate('/login', { replace: true })
	}

	$: proyectos = getProjects()

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
		deleteProjectById(id)

		proyectos = getProjects()
	}

	function handleSearch(event) {
		const search = event.target.value
		if (!search) {
			proyectos = getProjects()
		}

		clearTimeout(timer) // Usa clearTimeout en lugar de clearInterval

		timer = setTimeout(() => {
			fetch(
				`http://localhost/proyecto-DAW/backend/api/proyectos?tema=${search}`
			)
				.then(res => {
					proyectos = res.json()
				})
				.catch(error => {
					console.error('Error fetching projects:', error)
				})
		}, 750)
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
					on:keyup={handleSearch}
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
			{#await proyectos}
				<p>Cargango proyectos...</p>
			{:then proyectos}
				{#each proyectos as project}
					<tr>
						<td class="px-4 py-2 whitespace-nowrap min-w-max w-8 max-w-16">
							<input
								type="checkbox"
								bind:group={ids_usuarios.ids}
								value={project.id}
							/>
						</td>
						<td class=" px-2 py-2 whitespace-nowrap">
							{project.tema}
						</td>
						<td class=" px-2 py-2 whitespace-nowrap">
							{project.empresa.nombre}
						</td>
						<td class=" px-2 py-2 whitespace-nowrap">
							{project.asesor.nombres}
							{project.asesor.apellidos}
						</td>
						<td class=" px-2 py-2 whitespace-nowrap">
							{project.estado}
						</td>
						<td class=" px-2 py-2 whitespace-nowrap">
							{project.creado_por.nombres}
							{project.creado_por.apellidos}
						</td>
						<td class=" px-2 py-2 whitespace-nowrap">
							{project.tel}
						</td>
						<td class=" px-2 py-2 whitespace-nowrap">
							{project.fecha_presentacion}
						</td>
						<td class=" px-2 py-2 whitespace-nowrap">
							{project.email}
						</td>
						<td
							class="px-2 py-2 whitespace-nowrap flex justify-between items-center"
						>
							<button
								type="button"
								class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md mr-4 focus:outline-none focus:bg-red-600"
								on:click={handleDelete(project.id)}>Eliminar</button
							>
							<div class="flex gap-2 justify-between items-center">
								<Link
									to="/usuarios/{project.id}"
									class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:bg-blue-600"
								>
									Editar Usuario
								</Link>
							</div>
						</td>
					</tr>
				{/each}
			{/await}
		</tbody>
	</Table>
</Container>

{#if showToast}
	<Toast bind:toast={toastElement} text={toastText} {variant} bind:showToast />
{/if}
