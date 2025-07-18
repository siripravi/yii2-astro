<?php
// assets/Yii2ButtonAsset.php
namespace app\assets;
use yii\web\AssetBundle;

class Yii2ButtonAsset extends AssetBundle {
  public $js = [
    '/dist/main.js' // Vite-built entry point
  ];
}