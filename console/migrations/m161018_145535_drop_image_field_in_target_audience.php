<?php

use console\models\db\Migration;

class m161018_145535_drop_image_field_in_target_audience extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%target_audience}}', 'image');
    }

    public function down()
    {
        $this->addColumn('{{%target_audience}}', 'image', $this->string());
    }
}
