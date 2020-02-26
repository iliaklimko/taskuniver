<?php

use console\models\db\Migration;

class m170126_091628_drop_post_en_columns extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%post}}', 'title_en');
        $this->dropColumn('{{%post}}', 'body_en');
        $this->dropColumn('{{%post}}', 'h1_en');
        $this->dropColumn('{{%post}}', 'meta_keywords_en');
        $this->dropColumn('{{%post}}', 'meta_description_en');
    }

    public function down()
    {
        $this->addColumn('{{%post}}', 'title_en', $this->string()->notNull());
        $this->addColumn('{{%post}}', 'body_en', $this->text()->notNull());
        $this->addColumn('{{%post}}', 'h1_en', $this->string());
        $this->addColumn('{{%post}}', 'meta_keywords_en', $this->string());
        $this->addColumn('{{%post}}', 'meta_description_en', $this->string());
    }
}
