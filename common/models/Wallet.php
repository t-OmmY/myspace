<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wallet".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $description
 * @property float $balance
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Exchange[] $exchanges
 * @property Exchange[] $exchanges0
 * @property Income[] $incomes
 * @property Outgo[] $outgos
 * @property User $user
 * @property User[] $users
 */
class Wallet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wallet';
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
            [['name', 'code'], 'required', 'message' => ''],
            [['name', 'code', 'description'], 'string'],
            [['balance'], 'safe'],
            [['balance'], 'default', 'value'=> 0],
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
            'name' => Yii::t('app', 'Название'),
            'code' => Yii::t('app', 'Код'),
            'balance' => Yii::t('app', 'Баланс'),
            'description' => Yii::t('app', 'Описание'),
            'created_at' => Yii::t('app', 'Создано'),
            'updated_at' => Yii::t('app', 'Изменено'),
            'label' => Yii::t('app', 'Кошелек'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIncomes()
    {
        return $this->hasMany(Income::className(), ['wallet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutgos()
    {
        return $this->hasMany(Outgo::className(), ['wallet_id' => 'id']);
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
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['main_wallet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExchanges()
    {
        return $this->hasMany(Exchange::className(), ['wallet_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExchanges0()
    {
        return $this->hasMany(Exchange::className(), ['wallet_to' => 'id']);
    }

    /**
     * @param bool $rates
     * @return array
     * @internal param $user_wallets
     * @internal param $user
     */
    public static function getTotalBalance($rates = false)
    {
        $model = new self();
        $model->user_id = Yii::$app->getUser()->id;
        $user = $model->user;
        $user_wallets = $user->wallets;
        $main_wallet = $user->mainWallet;

        if (!$rates) {
            $rates = Exchange::getRates($user_wallets, $main_wallet);
        }
        $total_balance = 0;

        /** @var Wallet $wallet */
        foreach ($user_wallets as $wallet) {
            $total_balance += $wallet->balance * (isset($rates[$wallet->code]) ? $rates[$wallet->code] : 1);
        }

        return [
            'value' => round($total_balance, 2),
            'currency' => $main_wallet->code,
        ];
    }
}
