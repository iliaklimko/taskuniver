<?php

use console\models\db\Migration;
use common\models\Admin;

class m160829_152254_init_admin extends Migration
{
    public function up()
    {
        $this->createTable('{{%admin_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->createTable('{{%admin}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(Admin::STATUS_ACTIVE),
            'admin_group_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey("fk_admin_admin_group", "{{%admin}}",  "admin_group_id", "{{%admin_group}}", "id", "SET NULL", "CASCADE");
    }

    public function down()
    {
        $this->dropTable('{{%admin_group}}');
        $this->dropTable('{{%admin}}');
    }
}
