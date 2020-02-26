<?php

use console\models\db\Migration;

class m161118_170649_add_updated_by_owner extends Migration
{
    public function up()
    {
        $this->addColumn('{{%excursion}}', 'updated_by_owner', $this->boolean()->notNull()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%excursion}}', 'updated_by_owner');
    }
}
