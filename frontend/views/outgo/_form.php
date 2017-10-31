<?php

use common\models\Wallet;
use common\models\OutgoSource;
use common\models\OutgoType;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Outgo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="outgo-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                'value' => date("Y-m-d"),
                'pluginOptions' => [
                    'autoFocus' => true,
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd 12:00:00',
                    'todayHighlight' => true,
                ]
            ]); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'outgo_source_id')->dropDownList(
                ArrayHelper::map(OutgoSource::find()->where(['user_id' => Yii::$app->user->id])->orderBy('name ASC')->asArray()->all(), 'id', 'name'),
            ['prompt'=>'-Choose a Source-',
                'onchange'=>'
                $.post( "'.Yii::$app->urlManager->createUrl('outgo/lists?id=').'"+$(this).val(), function( data ) {
                  $( "select#source_type" ).html( data );
                });
            ',  'autofocus' => 'autodocus']) ?>
        </div>
        <div class="col-sm-2">
            <?php
            if (!empty($model->outgo_type_id)){
                $types = ArrayHelper::map(OutgoType::find()->where(['outgo_source_id' => $model->outgo_source_id])->orderBy('name ASC')->asArray()->all(), 'id', 'name');
            } else {
                $types = [];
            }
            ?>
            <?= $form->field($model, 'outgo_type_id')->dropDownList(
                $types,
                [
                    'id'=>'source_type',
                    'options' => [$model->outgo_type_id => ['selected'=>true]]
                ]
            ) ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'value')->textInput() ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'wallet_id')->dropDownList(
                ArrayHelper::map(Wallet::find()->where(['user_id' => Yii::$app->user->id])->orderBy('name ASC')->asArray()->all(), 'id', 'name')
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
