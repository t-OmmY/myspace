<?php

use common\models\Wallet;
use common\models\IncomeSource;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Income */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="income-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'date_from')->widget(DatePicker::classname(), [
                'value' => date("Y-m-d"),
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'date_to')->widget(DatePicker::classname(), [
                'value' => date("Y-m-d"),
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'income_source_id')->dropDownList(
                ArrayHelper::map(IncomeSource::find()->where(['user_id' => Yii::$app->user->id])->orderBy('name ASC')->asArray()->all(), 'id', 'name'),
                ['autofocus' => 'autodocus']) ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'value')->textInput() ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'wallet_id')->dropDownList(
                ArrayHelper::map(Wallet::find()->where(['user_id' => Yii::$app->user->id])->orderBy('name ASC')->asArray()->all(), 'id', 'name')
            ) ?>
        </div>
        <div class="col-sm-4">
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
