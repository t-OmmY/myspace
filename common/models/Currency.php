<?php

namespace common\models;
use linslin\yii2\curl\Curl;
use Yii;


/**
 * Created by PhpStorm.
 * User: technokid
 * Date: 13.03.17
 * Time: 10:59
 */
class Currency
{
	private $currency_url;

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

		//curl object init
		$curl = $this->setConnect();

		//request
        $response = $curl->setGetParams([
            'currencies' => '["' . $this->getStringCurrencies($currencies) . '"]',
        ])
            ->get($this->currency_url);

		if($curl->errorCode)
			return null;

		//handle response
		if (!empty($response)) {
			if (!empty($to)) {
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