<?php

namespace frontend\controllers;

use common\models\Wallet;
use Exception;
use Throwable;
use Yii;
use common\models\Exchange;
use common\models\ExchangeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExchangeController implements the CRUD actions for Exchange model.
 */
class ExchangeController extends Controller
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
        ];
    }

    /**
     * Lists all Exchange models.
     * @return mixed
     * @throws Exception
     * @throws Throwable
     */
    public function actionIndex()
    {
        $model = new Exchange();

        $model->user_id = Yii::$app->getUser()->id;
        $model->date = date("Y-m-d H:i:s");

        if ($model->load(Yii::$app->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $wallet_from = $model->walletFrom;
                $wallet_from->balance -= $model->value;
                $wallet_from->save();

                $wallet_to = $model->walletTo;
                $wallet_to->balance += ($model->value * $model->rate);
                $wallet_to->save();

                $model->save();

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
            return $this->redirect(['index']);
        }

        $searchModel = new ExchangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $total_balance = Wallet::getTotalBalance();

        return $this->render('index', [
            'total_balance' =>  $total_balance,
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Income model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws Exception
     * @throws Throwable
     */
    public function actionDelete($id)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $model = $this->findModel($id);

            $wallet_from = $model->walletFrom;
            $wallet_from->balance += $model->value;
            $wallet_from->save();

            $wallet_to = $model->walletTo;
            $wallet_to->balance -= ($model->value * $model->rate);
            $wallet_to->save();

            $model->delete();

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Exchange model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Exchange the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Exchange::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
