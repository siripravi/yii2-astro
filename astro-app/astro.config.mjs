import { defineConfig } from "astro/config";
import react from "@astrojs/react";
import fs from 'fs';
import path from 'path';

// Ensure directory exists
const webrootPath = path.resolve('../web/_astro');
if (!fs.existsSync(path.dirname(webrootPath))) {
  fs.mkdirSync(path.dirname(webrootPath), { recursive: true });
}

export default defineConfig({
  integrations: [react()],
  outDir: "./dist",
  vite: {
    plugins: [
      {
        name: 'copy-astro-files',
        closeBundle: async () => {
          try {
            await fs.promises.cp(
              path.resolve('dist/_astro'),
              webrootPath,
              { 
                recursive: true,
                force: true
              }
            );
            console.log('Successfully copied _astro files!');
          } catch (err) {
            console.error('Error copying files:', err);
          }
        }
      }
    ],
    build: {
      minify: false
    }
  }
});
/*
export default defineConfig({
  integrations: [react()],
  outDir: "./dist",
   output: 'static',
   vite: {
    logLevel: 'info',
    build: {
      minify: false
    }
  }
});  */