<?php

use common\models\OutgoType;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\OutgoTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Outgo Types');
$this->params['breadcrumbs'][] = $this->title;
$table_name = str_replace('_', '-', get_class($model)::tableName());

?>
<div class="outgo-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<div class="outgo-type-index">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'b-table b-table_orders b-table_trasaction'
        ],
        'rowOptions' => ['class' => 'tr', 'ondblclick' => "window.location.href = '/{$table_name}/update?id=' + $(this).attr('data-key')"],
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
                'class' => '\kartik\grid\DataColumn',
                'label' => Yii::t('app', 'Категория'),
                'value' => 'outgoSource.name',
                'attribute' => 'outgo_source_id',
                'filterType' => GridView::FILTER_SELECT2,
                'hAlign' => 'left',
                'vAlign' => 'middle',
                'noWrap' => true,
                'width' => '150px',
                'filter' => ArrayHelper::map(OutgoType::find()
                    ->innerJoinWith('outgoSource')
                    ->orderBy('name')->asArray()->all(), 'outgo_source_id', 'outgoSource.name'
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
                'attribute' => 'name',
                'label' => Yii::t('app', 'Значение'),
                'filterInputOptions' => ['placeholder' => 'Поиск', 'class' => 'form-control'],
                'headerOptions' => ['class' => 'th b-table__col-4'],
                'contentOptions' => ['class' => 'td b-table__col-4'],
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
                    return '/outgo-type/' . $action . '?id=' . $key;
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
                    return '/outgo-type/' . $action . '?id=' . $key;
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
