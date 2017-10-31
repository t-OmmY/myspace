<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Wallet */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Wallet',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wallets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wallet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'currency_list' => $currency_list,
        'model' => $model,
    ]) ?>

</div>
