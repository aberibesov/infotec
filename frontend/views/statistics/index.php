<?php

use yii\grid\SerialColumn;
use common\models\Author;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\search\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'TOP 10';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => SerialColumn::class],

            'year',
            [
                'attribute' => 'authorID',
                'filter' => Author::find()->select(['full_name'])->indexBy('id')->column(),
                'value' => static function ($model) {
                    return $model->authorName;
                }
            ],
            'countBooks',
            [
                'class' => ActionColumn::class,
                'template' => '{subscribe}',
                'buttons' => [
                    'subscribe' => static function ($url, $model) {
                        $icon = Html::tag('span', 'Подписаться');
                        return Html::a(
                            $icon,
                            ['statistics/add-notification', 'author_id' => $model->authorID],
                            ['title' => 'subscribe', 'data-pjax' => '0', 'target' => 'blank']
                        );
                    },
                ]
            ],
        ],
    ]); ?>


</div>
