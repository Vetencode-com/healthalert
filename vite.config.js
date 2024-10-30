import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";
import { readdirSync } from 'fs';
import { resolve, join } from 'path';

// Function to recursively get all .js files in the directory
function getAllJsFiles(dirPath) {
    let jsFiles = [];
    const files = readdirSync(dirPath, { withFileTypes: true });

    for (const file of files) {
        const filePath = join(dirPath, file.name);
        if (file.isDirectory()) {
            jsFiles = jsFiles.concat(getAllJsFiles(filePath));
        } else if (file.isFile() && file.name.endsWith('.js')) {
            jsFiles.push(filePath);
        }
    }
    return jsFiles;
}

export default defineConfig({
    resolve: {
        alias: {
            "~bootstrap": resolve(__dirname, "node_modules/bootstrap"),
            "~bootstrap-icons": resolve(
                __dirname,
                "node_modules/bootstrap-icons",
            ),
            "~perfect-scrollbar": resolve(
                __dirname,
                "node_modules/perfect-scrollbar",
            ),
            "~@fontsource": resolve(__dirname, "node_modules/@fontsource"),
            "~ext": resolve(__dirname, "node_modules"),
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/sass/bootstrap.scss",
                "resources/sass/themes/dark/app-dark.scss",
                "resources/sass/app.scss",
                "resources/sass/pages/auth.scss",
                "resources/css/app.css",
                ...getAllJsFiles(resolve(__dirname, "resources/js")),
                // "resources/js/app.js",
                // "resources/js/initTheme.js"
            ],
            refresh: true,
        }),
    ],
});
