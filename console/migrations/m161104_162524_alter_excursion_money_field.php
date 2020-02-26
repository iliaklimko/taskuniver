<?php

use console\models\db\Migration;

class m161104_162524_alter_excursion_money_field extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%excursion}}', 'current_price', $this->money(19, 2));
        $this->alterColumn('{{%excursion}}', 'old_price', $this->money(19, 2));
    }

    public function down()
    {
        $this->alterColumn('{{%excursion}}', 'current_price', $this->money());
        $this->alterColumn('{{%excursion}}', 'old_price', $this->money());
    }
}
