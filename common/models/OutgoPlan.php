<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "outgo_plan".
 *
 * @property integer $id
 * @property string $date_from
 * @property string $date_to
 * @property integer $outgo_source_id
 * @property integer $outgo_type_id
 * @property double $value
 * @property integer $wallet_id
 * @property string $description
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property OutgoSource $outgoSource
 * @property OutgoType $outgoType
 * @property User $user
 * @property Wallet $wallet
 */
class OutgoPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outgo_plan';
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
            [['outgo_source_id', 'outgo_type_id', 'wallet_id', 'user_id'], 'integer'],
            [['value'], 'number'],
            [['description'], 'string'],
            [['date_from', 'date_to', 'value', 'wallet_id', 'user_id', 'outgo_source_id', 'outgo_type_id'], 'required', 'message' => ''],
            [['outgo_source_id'], 'exist', 'skipOnError' => true, 'targetClass' => OutgoSource::className(), 'targetAttribute' => ['outgo_source_id' => 'id']],
            [['outgo_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OutgoType::className(), 'targetAttribute' => ['outgo_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['wallet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['wallet_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => Yii::t('app', '#'),
			'date_from' => Yii::t('app', 'Дата С'),
			'date_to' => Yii::t('app', 'Дата По'),
            'outgo_source_id' => Yii::t('app', 'Категория'),
            'outgo_type_id' => Yii::t('app', 'Подкатегория'),
			'value' => Yii::t('app', 'Значение'),
			'wallet_id' => Yii::t('app', 'Кошелек'),
			'description' => Yii::t('app', 'Описание'),
			'user_id' => Yii::t('app', 'Пользователь'),
			'created_at' => Yii::t('app', 'Создано'),
			'updated_at' => Yii::t('app', 'Изменено'),
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWallet()
    {
        return $this->hasOne(Wallet::className(), ['id' => 'wallet_id']);
    }
}
