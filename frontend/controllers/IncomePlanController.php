<?php

namespace frontend\controllers;

use Yii;
use common\models\IncomePlan;
use common\models\IncomePlanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IncomePlanController implements the CRUD actions for IncomePlan model.
 */
class IncomePlanController extends Controller
{
    /**
     * @inheritdoc
     */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			[
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'actions' => ['login', 'error'],
						'allow' => true,
					],
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			]
		];
	}

    /**
     * Lists all IncomePlan models.
     * @return mixed
     */
    public function actionIndex()
    {
		$model = new IncomePlan();

        /** @var IncomePlan $last_record */
        $last_record = IncomePlan::find()->orderBy(['id' => SORT_DESC])->one();

        $model->user_id = Yii::$app->getUser()->id;
        $model->date_from = $last_record ? $last_record->date_from : date("Y-m-d");
        $model->date_to = $last_record ? $last_record->date_to : date('Y-m-d', strtotime("+1 months", strtotime($model->date_from)));

		$model->wallet_id = $model->user->main_wallet_id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}


        $searchModel = new IncomePlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
		]);
    }

    /**
     * Updates an existing IncomePlan model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		$model->user_id = Yii::$app->getUser()->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

		return $this->render('update', [
			'model' => $model,
		]);

    }

    /**
     * Deletes an existing IncomePlan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the IncomePlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IncomePlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IncomePlan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
