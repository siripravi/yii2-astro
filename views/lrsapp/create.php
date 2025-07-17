<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var micro\models\Lrsapp $model */

$this->title = 'Create Lrsapp';
$this->params['breadcrumbs'][] = ['label' => 'Lrsapps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lrsapp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
