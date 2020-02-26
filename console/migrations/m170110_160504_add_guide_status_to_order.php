<?php

use console\models\db\Migration;

class m170110_160504_add_guide_status_to_order extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'guide_status', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%order}}', 'guide_message', $this->text());
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'guide_status');
        $this->dropColumn('{{%order}}', 'guide_message');
    }
}
