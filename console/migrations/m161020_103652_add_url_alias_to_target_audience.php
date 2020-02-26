<?php

use console\models\db\Migration;

class m161020_103652_add_url_alias_to_target_audience extends Migration
{
    public function up()
    {
        $this->addColumn('{{%target_audience}}', 'url_alias', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%target_audience}}', 'url_alias');
    }
}
