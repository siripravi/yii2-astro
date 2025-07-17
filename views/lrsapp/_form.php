<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var micro\models\Lrsapp $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lrsapp-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appl_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'survey_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plot_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_layout')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aprv_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fee_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
