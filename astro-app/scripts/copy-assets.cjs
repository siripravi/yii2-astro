const fs = require('fs-extra');
const src = './dist';
const dest = '../web/astro-dist';

if (fs.existsSync(src)) {
  fs.emptyDirSync(dest);
  fs.copySync(src, dest);
  console.log('✅ Astro assets copied successfully');
} else {
  console.error('❌ Build failed: no files in dist/');
  process.exit(1);
}