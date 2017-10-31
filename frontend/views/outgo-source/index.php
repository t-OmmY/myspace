<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\OutgoSourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Outgo Sources');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="outgo-source-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<div class="outgo-source-index">
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
                'attribute' => 'name',
                'label' => Yii::t('app', 'Название'),
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
                    return '/outgo-source/' . $action . '?id=' . $key;
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
                    return '/outgo-source/' . $action . '?id=' . $key;
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
