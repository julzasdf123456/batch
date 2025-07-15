import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import inject from "@rollup/plugin-inject";

import vue from "@vitejs/plugin-vue";

export default defineConfig({
    // server: {
    //     host: 'batch.local', // ✅ Force IPv4 instead of 'batch.local'
    //     port: 5173,
    //     hmr: {
    //       host: 'batch.local',
    //     },
    // },
    plugins: [
        vue(),

        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
