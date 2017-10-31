<?php

use yii\db\Migration;

class m171029_200142_Add_value_to_exchange_table extends Migration
{

    public function up()
    {
        $this->addColumn('{{%exchange}}', 'value', $this->float());
        $this->addColumn('{{%exchange}}', 'description', $this->text());

    }

    public function down()
    {
        $this->dropColumn('{{%exchange}}', 'value');
        $this->dropColumn('{{%exchange}}', 'description');

    }
}
