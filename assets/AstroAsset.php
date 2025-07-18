<?php
namespace app\assets;

use yii\web\AssetBundle;

class AstroAsset extends AssetBundle
{
    public $baseUrl = '@web/astro-dist';
    public $css = [
        'assets/*.css'
    ];
    public $js = [
        'js/*.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}