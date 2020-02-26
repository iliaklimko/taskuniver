<?php

use console\models\db\Migration;

/**
 * Handles the creation for table `post_subject`.
 */
class m160914_075133_create_post_subject_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%post_subject}}', [
            'post_id' => $this->integer()->notNull(),
            'model_class' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%post_subject}}', ['post_id']);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%post_subject}}');
    }
}
