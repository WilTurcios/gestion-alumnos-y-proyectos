<script>
	export let placeHolder = ''
	export const searchInfo = data => console.log('Nothing here')

	let search = ''
	let message = ''

	const handleSubmit = e => {
		if (!search.trim())
			message = 'Asegurate de proporcionar un valor antes de enviar el usuario'

		searchInfo(search)
	}
</script>

<div>
	<form on:submit|preventDefault={handleSubmit} class="p-2">
		<input
			type="text"
			bind:value={search}
			placeholder={placeHolder}
			class="shadow-sm md:w-80 p-2"
		/>
	</form>
</div>
