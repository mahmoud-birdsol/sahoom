import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        ...(process.env.LARAVEL_SAIL === '1' ? {
            host: '0.0.0.0',
            strictPort: true,
            hmr: {
                host: 'localhost',
            },
            watch: {
                usePolling: true,
                interval: 500,
                ignored: ['**/vendor/**', '**/storage/**'],
            },
        } : {}),
    },
});
