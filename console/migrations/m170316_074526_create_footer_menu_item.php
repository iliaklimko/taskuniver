<?php

use console\models\db\Migration;

class m170316_074526_create_footer_menu_item extends Migration
{
    public function up()
    {
        $this->createTable('{{%footer_menu_item}}', [
            'id'       => $this->primaryKey(),
            'column'   => $this->string()->notNull(),
            'position' => $this->integer()->defaultValue(0),
        ]);

        $this->createTable('{{%footer_menu_item_translation}}', [
            'footer_menu_item_id' => $this->integer()->notNull(),
            'language_code'       => $this->string(16)->notNull(),
            'title'               => $this->string()->notNull(),
            'url'                 => $this->string()->notNull(),
        ]);
        $this->addPrimaryKey(null, '{{%footer_menu_item_translation}}', ['footer_menu_item_id', 'language_code']);
    }

    public function down()
    {
        $this->dropTable('{{%footer_menu_item_translation}}');
        $this->dropTable('{{%footer_menu_item}}');
    }
}
