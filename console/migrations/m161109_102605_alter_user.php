<?php

use console\models\db\Migration;

class m161109_102605_alter_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'account_vkontakte', $this->string());
        $this->addColumn('{{%user}}', 'account_facebook', $this->string());
        $this->addColumn('{{%user}}', 'account_instagram', $this->string());
        $this->addColumn('{{%user}}', 'account_twitter', $this->string());

        $this->addColumn('{{%user}}', 'instant_confirmation', $this->boolean()->notNull()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'account_vkontakte');
        $this->dropColumn('{{%user}}', 'account_facebook');
        $this->dropColumn('{{%user}}', 'account_instagram');
        $this->dropColumn('{{%user}}', 'account_twitter');

        $this->dropColumn('{{%user}}', 'instant_confirmation');
    }
}
