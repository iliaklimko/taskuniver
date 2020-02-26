<?php

use console\models\db\Migration;

class m170126_143949_alter_order_foreign_key extends Migration
{
    public function up()
    {
        $this->dropForeignKey("fk_order_excursion", "{{%order}}");
        $this->addForeignKey("fk_order_excursion", "{{%order}}",  "excursion_id", "{{%excursion}}", "id", 'SET NULL');
    }

    public function down()
    {
        $this->dropForeignKey("fk_order_excursion", "{{%order}}");
        $this->addForeignKey("fk_order_excursion", "{{%order}}",  "excursion_id", "{{%excursion}}", "id");
    }
}
