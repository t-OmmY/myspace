<?php

namespace common\models;

use kartik\daterange\DateRangeBehavior;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\IncomePlan;

/**
 * IncomePlanSearch represents the model behind the search form about `common\models\IncomePlan`.
 */
class IncomePlanSearch extends IncomePlan
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
            [['id', 'income_source_id', 'wallet_id', 'user_id'], 'integer'],
            [['date_from', 'date_to', 'description'], 'safe'],
			[['incomeSource', 'wallet'], 'safe'],
			[['income_source_id', 'wallet_id'], 'safe'],
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
        $query = IncomePlan::find()->where(['income_plan.user_id' => Yii::$app->user->id]);

		$query->joinWith(['wallet', 'incomeSource', 'user']);

		$query->orderBy('date_from DESC');
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

		$dataProvider->sort->attributes['incomeSource'] = [
			'asc' => ['incomeSource.name' => SORT_ASC],
			'desc' => ['incomeSource.name' => SORT_DESC],
		];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
		$query->andFilterWhere([
			'value' => $this->value,
			'wallet_id' => $this->wallet_id,
			'income_source_id' => $this->income_source_id,
		]);

		$query->andFilterWhere(['like', 'income.description', $this->description]);


		if (!empty($this->date_from)){
			$dates = explode(' - ', $this->date_from);
			$query->andFilterWhere(['between', 'date_from', $dates[0], $dates[1]]);
		}

		if (!empty($this->date_to)){
			$dates = explode(' - ', $this->date_to);
			$query->andFilterWhere(['between', 'date_to', $dates[0], $dates[1]]);
		}

        return $dataProvider;
    }
}
