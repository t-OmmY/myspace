<?php

namespace frontend\controllers;

use common\models\Wallet;
use Exception;
use Throwable;
use Yii;
use common\models\Income;
use common\models\IncomeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IncomeController implements the CRUD actions for Income model.
 */
class IncomeController extends Controller
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
     * Lists all Income models.
     * @return mixed
     * @throws Exception
     * @throws Throwable
     */
    public function actionIndex()
    {
        $model = new Income();

        $model->user_id = Yii::$app->getUser()->id;
        $model->date = date("Y-m-d H:i:s");

        if ($model->load(Yii::$app->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $wallet = $model->wallet;
                $wallet->balance += $model->value;
                $wallet->save();

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

        $searchModel = new IncomeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $total_balance = Wallet::getTotalBalance();

        return $this->render('index', [
            'total_balance' =>  $total_balance,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,

        ]);
    }

    /**
     * Updates an existing Income model.
     * @param integer $id
     * @return mixed
     * @throws Exception
     * @throws Throwable
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->user_id = Yii::$app->getUser()->id;

        if ($model->load(Yii::$app->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $old_value = $model->getOldAttribute('value');
                $old_wallet_id = $model->getOldAttribute('wallet_id');
                if ($old_wallet_id != $model->wallet_id) {

                    /** @var Wallet $old_wallet */
                    $old_wallet = Wallet::find()->where(['id' => $old_wallet_id])->one();
                    $old_wallet->balance -= $old_value;
                    $old_wallet->save();

                    $wallet = $model->wallet;
                    $wallet->balance += $model->value;
                    $wallet->save();
                } elseif ($old_value != $model->value) {
                    $difference = $model->value - $old_value;

                    $wallet = $model->wallet;
                    $wallet->balance += $difference;
                    $wallet->save();
                }

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

        return $this->render('update', [
            'model' => $model,
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
            $wallet = $model->wallet;
            $wallet->balance -= $model->value;
            $wallet->save();

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
     * Finds the Income model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Income the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Income::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
