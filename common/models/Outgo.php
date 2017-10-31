<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "outgo".
 *
 * @property integer $id
 * @property string $date
 * @property integer $user_id
 * @property integer $outgo_source_id
 * @property integer $outgo_type_id
 * @property double $value
 * @property integer $wallet_id
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Wallet $wallet
 * @property OutgoSource $outgoSource
 * @property OutgoType $outgoType
 * @property User $user
 */
class Outgo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outgo';
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
            [['date', 'outgo_source_id', 'outgo_type_id', 'wallet_id', 'value'], 'required', 'message' => ''],
            [['user_id', 'outgo_source_id', 'outgo_type_id', 'wallet_id'], 'integer', 'message' => ''],
            [['value'], 'number', 'message' => ''],
            [['description'], 'string'],
            [['wallet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['wallet_id' => 'id']],
            [['outgo_source_id'], 'exist', 'skipOnError' => true, 'targetClass' => OutgoSource::className(), 'targetAttribute' => ['outgo_source_id' => 'id']],
            [['outgo_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OutgoType::className(), 'targetAttribute' => ['outgo_type_id' => 'id']],
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
            'date' => Yii::t('app', 'Дата платежа'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'outgo_source_id' => Yii::t('app', 'Категория'),
            'outgo_type_id' => Yii::t('app', 'Подкатегория'),
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
    public function getOutgoSource()
    {
        return $this->hasOne(OutgoSource::className(), ['id' => 'outgo_source_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutgoType()
    {
        return $this->hasOne(OutgoType::className(), ['id' => 'outgo_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
