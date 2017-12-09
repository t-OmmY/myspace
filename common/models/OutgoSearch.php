<?php

namespace common\models;

use kartik\daterange\DateRangeBehavior;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Outgo;

/**
 * OutgoSearch represents the model behind the search form about `common\models\Outgo`.
 */
class OutgoSearch extends Outgo
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
            [['id', 'user_id', 'outgo_source_id', 'outgo_type_id', 'wallet_id', 'created_at', 'updated_at'], 'integer'],
            [['date', 'description'], 'safe'],
            [['outgoSource', 'wallet', 'outgoType'], 'safe'],
            [['outgo_source_id', 'wallet_id', 'outgo_type_id'], 'safe'],
            [['value'], 'number'],
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
        $query = Outgo::find()->where(['outgo.user_id' => Yii::$app->user->id]);

        $query->joinWith(['wallet', 'outgoSource', 'outgoType', 'user']);

        $query->orderBy('date DESC');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],

        ]);

        $dataProvider->sort->attributes['wallet'] = [
            'asc' => ['wallet.name' => SORT_ASC],
            'desc' => ['wallet.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['outgoSource'] = [
            'asc' => ['outgoSource.name' => SORT_ASC],
            'desc' => ['outgoSource.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['outgoType'] = [
            'asc' => ['outgoType.name' => SORT_ASC],
            'desc' => ['outgoType.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'outgo.outgo_source_id' => $this->outgo_source_id,
            'outgo.outgo_type_id' => $this->outgo_type_id,
            'value' => $this->value,
            'wallet_id' => $this->wallet_id,
        ]);

        $query->andFilterWhere(['like', 'outgo.description', $this->description]);

        if (!empty($this->date)){
            $dates = explode(' - ', $this->date);
            $query->andFilterWhere(['between', 'date', $dates[0], $dates[1]]);
        }

        return $dataProvider;
    }
}
