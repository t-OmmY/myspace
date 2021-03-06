<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\IncomePlan */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Income Plan',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Income Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['index', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="income-plan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
