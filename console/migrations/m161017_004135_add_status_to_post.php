<?php

use console\models\db\Migration;

class m161017_004135_add_status_to_post extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'status', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%post}}', 'rejection_reason', $this->text());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'status');
        $this->dropColumn('{{%post}}', 'rejection_reason');
    }
}
