<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var micro\models\Lrsapp $model */

$this->title = 'Update Lrsapp: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lrsapps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lrsapp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
