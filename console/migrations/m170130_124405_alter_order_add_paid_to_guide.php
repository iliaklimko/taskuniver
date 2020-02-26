<?php

use console\models\db\Migration;

class m170130_124405_alter_order_add_paid_to_guide extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'paid_to_guide', $this->boolean()->notNull()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'paid_to_guide');
    }
}
