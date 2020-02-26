<?php

use console\models\db\Migration;

class m161102_112452_add_images_to_excursion extends Migration
{
    public function up()
    {
        $this->createTable('{{%excursion_image}}', [
            'id' => $this->primaryKey(),
            'filename' => $this->string()->notNull(),
            'main' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'excursion_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-excursion_image-excursion_id', '{{%excursion_image}}', 'excursion_id', '{{%excursion}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-excursion_image-excursion_id', '{{%excursion_image}}');
        $this->dropTable('{{%excursion_image}}');
    }
}
