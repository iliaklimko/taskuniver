<?php

use console\models\db\Migration;

class m160914_124219_add_image_field_to_target_audience extends Migration
{
    public function up()
    {
        $this->addColumn('{{%target_audience}}', 'image', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%target_audience}}', 'image');
    }
}
