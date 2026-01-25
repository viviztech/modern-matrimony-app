import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { visualizer } from 'rollup-plugin-visualizer';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        // Bundle analyzer (only in analysis mode)
        process.env.ANALYZE && visualizer({
            filename: './storage/stats.html',
            open: true,
            gzipSize: true,
            brotliSize: true,
        }),
    ],
    build: {
        // Code splitting
        rollupOptions: {
            output: {
                manualChunks: {
                    // Split vendor chunks
                    'vendor': [
                        'axios',
                        'alpinejs',
                    ],
                    // Chart.js separate chunk
                    'charts': [
                        'chart.js',
                    ],
                    // Laravel Echo separate chunk (for real-time features)
                    'realtime': [
                        'laravel-echo',
                        'pusher-js',
                    ],
                },
                // Naming strategy for chunks
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/\.(png|jpe?g|svg|gif|tiff|bmp|ico)$/i.test(assetInfo.name)) {
                        return `images/[name]-[hash][extname]`;
                    } else if (/\.(woff2?|eot|ttf|otf)$/i.test(assetInfo.name)) {
                        return `fonts/[name]-[hash][extname]`;
                    }
                    return `${ext}/[name]-[hash][extname]`;
                },
            },
        },
        // Chunk size warnings
        chunkSizeWarningLimit: 1000, // KB
        // Minification
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: process.env.NODE_ENV === 'production', // Remove console.logs in production
                drop_debugger: true,
                pure_funcs: process.env.NODE_ENV === 'production' ? ['console.log', 'console.info', 'console.debug'] : [],
            },
        },
        // Source maps (disable in production for smaller bundle)
        sourcemap: process.env.NODE_ENV !== 'production',
        // CSS code splitting
        cssCodeSplit: true,
        // Asset inline limit
        assetsInlineLimit: 4096, // 4kb
        // Report compressed size
        reportCompressedSize: true,
        // Increase target for better performance
        target: 'es2015',
    },
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: false,
        },
    },
    optimizeDeps: {
        include: ['axios', 'alpinejs'],
    },
});
