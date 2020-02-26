<?php

use console\models\db\Migration;

class m160921_133231_add_author_to_post extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'user_id', $this->integer());
        $this->addForeignKey("fk_post_user", "{{%post}}",  "user_id", "{{%user}}", "id", "SET NULL", "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_post_user", "{{%post}}");
        $this->dropColumn('{{%post}}', 'user_id');
    }
}
