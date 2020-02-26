<?php

use console\models\db\Migration;

class m170130_091138_create_tag_en extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tag}}', 'language_code', $this->string(16)->notNull()->defaultValue('ru'));
    }

    public function down()
    {
        $this->dropColumn('{{%tag}}', 'language_code');
    }
}
