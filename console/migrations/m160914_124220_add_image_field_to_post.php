<?php

use console\models\db\Migration;

class m160914_124220_add_image_field_to_post extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'image', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'image');
    }
}
