import { defineConfig } from 'vite';
import * as path from 'path';


export default defineConfig({
    publicDir: 'static',
    build: {
        outDir: 'public',
        emptyOutDir: false,
        rollupOptions: {
            input: './src/main.ts',
            output: {
                entryFileNames: 'main.js',
                format: 'iife',
            },
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './src'),
        },
        extensions: ['.ts', '.js'],
    },
});