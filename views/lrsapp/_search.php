<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var micro\models\LrsappSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lrsapp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'mobile') ?>

    <?= $form->field($model, 'appl_no') ?>

    <?= $form->field($model, 'name1') ?>

    <?= $form->field($model, 'name2') ?>

    <?php // echo $form->field($model, 'survey_no') ?>

    <?php // echo $form->field($model, 'plot_no') ?>

    <?php // echo $form->field($model, 'is_layout') ?>

    <?php // echo $form->field($model, 'aprv_status') ?>

    <?php // echo $form->field($model, 'fee_status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
