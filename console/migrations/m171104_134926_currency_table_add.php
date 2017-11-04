<?php

use yii\db\Migration;

class m171104_134926_currency_table_add extends Migration
{
    public function up()
    {
        $this->createTable('{{%currency}}', [
            'id'            => $this->primaryKey(),
            'date'          => $this->date(),
            'request'          => $this->string(255),
            'response'          => $this->string(255),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('currency');
    }
}
