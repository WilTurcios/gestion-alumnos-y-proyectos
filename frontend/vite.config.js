import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'

// https://vitejs.dev/config/
export default defineConfig({
	esbuild: {
		supported: {
			'top-level-await': true
		}
	},
	plugins: [svelte()],
	build: {
		outDir: '../administracion',
		target: 'es2022'
	}
})
