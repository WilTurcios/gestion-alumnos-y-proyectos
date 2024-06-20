<script>
	let status = null
	const handleClick = e => {
		status = fetch('http://localhost/proyecto-DAW/backend/backup', {
			method: 'POST'
		})
			.then(res => {
				if (res.ok) return res.json()
			})
			.then(data => data?.status)
	}
</script>

<div class="w-screen h-screen grid place-items-center">
	<div class="w-96 flex gap-2 flex-col">
		<button
			class="px-4 py-2 text-white bg-black rounded"
			on:click={handleClick}
		>
			Crear Copia de Seguridad
		</button>
		<p class="text-2xl font-semibold text-pretty text-center">
			Cuando realices esta copia de seguridad, se crearán archivos .csv
			representando cada una de las tablas de la base de datos. Si no hay ningún
			registro en ellas, entonces no se crearán los archivos
		</p>
	</div>
	{#if status}
		<p>{status}</p>
	{/if}
</div>
