<?php

use console\models\db\Migration;

class m170118_224849_premium_block_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%premium_block}}', [
            'id'         => $this->primaryKey(),
            'alias'      => $this->string(),
            'image'      => $this->string(),
            'city_id'    => $this->integer(),
        ]);
        $this->createTable('{{%premium_block_translation}}', [
            'premium_block_id' => $this->integer()->notNull(),
            'language_code'    => $this->string(16)->notNull(),
            'link_url'         => $this->string(),
            'link_label'       => $this->string(),
            'title'            => $this->string(),
            'subtitle'         => $this->string(),
        ]);
        $this->addPrimaryKey(null, '{{%premium_block_translation}}', ['premium_block_id', 'language_code']);

        $this->addForeignKey('fk-premium_block-city_id', '{{%premium_block}}', 'city_id', '{{%city}}', 'id', 'SET NULL');
    }

    public function down()
    {
        $this->dropForeignKey('fk-premium_block-city_id', '{{%premium_block}}');
        $this->dropTable('{{%premium_block}}');
        $this->dropTable('{{%premium_block_translation}}');
    }
}
