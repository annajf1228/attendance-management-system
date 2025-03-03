import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/sass/common/base.scss',
                'resources/sass/login/index.scss',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: true,
        hmr: {
            host: 'localhost',
        },
    },
});
