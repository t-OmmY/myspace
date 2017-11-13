<?php

use yii\db\Migration;

/**
 * Class m171109_132834_planning_tables_create
 */
class m171109_132834_planning_tables_create extends Migration
{
	public function up()
	{
		$this->createTable('{{%income_plan}}', [
			'id'            => $this->primaryKey(),
			'date_from'          => $this->date(),
			'date_to'          => $this->date(),
			'income_source_id'     => $this->integer(),
			'value'         => $this->float(),
			'wallet_id'   => $this->integer(),
			'description'   => $this->text(),
			'user_id'       => $this->integer(),
			'created_at'    => $this->integer()->notNull(),
			'updated_at'    => $this->integer()->notNull(),
		], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

		$this->createTable('{{%outgo_plan}}', [
			'id'            => $this->primaryKey(),
			'date_from'          => $this->date(),
			'date_to'          => $this->date(),
			'outgo_source_id'   => $this->integer(),
			'outgo_type_id'    => $this->integer(),
			'value'         => $this->float(),
			'wallet_id'   => $this->integer(),
			'description'   => $this->text(),
			'user_id'       => $this->integer(),
			'created_at'    => $this->integer()->notNull(),
			'updated_at'    => $this->integer()->notNull(),
		], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');


		// creates index for column `user_id`
		$this->createIndex(
			'idx-income_plan-user_id',
			'income_plan',
			'user_id'
		);

		// add foreign key for table `user`
		$this->addForeignKey(
			'fk-income_plan-user_id',
			'income_plan',
			'user_id',
			'user',
			'id',
			'CASCADE'
		);

		// creates index for column `income_source_id`
		$this->createIndex(
			'idx-income_plan-income_source_id',
			'income_plan',
			'income_source_id'
		);

		// add foreign key for table `income`
		$this->addForeignKey(
			'fk-income_plan-income_source_id',
			'income_plan',
			'income_source_id',
			'income_source',
			'id',
			'CASCADE'
		);

		// creates index for column `wallet_id`
		$this->createIndex(
			'idx-income_plan-wallet_id',
			'income_plan',
			'wallet_id'
		);

		// add foreign key for table `wallet`
		$this->addForeignKey(
			'fk-income_plan-wallet_id',
			'income_plan',
			'wallet_id',
			'wallet',
			'id',
			'CASCADE'
		);

		// creates index for column `user_id`
		$this->createIndex(
			'idx-outgo_plan-user_id',
			'outgo_plan',
			'user_id'
		);

		// add foreign key for table `user`
		$this->addForeignKey(
			'fk-outgo_plan-user_id',
			'outgo_plan',
			'user_id',
			'user',
			'id',
			'CASCADE'
		);

		// creates index for column `outgo_source_id`
		$this->createIndex(
			'idx-outgo_plan-outgo_source_id',
			'outgo_plan',
			'outgo_source_id'
		);

		// add foreign key for table `outgo`
		$this->addForeignKey(
			'fk-outgo_plan-outgo_source_id',
			'outgo_plan',
			'outgo_source_id',
			'outgo_source',
			'id',
			'CASCADE'
		);

		// creates index for column `outgo_type_id`
		$this->createIndex(
			'idx-outgo_plan-outgo_type_id',
			'outgo_plan',
			'outgo_type_id'
		);

		// add foreign key for table `outgo_type`
		$this->addForeignKey(
			'fk-outgo_plan-outgo_type_id',
			'outgo_plan',
			'outgo_type_id',
			'outgo_type',
			'id',
			'CASCADE'
		);

		// creates index for column `wallet_id`
		$this->createIndex(
			'idx-outgo_plan-wallet_id',
			'outgo_plan',
			'wallet_id'
		);

		// add foreign key for table `wallet`
		$this->addForeignKey(
			'fk-outgo_plan-wallet_id',
			'outgo_plan',
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
			'fk-income_plan-wallet_id',
			'income_plan'
		);

		// drops index for column `wallet_id`
		$this->dropIndex(
			'idx-income_plan-wallet_id',
			'income_plan'
		);

		// drops foreign kincomeey for table `income_source`
		$this->dropForeignKey(
			'fk-income_plan-income_source_id',
			'income_plan'
		);

		// drops index for column `income_source_id`
		$this->dropIndex(
			'idx-income_plan-income_source_id',
			'income_plan'
		);

		// drops foreign key for table `user`
		$this->dropForeignKey(
			'fk-income_plan-user_id',
			'income_plan'
		);

		// drops index for column `user_id`
		$this->dropIndex(
			'idx-income_plan-user_id',
			'income_plan'
		);

		// drops foreign key for table `outgo`
		$this->dropForeignKey(
			'fk-outgo_plan-outgo_type_id',
			'outgo_plan'
		);

		// drops index for column `wallet_id`
		$this->dropIndex(
			'idx-outgo_plan-outgo_type_id',
			'outgo_plan'
		);

		// drops foreign key for table `outgo_source`
		$this->dropForeignKey(
			'fk-outgo_plan-outgo_source_id',
			'outgo_plan'
		);

		// drops index for column `outgo_source_id`
		$this->dropIndex(
			'idx-outgo_plan-outgo_source_id',
			'outgo_plan'
		);

		// drops foreign key for table `user`
		$this->dropForeignKey(
			'fk-outgo_plan-user_id',
			'outgo_plan'
		);

		// drops index for column `user_id`
		$this->dropIndex(
			'idx-outgo_plan-user_id',
			'outgo_plan'
		);

		// drops foreign key for table `outgo`
		$this->dropForeignKey(
			'fk-outgo_plan-wallet_id',
			'outgo_plan'
		);

		// drops index for column `wallet_id`
		$this->dropIndex(
			'idx-outgo_plan-wallet_id',
			'outgo_plan'
		);

		$this->dropTable('outgo_plan');
		$this->dropTable('income_plan');
	}
}
