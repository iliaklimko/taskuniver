<?php

use console\models\db\Migration;

class m161220_110550_static_page_init extends Migration
{
    public function up()
    {
        /** create static_page */
        $this->createTable('{{%static_page}}', [
            'id'         => $this->primaryKey(),
            'url_alias'  => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createTable('{{%static_page_translation}}', [
            'static_page_id'   => $this->integer()->notNull(),
            'language_code'    => $this->string(16)->notNull(),
            'title'            => $this->string()->notNull(),
            'body'             => $this->text()->notNull(),
            'h1'               => $this->string(),
            'meta_keywords'    => $this->string(),
            'meta_description' => $this->string(),
        ]);
        $this->addPrimaryKey(null, '{{%static_page_translation}}', ['static_page_id', 'language_code']);
        /** *********** */
    }

    public function down()
    {
        /** drop static_page */
        $this->dropTable('{{%static_page}}');
        $this->dropTable('{{%static_page_translation}}');
        /** ****************** */
    }
}
