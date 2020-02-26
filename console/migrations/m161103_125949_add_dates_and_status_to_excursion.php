<?php

use console\models\db\Migration;

class m161103_125949_add_dates_and_status_to_excursion extends Migration
{
    public function up()
    {
        $this->addColumn('{{%excursion}}', 'dates', $this->text());
        $this->addColumn('{{%excursion}}', 'status', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%excursion}}', 'rejection_reason', $this->text());
    }

    public function down()
    {
        $this->dropColumn('{{%excursion}}', 'dates');
        $this->dropColumn('{{%excursion}}', 'status');
        $this->dropColumn('{{%excursion}}', 'rejection_reason');
    }
}
