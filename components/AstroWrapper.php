<?php
namespace app\components;
use yii\helpers\Html;
use yii\helpers\Json;
// Original Yii2 widget (legacy-compatible wrapper)
class AstroWrapper extends \yii\base\Widget
{
    public $component;
    public $props = [];

    public function run()
    {
        return Html::tag('astro-island', '', [
            'component' => $this->component,
            'props' => Json::encode($this->props),
            'client' => 'load'
        ]);
    }
}