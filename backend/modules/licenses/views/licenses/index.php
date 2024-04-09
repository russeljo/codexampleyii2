<?php

use backend\modules\licenses\controllers\LicensesController;
use backend\modules\licenses\models\LicensesSearch;
use common\models\db\licenses\Licenses;
use kartik\date\DatePicker;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var View               $this  */
/* @var LicensesSearch     $searchModel  */
/* @var ActiveDataProvider $dataProvider  */

$this->title = 'Справочник лицензий';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-header">
    <h1><?= $this->title; ?></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class=" col-md-2 mb-2">
                            <?= Html::a(
                                'Добавить ключ',
                                Url::to(LicensesController::getUrlRoute(LicensesController::ACTION_CREATE)),
                                [
                                    'title' => 'Добавить ключ',
                                    'class' => 'btn btn-success',
                                    'data' => [
                                        'confirm' => 'Ключ будет сгенерирован автоматически.',
                                        'method' => 'POST'
                                    ],
                                ]
                            ) ?>
                            <br>
                        </div>
                    </div>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel'   => $searchModel,
                        'layout'        =>"{summary}\n{items}",
                        'summary'       =>"Показано <b>{begin}-{end}</b> из <b>{totalCount}</b> записей",
                        'columns' => [
                            Licenses::ATTR_KEY,
                            [
                                'attribute' => Licenses::RELATION_AUTHOR,
                                'format' => 'raw',
                                'value' => function (Licenses $data) {
                                    return $data->author->getFio();
                                },
                            ],
                            [
                                'attribute' => Licenses::ATTR_DATE_CREATE,
                                'headerOptions' => ['style' => 'width:15%'],
                                'value' => function ($data) {
                                    return Yii::$app->formatter->asDateTime($data->date_create);
                                },
                                'filter'    => DatePicker::widget([
                                    'model'         => $searchModel,
                                    'attribute'     => Licenses::ATTR_DATE_CREATE,
                                    'options'       => ['placeholder' => 'Выберите дату'],
                                    'pluginOptions' => [
                                        'format'    => 'yyyy-mm-dd',
                                    ]
                                ]),
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Действия',
                                'template'=> '&nbsp;{update}&nbsp;',
                                'headerOptions' => ['style' => 'width:10%'],
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    if ($action === 'update') {
                                        return Url::to(LicensesController::getUrlRoute(LicensesController::ACTION_UPDATE, ['id' => $model->id]));
                                    }
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <nav aria-label="Page navigation">
                <?= LinkPager::widget([
                    'pagination'                    => $dataProvider->pagination,
                    'maxButtonCount'                => 10,
                    'linkOptions'                   => [
                        'class' => 'page-link'
                    ],
                    'pageCssClass'                  => [
                        'class' => 'page-item'
                    ],
                    'disabledListItemSubTagOptions' => [
                        'tag'   => 'a',
                        'class' => 'page-link'
                    ],
                    'nextPageLabel'                 => 'Вперед',
                    'prevPageLabel'                 => 'Назад',
                    'firstPageLabel'                => 'В начало',
                    'lastPageLabel'                 => 'В конец',
                    'nextPageCssClass'              => 'page-item',
                    'prevPageCssClass'              => 'page-item',
                    'firstPageCssClass'             => 'page-item',
                    'lastPageCssClass'              => 'page-item'
                ]); ?>
            </nav>
        </div>
    </div>
</div>