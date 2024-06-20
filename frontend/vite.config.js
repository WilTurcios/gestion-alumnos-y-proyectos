import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'

// https://vitejs.dev/config/
export default defineConfig({
	build: {
		target: 'es2022'
	},
	esbuild: {
		supported: {
			'top-level-await': true
		}
	},
	plugins: [svelte()],
	build: {
		outDir: '../administracion'
	}
})
