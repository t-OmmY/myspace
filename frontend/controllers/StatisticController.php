<?php
/**
 * Created by PhpStorm.
 * User: dkrasnykh
 * Date: 04.11.17
 * Time: 16:58
 */

namespace frontend\controllers;


use common\models\Outgo;
use common\models\User;
use DateTime;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;

class StatisticController extends Controller
{
    public function actionIndex()
    {
        //todo here should be a list of availables reports
    }

    //todo this method should be private with getting some parameters
    public function actionStory()
    {
        $rows = (new Query())
            ->select(['YEAR(date) AS year', 'MONTH(date) AS month', 'outgo_source.name AS source', 'sum(value) AS value'])
            ->from('outgo')
            ->innerJoin('outgo_source', 'outgo.outgo_source_id = outgo_source.id')
            ->where('YEAR(date) in (2017, 2016, 2015, 2014)') //todo this is parameter!!!
            ->groupBy(['YEAR(date)', 'MONTH(date)', 'outgo_source_id'])
            ->all();

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
//            foreach ($data[$year] as $slice => $info) {
//                $data[$year][$slice]['total'] = array_sum($info['data']);
//                arsort($data[$year][$slice]['data']);
//            }
        }

        return $this->render('story', ['Data' => Json::encode($data['2017'])]);
    }
}