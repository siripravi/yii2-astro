const fs = require('fs');
const path = require('path');
const postcss = require('postcss');
const purgecss = require('@fullhuman/postcss-purgecss');
const glob = require('glob');

// Find all CSS files in astro-dist/assets
const cssFiles = glob.sync(path.join(__dirname, '../web/astro-dist/assets/*.css'));

if (cssFiles.length === 0) {
  console.error('❌ No CSS files found to purge.');
  process.exit(1);
}

cssFiles.forEach(filePath => {
  const css = fs.readFileSync(filePath, 'utf8');
  const outputPath = filePath.replace(/\.css$/, '.purged.css');

  postcss([
    purgecss({
      content: [
        path.join(__dirname, '../web/**/*.php'),
        path.join(__dirname, '../web/astro-dist/**/*.html'),
      ],
      defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || [],
      safelist: [
        /^yii-/,
        /^js-/,
        /-(leave|enter|appear)(|-(to|from|active))$/,
        /data-v-.*/
      ]
    })
  ])
    .process(css, { from: filePath, to: outputPath })
    .then(result => {
      fs.writeFileSync(outputPath, result.css, 'utf8');
      console.log(`✅ Purged CSS written to: ${outputPath}`);
    })
    .catch(err => {
      console.error(`❌ Error purging ${filePath}:`, err);
    });
});
