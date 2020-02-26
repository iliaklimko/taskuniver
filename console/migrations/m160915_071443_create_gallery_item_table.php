<?php

use console\models\db\Migration;

/**
 * Handles the creation for table `gallery_item`.
 */
class m160915_071443_create_gallery_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%gallery_item}}', [
            'id' => $this->primaryKey(),
            'filename' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-gallery_item-post_id', '{{%gallery_item}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%gallery_item}}');
    }
}
