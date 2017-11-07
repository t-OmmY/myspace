<?php

use common\models\Income;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\IncomeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Incomes');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>
    <?= Html::encode($this->title) ?>

    <span class="pull-right text-warning">
                    <strong><?=$total_balance["value"] . ' ' . strtolower($total_balance["currency"])?></strong>
    </span>
</h1>


<div class="income-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<div class="income-index">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'b-table b-table_orders b-table_trasaction'
        ],
        'rowOptions' => ['class' => 'tr'],
        'filterRowOptions' => ['class' => 'tr form-filter'],

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'bordered' => false,
        'striped' => false,
        'hover' => true,
        'responsiveWrap' => false,
        'summary' => '',
        'resizableColumns' => false,
//        'pager' => [
//            'class' => ScrollPager::className(),
//            'container' => '.grid-view tbody',
//            'triggerOffset' => '20',
//            'item' => 'tr',
//            'paginationSelector' => '.grid-view .pagination',
//            'triggerTemplate' => '<tr class="ias-trigger"><td colspan="100%" style="text-align: center"><a style="cursor: pointer">{text}</a></td></tr>',
//        ],
        'columns' => [
            [
                'attribute' => 'date',
                'label' => Yii::t('app', 'Дата'),
                'value' => function ($model) {
                    return $model->date;
                },
                'headerOptions' => ['class' => 'th b-table__col-6'],
                'contentOptions' => ['class' => 'td b-table__col-6'],
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'convertFormat'=>true,
                    'pluginOptions'=>[
                        'timePicker'=>true,
                        'timePickerIncrement'=>30,
                        'locale'=>[
                            'format'=>'Y-m-d h:i:s'
                        ]
                    ]
                ],
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => Yii::t('app', 'Источник поступления'),
                'value' => 'incomeSource.name',
                'attribute' => 'income_source_id',
                'filterType' => GridView::FILTER_SELECT2,
                'hAlign' => 'left',
                'vAlign' => 'middle',
                'noWrap' => true,
                'width' => '150px',
                'filter' => ArrayHelper::map(Income::find()
                    ->innerJoinWith('incomeSource')
                    ->orderBy('name')->asArray()->all(), 'income_source_id', 'incomeSource.name'
                ),
                'filterWidgetOptions' => [
                    'size' => Select2::SMALL,
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
                'filterInputOptions' => ['placeholder' => 'Поиск', 'class' => 'form-control'],
                'headerOptions' => ['class' => 'th b-table__col-3'],
                'contentOptions' => ['class' => 'td b-table__col-3'],
            ],
            [
                'attribute' => 'value',
                'label' => Yii::t('app', 'Значение'),
                'filterInputOptions' => ['placeholder' => 'Поиск', 'class' => 'form-control'],
                'headerOptions' => ['class' => 'th b-table__col-4'],
                'contentOptions' => ['class' => 'td b-table__col-4'],
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'label' => Yii::t('app', 'Кошелек'),
                'value' => 'wallet.name',
                'attribute' => 'wallet_id',
                'filterType' => GridView::FILTER_SELECT2,
                'hAlign' => 'left',
                'vAlign' => 'middle',
                'noWrap' => true,
                'width' => '150px',
                'filter' => ArrayHelper::map(Income::find()
                    ->innerJoinWith('wallet')
                    ->orderBy('name')->asArray()->all(), 'wallet_id', 'wallet.name'
                ),
                'filterWidgetOptions' => [
                    'size' => Select2::SMALL,
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
                'filterInputOptions' => ['placeholder' => 'Поиск', 'class' => 'form-control'],
                'headerOptions' => ['class' => 'th b-table__col-3'],
                'contentOptions' => ['class' => 'td b-table__col-3'],
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('app', 'Описание'),
                'filterInputOptions' => ['placeholder' => 'Поиск', 'class' => 'form-control'],
                'headerOptions' => ['class' => 'th b-table__col-4'],
                'contentOptions' => ['class' => 'td b-table__col-4'],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'header' => Html::a(Yii::t('app', 'Очистить фильтр'), ['index'], ['class' => 'btn btn-warning']),
                'template' => '{update}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return '/income/' . $action . '?id=' . $key;
                },
                'viewOptions' => ['title' => 'Изменить', 'data-toggle' => 'tooltip', 'class' => 'b-table__linkIcon'],
                'headerOptions' => ['class' => 'th b-table__col-1'],
                'contentOptions' => ['class' => 'td b-table__col-1 js-positionedColumn xs-positioned'],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'header' => '',
                'template' => '{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return '/income/' . $action . '?id=' . $key;
                },
                'viewOptions' => ['title' => 'Удалить', 'data-toggle' => 'tooltip', 'class' => 'b-table__linkIcon'],
                'headerOptions' => ['class' => 'th b-table__col-1'],
                'contentOptions' => ['class' => 'td b-table__col-1 js-positionedColumn xs-positioned'],
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>
</div>


