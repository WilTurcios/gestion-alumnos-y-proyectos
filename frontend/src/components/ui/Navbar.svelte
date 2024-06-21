<script>
	import { Link } from 'svelte-routing'
</script>

<nav class="flex justify-evenly items-center gap-2 p-2">
	<Link to="/">Inicio</Link>
	<Link to="/usuarios">Usuarios</Link>
	<Link to="/empresas">Empresas</Link>
	<Link to="/estudiantes">Estudiantes</Link>
	<Link to="/materias">Materias</Link>
	<Link to="/proyectos">Proyectos</Link>
	<Link to="/grupos">Grupos</Link>
	<Link to="/backup">Copia de Seguridad</Link>
	<Link to="/perfil">Perfil</Link>
</nav>
