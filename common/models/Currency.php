<?php

namespace common\models;
use linslin\yii2\curl\Curl;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string $date
 * @property string $request
 * @property string $response
 * @property integer $created_at
 * @property integer $updated_at
 */
class Currency extends \yii\db\ActiveRecord
{
	private $currency_url;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'request', 'response'], 'safe'],
            [['request', 'response'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
            'request' => Yii::t('app', 'Request'),
            'response' => Yii::t('app', 'Response'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	/**
	 * function __construct
	 */
	public function __construct()
	{
		$this->currency_url = 'https://travel-info.travel-swift.com/convertCurrency';
	}

	/**
	 * @return Curl
	 */
	private function setConnect()
	{
		$curl = new Curl();

		$curl->setOption(CURLOPT_RETURNTRANSFER, true);
		$curl->setOption(CURLOPT_SSL_VERIFYHOST, 0);
		$curl->setOption(CURLOPT_SSL_VERIFYPEER, false);

		return $curl;
	}

	/**
	 * @param array $currencies
	 * @param array $to
	 *
	 * Response example
	 *
	 * stdClass Object
	 * (
	 * [UAH:EUR] => 0.035366
	 * [EUR:UAH] => 28.3635
	 * )
	 *
	 * @return mixed|null
	 */
	public function currency($currencies = array(), $to = array())
	{
		//check currency list
		if (empty($currencies))
			$currencies = Yii::$app->params['currency_list'];

		$curr_str = '["' . $this->getStringCurrencies($currencies) . '"]';

		/** @var Currency $response */
		$response = Currency::find()->where(['request' => $curr_str, 'date' => date('Y-m-d')])->one();
		if ($response){
            $to_currency = $this->getConvertString($to);

            return $this->handleResponse($response->response, $to_currency);
        }

		//curl object init
		$curl = $this->setConnect();

		//request
        $response = $curl->setGetParams([
            'currencies' => $curr_str,
        ])
            ->get($this->currency_url);

		if($curl->errorCode)
			return null;

		//handle response
		if (!empty($response)) {
			if (!empty($to)) {

			    $currency = new self;
                $currency->date = date('Y-m-d');
                $currency->response = $response;
                $currency->request = $curr_str;
                $currency->save();

				$to_currency = $this->getConvertString($to);

				return $this->handleResponse($response, $to_currency);

			} else {
				return $response;
			}
		} else {
			return NULL;
		}
	}

	/**
	 * @param array $currencies
	 *
	 * @return array|string
	 */
	private function getStringCurrencies($currencies = array())
	{
		if (empty($currencies)) {
			return [];
		}

		return implode('","', $currencies);
	}

	/**
	 * @param array $currencies
	 *
	 * @return array|string
	 */
	private function getConvertString($currencies = array())
	{
		if (empty($currencies)) {
			return [];
		}

		return implode(':', str_replace('"', '', $currencies));
	}

	/**
	 * @param $response
	 * @param $to_currency
	 *
	 * @return mixed
	 */
	public function handleResponse($response, $to_currency)
    {
        $result = [];
        foreach (json_decode($response, true) as $key => $item) {
            if (substr($key, -3) == $to_currency) {
                $result[substr($key, 0, 3)] = $item;
            }
        }
        return $result;
    }
}