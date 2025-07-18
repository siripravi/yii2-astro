// astro.config.mjs
import { defineConfig } from 'astro/config';
import react from '@astrojs/react';
import path from 'node:path';

export default defineConfig({
  outDir: './dist', // Keep build output inside project
  integrations: [react()],
  vite: {
    css: {
      postcss: './postcss.config.mjs' // Explicitly point to config
    },
    build: {
      assetsDir: 'assets',
      emptyOutDir: true,
      rollupOptions: {
        output: {
          entryFileNames: 'js/[name].[hash].js',
          chunkFileNames: 'js/[name].[hash].js',
          assetFileNames: 'assets/[name].[hash][extname]'
        }
      }
    }
  }
});