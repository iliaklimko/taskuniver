<?php

use console\models\db\Migration;

class m161108_161636_add_similar_excursions extends Migration
{
    public function up()
    {
        $this->createTable('{{%excursion__similar}}', [
            'excursion_id' => $this->integer()->notNull(),
            'similar_excursion_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%excursion__similar}}', ['excursion_id', 'similar_excursion_id']);
        $this->addForeignKey('fk-excursion__similar-excursion_id', '{{%excursion__similar}}', 'excursion_id', '{{%excursion}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-excursion__similar-similar_excursion_id', '{{%excursion__similar}}', 'similar_excursion_id', '{{%excursion}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-excursion__similar-excursion_id', '{{%excursion__similar}}');
        $this->dropForeignKey('fk-excursion__similar-similar_excursion_id', '{{%excursion__similar}}');
        $this->dropTable('{{%excursion__similar}}');
    }
}
