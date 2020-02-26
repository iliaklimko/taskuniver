<?php

use console\models\db\Migration;

class m161226_095604_currency_convert extends Migration
{
    public function up()
    {
        $this->addColumn('{{%currency}}', 'amount_cnt', $this->integer()->notNull()->defaultValue(1));
        $this->addColumn('{{%currency}}', 'amount', $this->money(19, 2)->notNull()->defaultValue(1.0));
        $this->addColumn('{{%currency}}', 'base', $this->boolean()->notNull()->defaultValue(false));

        $this->addColumn('{{%excursion}}', 'currency_id', $this->integer());
        $this->addForeignKey("fk_excursion_currency", "{{%excursion}}",  "currency_id", "{{%currency}}", "id");
    }

    public function down()
    {
        $this->dropColumn('{{%currency}}', 'amount_cnt');
        $this->dropColumn('{{%currency}}', 'amount');
        $this->dropColumn('{{%currency}}', 'base');

        $this->dropForeignKey("fk_excursion_currency", "{{%excursion}}");
        $this->dropColumn('{{%excursion}}', 'currency_id');
    }
}
