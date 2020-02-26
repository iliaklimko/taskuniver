<?php

use console\models\db\Migration;

class m170221_141237_drop_unique_in_city_and_sight_translations extends Migration
{
    public function up()
    {
        $this->dropIndex('name', '{{%city_translation}}');
        $this->dropIndex('name', '{{%sight_translation}}');
    }

    public function down()
    {
        $this->createIndex('name', '{{%sight_translation}}', 'name', true);
    }
}
