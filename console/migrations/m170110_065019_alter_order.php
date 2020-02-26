<?php

use console\models\db\Migration;

class m170110_065019_alter_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'name', $this->string());
        $this->addColumn('{{%order}}', 'phone', $this->string());
        $this->addColumn('{{%order}}', 'email', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'name');
        $this->dropColumn('{{%order}}', 'phone');
        $this->dropColumn('{{%order}}', 'email');
    }
}
