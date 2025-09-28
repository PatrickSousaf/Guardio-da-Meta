import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true, // necessário para Blade e PHP
        }),
    ],
     server: {
        host: '0.0.0.0',  // 👈 Adicione esta linha
        hmr: {
            host: 'localhost'  // 👈 E esta linha
        }
    },
});
