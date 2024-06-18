<script>
	export let imageContainer, showImage, placeholder, image
</script>

<div class="py-4 flex flex-col justify-center items-center">
	<h1 class="text-lg font-semibold mb-4">
		Previsualización de la Fotografía del Estudiante
	</h1>
	<div
		bind:this={imageContainer}
		class="w-80 min-w-32 min-h-32 border-gray-700 border-2"
	>
		{#if showImage}
			<img bind:this={image} src="" alt="Preview" />
		{:else}
			<span
				bind:this={placeholder}
				class="grid place-items-center text-3xl font-light">Image Preview</span
			>
		{/if}
	</div>
</div>
