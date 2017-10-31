<?php

namespace common\models;

use kartik\daterange\DateRangeBehavior;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Exchange;

/**
 * ExchangeSearch represents the model behind the search form about `common\models\Exchange`.
 */
class ExchangeSearch extends Exchange
{
    public $createTimeRange;
    public $createTimeStart;
    public $createTimeEnd;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'createTimeRange',
                'dateStartAttribute' => 'createTimeStart',
                'dateEndAttribute' => 'createTimeEnd',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wallet_from', 'wallet_to', 'user_id'], 'integer'],
            [['date'], 'safe'],
            [['walletFrom', 'walletTo', 'description'], 'safe'],
            [['rate', 'value'], 'number'],
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Exchange::find()->where(['exchange.user_id' => Yii::$app->user->id]);

        $query->joinWith(['user', 'walletFrom']);

        $query->orderBy('date DESC');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $dataProvider->sort->attributes['walletFrom'] = [
            'asc' => ['walletFrom.name' => SORT_ASC],
            'desc' => ['walletFrom.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'wallet_from' => $this->wallet_from,
            'wallet_to' => $this->wallet_to,
            'rate' => $this->rate,
            'value' => $this->value,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'income.description', $this->description]);

        if (!empty($this->date)){
            $dates = explode(' - ', $this->date);
            $query->andFilterWhere(['between', 'date', $dates[0], $dates[1]]);
        }


        return $dataProvider;
    }
}
