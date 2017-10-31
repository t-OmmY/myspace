<?php

namespace frontend\controllers;

use common\models\Exchange;
use common\models\Income;
use common\models\Outgo;
use Yii;
use common\models\Wallet;
use common\models\WalletSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\User;

/**
 * WalletController implements the CRUD actions for Wallet model.
 */
class WalletController extends Controller
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
     * Lists all Wallet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Wallet();
        $currency_list = Yii::$app->params['currency_list'];
        $model->user_id = Yii::$app->getUser()->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $searchModel = new WalletSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'currency_list' => $currency_list,
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Wallet model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->user_id = Yii::$app->getUser()->id;
        $currency_list = Yii::$app->params['currency_list'];


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'currency_list' => $currency_list,
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Wallet model.
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
     * Finds the Wallet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Wallet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Wallet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRefresh()
    {
        $wallets = Wallet::find()->where(['user_id' => Yii::$app->getUser()->id])->all();

        /** @var Wallet $wallet */
        foreach ($wallets as $wallet) {

            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
SELECT IFNULL(sum(VALUE),0) 
+ (SELECT IFNULL(sum(VALUE*RATE),0) FROM exchange WHERE user_id = :user_id and wallet_to = :wallet_id)
- (SELECT IFNULL(sum(VALUE),0) FROM outgo WHERE user_id = :user_id and wallet_id = :wallet_id)
- (SELECT IFNULL(sum(VALUE),0) FROM exchange WHERE user_id = :user_id and wallet_from = :wallet_id)
 as balance FROM income WHERE user_id = :user_id and wallet_id = :wallet_id",
                [
                    ':user_id' => Yii::$app->getUser()->id,
                    ':wallet_id' => $wallet->id
                ]
            );

            $result = $command->queryOne();
            $wallet->balance = $result['balance'];
            $wallet->save();
        }

        return $this->redirect(['index']);
    }
}
