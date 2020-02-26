<?php

use console\models\db\Migration;

class m161020_120158_add_url_alias_to_post_category extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post_category}}', 'url_alias', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%post_category}}', 'url_alias');
    }
}
