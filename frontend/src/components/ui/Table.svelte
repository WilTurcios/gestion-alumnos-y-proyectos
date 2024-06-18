<script>
	export let headers, title
</script>

<h1 class="text-2xl mb-10">{title}</h1>
<table class="shadow-lg rounded px-4 py-2">
	<thead>
		{#each headers as header}
			<th
				class="min-w-max px-2 py-2 bg-gray-50 text-xs font-medium text-gray-500 tracking-wider"
				>{header}</th
			>
		{/each}
	</thead>
	<slot />
</table>
