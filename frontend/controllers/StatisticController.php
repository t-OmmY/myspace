<?php
/**
 * Created by PhpStorm.
 * User: dkrasnykh
 * Date: 04.11.17
 * Time: 16:58
 */

namespace frontend\controllers;


use common\models\Exchange;
use common\models\Outgo;
use common\models\User;
use common\models\Wallet;
use DateTime;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class StatisticController extends Controller
{
    public function actionIndex()
    {
        //todo here should be a list of availables reports
    }

    public function actionStory()
    {
        $years = ArrayHelper::map(Outgo::find()->select('YEAR(date)')->where(['user_id' => Yii::$app->user->id])->groupBy('YEAR(date)')->orderBy('YEAR(date) DESC')->asArray()->all(), 'YEAR(date)', 'YEAR(date)');

        $data = $this->getYearData(max($years));

        $model = new Outgo();

        return $this->render('story', [
            'model' => $model,
            'years' => $years,
            'Data' => Json::encode($data[max($years)])
        ]);
    }

    /**
     * @param $year
     * @return array
     */
    private function getYearData($year)
    {
        $rows = (new Query())
            ->select(['YEAR(date) AS year', 'MONTH(date) AS month', 'outgo_source.name AS source', 'sum(value) AS value', 'outgo.wallet_id'])
            ->from('outgo')
            ->innerJoin('outgo_source', 'outgo.outgo_source_id = outgo_source.id')
            ->where('YEAR(date) in (' . $year . ')')
            ->where(['outgo.user_id' => Yii::$app->user->id])
            ->groupBy(['YEAR(date)', 'MONTH(date)', 'outgo_source_id', 'outgo.wallet_id'])
            ->all();

        $rows = $this->recalcToMainWallet($rows);

        $data = [];
        foreach ($rows as $row){
            if (!isset($data[$row['year']])){
                $data[$row['year']] = [];
            }
            if (!isset($data[$row['year']][$row['month']])){
                $data[$row['year']][$row['month']] = [];
            }
            $data[$row['year']][$row['month']]['slice'] = DateTime::createFromFormat('!m', $row['month'])->format('F');
            $data[$row['year']][$row['month']]['data'][$row['source']] = round($row['value'], 2);
        }

        foreach ($data as $year => $datum) {
            $data[$year] = array_values($datum);
        }

        return $data;
    }

    public function actionYear($year)
    {
        $data = $this->getYearData($year);
        echo json_encode($data);
    }

    public function recalcToMainWallet($data)
    {
        $model = new Wallet();
        $model->user_id = Yii::$app->getUser()->id;

        $user = $model->user;
        $main_wallet_id = $user->main_wallet_id;

        $main_wallet = $user->mainWallet;
        $user_wallets = $user->wallets;
        $rates = Exchange::getRates($user_wallets, $main_wallet);

        foreach ($data as $key => $datum) {
            if ($datum['wallet_id'] != $main_wallet_id){
                $wallet = Wallet::findOne(['id' => $datum['wallet_id']]);
                $wallet_code = $wallet->code;
                $data[$key]['value'] = $data[$key]['value'] * $rates[$wallet_code];
                $data[$key]['wallet_id'] = $main_wallet_id;
            }
        }

        return $data;
    }
}