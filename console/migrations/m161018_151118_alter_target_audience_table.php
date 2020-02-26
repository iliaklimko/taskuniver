<?php

use console\models\db\Migration;

class m161018_151118_alter_target_audience_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%target_audience}}', 'alias', $this->string()->unique()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%target_audience}}', 'alias');
    }
}
