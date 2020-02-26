<?php

use console\models\db\Migration;

class m161118_084911_add_start_time_to_excursion extends Migration
{
    public function up()
    {
        $this->addColumn('{{%excursion}}', 'start_time', $this->string()->notNull()->defaultValue('00:00'));
    }

    public function down()
    {
        $this->dropColumn('{{%excursion}}', 'start_time');
    }
}
