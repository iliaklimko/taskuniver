<?php

use console\models\db\Migration;

class m161021_153806_add_user_name_to_post extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'user_name', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'user_name');
    }
}
