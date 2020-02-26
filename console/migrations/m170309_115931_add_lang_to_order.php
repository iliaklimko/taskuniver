<?php

use console\models\db\Migration;

class m170309_115931_add_lang_to_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'language_code', $this->string(16)->defaultValue('ru'));
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'language_code');
    }
}
