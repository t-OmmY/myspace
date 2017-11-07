<?php

namespace frontend\controllers;

use common\models\Exchange;
use common\models\Income;
use common\models\Outgo;
use linslin\yii2\curl\Curl;
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

        $user = $model->user;
        $main_wallet = $user->mainWallet;
        $user_wallets = $user->wallets;
        $user_wallets_list = [];
        foreach ($user_wallets as $user_wallet) {
            $user_wallets_list[$user_wallet->id] = $user_wallet->code;
        }
        $user_wallets_list = array_unique($user_wallets_list);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $searchModel = new WalletSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $total_balance = Wallet::getTotalBalance();

        return $this->render('index', [
            'user_wallets_list' => $user_wallets_list,
            'main_wallet' => $main_wallet,
            'total_balance' =>  $total_balance,
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

            $income_sum = Income::find()->where(['user_id' => Yii::$app->getUser()->id, 'wallet_id' => $wallet->id])->sum('value');
            $exchange_to_sum = Exchange::find()->where(['user_id' => Yii::$app->getUser()->id, 'wallet_to' => $wallet->id])->sum('value*rate');
            $outgo_sum = Outgo::find()->where(['user_id' => Yii::$app->getUser()->id, 'wallet_id' => $wallet->id])->sum('value');
            $exchange_from_sum = Exchange::find()->where(['user_id' => Yii::$app->getUser()->id, 'wallet_from' => $wallet->id])->sum('value');

            $wallet->balance = $income_sum + $exchange_to_sum - $outgo_sum - $exchange_from_sum;
            $wallet->save();
        }

        return $this->redirect(['index']);
    }

    public function actionChangeMainWallet()
    {
        $model = $this->findModel(Yii::$app->request->get('id'));
        if (!empty($model)){
            $user = $model->user;
            $user->main_wallet_id = Yii::$app->request->get('id');
            $user->save();
        }
        return $this->redirect(['index']);
    }
}
