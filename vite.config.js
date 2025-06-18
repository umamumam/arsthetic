import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Aktifkan akses dari network
        port: 5173,
        strictPort: true,
        fs: {
            allow: [
                process.cwd(),
                '/public/mind-ar-js',
                '/mind-ar-js'
            ]
        },
        proxy: {
            // Proxy untuk API Laravel
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                secure: false
            },
            // Proxy khusus untuk file MindAR di development
            '/mind-ar-js': {
                target: 'http://localhost:5173',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/mind-ar-js/, '/public/mind-ar-js')
            }
        }
    },
    build: {
        assetsInclude: ['**/*.html', 'public/mind-ar-js/**'],
        rollupOptions: {
            output: {
                // Preserve struktur folder di build production
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.includes('mind-ar-js')) {
                        return 'mind-ar-js/[name][extname]'
                    }
                    return 'assets/[name][extname]'
                }
            }
        }
    }
});
