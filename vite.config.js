import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';


export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, '/resources/js'),
            "@commonComponents": path.resolve(
                __dirname,
                "resources/js/common/components"
            ),
            "@layouts": path.resolve(
                __dirname,
                "resources/js/layouts"
            ),
            "@commonServices": path.resolve(__dirname,"resources/js/common/services"),
            "@svg": path.resolve(__dirname, "resources/js/svgs/"),
            "@commonVendor": path.resolve(
                __dirname,
                "resources/js/common/vendor"
            ),
            "@commonStores": path.resolve(
                __dirname,
                "resources/js/common/stores"
            ),
            "@config": path.resolve(
                __dirname,
                "resources/js/config"
            ),
            "@pages": path.resolve(
                __dirname,
                "resources/js/pages"
            ),
        }},
});
