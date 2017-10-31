<?php

use common\models\OutgoSource;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OutgoType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="outgo-type-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'outgo_source_id')->dropDownList(
                ArrayHelper::map(OutgoSource::find()->where(['user_id' => Yii::$app->user->id])->asArray()->all(), 'id', 'name')
            ) ?>
        </div>

        <div class="col-sm-3">
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
