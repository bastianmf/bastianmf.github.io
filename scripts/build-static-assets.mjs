import { readdir, readFile } from "node:fs/promises";
import { join } from "node:path";

const roots = ["public", "resources/views"];
const forbidden = ["localhost:5173", "127.0.0.1:5173"];
const textExtensions = new Set([
    ".blade.php",
    ".css",
    ".html",
    ".js",
    ".json",
    ".php",
    ".txt",
    ".xml",
]);

async function walk(dir) {
    const entries = await readdir(dir, { withFileTypes: true });
    const files = [];

    for (const entry of entries) {
        const path = join(dir, entry.name);

        if (entry.isDirectory()) {
            files.push(...await walk(path));
        } else {
            files.push(path);
        }
    }

    return files;
}

function isTextFile(path) {
    return [...textExtensions].some((extension) => path.endsWith(extension));
}

for (const root of roots) {
    for (const file of await walk(root)) {
        if (!isTextFile(file)) {
            continue;
        }

        const content = await readFile(file, "utf8");
        const found = forbidden.find((needle) => content.includes(needle));

        if (found) {
            throw new Error(`Forbidden Vite dev server reference "${found}" in ${file}`);
        }
    }
}

console.log("No Vite pipeline detected; static public assets are ready.");
