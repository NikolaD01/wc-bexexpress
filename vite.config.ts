import { defineConfig } from 'vite';
import * as path from 'path';


export default defineConfig({
    publicDir: 'static',
    build: {
        outDir: 'public',
        emptyOutDir: false,
        rollupOptions: {
            input: {
                main: './src/main.ts',
                admin: './src/admin.ts',
            },
            output: {
                entryFileNames: '[name].js',
                format: 'esm',
                inlineDynamicImports: false,
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