<?php

use console\models\db\Migration;
use common\models\User;

class m160921_091416_user_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%user_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'full_name' => $this->string(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(User::STATUS_ACTIVE),
            'user_group_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey("fk_user_user_group", "{{%user}}",  "user_group_id", "{{%user_group}}", "id", "SET NULL", "CASCADE");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey("fk_user_user_group", "{{%user}}");
        $this->dropTable('{{%user_group}}');
        $this->dropTable('{{%user}}');
    }
}
