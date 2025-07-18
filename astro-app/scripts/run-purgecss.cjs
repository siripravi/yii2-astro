// scripts/run-purgecss.cjs
const { execSync } = require('child_process');
const path = require('path');

try {
  // Use the Windows-style path with forward slashes
  const configPath = path.resolve(__dirname, '../purgecss.config.cjs').replace(/\\/g, '/');
  
  execSync(`purgecss --config ${configPath}`, {
    stdio: 'inherit',
    shell: true
  });
  console.log('✅ CSS purging completed successfully');
} catch (error) {
  console.error('❌ CSS purging failed:', error.message);
  process.exit(1);
}