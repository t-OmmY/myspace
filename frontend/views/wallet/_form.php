<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Wallet */
/* @var $currency_list array */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wallet-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'code')->dropDownList($currency_list) ?>
        </div>

        <div class="col-sm-4">
            <?= $form->field($model, 'description')->textInput() ?>
        </div>

        <div class="col-sm-2">
            <?= $form->field($model, 'balance')->textInput(['readonly' => 'true']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
