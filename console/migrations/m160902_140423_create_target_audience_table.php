<?php

use console\models\db\Migration;

/**
 * Handles the creation for table `target_audience`.
 */
class m160902_140423_create_target_audience_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%target_audience}}', [
            'id' => $this->primaryKey(),
        ]);

        $this->createTable('{{%target_audience_translation}}', [
            'target_audience_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%target_audience_translation}}', ['target_audience_id', 'language_code']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%target_audience}}');
        $this->dropTable('{{%target_audience_translation}}');
    }
}
