<?php

namespace frontend\controllers;

use yii\helpers\Json;
use yii\web\Controller;
use Yii;

/**
 * Site controller
 */
class TestController extends Controller
{
	public function init()
	{
		parent::init();
		$this->enableCsrfValidation = false;
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 * @throws \yii\base\InvalidParamException
	 */
	public function actionIndex()
	{
		$data = [
			[
				'slice' => 'Январь',
				'data' => [
					'low' => 4786,
					'mid' => 1319,
					'high' => 249,
				]
			],
			[
				'slice' => 'Февраль',
				'data' => [
					'low' => 7654,
					'mid' => 14319,
					'mid2' => 14319,
					'mi3d' => 4566,
					'mi4d' => 2223,
					'mi5d' => 2457,
					'high' => 1321,
				]
			],
			[
				'slice' => 'Март',
				'data' => [
					'low' => 555,
					'mid' => 3133,
					'high' => 2652,
				]
			],
			[
				'slice' => 'Апрель',
				'data' => [
					'low' => 12122,
					'mid' => 5555,
					'high' => 582,
					'super' => 900,
				]
			],
			[
				'slice' => 'Май',
				'data' => [
					'low' => 2123,
					'mid' => 233,
					'high' => 887,
					'jon' => 1143
				]
			],
			[
				'slice' => 'Июнь',
				'data' => [
					'low' => 1222,
					'mid' => 332,
					'high' => 812,
				]
			],
			[
				'slice' => 'Июль',
				'data' => [
					'low' => 3322,
					'mid' => 1319,
					'high' => 645,
				]
			],
		];

		return $this->render('index', ['Data' => Json::encode($data)]);
	}

    public function actionStatus()
    {
        /**
         * "id","order","score","weight","color","label"
        "FIS",1.1,59,0.5,"#9E0041","Fisheries"
        "MAR",1.3,24,0.5,"#C32F4B","Mariculture"
        "AO",2,98,1,"#E1514B","Artisanal Fishing Opportunities"
        "NP",3,60,1,"#F47245","Natural Products"
        "CS",4,74,1,"#FB9F59","Carbon Storage"
        "CP",5,70,1,"#FEC574","Coastal Protection"
        "TR",6,42,1,"#FAE38C","Tourism &  Recreation"
        "LIV",7.1,77,0.5,"#EAF195","Livelihoods"
        "ECO",7.3,88,0.5,"#C7E89E","Economies"
        "ICO",8.1,60,0.5,"#9CD6A4","Iconic Species"
        "LSP",8.3,65,0.5,"#6CC4A4","Lasting Special Places"
        "CW",9,71,1,"#4D9DB4","Clean Waters"
        "HAB",10.1,88,0.5,"#4776B4","Habitats"
        "SPP",10.3,83,0.5,"#5E4EA1","Species"
         */
        $data = [
            [
                'id' => 'MAIN',
                'order' => '1',
                'score' => '77',
                'weight' => '1',
                'color' => 'green',
                'label' => 'Обязательыне расходы',
            ],
            [
                'id' => 'DASHA',
                'order' => '2',
                'score' => '106',
                'weight' => '1',
                'color' => 'red',
                'label' => 'Даша',
            ],
            [
                'id' => 'PETS',
                'order' => '3',
                'score' => '63',
                'weight' => '1',
                'color' => 'yellow',
                'label' => 'Домашние животные',
            ],
            [
                'id' => 'RELAX',
                'order' => '1',
                'score' => '70',
                'weight' => '1',
                'color' => 'ping',
                'label' => 'Развлечения',
            ],
            [
                'id' => 'OTHER',
                'order' => '5',
                'score' => '140',
                'weight' => '1',
                'color' => 'blue',
                'label' => 'Другие расходы',
            ],

        ];

        return $this->render('status', ['Data' => Json::encode($data)]);
    }

    public function actionDrop()
    {
        var_dump(Yii::$app->params['currency_list']);
    }
}