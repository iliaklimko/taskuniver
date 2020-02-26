<?php

use console\models\db\Migration;

class m170112_133338_create_email_template_tbl extends Migration
{
    public function up()
    {
        $this->createTable('{{%email_template}}', [
            'id'         => $this->primaryKey(),
            'alias'      => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createTable('{{%email_template_translation}}', [
            'email_template_id' => $this->integer()->notNull(),
            'language_code'     => $this->string(16)->notNull(),
            'subject'           => $this->string(),
            'body'              => $this->text(),
        ]);
        $this->addPrimaryKey(null, '{{%email_template_translation}}', ['email_template_id', 'language_code']);
    }

    public function down()
    {
        $this->dropTable('{{%email_template}}');
        $this->dropTable('{{%email_template_translation}}');
    }
}
