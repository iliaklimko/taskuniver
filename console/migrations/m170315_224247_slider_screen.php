<?php

use console\models\db\Migration;

class m170315_224247_slider_screen extends Migration
{
    public function up()
    {
        $this->createTable('{{%slider_screen}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string()->notNull(),
            'image' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%slider_screen}}');
    }
}
