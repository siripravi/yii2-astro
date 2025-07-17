<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Fetch LRS Applications';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'start')->textInput(['placeholder' => 'e.g., M/ADIB/000031/2020']) ?>
<?= $form->field($model, 'end')->textInput(['placeholder' => 'e.g., M/ADIB/000060/2020']) ?>

<div class="form-group">
    <?= Html::submitButton('Fetch', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
