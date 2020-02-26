<?php

use console\models\db\Migration;

class m170110_140637_add_unique_key_to_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'code', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'code');
    }
}
