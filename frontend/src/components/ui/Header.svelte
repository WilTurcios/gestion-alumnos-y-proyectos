<script>
	import { navigate } from 'svelte-routing'
	import { AuthenticatedUser } from '../../store/AuthenticatedUserStore'
	import Navbar from './Navbar.svelte'

	const handleClick = e => {
		AuthenticatedUser.logout().then(() => {
			console.log('Sesión cerrada correctamente')

			navigate('/login', { replace: true })
		})
	}
</script>

<header
	class="lg:w-[1200px] md:w-screen h-24 shadow-md flex justify-between items-center p-4 sticky top-0 left-0 bg-white"
>
	<h1 class="text-2xl">My Website</h1>

	<Navbar />
	<button class="px-4 py-2 text-white bg-black rounded" on:click={handleClick}>
		Cerrar Sesión
	</button>
</header>
