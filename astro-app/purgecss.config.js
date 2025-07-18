// purgecss.config.cjs
const path = require('path');
const { fileURLToPath } = require('url');

// Convert Windows paths to file:// URLs
function toFileURL(filePath) {
  const absolutePath = path.resolve(filePath).replace(/\\/g, '/');
  return `file:///${absolutePath}`;
}

module.exports = {
  content: [
    toFileURL(path.join(__dirname, '../web/**/*.php')),
    toFileURL(path.join(__dirname, '../web/astro-dist/**/*.html'))
  ],
  css: [toFileURL(path.join(__dirname, '../web/astro-dist/assets/*.css'))],
  defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || [],
  safelist: [
    /-(leave|enter|appear)(|-(to|from|active))$/,
    /^router-link(|-exact)-active$/,
    /data-v-.*/,
    /^js-/,
    /^yii-/
  ]
};