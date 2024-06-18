<script>
	import Header from './components/ui/Header.svelte'
	import Footer from './components/ui/Footer.svelte'
	import Home from './pages/Home.svelte'
	import Students from './pages/estudiantes/Students.svelte'
	import Users from './pages/usuarios/Users.svelte'
	import Groups from './pages/grupos/Groups.svelte'
	import Companies from './pages/empresas/Companies.svelte'
	import { Router, Route, navigate } from 'svelte-routing'
	import Container from './components/ui/Container.svelte'
	import EditStudent from './pages/estudiantes/EditStudent.svelte'
	import EditUser from './pages/usuarios/EditUser.svelte'
	import AddUser from './pages/usuarios/AddUser.svelte'
	import AddStudent from './pages/estudiantes/AddStudent.svelte'
	import EditCompany from './pages/empresas/EditCompany.svelte'
	import AddCompany from './pages/empresas/AddCompany.svelte'
	import EditGroup from './pages/grupos/EditGroup.svelte'
	import AddGroup from './pages/grupos/AddGroup.svelte'
	import GridComponent from './pages/Grid.svelte'
	import { AuthenticatedUser } from './store/AuthenticatedUserStore'
	import Login from './pages/auth/Login.svelte'

	// if ($AuthenticatedUser === null) {
	// 	navigate('/login', { replace: true })
	// }
</script>

<main>
	<Router>
		<!-- {#if $AuthenticatedUser !== null} -->
		<Container>
			<Header />
		</Container>

		<Route path="/">
			<Home />
		</Route>
		<Route path="/estudiantes">
			<Students />
		</Route>
		<Route path="/estudiantes/agregar_estudiante">
			<AddStudent />
		</Route>
		<Route path="/estudiantes/:id" let:params>
			<EditStudent current_student_id={params.id} />
		</Route>
		<Route path="/grupos">
			<Groups />
		</Route>
		<Route path="/grupos/agregar_grupo">
			<AddGroup />
		</Route>
		<Route path="/grupos/:id" let:params>
			<EditGroup currentGroupID={params.id} />
		</Route>
		<Route path="/empresas">
			<Companies />
		</Route>
		<Route path="/empresas/agregar_empresa">
			<AddCompany />
		</Route>
		<Route path="/empresas/:id" let:params>
			<EditCompany currentCompanyId={params.id} />
		</Route>
		<Route path="/usuarios">
			<Users />
		</Route>
		<Route path="/usuarios/agregar_usuario">
			<AddUser />
		</Route>
		<Route path="/usuarios/:id" let:params>
			<EditUser currentUserID={params.id} />
		</Route>
		<Route path="/perfil">
			<Users />
		</Route>
		<Route path="/prueba">
			<GridComponent />
		</Route>
		<Container>
			<Footer />
		</Container>
		<!-- {:else} -->
		<!-- <Route path="/login">
				<Login />
			</Route> -->
		<!-- {/if} -->
	</Router>
</main>
