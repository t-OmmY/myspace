<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "income_source".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Income[] $incomes
 */
class IncomeSource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'income_source';
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
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '#'),
            'name' => Yii::t('app', 'Источник'),
            'description' => Yii::t('app', 'Описание'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            'label' => Yii::t('app', 'Источник'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIncomes()
    {
        return $this->hasMany(Income::className(), ['income_source_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
