<?php

use console\models\db\Migration;

class m161014_124225_add_priority_to_target_audience extends Migration
{
    public function up()
    {
        $this->addColumn('{{%target_audience}}', 'priority', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%target_audience}}', 'priority');
    }
}
