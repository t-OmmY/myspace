<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "exchange".
 *
 * @property integer $id
 * @property string $date
 * @property integer $wallet_from
 * @property integer $wallet_to
 * @property double $rate
 * @property float $value
 * @property string $description
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Wallet $walletFrom
 * @property Wallet $walletTo
 */
class Exchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exchange';
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
            [['date', 'wallet_from', 'wallet_to', 'rate', 'value'], 'required', 'message' => ''],
            [['wallet_from', 'wallet_to', 'user_id'], 'integer'],
            [['rate', 'value'], 'number'],
            [['description'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['wallet_from'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['wallet_from' => 'id']],
            [['wallet_to'], 'exist', 'skipOnError' => true, 'targetClass' => Wallet::className(), 'targetAttribute' => ['wallet_to' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '#'),
            'date' => Yii::t('app', 'Дата'),
            'wallet_from' => Yii::t('app', 'С какого кошелька'),
            'wallet_to' => Yii::t('app', 'На какой кошелек'),
            'rate' => Yii::t('app', 'Курс'),
            'value' => Yii::t('app', 'Сколько'),
            'description' => Yii::t('app', 'Описание'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            'label' => Yii::t('app', 'Обмен валют'),
        ];
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
    public function getWalletFrom()
    {
        return $this->hasOne(Wallet::className(), ['id' => 'wallet_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletTo()
    {
        return $this->hasOne(Wallet::className(), ['id' => 'wallet_to']);
    }
}
