<?php

use console\models\db\Migration;

class m160914_124218_add_image_field_to_city extends Migration
{
    public function up()
    {
        $this->addColumn('{{%city}}', 'image', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%city}}', 'image');
    }
}
