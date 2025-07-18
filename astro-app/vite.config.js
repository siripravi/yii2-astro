import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
  plugins: [react()],
  build: {
    outDir: '../web/dist', // Output to Yii2's web-accessible folder
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/islands/Yii2Button.jsx')
      },
      output: {
        entryFileNames: `[name].js`,
        assetFileNames: `[name].[ext]`
      }
    }
  },
  server: {
    cors: true,
    proxy: {
      '/api': {
        target: 'http://localhost:8080', // Yii2 dev server
        changeOrigin: true
      }
    }
  }
});