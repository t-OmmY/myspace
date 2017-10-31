<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OutgoSource */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="outgo-source-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-5">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-sm-5">
            <?= $form->field($model, 'description')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
