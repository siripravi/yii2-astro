<?php

use app\helpers\Astro;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin(['id' => 'form-rate']); ?>

<?php echo $model->rating;?>
<?php echo $form->field($model, 'rating')->hiddenInput([
    'id' => 'product-rating',
    'value' => $model->rating ?? 2
])->label(false);

// 2. Star rating component
echo Astro::partial('StarRating', [
    'fieldId' => 'product-rating',
    'initial' => $model->rating ?? 1,
    'max' => 5
]);
?>
<div class="form-group">
    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>