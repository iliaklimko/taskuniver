<?php

use console\models\db\Migration;

/**
 * Handles the dropping for table `username_column_in_user`.
 */
class m161007_001023_drop_username_column_in_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%user}}', 'username');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('{{%user}}', 'username', $this->string()->notNull()->unique());
    }
}
