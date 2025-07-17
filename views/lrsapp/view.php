<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var micro\models\Lrsapp $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lrsapps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lrsapp-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'mobile',
            'appl_no',
            'name1',
            'name2',
            'survey_no',
            'plot_no',
            'is_layout',
            'aprv_status',
            'fee_status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
