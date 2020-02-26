<?php

use console\models\db\Migration;

class m161019_022556_create_social_links extends Migration
{
    public function up()
    {
        $this->createTable('{{%social_link}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
            'alias' => $this->string()->unique()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%social_link}}');
    }
}
