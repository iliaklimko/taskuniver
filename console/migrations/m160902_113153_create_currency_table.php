<?php

use console\models\db\Migration;

/**
 * Handles the creation for table `currency`.
 */
class m160902_113153_create_currency_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
        ]);

        $this->createTable('{{%currency_translation}}', [
            'currency_id' => $this->integer()->notNull(),
            'language_code' => $this->string(16)->notNull(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->addPrimaryKey(null, '{{%currency_translation}}', ['currency_id', 'language_code']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%currency}}');
        $this->dropTable('{{%currency_translation}}');
    }
}
