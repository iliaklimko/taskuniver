<?php

use console\models\db\Migration;

class m170315_215510_create_analytics extends Migration
{
    public function up()
    {
        $this->createTable('{{%analytics}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string()->notNull(),
            'body' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%analytics}}');
    }
}
