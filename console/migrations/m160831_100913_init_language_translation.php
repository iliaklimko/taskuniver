<?php

use console\models\db\Migration;

class m160831_100913_init_language_translation extends Migration
{
    public function up()
    {
        $this->createTable('{{%language}}', [
            'id' => $this->primaryKey(),
        ]);

        $this->createTable('{{%language_translation}}', [
            'language_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%language_translation}}', ['language_id', 'language_code']);
    }

    public function down()
    {
        $this->dropTable('{{%language}}');
        $this->dropTable('{{%language_translation}}');
    }
}
