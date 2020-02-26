<?php

use console\models\db\Migration;

class m161007_120959_add_signup_confirm_token_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'signup_confirm_token', $this->string()->unique());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'signup_confirm_token');
    }
}
