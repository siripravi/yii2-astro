// postcss.config.mjs
import path from 'node:path';
import pkg from '@fullhuman/postcss-purgecss';

// Handle different export styles from the package
const purgecss = pkg.default?.default || pkg.default || pkg;

export default {
  plugins: [
    purgecss({
      content: [
        './src/**/*.{astro,jsx,tsx}',
        path.resolve('../web/views/**/*.php')
      ],
      defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || [],
      safelist: [
        /^yii-/,
        /^js-/,
        /-(leave|enter|appear)(|-(to|from|active))$/,
        /data-v-.*/
      ]
    })
  ]
};