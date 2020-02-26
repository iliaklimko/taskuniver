<?php

use console\models\db\Migration;

class m161114_052048_excursion_translation_change_unique extends Migration
{
    public function up()
    {
        $this->dropIndex('title', '{{%excursion_translation}}');
        $this->createIndex('title', '{{%excursion_translation}}', ['title', 'language_code'], true );
    }

    public function down()
    {
        $this->dropIndex('title', '{{%excursion_translation}}');
        $this->createIndex('title', '{{%excursion_translation}}', 'title', true );
    }
}
