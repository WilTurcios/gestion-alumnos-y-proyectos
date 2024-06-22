<script>
	import { navigate } from 'svelte-routing'
	import { logout } from '../../services/UserService'
	import { AuthenticatedUser } from '../../store/AuthenticatedUserStore'
	import Navbar from './Navbar.svelte'

	const handleClick = e => {
		logout().then(() => {
			AuthenticatedUser.set(null)
			navigate('/login', { replace: true })
		})
	}
</script>

<header
	class="lg:w-[1200px] md:w-screen h-24 shadow-md flex justify-between items-center p-4 sticky top-0 left-0 bg-white"
>
	<h1 class="text-2xl">
		<slot />
	</h1>

	<Navbar />
	<button class="px-4 py-2 text-white bg-black rounded" on:click={handleClick}>
		Cerrar Sesión
	</button>
</header>
