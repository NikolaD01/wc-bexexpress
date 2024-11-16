import { defineConfig } from 'vite';
import * as path from 'path';


export default defineConfig({
    build: {
        outDir: 'public',
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