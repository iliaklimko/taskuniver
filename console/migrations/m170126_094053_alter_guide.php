<?php

use console\models\db\Migration;

class m170126_094053_alter_guide extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'interface_language', $this->string(16)->notNull()->defaultValue('ru'));
        $this->addColumn('{{%user}}', 'full_name_en', $this->string());

    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'interface_language');
        $this->dropColumn('{{%user}}', 'full_name_en');
    }
}
