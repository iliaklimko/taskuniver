<?php

use console\models\db\Migration;

/**
 * Handles the creation for table `sight`.
 */
class m160906_073743_create_sight_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%sight}}', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer()
        ]);
        $this->addForeignKey('fk_sight_city', '{{%sight}}', 'city_id', '{{%city}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%sight_translation}}', [
            'sight_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%sight_translation}}', ['sight_id', 'language_code']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%sight}}');
        $this->dropTable('{{%sight_translation}}');
    }
}
