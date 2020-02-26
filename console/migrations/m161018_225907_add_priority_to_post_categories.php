<?php

use console\models\db\Migration;

class m161018_225907_add_priority_to_post_categories extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post_category}}', 'priority', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%post_category}}', 'priority');
    }
}
