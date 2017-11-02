<?php

use yii\db\Migration;

class m171101_210103_add_main_wallet_field_to_users extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'main_wallet_id', $this->integer());

        // creates index for column `wallet_id`
        $this->createIndex(
            'idx-user-main_wallet_id',
            'user',
            'main_wallet_id'
        );

        // add foreign key for table `wallet`
        $this->addForeignKey(
            'fk-user-main_wallet_id',
            'user',
            'main_wallet_id',
            'wallet',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'main_wallet_id');

        $this->dropForeignKey(
            'fk-user-main_wallet_id',
            'user'
        );

        $this->dropIndex(
            'idx-user-main_wallet_id',
            'user'
        );
    }
}
