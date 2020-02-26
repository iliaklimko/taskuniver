<?php

use console\models\db\Migration;

class m170316_091955_main_benefits extends Migration
{
    public function up()
    {
        $this->createTable('{{%main_benefit}}', [
            'id'       => $this->primaryKey(),
            'block'    => $this->string()->notNull(),
            'icon'     => $this->string(),
        ]);

        $this->createTable('{{%main_benefit_translation}}', [
            'main_benefit_id' => $this->integer()->notNull(),
            'language_code'   => $this->string(16)->notNull(),
            'title'           => $this->string()->notNull(),
            'body'            => $this->text()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%main_benefit_translation}}', ['main_benefit_id', 'language_code']);
    }

    public function down()
    {
        $this->dropTable('{{%main_benefit_translation}}');
        $this->dropTable('{{%main_benefit}}');
    }
}
