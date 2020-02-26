<?php

use console\models\db\Migration;

class m161102_125217_add_priority_to_excursion extends Migration
{
    public function up()
    {
        $this->addColumn('{{%excursion_type}}', 'priority', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%excursion_theme}}', 'priority', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%excursion_type}}', 'priority');
        $this->dropColumn('{{%excursion_theme}}', 'priority');
    }
}
