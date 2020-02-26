<?php

use console\models\db\Migration;

class m170109_111322_create_order extends Migration
{
    public function up()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
            'excursion_id' => $this->integer(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'price' => $this->integer()->notNull(),
            'currency' => $this->string(),
            'date' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey("fk_order_excursion", "{{%order}}",  "excursion_id", "{{%excursion}}", "id");
    }

    public function down()
    {
        $this->dropForeignKey("fk_order_excursion", "{{%order}}");
        $this->dropTable('{{%order}}');
    }
}
