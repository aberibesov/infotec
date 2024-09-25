<?php

namespace frontend\controllers;

use frontend\models\StatisticModel;
use frontend\models\SubscribeAuthorForm;
use Yii;

class StatisticsController extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StatisticModel();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    public function actionAddNotification($author_id)
    {
        $model = new SubscribeAuthorForm();
        $model->authorID = $author_id;
        if ($model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Успешно добавлено уведомление');
        }

        return $this->render('addNotification', compact('model'));
    }
}