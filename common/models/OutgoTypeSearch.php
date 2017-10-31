<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OutgoType;

/**
 * OutgoTypeSearch represents the model behind the search form about `common\models\OutgoType`.
 */
class OutgoTypeSearch extends OutgoType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'outgo_source_id'], 'integer'],
            [['name', 'description'], 'safe'],
            [['outgoSource'], 'safe'],
            [['outgo_source_id'], 'safe'],
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
        $query = OutgoType::find()->where(['outgo_type.user_id' => Yii::$app->user->id]);

        $query->joinWith(['outgoSource', 'user']);

        $query->orderBy('name');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],

        ]);

        $dataProvider->sort->attributes['outgoSource'] = [
            'asc' => ['outgoSource.name' => SORT_ASC],
            'desc' => ['outgoSource.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'outgo_source_id' => $this->outgo_source_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
