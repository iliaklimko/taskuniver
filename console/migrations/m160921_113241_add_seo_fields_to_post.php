<?php

use console\models\db\Migration;

class m160921_113241_add_seo_fields_to_post extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'url_alias', $this->string());
        $this->addColumn('{{%post}}', 'h1', $this->string());
        $this->addColumn('{{%post}}', 'meta_keywords', $this->string());
        $this->addColumn('{{%post}}', 'meta_description', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'url_alias');
        $this->dropColumn('{{%post}}', 'h1');
        $this->dropColumn('{{%post}}', 'meta_keywords');
        $this->dropColumn('{{%post}}', 'meta_description');
    }
}
