<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OutgoPlan */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Outgo Plan',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Outgo Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="outgo-plan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
