import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: [
                "resources/js/**",
                "routes/**",
                "resources/views/**",
                "app/Livewire/**",
                "app/Filament/**",
                "app/Providers/Filament/**",
            ],
        }),
    ],
    esbuild: {
        minifyWhitespace: true,
    },
    build: {
        minify: "esbuild",
        cssMinify: "esbuild",
    },
});
