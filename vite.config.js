import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input  : [
                'resources/css/app.css',
                'resources/css/fonts.css',
                'resources/css/sidebar.css',
                'resources/js/acceptance_terms.js',
                'resources/js/app.js',
                'resources/js/qrcode.js',
                'resources/js/questions.js',
                'resources/js/schedules.js',
                'resources/js/sidebar.js',
                'resources/admin/js/events.js',
            ],
            refresh: true,
        }),
    ],
});
