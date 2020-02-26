<?php

use console\models\db\Migration;

class m161007_005515_add_alias_column_to_user_group extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_group}}', 'alias', $this->string()->unique()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%user_group}}', 'alias');
    }
}
