<?php

use console\models\db\Migration;

class m161016_211852_add_content_icons_to_post extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'contains_images', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('{{%post}}', 'contains_videos', $this->boolean()->notNull()->defaultValue(false));
    }

    public function down()
    {
        $this->addColumn('{{%post}}', 'contains_images');
        $this->addColumn('{{%post}}', 'contains_videos');
    }
}
