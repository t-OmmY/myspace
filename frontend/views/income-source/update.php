<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\IncomeSource */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Income Source',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Income Sources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="income-source-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
