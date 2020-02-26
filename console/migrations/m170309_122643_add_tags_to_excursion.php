<?php

use console\models\db\Migration;

class m170309_122643_add_tags_to_excursion extends Migration
{
    public function up()
    {
        /** create excursion__tag */
        $this->createTable('{{%excursion__tag}}', [
            'excursion_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion__tag}}', ['excursion_id', 'tag_id']);
    }

    public function down()
    {
        /** drop excursion__tag */
        $this->dropTable('{{%excursion__tag}}');
        /** ************** */
    }
}
