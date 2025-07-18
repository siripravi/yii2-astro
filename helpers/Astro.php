<?php
namespace app\helpers;

use Yii;

class Astro
{
    protected static function findHashedAsset($baseName)
    {
        $webroot = Yii::getAlias('@webroot');
        $files = glob("$webroot/astro-dist/js/{$baseName}.*.js");
        
        if (empty($files)) {
            Yii::error("Astro asset not found: {$baseName}.*.js");
            return null;
        }
        
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $filemtime = filemtime($files[0]);
        return "/astro-dist/js/" . basename($files[0]) . "?v=$filemtime";
    }

    public static function partial($componentName, $props = array())
    {
        $uid = uniqid();
        $componentFile = self::findHashedAsset($componentName);
        
        if (!$componentFile) {
            return '<!-- Component file not found -->';
        }

        $fieldId = isset($props['fieldId']) ? $props['fieldId'] : 'product-rating';
        $initial = isset($props['initial']) ? (int)$props['initial'] : 0;
        $max = isset($props['max']) ? (int)$props['max'] : 5;

        $fallback = '';
        for ($i = 0; $i < $max; $i++) {
            $color = $i < $initial ? 'gold' : 'gray';
            $fallback .= '<span style="font-size:1.5rem;color:'.$color.'">★</span>';
        }

        return <<<HTML
<div id="react-root-{$uid}" style="display:flex;gap:0.5rem">
  {$fallback}
</div>

<script>
(function() {
  if (document.querySelector('#react-root-{$uid} [data-reactroot]')) return;

  function loadReact() {
    return new Promise((resolve) => {
      if (window.React && window.ReactDOM) return resolve();

      const reactScript = document.createElement('script');
      reactScript.src = 'https://unpkg.com/react@18/umd/react.production.min.js';
      reactScript.crossOrigin = '';
      
      const reactDOMScript = document.createElement('script');
      reactDOMScript.src = 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js';
      reactDOMScript.crossOrigin = '';

      reactScript.onload = function() {
        document.head.appendChild(reactDOMScript);
        reactDOMScript.onload = resolve;
      };
      
      document.head.appendChild(reactScript);
    });
  }

  async function mountComponent() {
    const container = document.getElementById('react-root-{$uid}');
    if (!container) return;

    try {
      await loadReact();
      
      const { default: StarRating } = await import('{$componentFile}');
      
      ReactDOM.createRoot(container).render(
        React.createElement(StarRating, {
          fieldId: '{$fieldId}',
          initial: {$initial},
          max: {$max}
        })
      );
    } catch (err) {
      console.error('StarRating failed to load:', err);
      container.style.display = 'flex';
    }
  }

  mountComponent();
})();
</script>
HTML;
    }
}