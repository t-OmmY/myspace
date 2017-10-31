<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "income".
 *
 * @property integer $id
 * @property string $date
 * @property integer $user_id
 * @property integer $income_source_id
 * @property double $value
 * @property integer $wallet_id
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Wallet $wallet
 * @property IncomeSource $incomeSource
 * @property User $user
 */
class Income extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'income';
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
            [['date', 'income_source_id', 'wallet_id', 'value'], 'required', 'message' => ''],
            [['user_id', 'income_source_id', 'wallet_id'], 'integer'],
            [['value'], 'number'],
            [['description'], 'string'],
            [['wallet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['wallet_id' => 'id']],
            [['income_source_id'], 'exist', 'skipOnError' => true, 'targetClass' => IncomeSource::className(), 'targetAttribute' => ['income_source_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '#'),
            'date' => Yii::t('app', 'Дата поступления'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'income_source_id' => Yii::t('app', 'Источник поступления'),
            'value' => Yii::t('app', 'Значение'),
            'wallet_id' => Yii::t('app', 'Кошелек'),
            'description' => Yii::t('app', 'Описание'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWallet()
    {
        return $this->hasOne(Wallet::className(), ['id' => 'wallet_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIncomeSource()
    {
        return $this->hasOne(IncomeSource::className(), ['id' => 'income_source_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
