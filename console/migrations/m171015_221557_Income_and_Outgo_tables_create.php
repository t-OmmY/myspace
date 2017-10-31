<?php

use yii\db\Migration;

class m171015_221557_Income_and_Outgo_tables_create extends Migration
{
    public function up()
    {
        $this->createTable('{{%income_source}}', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string(255),
            'description'   => $this->text(),
            'user_id'       => $this->integer(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%wallet}}', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string(255),
            'code'          => $this->string(255),
            'description'   => $this->text(),
            'balance'       => $this->float()->defaultValue(0),
            'user_id'       => $this->integer(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%exchange}}', [
            'id'            => $this->primaryKey(),
            'date'          => $this->dateTime(),
            'wallet_from'   => $this->integer(),
            'wallet_to'     => $this->integer(),
            'rate'          => $this->float(),
            'user_id'       => $this->integer(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%income}}', [
            'id'            => $this->primaryKey(),
            'date'          => $this->dateTime(),
            'user_id'       => $this->integer(),
            'income_source_id'     => $this->integer(),
            'value'         => $this->float(),
            'wallet_id'   => $this->integer(),
            'description'   => $this->text(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        // creates index for column `user_id`
        $this->createIndex(
            'idx-exchange-user_id',
            'exchange',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-exchange-user_id',
            'exchange',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `wallet_from`
        $this->createIndex(
            'idx-exchange-wallet_from',
            'exchange',
            'wallet_from'
        );

        // add foreign key for table `wallet`
        $this->addForeignKey(
            'fk-exchange-wallet_from',
            'exchange',
            'wallet_from',
            'wallet',
            'id',
            'CASCADE'
        );

        // creates index for column `wallet_to`
        $this->createIndex(
            'idx-exchange-wallet_to',
            'exchange',
            'wallet_to'
        );

        // add foreign key for table `wallet`
        $this->addForeignKey(
            'fk-exchange-wallet_to',
            'exchange',
            'wallet_to',
            'wallet',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-wallet-user_id',
            'wallet',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-wallet-user_id',
            'wallet',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-income_source-user_id',
            'income_source',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-income_source-user_id',
            'income_source',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-income-user_id',
            'income',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-income-user_id',
            'income',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `income_source_id`
        $this->createIndex(
            'idx-income-income_source_id',
            'income',
            'income_source_id'
        );

        // add foreign key for table `income`
        $this->addForeignKey(
            'fk-income-income_source_id',
            'income',
            'income_source_id',
            'income_source',
            'id',
            'CASCADE'
        );

        // creates index for column `wallet_id`
        $this->createIndex(
            'idx-income-wallet_id',
            'income',
            'wallet_id'
        );

        // add foreign key for table `wallet`
        $this->addForeignKey(
            'fk-income-wallet_id',
            'income',
            'wallet_id',
            'wallet',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%outgo_source}}', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string(255),
            'description'   => $this->text(),
            'user_id'       => $this->integer(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%outgo_type}}', [
            'id'                => $this->primaryKey(),
            'name'              => $this->string(255),
            'outgo_source_id'   => $this->integer(),
            'description'       => $this->text(),
            'user_id'       => $this->integer(),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createTable('{{%outgo}}', [
            'id'                => $this->primaryKey(),
            'date'              => $this->dateTime(),
            'user_id'           => $this->integer(),
            'outgo_source_id'   => $this->integer(),
            'outgo_type_id'    => $this->integer(),
            'value'             => $this->float(),
            'wallet_id'       => $this->integer(),
            'description'       => $this->text(),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        // creates index for column `user_id`
        $this->createIndex(
            'idx-outgo-user_id',
            'outgo',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-outgo-user_id',
            'outgo',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-outgo_type-user_id',
            'outgo_type',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-outgo_type-user_id',
            'outgo_type',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-outgo_source-user_id',
            'outgo_source',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-outgo_source-user_id',
            'outgo_source',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `outgo_source_id`
        $this->createIndex(
            'idx-outgo-outgo_source_id',
            'outgo',
            'outgo_source_id'
        );

        // add foreign key for table `outgo`
        $this->addForeignKey(
            'fk-outgo-outgo_source_id',
            'outgo',
            'outgo_source_id',
            'outgo_source',
            'id',
            'CASCADE'
        );

        // creates index for column `outgo_source_id`
        $this->createIndex(
            'idx-outgo_type-outgo_source_id',
            'outgo_type',
            'outgo_source_id'
        );

        // add foreign key for table `outgo`
        $this->addForeignKey(
            'fk-outgo_type-outgo_source_id',
            'outgo_type',
            'outgo_source_id',
            'outgo_source',
            'id',
            'CASCADE'
        );

        // creates index for column `outgo_type_id`
        $this->createIndex(
            'idx-outgo-outgo_type_id',
            'outgo',
            'outgo_type_id'
        );

        // add foreign key for table `outgo_type`
        $this->addForeignKey(
            'fk-outgo-outgo_type_id',
            'outgo',
            'outgo_type_id',
            'outgo_type',
            'id',
            'CASCADE'
        );

        // creates index for column `wallet_id`
        $this->createIndex(
            'idx-outgo-wallet_id',
            'outgo',
            'wallet_id'
        );

        // add foreign key for table `wallet`
        $this->addForeignKey(
            'fk-outgo-wallet_id',
            'outgo',
            'wallet_id',
            'wallet',
            'id',
            'CASCADE'
        );

    }

    public function down()
    {
        // drops foreign key for table `income`
        $this->dropForeignKey(
            'fk-income-wallet_id',
            'income'
        );

        // drops index for column `wallet_id`
        $this->dropIndex(
            'idx-income-wallet_id',
            'income'
        );

        // drops foreign kincomeey for table `income_source`
        $this->dropForeignKey(
            'fk-income-income_source_id',
            'income'
        );

        // drops index for column `income_source_id`
        $this->dropIndex(
            'idx-income-income_source_id',
            'income'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-income-user_id',
            'income'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-income-user_id',
            'income'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-wallet-user_id',
            'wallet'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-wallet-user_id',
            'wallet'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-income_source-user_id',
            'income_source'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-income_source-user_id',
            'income_source'
        );

        // drops foreign key for table `outgo`
        $this->dropForeignKey(
            'fk-outgo-outgo_type_id',
            'outgo'
        );

        // drops index for column `wallet_id`
        $this->dropIndex(
            'idx-outgo-outgo_type_id',
            'outgo'
        );

        // drops foreign key for table `outgo_source`
        $this->dropForeignKey(
            'fk-outgo-outgo_source_id',
            'outgo'
        );

        // drops index for column `outgo_source_id`
        $this->dropIndex(
            'idx-outgo-outgo_source_id',
            'outgo'
        );

        // drops foreign key for table `outgo_source`
        $this->dropForeignKey(
            'fk-outgo_type-outgo_source_id',
            'outgo_type'
        );

        // drops index for column `outgo_source_id`
        $this->dropIndex(
            'idx-outgo_type-outgo_source_id',
            'outgo_type'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-outgo-user_id',
            'outgo'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-outgo-user_id',
            'outgo'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-outgo_type-user_id',
            'outgo_type'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-outgo_type-user_id',
            'outgo_type'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-outgo_source-user_id',
            'outgo_source'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-outgo_source-user_id',
            'outgo_source'
        );

        // drops foreign key for table `outgo`
        $this->dropForeignKey(
            'fk-outgo-wallet_id',
            'outgo'
        );

        // drops index for column `wallet_id`
        $this->dropIndex(
            'idx-outgo-wallet_id',
            'outgo'
        );

        $this->dropForeignKey(
            'fk-exchange-user_id',
            'exchange'
        );

        $this->dropIndex(
            'idx-exchange-user_id',
            'exchange'
        );

        $this->dropForeignKey(
            'fk-exchange-wallet_from',
            'exchange'
        );

        $this->dropIndex(
            'idx-exchange-wallet_from',
            'exchange'
        );

        $this->dropForeignKey(
            'fk-exchange-wallet_to',
            'exchange'
        );

        $this->dropIndex(
            'idx-exchange-wallet_to',
            'exchange'
        );

        $this->dropTable('outgo');
        $this->dropTable('outgo_source');
        $this->dropTable('outgo_type');
        $this->dropTable('income');
        $this->dropTable('income_source');
        $this->dropTable('wallet');
        $this->dropTable('exchange');
    }
}
