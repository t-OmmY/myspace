<?php

use common\models\Wallet;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Exchange */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exchange-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                'value' => date("Y-m-d"),
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd 12:00:00',
                    'todayHighlight' => true
                ]
            ]); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'wallet_from')->dropDownList(
                ArrayHelper::map(Wallet::find()->where(['user_id' => Yii::$app->user->id])->asArray()->all(), 'id', 'name' )
            ) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'wallet_to')->dropDownList(
                ArrayHelper::map(Wallet::find()->where(['user_id' => Yii::$app->user->id])->asArray()->all(), 'id', 'name')
            ) ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'value')->textInput() ?>
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'rate')->textInput() ?>
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
